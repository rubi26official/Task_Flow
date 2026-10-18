<?php
require_once 'partials.php';
$user = require_login();
$uid = (int)$user['id'];
$isAdmin = $user['role'] === 'admin';
$pdo = db();
// Create one office group conversation and ensure approved members are members.
$group = $pdo->query("SELECT id FROM conversations WHERE type='group' AND title='Office group' LIMIT 1")->fetchColumn();
if (!$group) {
    $pdo->prepare("INSERT INTO conversations (type,title,created_by) VALUES ('group','Office group',?)")->execute([$uid]);
    $group = (int)$pdo->lastInsertId();
}
$group = (int)$group;
$pdo->prepare("INSERT IGNORE INTO conversation_members (conversation_id,user_id) SELECT ?,id FROM users WHERE approval_status='approved'")->execute([$group]);
$selected = (int)($_GET['conversation'] ?? $group);
$allowed = $pdo->prepare('SELECT 1 FROM conversation_members WHERE conversation_id=? AND user_id=?');
$allowed->execute([$selected,$uid]);
if (!$allowed->fetchColumn()) $selected = $group;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = (string)($_POST['action'] ?? '');
    if ($action === 'send') {
        $conversation = (int)($_POST['conversation_id'] ?? $group);
        $body = trim((string)($_POST['body'] ?? ''));
        $allowed->execute([$conversation,$uid]);
        if ($allowed->fetchColumn() && $body !== '' && mb_strlen($body) <= 3000) {
            $stmt=$pdo->prepare('INSERT INTO messages (conversation_id,sender_id,body) VALUES (?,?,?)');
            $stmt->execute([$conversation,$uid,$body]);
            $pdo->prepare('UPDATE conversation_members SET last_read_at=NOW() WHERE conversation_id=? AND user_id=?')->execute([$conversation,$uid]);
        } else flash('Message is empty or too long.', 'error');
        header('Location: chat.php?conversation=' . $conversation); exit;
    }
    if ($action === 'private' && $isAdmin) {
        $target = (int)($_POST['target_id'] ?? 0);
        $check=$pdo->prepare("SELECT id FROM users WHERE id=? AND role='user' AND approval_status='approved'");
        $check->execute([$target]);
        if ($check->fetchColumn() && $target !== $uid) {
            $stmt=$pdo->prepare("SELECT c.id FROM conversations c JOIN conversation_members a ON a.conversation_id=c.id JOIN conversation_members b ON b.conversation_id=c.id WHERE c.type='private' AND a.user_id=? AND b.user_id=? LIMIT 1");
            $stmt->execute([$uid,$target]); $conv=$stmt->fetchColumn();
            if (!$conv) {
                $pdo->prepare("INSERT INTO conversations (type,title,created_by) VALUES ('private',NULL,?)")->execute([$uid]); $conv=(int)$pdo->lastInsertId();
                $pdo->prepare('INSERT INTO conversation_members (conversation_id,user_id) VALUES (?,?),(?,?)')->execute([$conv,$uid,$conv,$target]);
            }
            header('Location: chat.php?conversation='.(int)$conv); exit;
        }
    }
}
if (!$isAdmin) {
    // Members may use the group and direct conversations that include them; only admin-created direct threads are offered by default.
    $convs=$pdo->prepare("SELECT c.id,c.type,c.title, (SELECT u.name FROM conversation_members cm2 JOIN users u ON u.id=cm2.user_id WHERE cm2.conversation_id=c.id AND cm2.user_id<>? LIMIT 1) AS other_name FROM conversations c JOIN conversation_members cm ON cm.conversation_id=c.id WHERE cm.user_id=? ORDER BY c.type='group' DESC,c.id DESC");
    $convs->execute([$uid,$uid]); $conversations=$convs->fetchAll();
} else {
    $convs=$pdo->prepare("SELECT c.id,c.type,c.title, (SELECT u.name FROM conversation_members cm2 JOIN users u ON u.id=cm2.user_id WHERE cm2.conversation_id=c.id AND cm2.user_id<>? LIMIT 1) AS other_name FROM conversations c JOIN conversation_members cm ON cm.conversation_id=c.id WHERE cm.user_id=? ORDER BY c.type='group' DESC,c.id DESC");
    $convs->execute([$uid,$uid]); $conversations=$convs->fetchAll();
}
$messagesStmt=$pdo->prepare("SELECT m.*,u.name AS sender_name FROM messages m JOIN users u ON u.id=m.sender_id WHERE m.conversation_id=? ORDER BY m.created_at ASC LIMIT 300");
$messagesStmt->execute([$selected]); $messages=$messagesStmt->fetchAll();
$pdo->prepare('UPDATE conversation_members SET last_read_at=NOW() WHERE conversation_id=? AND user_id=?')->execute([$selected,$uid]);
$members=$isAdmin ? $pdo->query("SELECT id,name FROM users WHERE role='user' AND approval_status='approved' ORDER BY name")->fetchAll() : [];
app_start('Team chat', $user, 'chat.php');
?>
<div class="page-head"><div><h1>Team chat</h1><p>Keep conversations close to the work.</p></div></div>
<?php if ($isAdmin && $members): ?><section class="panel"><div class="panel-head"><h2>Start a private conversation</h2></div><form method="post" class="inline-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="private"><select name="target_id" required><?php foreach ($members as $m): ?><option value="<?= (int)$m['id'] ?>"><?= e($m['name']) ?></option><?php endforeach; ?></select><button class="small-btn">Open private chat</button></form></section><?php endif; ?>
<div class="chat-layout"><aside class="chat-people"><?php foreach ($conversations as $c): ?><a class="chat-person <?= (int)$c['id']===$selected?'active':'' ?>" href="chat.php?conversation=<?= (int)$c['id'] ?>"><strong><?= e($c['type']==='group' ? ($c['title'] ?: 'Office group') : ($c['other_name'] ?: 'Private chat')) ?></strong><div class="helper"><?= $c['type']==='group'?'Everyone in the office':'Direct conversation' ?></div></a><?php endforeach; ?></aside><section class="chat-main"><div class="chat-messages" id="chat-messages"><?php if (!$messages): ?><div class="empty">No messages yet. Start the conversation.</div><?php else: foreach ($messages as $m): ?><div class="message <?= (int)$m['sender_id']===$uid?'mine':'' ?>"><div class="message-meta"><?= e($m['sender_name']) ?> · <?= e(date('M j, g:i A',strtotime($m['created_at']))) ?></div><div class="bubble"><?= nl2br(e($m['body'])) ?></div></div><?php endforeach; endif; ?></div><form class="chat-compose" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="send"><input type="hidden" name="conversation_id" value="<?= $selected ?>"><input name="body" maxlength="3000" required placeholder="Write a message…"><button type="submit">Send ↗</button></form></section></div>
<script>const box=document.getElementById('chat-messages');if(box)box.scrollTop=box.scrollHeight;</script>
<?php app_end(); ?>

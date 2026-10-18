<?php
require_once 'partials.php';
$user = require_login();
if ($user['role'] === 'admin') { header('Location: admin.php'); exit; }
$uid = (int)$user['id'];
$summary = ['total'=>0,'active'=>0,'submitted'=>0,'completed'=>0];
$stmt = db()->prepare("SELECT status, COUNT(*) AS n FROM task_assignees WHERE user_id=? GROUP BY status");
$stmt->execute([$uid]);
foreach ($stmt->fetchAll() as $r) {
    $summary['total'] += (int)$r['n'];
    if (in_array($r['status'], ['assigned','in_progress','revision_requested'], true)) $summary['active'] += (int)$r['n'];
    if ($r['status'] === 'submitted') $summary['submitted'] += (int)$r['n'];
    if ($r['status'] === 'completed') $summary['completed'] += (int)$r['n'];
}
$stmt = db()->prepare("SELECT t.*,a.status AS my_status,a.completed_at,u.name AS creator FROM task_assignees a JOIN tasks t ON t.id=a.task_id JOIN users u ON u.id=t.created_by WHERE a.user_id=? AND a.status <> 'completed' ORDER BY (t.due_at IS NULL),t.due_at ASC LIMIT 8");
$stmt->execute([$uid]); $tasks = $stmt->fetchAll();
$stmt = db()->prepare("SELECT COUNT(*) FROM messages m JOIN conversations c ON c.id=m.conversation_id JOIN conversation_members cm ON cm.conversation_id=c.id WHERE cm.user_id=? AND m.sender_id<>? AND m.created_at > COALESCE(cm.last_read_at,'1970-01-01')");
$stmt->execute([$uid,$uid]); $unread = (int)$stmt->fetchColumn();
app_start('My dashboard', $user, 'dashboard.php');
?>
<div class="page-head"><div><h1>Welcome back, <?= e($user['name']) ?> 👋</h1><p>Here's your personal work overview.</p></div><a class="btn" href="tasks.php">View my tasks →</a></div>
<div class="stats"><div class="stat-card"><div class="stat-top">All assigned <span class="stat-icon">☷</span></div><div class="stat-number"><?= $summary['total'] ?></div><div class="stat-foot">Tasks assigned to you</div></div><div class="stat-card"><div class="stat-top">In progress <span class="stat-icon">◷</span></div><div class="stat-number"><?= $summary['active'] ?></div><div class="stat-foot">Work to keep moving</div></div><div class="stat-card"><div class="stat-top">Awaiting review <span class="stat-icon">↗</span></div><div class="stat-number"><?= $summary['submitted'] ?></div><div class="stat-foot">Submitted to the admin</div></div><div class="stat-card"><div class="stat-top">Completed <span class="stat-icon">✓</span></div><div class="stat-number"><?= $summary['completed'] ?></div><div class="stat-foot">Finished assignments</div></div></div>
<section class="panel"><div class="panel-head"><h2>Your upcoming work</h2><a href="tasks.php">See all tasks →</a></div><?php if (!$tasks): ?><div class="empty">No open tasks right now. Enjoy the breathing room!</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Task</th><th>Priority</th><th>Due</th><th>Status</th><th></th></tr></thead><tbody><?php foreach ($tasks as $t): ?><tr><td><strong><?= e($t['title']) ?></strong><div class="helper">Assigned by <?= e($t['creator']) ?></div></td><td><?= status_badge($t['priority']) ?></td><td><?= $t['due_at'] ? e(date('M j, Y · g:i A', strtotime($t['due_at']))) : 'No deadline' ?></td><td><?= status_badge($t['my_status']) ?></td><td><a class="btn ghost small-btn" href="tasks.php?view=<?= (int)$t['id'] ?>">Open</a></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
<div class="two-col"><section class="panel"><div class="panel-head"><h2>Quick actions</h2></div><p class="muted">Keep your work moving and make progress visible.</p><div class="actions"><a class="btn" href="tasks.php">Open task list</a><a class="btn ghost" href="chat.php">Message admin<?= $unread ? ' (' . $unread . ')' : '' ?></a></div></section><section class="panel"><div class="panel-head"><h2>Good habits</h2></div><p class="muted">Update task status as you work. When a task is finished, submit a short note so the admin knows what was delivered.</p></section></div>
<?php app_end(); ?>

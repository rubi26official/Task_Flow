<?php
require_once 'partials.php';
$user = require_login();
$isAdmin = $user['role'] === 'admin';
$uid = (int)$user['id'];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = (string)($_POST['action'] ?? '');
    if ($action === 'create' && $isAdmin) {
        $title = trim((string)($_POST['title'] ?? ''));
        $description = trim((string)($_POST['description'] ?? ''));
        $priority = (string)($_POST['priority'] ?? 'medium');
        $due = trim((string)($_POST['due_at'] ?? ''));
        $assignees = $_POST['assignees'] ?? [];
        if (!is_array($assignees)) $assignees = [];
        $assignees = array_values(array_unique(array_filter(array_map('intval', $assignees), fn($v) => $v > 0)));
        if (!in_array($priority, ['low','medium','high','urgent'], true)) $priority = 'medium';
        if ($title === '' || count($assignees) === 0) {
            flash('Enter a task title and select at least one approved member.', 'error');
        } else {
            $valid = db()->prepare("SELECT id FROM users WHERE role='user' AND approval_status='approved' AND id IN (" . implode(',', array_fill(0,count($assignees),'?')) . ")");
            $valid->execute($assignees); $validIds = array_map('intval', array_column($valid->fetchAll(), 'id'));
            if (!$validIds) flash('No approved members selected.', 'error');
            else {
                $dueValue = null;
                if ($due !== '') { $dt = DateTime::createFromFormat('Y-m-d\TH:i', $due); if ($dt) $dueValue = $dt->format('Y-m-d H:i:s'); }
                $stmt = db()->prepare('INSERT INTO tasks (title,description,priority,due_at,created_by) VALUES (?,?,?,?,?)');
                $stmt->execute([$title,$description,$priority,$dueValue,$uid]); $taskId = (int)db()->lastInsertId();
                $assign = db()->prepare("INSERT INTO task_assignees (task_id,user_id,status) VALUES (?,?,'assigned')");
                foreach ($validIds as $memberId) $assign->execute([$taskId,$memberId]);
                flash('Task created and assigned to ' . count($validIds) . ' member(s).');
            }
        }
        header('Location: tasks.php'); exit;
    }
    if ($action === 'status') {
        $taskId = (int)($_POST['task_id'] ?? 0);
        $newStatus = (string)($_POST['status'] ?? '');
        $allowed = $isAdmin ? ['completed','revision_requested','in_progress','assigned'] : ['in_progress','submitted'];
        if (!in_array($newStatus, $allowed, true)) { flash('Invalid task status.', 'error'); header('Location: tasks.php'); exit; }
        if ($isAdmin) {
            $assigneeId = (int)($_POST['assignee_id'] ?? 0);
            $stmt = db()->prepare('UPDATE task_assignees SET status=?, completed_at=IF(?="completed",NOW(),NULL) WHERE task_id=? AND user_id=?');
            $stmt->execute([$newStatus,$newStatus,$taskId,$assigneeId]);
        } else {
            $stmt = db()->prepare('UPDATE task_assignees SET status=?, completed_at=IF(?="completed",NOW(),NULL) WHERE task_id=? AND user_id=?');
            $stmt->execute([$newStatus,$newStatus,$taskId,$uid]);
        }
        flash('Task status updated.');
        header('Location: tasks.php' . (!empty($_POST['return_view']) ? '?view=' . (int)$_POST['return_view'] : '')); exit;
    }
    if ($action === 'comment') {
        $taskId = (int)($_POST['task_id'] ?? 0); $comment = trim((string)($_POST['comment'] ?? ''));
        $stmt = db()->prepare('SELECT id FROM tasks WHERE id=?'); $stmt->execute([$taskId]); $task = $stmt->fetch();
        if ($task && $comment !== '' && mb_strlen($comment) <= 3000) {
            $allowed = $isAdmin;
            if (!$isAdmin) { $check=db()->prepare('SELECT 1 FROM task_assignees WHERE task_id=? AND user_id=?'); $check->execute([$taskId,$uid]); $allowed=(bool)$check->fetchColumn(); }
            if ($allowed) { $ins=db()->prepare('INSERT INTO task_comments (task_id,user_id,comment) VALUES (?,?,?)'); $ins->execute([$taskId,$uid,$comment]); flash('Comment added.'); }
            else flash('You cannot comment on this task.', 'error');
        } else flash('Enter a comment.', 'error');
        header('Location: tasks.php?view=' . $taskId); exit;
    }
}
$members = $isAdmin ? db()->query("SELECT id,name,email FROM users WHERE role='user' AND approval_status='approved' ORDER BY name")->fetchAll() : [];
$viewId = (int)($_GET['view'] ?? 0);
$taskDetail = null; $comments = [];
if ($viewId) {
    $sql = "SELECT t.*,u.name AS creator,a.user_id AS assignee_id,a.status AS my_status,au.name AS assignee_name FROM tasks t JOIN users u ON u.id=t.created_by JOIN task_assignees a ON a.task_id=t.id JOIN users au ON au.id=a.user_id WHERE t.id=?";
    $params = [$viewId];
    if (!$isAdmin) { $sql .= ' AND a.user_id=?'; $params[] = $uid; }
    $sql .= ' ORDER BY au.name';
    $st = db()->prepare($sql); $st->execute($params); $rows = $st->fetchAll();
    if ($rows) {
        $taskDetail = $rows[0];
        if ($isAdmin) $taskDetail['assignees'] = $rows;
        $c = db()->prepare('SELECT c.*,u.name FROM task_comments c JOIN users u ON u.id=c.user_id WHERE c.task_id=? ORDER BY c.created_at ASC'); $c->execute([$viewId]); $comments=$c->fetchAll();
    }
}
if ($isAdmin) {
    $stmt = db()->query("SELECT t.*,u.name AS creator, COUNT(a.user_id) AS assigned_count, SUM(a.status='completed') AS completed_count, GROUP_CONCAT(CONCAT(au.name, '::', a.status, '::', a.user_id) SEPARATOR '||') AS assignee_summary FROM tasks t JOIN users u ON u.id=t.created_by LEFT JOIN task_assignees a ON a.task_id=t.id LEFT JOIN users au ON au.id=a.user_id GROUP BY t.id ORDER BY t.created_at DESC");
    $tasks = $stmt->fetchAll();
} else {
    $stmt = db()->prepare("SELECT t.*,u.name AS creator,a.status AS my_status,a.completed_at FROM tasks t JOIN task_assignees a ON a.task_id=t.id JOIN users u ON u.id=t.created_by WHERE a.user_id=? ORDER BY (a.status='completed'),(t.due_at IS NULL),t.due_at ASC,t.created_at DESC");
    $stmt->execute([$uid]); $tasks=$stmt->fetchAll();
}
app_start($isAdmin ? 'Task management' : 'My tasks', $user, 'tasks.php');
?>
<div class="page-head"><div><h1><?= $isAdmin ? 'Task management' : 'My tasks' ?></h1><p><?= $isAdmin ? 'Create assignments and review progress across the team.' : 'Everything assigned to you, with clear deadlines and status.' ?></p></div><?php if ($isAdmin): ?><a class="btn" href="tasks.php?create=1#create-task">＋ Create task</a><?php endif; ?></div>
<?php if ($viewId): ?>
<section class="panel"><?php if (!$taskDetail): ?><div class="empty">Task not found or you do not have access to it.</div><?php else: ?><div class="panel-head"><div><h2><?= e($taskDetail['title']) ?></h2><div class="helper">Created by <?= e($taskDetail['creator']) ?> · <?= e(ucfirst($taskDetail['priority'])) ?> priority</div></div><a class="btn ghost small-btn" href="tasks.php">← Back to tasks</a></div><p><?= nl2br(e($taskDetail['description'] ?: 'No additional description.')) ?></p><p class="muted">Deadline: <?= $taskDetail['due_at'] ? e(date('M j, Y · g:i A', strtotime($taskDetail['due_at']))) : 'No deadline set' ?></p>
<?php if ($isAdmin): ?><h3 style="margin:22px 0 10px">Assignee progress</h3><div class="table-wrap"><table><thead><tr><th>Member</th><th>Status</th><th>Update status</th></tr></thead><tbody><?php foreach ($taskDetail['assignees'] as $a): ?><tr><td><?= e($a['assignee_name']) ?></td><td><?= status_badge($a['my_status']) ?></td><td><form class="inline-form" method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="status"><input type="hidden" name="task_id" value="<?= (int)$viewId ?>"><input type="hidden" name="assignee_id" value="<?= (int)$a['assignee_id'] ?>"><input type="hidden" name="return_view" value="<?= (int)$viewId ?>"><select name="status"><?php foreach (['assigned','in_progress','submitted','completed','revision_requested'] as $s): ?><option value="<?= e($s) ?>" <?= $s===$a['my_status']?'selected':'' ?>><?= e(ucwords(str_replace('_',' ',$s))) ?></option><?php endforeach; ?></select><button class="small-btn">Save</button></form></td></tr><?php endforeach; ?></tbody></table></div><?php else: ?><p>Status: <?= status_badge($taskDetail['my_status']) ?></p><?php if (in_array($taskDetail['my_status'], ['assigned','revision_requested'], true)): ?><form method="post" style="display:inline"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="status"><input type="hidden" name="task_id" value="<?= (int)$viewId ?>"><input type="hidden" name="status" value="in_progress"><button class="secondary">Start task</button></form><?php endif; ?><?php if (in_array($taskDetail['my_status'], ['assigned','in_progress','revision_requested'], true)): ?><form method="post" style="display:inline;margin-left:8px"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="status"><input type="hidden" name="task_id" value="<?= (int)$viewId ?>"><input type="hidden" name="status" value="submitted"><button>Submit for review</button></form><?php endif; ?><?php endif; ?>
<h3 style="margin:26px 0 10px">Task discussion</h3><?php if (!$comments): ?><p class="muted">No comments yet.</p><?php else: foreach ($comments as $c): ?><div style="padding:12px 0;border-bottom:1px solid var(--line)"><strong><?= e($c['name']) ?></strong> <span class="helper">· <?= e(date('M j, g:i A',strtotime($c['created_at']))) ?></span><div><?= nl2br(e($c['comment'])) ?></div></div><?php endforeach; endif; ?><form method="post" style="margin-top:15px"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="comment"><input type="hidden" name="task_id" value="<?= (int)$viewId ?>"><div class="field"><label for="comment">Add a comment or completion note</label><textarea id="comment" name="comment" maxlength="3000" required placeholder="Share an update..."></textarea></div><button>Add comment</button></form><?php endif; ?></section>
<?php endif; ?>
<?php if ($isAdmin && (!empty($_GET['create']) || !$tasks)): ?><section class="panel" id="create-task"><div class="panel-head"><h2>Create a task</h2></div><?php if (!$members): ?><div class="notice warning">Approve at least one team member before assigning tasks.</div><?php else: ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="action" value="create"><div class="two-col"><div class="field"><label for="title">Task title</label><input id="title" name="title" maxlength="180" required placeholder="e.g. Prepare monthly report"></div><div class="field"><label for="priority">Priority</label><select id="priority" name="priority"><option value="low">Low</option><option value="medium" selected>Medium</option><option value="high">High</option><option value="urgent">Urgent</option></select></div></div><div class="field"><label for="description">Description and instructions</label><textarea id="description" name="description" maxlength="10000" placeholder="What needs to be done? Include useful context."></textarea></div><div class="field"><label for="due_at">Deadline (optional)</label><input id="due_at" type="datetime-local" name="due_at"></div><div class="field"><label>Assign to</label><div class="checkbox-row"><input type="checkbox" id="select-all"><label for="select-all">Select all approved members</label></div><div class="feature-grid" style="grid-template-columns:repeat(2,minmax(0,1fr));margin-top:10px"><?php foreach ($members as $m): ?><label class="feature" style="padding:12px;display:flex;gap:9px;align-items:center"><input class="assignee-check" type="checkbox" name="assignees[]" value="<?= (int)$m['id'] ?>" style="width:auto"><span><strong><?= e($m['name']) ?></strong><div class="helper"><?= e($m['email']) ?></div></span></label><?php endforeach; ?></div></div><button>Create and assign task →</button></form><script>document.getElementById('select-all').addEventListener('change',function(){document.querySelectorAll('.assignee-check').forEach(c=>c.checked=this.checked)});document.querySelectorAll('.assignee-check').forEach(c=>c.addEventListener('change',function(){const all=[...document.querySelectorAll('.assignee-check')];document.getElementById('select-all').checked=all.length>0&&all.every(x=>x.checked)}));</script><?php endif; ?></section><?php endif; ?>
<section class="panel"><div class="panel-head"><h2><?= $isAdmin ? 'All assignments' : 'Task list' ?></h2><span class="muted" style="font-size:12px"><?= count($tasks) ?> tasks</span></div><?php if (!$tasks): ?><div class="empty">No tasks here yet.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Task</th><?php if ($isAdmin): ?><th>Assigned / complete</th><?php endif; ?><th>Priority</th><th>Deadline</th><th>Status</th><th>Details</th></tr></thead><tbody><?php foreach ($tasks as $t): ?><tr><td><strong><?= e($t['title']) ?></strong><div class="helper"><?= e(mb_strimwidth($t['description'] ?? '',0,70,'…')) ?></div></td><?php if ($isAdmin): ?><td><?= (int)$t['completed_count'] ?> / <?= (int)$t['assigned_count'] ?> complete</td><?php endif; ?><td><?= status_badge($t['priority']) ?></td><td><?= $t['due_at'] ? e(date('M j, Y · g:i A',strtotime($t['due_at']))) : '—' ?></td><td><?php if ($isAdmin): ?><span class="badge"><?= (int)$t['assigned_count'] ?> assigned</span><?php else: ?><?= status_badge($t['my_status']) ?><?php endif; ?></td><td><a class="btn ghost small-btn" href="tasks.php?view=<?= (int)$t['id'] ?>">Open</a></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
<?php app_end(); ?>

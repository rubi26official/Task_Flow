<?php
require_once 'partials.php';
$admin = require_admin();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $action = (string)($_POST['action'] ?? '');
    $uid = (int)($_POST['user_id'] ?? 0);
    if (in_array($action, ['approve','reject','suspend','reactivate'], true) && $uid > 0) {
        $status = ['approve'=>'approved','reject'=>'rejected','suspend'=>'suspended','reactivate'=>'approved'][$action];
        $stmt = db()->prepare("UPDATE users SET approval_status = ? WHERE id = ? AND role = 'user'");
        $stmt->execute([$status, $uid]);
        flash('Member status updated.');
    }
    header('Location: admin.php#approvals'); exit;
}
$counts = [];
$counts['members'] = (int)db()->query("SELECT COUNT(*) FROM users WHERE role='user' AND approval_status='approved'")->fetchColumn();
$counts['pending'] = (int)db()->query("SELECT COUNT(*) FROM users WHERE role='user' AND approval_status='pending'")->fetchColumn();
$counts['active'] = (int)db()->query("SELECT COUNT(*) FROM tasks t JOIN task_assignees a ON a.task_id=t.id WHERE a.status IN ('assigned','in_progress','revision_requested')")->fetchColumn();
$counts['completed'] = (int)db()->query("SELECT COUNT(*) FROM task_assignees WHERE status='completed'")->fetchColumn();
$pending = db()->query("SELECT id,name,email,created_at FROM users WHERE role='user' AND approval_status='pending' ORDER BY created_at ASC")->fetchAll();
$members = db()->query("SELECT id,name,email,approval_status,created_at FROM users WHERE role='user' ORDER BY created_at DESC")->fetchAll();
$recent = db()->query("SELECT t.id,t.title,t.priority,t.due_at,t.created_at,u.name AS creator FROM tasks t JOIN users u ON u.id=t.created_by ORDER BY t.created_at DESC LIMIT 8")->fetchAll();
app_start('Admin dashboard', $admin, 'admin.php');
?>
<div class="page-head"><div><h1>Good <?= (int)date('G') < 12 ? 'morning' : ((int)date('G') < 18 ? 'afternoon' : 'evening') ?>, <?= e($admin['name']) ?>.</h1><p>Here's what's happening across your workspace.</p></div><a class="btn" href="tasks.php?create=1">＋ Create task</a></div>
<div class="stats"><div class="stat-card"><div class="stat-top">Approved members <span class="stat-icon">♙</span></div><div class="stat-number"><?= $counts['members'] ?></div><div class="stat-foot">Active team accounts</div></div><div class="stat-card"><div class="stat-top">Pending approvals <span class="stat-icon">◷</span></div><div class="stat-number"><?= $counts['pending'] ?></div><div class="stat-foot">Waiting for review</div></div><div class="stat-card"><div class="stat-top">Open assignments <span class="stat-icon">☷</span></div><div class="stat-number"><?= $counts['active'] ?></div><div class="stat-foot">Assigned or in progress</div></div><div class="stat-card"><div class="stat-top">Completed tasks <span class="stat-icon">✓</span></div><div class="stat-number"><?= $counts['completed'] ?></div><div class="stat-foot">Marked complete</div></div></div>
<section class="panel" id="approvals"><div class="panel-head"><h2>Registration requests</h2><span class="badge pending"><?= count($pending) ?> pending</span></div><?php if (!$pending): ?><div class="empty">You're all caught up. No pending registration requests.</div><?php else: ?><div class="table-wrap"><table><thead><tr><th>Member</th><th>Email</th><th>Requested</th><th>Action</th></tr></thead><tbody><?php foreach ($pending as $p): ?><tr><td><strong><?= e($p['name']) ?></strong></td><td><?= e($p['email']) ?></td><td><?= e(date('M j, Y', strtotime($p['created_at']))) ?></td><td><div class="actions-cell"><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="user_id" value="<?= (int)$p['id'] ?>"><button class="small-btn" name="action" value="approve">Approve</button></form><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="user_id" value="<?= (int)$p['id'] ?>"><button class="small-btn danger" name="action" value="reject">Reject</button></form></div></td></tr><?php endforeach; ?></tbody></table></div><?php endif; ?></section>
<div class="two-col"><section class="panel"><div class="panel-head"><h2>Team members</h2><a href="#members">View all</a></div><div class="table-wrap"><table><thead><tr><th>Name</th><th>Status</th></tr></thead><tbody><?php foreach (array_slice($members,0,6) as $m): ?><tr><td><strong><?= e($m['name']) ?></strong><div class="helper"><?= e($m['email']) ?></div></td><td><?= status_badge($m['approval_status']) ?></td></tr><?php endforeach; ?></tbody></table></div></section><section class="panel"><div class="panel-head"><h2>Recently created tasks</h2><a href="tasks.php">All tasks</a></div><?php if (!$recent): ?><div class="empty">No tasks created yet.</div><?php else: foreach ($recent as $t): ?><div style="padding:12px 0;border-bottom:1px solid var(--line)"><strong><?= e($t['title']) ?></strong><div class="helper">Created by <?= e($t['creator']) ?> · <?= e(ucfirst($t['priority'])) ?> priority</div></div><?php endforeach; endif; ?></section></div>
<section class="panel" id="members"><div class="panel-head"><h2>Member access</h2><span class="muted" style="font-size:12px">Manage approval status</span></div><div class="table-wrap"><table><thead><tr><th>Name</th><th>Email</th><th>Status</th><th>Action</th></tr></thead><tbody><?php foreach ($members as $m): ?><tr><td><?= e($m['name']) ?></td><td><?= e($m['email']) ?></td><td><?= status_badge($m['approval_status']) ?></td><td><?php if ($m['approval_status'] === 'approved'): ?><form method="post" class="inline-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="user_id" value="<?= (int)$m['id'] ?>"><button class="small-btn secondary" name="action" value="suspend">Suspend</button></form><?php else: ?><form method="post" class="inline-form"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="user_id" value="<?= (int)$m['id'] ?>"><button class="small-btn" name="action" value="reactivate">Approve / Reactivate</button></form><?php endif; ?></td></tr><?php endforeach; ?></tbody></table></div></section>
<?php app_end(); ?>

<?php
require_once __DIR__ . '/config.php';

function header_html(string $title = 'OfficeFlow'): void {
    $user = current_user();
    echo '<!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><meta name="description" content="OfficeFlow task management workspace"><title>' . e($title) . ' · OfficeFlow</title>';
    include __DIR__ . '/style.php';
    echo '</head><body><header class="topbar"><div class="container nav"><a class="brand" href="index.php"><span class="brand-mark">O</span>OfficeFlow</a><nav class="navlinks">';
    if ($user) {
        echo '<a class="optional" href="' . ($user['role'] === 'admin' ? 'admin.php' : 'dashboard.php') . '">Dashboard</a><a href="tasks.php">Tasks</a><a class="optional" href="chat.php">Messages</a><a href="profile.php">Profile</a><a class="btn ghost" href="logout.php">Sign out</a>';
    } else {
        echo '<a class="optional" href="index.php#features">Features</a><a class="optional" href="index.php#how">How it works</a><a href="login.php">Sign in</a><a class="btn" href="register.php">Get started</a>';
    }
    echo '</nav></div></header>';
}
function footer_html(): void {
    echo '<footer class="footer"><div class="container" style="display:flex;justify-content:space-between;gap:12px;flex-wrap:wrap"><span>© ' . date('Y') . ' OfficeFlow. Built for better teamwork.</span><span>Plan clearly. Work together. Deliver on time.</span></div></footer></body></html>';
}
function app_start(string $title, array $user, string $active): void {
    header_html($title);
    echo '<div class="app"><aside class="sidebar"><div class="side-label">Workspace</div>';
    $items = $user['role'] === 'admin'
        ? [['admin.php','▦','Overview'],['admin.php#approvals','✓','Approvals'],['tasks.php','☷','All tasks'],['chat.php','◌','Team chat'],['profile.php','♙','My profile']]
        : [['dashboard.php','▦','Overview'],['tasks.php','☷','My tasks'],['chat.php','◌','Team chat'],['profile.php','♙','My profile']];
    foreach ($items as $item) {
        $isActive = $active === $item[0] || ($active === 'admin.php' && str_starts_with($item[0], 'admin.php'));
        echo '<a class="side-link ' . ($isActive ? 'active' : '') . '" href="' . e($item[0]) . '"><span class="ico">' . e($item[1]) . '</span>' . e($item[2]) . '</a>';
    }
    echo '<div class="side-label">Account</div><a class="side-link" href="logout.php"><span class="ico">↪</span>Sign out</a></aside><main class="main">';
    show_flash();
}
function app_end(): void {
    echo '</main></div>';
    footer_html();
}
function status_badge(string $status): string {
    return '<span class="badge ' . e($status) . '">' . e(ucwords(str_replace('_', ' ', $status))) . '</span>';
}
function can_view_task(array $task, array $user): bool {
    if ($user['role'] === 'admin') return true;
    $stmt = db()->prepare('SELECT 1 FROM task_assignees WHERE task_id = ? AND user_id = ?');
    $stmt->execute([(int)$task['id'], (int)$user['id']]);
    return (bool)$stmt->fetchColumn();
}

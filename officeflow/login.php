<?php
require_once 'partials.php';
if (current_user()) redirect_dashboard(current_user());
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    $stmt = db()->prepare('SELECT id,name,email,password_hash,role,approval_status FROM users WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if (!$user || !password_verify($password, $user['password_hash'])) {
        $error = 'Email or password is incorrect.';
    } elseif ($user['approval_status'] === 'pending') {
        $error = 'Your account is awaiting admin approval. Please try again after it has been approved.';
    } elseif ($user['approval_status'] !== 'approved') {
        $error = 'This account is not currently active. Please contact the admin.';
    } else {
        session_regenerate_id(true);
        $_SESSION['user_id'] = (int)$user['id'];
        redirect_dashboard($user);
    }
}
header_html('Sign in');
?>
<div class="auth-wrap"><section class="auth-card"><span class="eyebrow">Welcome back</span><h1>Sign in to OfficeFlow</h1><p class="muted">Your office workspace, all in one place.</p><?php show_flash(); ?><?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label for="email">Email address</label><input id="email" name="email" type="email" autocomplete="username" required value="<?= e($_POST['email'] ?? '') ?>" placeholder="you@company.com"></div><div class="field"><label for="password">Password</label><input id="password" name="password" type="password" autocomplete="current-password" required placeholder="Your password"></div><button class="full" type="submit">Sign in →</button></form><p class="muted" style="text-align:center;margin:20px 0 0">New to the team? <a href="register.php">Request access</a></p></section></div>
<?php footer_html(); ?>

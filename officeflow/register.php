<?php
require_once 'partials.php';
if (current_user()) redirect_dashboard(current_user());
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $email = strtolower(trim((string)($_POST['email'] ?? '')));
    $password = (string)($_POST['password'] ?? '');
    $confirm = (string)($_POST['confirm_password'] ?? '');
    if (mb_strlen($name) < 2 || mb_strlen($name) > 100) $errors[] = 'Please enter your full name.';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Please enter a valid email address.';
    if (strlen($password) < 10) $errors[] = 'Password must be at least 10 characters.';
    if ($password !== $confirm) $errors[] = 'Passwords do not match.';
    if (!$errors) {
        $check = db()->prepare('SELECT id FROM users WHERE email = ?');
        $check->execute([$email]);
        if ($check->fetch()) $errors[] = 'An account with this email already exists.';
        else {
            $stmt = db()->prepare("INSERT INTO users (name,email,password_hash,role,approval_status) VALUES (?,?,?,'user','pending')");
            $stmt->execute([$name, $email, password_hash($password, PASSWORD_DEFAULT)]);
            flash('Registration submitted. Please wait for admin approval before signing in.', 'warning');
            header('Location: login.php'); exit;
        }
    }
}
header_html('Create account');
?>
<div class="auth-wrap"><section class="auth-card"><span class="eyebrow">Team access</span><h1>Create your account</h1><p class="muted">Register with your office email. The admin will review your request.</p><?php foreach ($errors as $error): ?><div class="notice error"><?= e($error) ?></div><?php endforeach; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label for="name">Full name</label><input id="name" name="name" autocomplete="name" required maxlength="100" value="<?= e($_POST['name'] ?? '') ?>" placeholder="Your full name"></div><div class="field"><label for="email">Work email</label><input id="email" type="email" name="email" autocomplete="email" required value="<?= e($_POST['email'] ?? '') ?>" placeholder="you@company.com"></div><div class="field"><label for="password">Password</label><input id="password" type="password" name="password" autocomplete="new-password" minlength="10" required placeholder="At least 10 characters"></div><div class="field"><label for="confirm_password">Confirm password</label><input id="confirm_password" type="password" name="confirm_password" autocomplete="new-password" required placeholder="Enter it again"></div><button class="full" type="submit">Request access →</button></form><p class="muted" style="text-align:center;margin:20px 0 0">Already registered? <a href="login.php">Sign in</a></p></section></div>
<?php footer_html(); ?>

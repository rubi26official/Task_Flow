<?php
require_once 'partials.php';
$user = require_login();
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf();
    $name = trim((string)($_POST['name'] ?? ''));
    $current = (string)($_POST['current_password'] ?? '');
    $new = (string)($_POST['new_password'] ?? '');
    if (mb_strlen($name) < 2 || mb_strlen($name) > 100) $error = 'Please enter a valid name.';
    else {
        if ($new !== '') {
            $stmt=db()->prepare('SELECT password_hash FROM users WHERE id=?'); $stmt->execute([(int)$user['id']]); $hash=$stmt->fetchColumn();
            if (!password_verify($current, (string)$hash)) $error = 'Current password is incorrect.';
            elseif (strlen($new) < 10) $error = 'New password must be at least 10 characters.';
            else {
                $stmt=db()->prepare('UPDATE users SET name=?,password_hash=? WHERE id=?');
                $stmt->execute([$name,password_hash($new,PASSWORD_DEFAULT),(int)$user['id']]);
            }
        } else {
            $stmt=db()->prepare('UPDATE users SET name=? WHERE id=?'); $stmt->execute([$name,(int)$user['id']]);
        }
        if (!$error) { flash('Profile updated.'); header('Location: profile.php'); exit; }
    }
    $user['name'] = $name;
}
app_start('My profile', $user, 'profile.php');
?>
<div class="page-head"><div><h1>My profile</h1><p>Manage your account details and password.</p></div></div>
<section class="panel"><div class="profile-row"><div><div class="avatar"><?= e(strtoupper(mb_substr($user['name'],0,1))) ?></div><h3 style="margin:12px 0 2px"><?= e($user['name']) ?></h3><div class="helper"><?= e(ucfirst($user['role'])) ?> account</div></div><div><?php if ($error): ?><div class="notice error"><?= e($error) ?></div><?php endif; ?><form method="post"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><div class="field"><label for="name">Full name</label><input id="name" name="name" maxlength="100" required value="<?= e($user['name']) ?>"></div><div class="field"><label>Email address</label><input value="<?= e($user['email']) ?>" disabled><div class="helper">Contact the admin if your work email needs to change.</div></div><div class="field"><label>Account status</label><div><?= status_badge($user['approval_status']) ?></div></div><hr style="border:0;border-top:1px solid var(--line);margin:25px 0"><h3>Change password</h3><p class="helper">Leave password fields blank if you don't want to change it.</p><div class="field"><label for="current_password">Current password</label><input id="current_password" type="password" name="current_password" autocomplete="current-password"></div><div class="field"><label for="new_password">New password</label><input id="new_password" type="password" name="new_password" minlength="10" autocomplete="new-password"></div><button>Save changes</button></form></div></div></section>
<?php app_end(); ?>

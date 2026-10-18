# OfficeFlow — PHP + MySQL task manager

A clean starter app for a small office team. It uses PHP pages with inline CSS (no separate `index.html` or `style.css`), PDO/MySQL, sessions, CSRF tokens, role checks, registration approval, task assignment, task comments, and office chat.

## Requirements
- PHP 8.1+ (8.2+ recommended)
- MySQL 8+ or compatible MariaDB
- XAMPP, WAMP, MAMP, or a PHP web server

## Install locally with XAMPP
1. Copy the `officeflow` folder into `C:\xampp\htdocs\`.
2. Start Apache and MySQL in XAMPP.
3. Open phpMyAdmin at `http://localhost/phpmyadmin`.
4. Import `sql/officeflow.sql`.
5. Open `config.php` and set `DB_HOST`, `DB_NAME`, `DB_USER`, and `DB_PASS` to your local database credentials.
6. Open `http://localhost/officeflow/setup_admin.php`.
7. Create the admin account using your own email and a strong password.
8. **Delete `setup_admin.php` immediately after creating the admin account.**
9. Open `http://localhost/officeflow/` and sign in as admin.
10. Register member accounts; approve them from the admin dashboard.

## Included pages
- `index.php` — public landing page
- `register.php` — user registration, pending approval
- `login.php` / `logout.php` — session login/logout
- `admin.php` — overview, registration approvals, member access
- `dashboard.php` — member dashboard
- `tasks.php` — create assignments, task list, status updates, task discussion
- `chat.php` — office group chat and admin-started private chats
- `profile.php` — update name and password
- `config.php` — database connection and auth helpers
- `partials.php` — shared layout functions
- `style.php` — inline stylesheet included by PHP pages
- `sql/officeflow.sql` — database schema

## Notes / limitations
- This is a starter MVP, not a finished production service. Test locally before putting it online.
- Chat refreshes on page load; it is not WebSocket real-time chat.
- The starter stores text messages only; file uploads, email notifications, full reporting, and audit logs are not implemented yet.
- The admin setup script is intended for one-time local use and must be deleted afterward.
- For production, use HTTPS, secure session cookie settings, rate limiting, backups, error logging, and a protected deployment process. Never use the default XAMPP database credentials on a public server.
- Configure PHP timezone in `php.ini` (e.g. `Asia/Dhaka`) if you want local time displayed consistently.

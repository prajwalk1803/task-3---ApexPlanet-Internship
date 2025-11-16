# Backend Development & Database Integration - Task 3 (User Management System)

## What this project contains
- PHP + MySQL user management system
- Features: Registration, Login/Logout (sessions), Role-based access (user/admin), CRUD users, Profile edit with image upload
- Secure practices: prepared statements, password_hash, server-side validation for uploads

## Requirements
- PHP 7.4+ with mysqli, fileinfo enabled
- MySQL / MariaDB
- A web server (Apache/Nginx) or built-in PHP server for testing

## Setup
1. Create a database and import `create_db.sql`:
   - `mysql -u root -p < create_db.sql`  (or import via phpMyAdmin)
   - Default DB name in `db.php` is `task3_db`. Change credentials in `db.php` if needed.

2. Place the project folder in your web server root (e.g., `htdocs` or `www`) or run PHP built-in server:
   - `cd exp3_backend_task3`
   - `php -S localhost:8000`
   - Open http://localhost:8000 in your browser.

3. Default seeded admin account:
   - email: admin@example.com
   - password: Admin@123

## Files overview
- db.php: central DB connection
- register.php, login.php, logout.php: auth flow
- users.php: list of users (admin view)
- add_user.php / edit_user.php / delete_user.php: CRUD operations
- profile.php: view/edit own profile and upload picture
- uploads/: store user profile images (ensure writable)
- create_db.sql: SQL to create tables and seed admin

## Notes & security
- This is a teaching scaffold. For production, configure secure DB credentials, HTTPS, input sanitization, CSRF protection, stronger file handling, and more.

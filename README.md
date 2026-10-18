# 📚 Assignment Submission System

PHP + MySQL web app. **Admins** create/edit/delete assignments and grade submissions; **students** submit PDF/DOC/DOCX files before the deadline and view marks.

## Features
- Role-based login (admin / student), hashed passwords, CSRF protection
- Assignment CRUD, one submission per student, deadline enforcement
- File upload validation (type + 5 MB limit), randomized stored filenames
- Admin stats dashboard, grading (0–100), student marks view

## Run locally (XAMPP)
1. Copy this folder to `htdocs/AssignmentSystem`
2. phpMyAdmin → create DB `assignment_db` → Import `schema.sql`
3. Open `http://localhost/AssignmentSystem/`

Demo logins: `admin / admin123`, `student1 / student123`

## Configuration
Set env vars (or edit `config.php`): `DB_HOST`, `DB_USER`, `DB_PASS`, `DB_NAME`, `DB_PORT`.

## Tech
PHP 8 · MySQL/MariaDB · mysqli prepared statements · vanilla CSS

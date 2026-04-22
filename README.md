# Student Management System

A database-driven web application built with pure PHP and MySQL (PDO + OOP) implementing full CRUD operations for student data management.

**Course:** BSE2207 — Emerging Web Development Technologies
**Assignment:** Group Assignment — PHP CRUD System (Without Frameworks)

---

## Project Structure

```
php_assignment1/
├── .env                    ← Local environment variables (DB credentials) — NOT committed to git
├── .env.example            ← Template showing required variables — committed to git
├── .gitignore              ← Excludes .env from version control
├── schema.sql              ← Run this once in MySQL to create the database and table
├── config/
│   ├── env.php             ← Lightweight .env file loader (no external libraries)
│   └── Database.php        ← Singleton PDO connection class (reads from $_ENV)
├── models/
│   └── Student.php         ← Student model with all CRUD query methods (OOP)
├── includes/
│   ├── helpers.php         ← Utility functions: e(), validateStudentForm(), redirect()
│   ├── header.php          ← Shared HTML header and navigation
│   └── footer.php          ← Shared HTML footer
├── assets/
│   └── style.css           ← Full responsive UI styles
├── index.php               ← READ   — lists all student records in a table
├── create.php              ← CREATE — form and POST handler for new students
├── edit.php                ← UPDATE — pre-filled form and POST handler for edits
└── delete.php              ← DELETE — POST-only handler for removing records
```

---

## Requirements

- PHP 8.1 or higher
- MySQL 5.7+ / MariaDB 10.3+
- A local server environment: XAMPP, WAMP, or Laragon

---

## Setup Instructions

### 1. Place the project in your server root

Copy the `php_assignment1/` folder into your server's web root:

- **XAMPP:** `C:\xampp\htdocs\php_assignment1\`
- **WAMP:** `C:\wamp64\www\php_assignment1\`

### 2. Create the database

Open **phpMyAdmin** (or a MySQL CLI) and run the contents of `schema.sql`.
This creates the `student_management` database and the `students` table.

```sql
SOURCE /path/to/php_assignment1/schema.sql;
```

Or paste the file contents directly into the phpMyAdmin SQL tab.

### 3. Configure the database connection

Copy `.env.example` to `.env` and fill in your MySQL credentials:

```bash
cp .env.example .env
```

Then edit `.env`:

```env
DB_HOST=localhost
DB_PORT=3306
DB_NAME=student_management
DB_USER=root
DB_PASS=your_password_here
```

> `.env` is listed in `.gitignore` and will never be committed to version control.

### 4. Open the application

Start Apache and MySQL in XAMPP/WAMP, then visit:

```
http://localhost/php_assignment1/
```

---

## Features

| Requirement | Implementation |
|---|---|
| Full CRUD operations | `index.php`, `create.php`, `edit.php`, `delete.php` |
| PDO with OOP design | `Database.php` singleton, `Student.php` model class |
| Multi-field HTML form | 7 fields: student number, first name, last name, email, course, year, GPA |
| Dynamic tabular display | `index.php` renders all records in a styled HTML table |
| Server-side validation | `helpers.php` — required fields, format checks, range checks |
| Duplicate prevention | Unique constraints on `student_no` and `email`; checked before insert/update |
| PRG pattern | POST → redirect → GET prevents duplicate submissions on page refresh |
| XSS prevention | All output escaped via `htmlspecialchars()` through the `e()` helper |
| CSRF protection | Token stored in session, verified on every POST request |
| SQL injection prevention | PDO prepared statements used throughout all queries |
| Separation of concerns | Config / Model / View / Helpers cleanly separated into layers |

---

## Database Schema

```sql
CREATE TABLE students (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_no    VARCHAR(20)   NOT NULL UNIQUE,
    first_name    VARCHAR(50)   NOT NULL,
    last_name     VARCHAR(50)   NOT NULL,
    email         VARCHAR(100)  NOT NULL UNIQUE,
    course        VARCHAR(100)  NOT NULL,
    year_of_study TINYINT UNSIGNED NOT NULL,  -- 1 to 6
    gpa           DECIMAL(3,2)  DEFAULT NULL, -- 0.00 to 4.00, optional
    created_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at    TIMESTAMP     NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
```

---

## Pages Overview

| Page | Method | Description |
|---|---|---|
| `index.php` | GET | Lists all students; links to Edit and Delete for each row |
| `create.php` | GET / POST | Displays the add-student form; handles submission and validation |
| `edit.php` | GET / POST | Loads an existing record into a pre-filled form; handles updates |
| `delete.php` | POST only | Deletes a student record by ID; redirects back to the list |

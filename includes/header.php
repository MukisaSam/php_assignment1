<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($pageTitle ?? 'Student Management System') ?></title>
    <link rel="stylesheet" href="<?= e($cssPath ?? 'assets/style.css') ?>">
</head>
<body>

<header class="site-header">
    <div class="container">
        <h1 class="site-title">Student Management System</h1>
        <nav class="site-nav">
            <a href="index.php">All Students</a>
            <a href="create.php">Add Student</a>
        </nav>
    </div>
</header>

<main class="container">

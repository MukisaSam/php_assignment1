<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    set_flash('error', 'Invalid request token. Please try again.');
    redirect('create.php');
}

$validation = validate_student_input($_POST);
$data = $validation['data'];
$errors = $validation['errors'];

if ($errors !== []) {
    set_errors($errors);
    set_old_input($data);
    redirect('create.php');
}

try {
    $database = new Database();
    $studentModel = new Student($database->connect());

    if ($studentModel->emailExists($data['email'])) {
        set_errors(['email' => 'A student with this email already exists.']);
        set_old_input($data);
        redirect('create.php');
    }

    $studentModel->create($data);
    clear_old_input();
    set_flash('success', 'Student record created successfully.');
    redirect('index.php');
} catch (Throwable $exception) {
    set_flash('error', 'Could not save student record at the moment.');
    set_old_input($data);
    redirect('create.php');
}

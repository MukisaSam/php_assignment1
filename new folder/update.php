<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

if (!verify_csrf($_POST['csrf_token'] ?? null)) {
    set_flash('error', 'Invalid request token. Please try again.');
    redirect('index.php');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    set_flash('error', 'Invalid student ID provided.');
    redirect('index.php');
}

$validation = validate_student_input($_POST);
$data = $validation['data'];
$errors = $validation['errors'];

if ($errors !== []) {
    set_errors($errors);
    set_old_input($data);
    redirect('edit.php?id=' . (int) $id);
}

try {
    $database = new Database();
    $studentModel = new Student($database->connect());

    if ($studentModel->find((int) $id) === null) {
        set_flash('error', 'Student record not found.');
        redirect('index.php');
    }

    if ($studentModel->emailExists($data['email'], (int) $id)) {
        set_errors(['email' => 'A student with this email already exists.']);
        set_old_input($data);
        redirect('edit.php?id=' . (int) $id);
    }

    $studentModel->update((int) $id, $data);
    clear_old_input();
    set_flash('success', 'Student record updated successfully.');
    redirect('index.php');
} catch (Throwable $exception) {
    set_flash('error', 'Could not update student record at the moment.');
    set_old_input($data);
    redirect('edit.php?id=' . (int) $id);
}

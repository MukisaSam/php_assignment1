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

try {
    $database = new Database();
    $studentModel = new Student($database->connect());

    if ($studentModel->find((int) $id) === null) {
        set_flash('error', 'Student record not found.');
        redirect('index.php');
    }

    $studentModel->delete((int) $id);
    set_flash('success', 'Student record deleted successfully.');
    redirect('index.php');
} catch (Throwable $exception) {
    set_flash('error', 'Could not delete the student record right now.');
    redirect('index.php');
}

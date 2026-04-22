<?php
session_start();
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/models/Student.php';

// Only accept POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('index.php');
}

// CSRF validation
if (empty($_SESSION['csrf_token']) ||
    !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
    http_response_code(403);
    die('Invalid CSRF token.');
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if ($id && $id > 0) {
    $model = new Student();
    $model->delete($id);
}

redirect('index.php?flash=deleted');

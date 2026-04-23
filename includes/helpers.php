<?php

/** HTML-encode a value for safe output. */
function e(mixed $value): string
{
    return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/**
 * Validate and sanitize student form data.
 * Returns ['data' => [...], 'errors' => [...]].
 */
function validateStudentForm(array $post, int $excludeId = 0): array
{
    require_once __DIR__ . '/../models/Student.php';
    $model  = new Student();
    $errors = [];

    $studentNo   = trim($post['student_no']   ?? '');
    $firstName   = trim($post['first_name']   ?? '');
    $lastName    = trim($post['last_name']    ?? '');
    $email       = trim($post['email']        ?? '');
    $course      = trim($post['course']       ?? '');
    $yearRaw     = $post['year_of_study']     ?? '';

    if ($studentNo === '') {
        $errors['student_no'] = 'Student number is required.';
    } elseif (!preg_match('/^[A-Za-z0-9\/\-]{3,20}$/', $studentNo)) {
        $errors['student_no'] = 'Student number must be 3–20 alphanumeric characters (/ and - allowed).';
    } elseif ($model->studentNoExists($studentNo, $excludeId)) {
        $errors['student_no'] = 'This student number is already registered.';
    }

    if ($firstName === '') {
        $errors['first_name'] = 'First name is required.';
    } elseif (strlen($firstName) > 50) {
        $errors['first_name'] = 'First name must be 50 characters or fewer.';
    }

    if ($lastName === '') {
        $errors['last_name'] = 'Last name is required.';
    } elseif (strlen($lastName) > 50) {
        $errors['last_name'] = 'Last name must be 50 characters or fewer.';
    }

    if ($email === '') {
        $errors['email'] = 'Email is required.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Enter a valid email address.';
    } elseif ($model->emailExists($email, $excludeId)) {
        $errors['email'] = 'This email is already registered.';
    }

    if ($course === '') {
        $errors['course'] = 'Course is required.';
    } elseif (strlen($course) > 100) {
        $errors['course'] = 'Course name must be 100 characters or fewer.';
    }

    $year = filter_var($yearRaw, FILTER_VALIDATE_INT);
    if ($year === false || $year < 1 || $year > 6) {
        $errors['year_of_study'] = 'Year of study must be a whole number between 1 and 6.';
        $year = '';
    }

    return [
        'data' => [
            'student_no'    => $studentNo,
            'first_name'    => $firstName,
            'last_name'     => $lastName,
            'email'         => $email,
            'course'        => $course,
            'year_of_study' => $year,
        ],
        'errors' => $errors,
    ];
}

/** Redirect and stop execution. */
function redirect(string $url): never
{
    header('Location: ' . $url);
    exit;
}

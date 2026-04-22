<?php
session_start();
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/models/Student.php';

$pageTitle = 'Add Student';
$cssPath   = 'assets/style.css';

// Generate CSRF token once per session
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$errors = [];
$data   = [
    'student_no'    => '',
    'first_name'    => '',
    'last_name'     => '',
    'email'         => '',
    'course'        => '',
    'year_of_study' => '',
    'gpa'           => '',
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // CSRF check
    if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
        http_response_code(403);
        die('Invalid CSRF token.');
    }

    $result = validateStudentForm($_POST);
    $errors = $result['errors'];
    $data   = $result['data'];

    if (empty($errors)) {
        $model = new Student();
        $model->create($data);
        // Regenerate token so a back+submit doesn't repost
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        redirect('index.php?flash=created');
    }
}

include __DIR__ . '/includes/header.php';
?>

<div class="page-heading">
    <h2>Add New Student</h2>
    <a href="index.php" class="btn btn-secondary">&larr; Back to List</a>
</div>

<div class="card">
    <form method="POST" action="create.php" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token']) ?>">

        <div class="form-row">
            <div class="form-group">
                <label for="student_no">Student Number <span class="required">*</span></label>
                <input type="text" id="student_no" name="student_no"
                       value="<?= e($data['student_no']) ?>"
                       class="<?= isset($errors['student_no']) ? 'is-invalid' : '' ?>"
                       maxlength="20" required>
                <?php if (isset($errors['student_no'])): ?>
                    <span class="field-error"><?= e($errors['student_no']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="year_of_study">Year of Study <span class="required">*</span></label>
                <select id="year_of_study" name="year_of_study"
                        class="<?= isset($errors['year_of_study']) ? 'is-invalid' : '' ?>" required>
                    <option value="">— Select —</option>
                    <?php for ($y = 1; $y <= 6; $y++): ?>
                        <option value="<?= $y ?>" <?= (string)$data['year_of_study'] === (string)$y ? 'selected' : '' ?>>
                            Year <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
                <?php if (isset($errors['year_of_study'])): ?>
                    <span class="field-error"><?= e($errors['year_of_study']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label for="first_name">First Name <span class="required">*</span></label>
                <input type="text" id="first_name" name="first_name"
                       value="<?= e($data['first_name']) ?>"
                       class="<?= isset($errors['first_name']) ? 'is-invalid' : '' ?>"
                       maxlength="50" required>
                <?php if (isset($errors['first_name'])): ?>
                    <span class="field-error"><?= e($errors['first_name']) ?></span>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="last_name">Last Name <span class="required">*</span></label>
                <input type="text" id="last_name" name="last_name"
                       value="<?= e($data['last_name']) ?>"
                       class="<?= isset($errors['last_name']) ? 'is-invalid' : '' ?>"
                       maxlength="50" required>
                <?php if (isset($errors['last_name'])): ?>
                    <span class="field-error"><?= e($errors['last_name']) ?></span>
                <?php endif; ?>
            </div>
        </div>

        <div class="form-group">
            <label for="email">Email Address <span class="required">*</span></label>
            <input type="email" id="email" name="email"
                   value="<?= e($data['email']) ?>"
                   class="<?= isset($errors['email']) ? 'is-invalid' : '' ?>"
                   maxlength="100" required>
            <?php if (isset($errors['email'])): ?>
                <span class="field-error"><?= e($errors['email']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group">
            <label for="course">Course / Programme <span class="required">*</span></label>
            <input type="text" id="course" name="course"
                   value="<?= e($data['course']) ?>"
                   class="<?= isset($errors['course']) ? 'is-invalid' : '' ?>"
                   maxlength="100" required>
            <?php if (isset($errors['course'])): ?>
                <span class="field-error"><?= e($errors['course']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-group" style="max-width:160px">
            <label for="gpa">GPA (0.00 – 4.00)</label>
            <input type="number" id="gpa" name="gpa"
                   value="<?= e($data['gpa']) ?>"
                   class="<?= isset($errors['gpa']) ? 'is-invalid' : '' ?>"
                   min="0" max="4" step="0.01" placeholder="Optional">
            <?php if (isset($errors['gpa'])): ?>
                <span class="field-error"><?= e($errors['gpa']) ?></span>
            <?php endif; ?>
        </div>

        <div class="form-actions">
            <button type="submit" class="btn btn-primary">Save Student</button>
            <a href="index.php" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

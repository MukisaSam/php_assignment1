<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === false || $id === null || $id <= 0) {
    set_flash('error', 'Invalid student ID provided.');
    redirect('index.php');
}

$title = 'Edit Student';
$errors = get_errors();

$database = new Database();
$studentModel = new Student($database->connect());
$student = $studentModel->find((int) $id);

if ($student === null) {
    set_flash('error', 'Student record not found.');
    redirect('index.php');
}

$studentData = [
    'id' => (string) $student['id'],
    'first_name' => old_input('first_name', (string) $student['first_name']),
    'last_name' => old_input('last_name', (string) $student['last_name']),
    'email' => old_input('email', (string) $student['email']),
    'phone' => old_input('phone', (string) $student['phone']),
    'gender' => old_input('gender', (string) $student['gender']),
    'date_of_birth' => old_input('date_of_birth', (string) $student['date_of_birth']),
    'course' => old_input('course', (string) $student['course']),
];

clear_old_input();

require_once __DIR__ . '/includes/header.php';
?>

<section class="card">
    <h2>Edit Student #<?= (int) $studentData['id'] ?></h2>
    <form action="update.php" method="post" novalidate>
        <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
        <input type="hidden" name="id" value="<?= (int) $studentData['id'] ?>">

        <div class="grid">
            <div>
                <label for="first_name">First Name *</label>
                <input id="first_name" name="first_name" type="text" maxlength="100" value="<?= e($studentData['first_name']) ?>" required>
                <?php if (isset($errors['first_name'])): ?><p class="error-text"><?= e($errors['first_name']) ?></p><?php endif; ?>
            </div>

            <div>
                <label for="last_name">Last Name *</label>
                <input id="last_name" name="last_name" type="text" maxlength="100" value="<?= e($studentData['last_name']) ?>" required>
                <?php if (isset($errors['last_name'])): ?><p class="error-text"><?= e($errors['last_name']) ?></p><?php endif; ?>
            </div>

            <div>
                <label for="email">Email *</label>
                <input id="email" name="email" type="email" maxlength="255" value="<?= e($studentData['email']) ?>" required>
                <?php if (isset($errors['email'])): ?><p class="error-text"><?= e($errors['email']) ?></p><?php endif; ?>
            </div>

            <div>
                <label for="phone">Phone *</label>
                <input id="phone" name="phone" type="text" maxlength="20" value="<?= e($studentData['phone']) ?>" required>
                <?php if (isset($errors['phone'])): ?><p class="error-text"><?= e($errors['phone']) ?></p><?php endif; ?>
            </div>

            <div>
                <label for="gender">Gender *</label>
                <select id="gender" name="gender" required>
                    <option value="">Select</option>
                    <?php foreach (['Male', 'Female', 'Other'] as $option): ?>
                        <option value="<?= e($option) ?>" <?= $studentData['gender'] === $option ? 'selected' : '' ?>><?= e($option) ?></option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors['gender'])): ?><p class="error-text"><?= e($errors['gender']) ?></p><?php endif; ?>
            </div>

            <div>
                <label for="date_of_birth">Date of Birth *</label>
                <input id="date_of_birth" name="date_of_birth" type="date" value="<?= e($studentData['date_of_birth']) ?>" required>
                <?php if (isset($errors['date_of_birth'])): ?><p class="error-text"><?= e($errors['date_of_birth']) ?></p><?php endif; ?>
            </div>
        </div>

        <label for="course">Course *</label>
        <input id="course" name="course" type="text" maxlength="120" value="<?= e($studentData['course']) ?>" required>
        <?php if (isset($errors['course'])): ?><p class="error-text"><?= e($errors['course']) ?></p><?php endif; ?>

        <button type="submit">Update Student</button>
    </form>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

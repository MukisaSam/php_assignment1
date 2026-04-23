<?php

declare(strict_types=1);

require_once __DIR__ . '/includes/bootstrap.php';

$title = 'Student List';
$flash = get_flash();
$students = [];
$errorMessage = null;

try {
    $database = new Database();
    $studentModel = new Student($database->connect());
    $students = $studentModel->all();
} catch (Throwable $exception) {
    $errorMessage = 'Unable to load students. Check your database connection and table setup.';
}

require_once __DIR__ . '/includes/header.php';
?>

<section class="card">
    <?php if ($flash !== null): ?>
        <div class="message <?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>

    <?php if ($errorMessage !== null): ?>
        <div class="message error"><?= e($errorMessage) ?></div>
    <?php else: ?>
        <h2>All Students</h2>
        <p>Total: <?= count($students) ?></p>

        <?php if ($students === []): ?>
            <p>No records found yet. Start by adding a student.</p>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Gender</th>
                        <th>Date of Birth</th>
                        <th>Course</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($students as $index => $student): ?>
                    <tr>
                        <td><?= (int) $index + 1 ?></td>
                        <td><?= e($student['first_name'] . ' ' . $student['last_name']) ?></td>
                        <td><?= e($student['email']) ?></td>
                        <td><?= e($student['phone']) ?></td>
                        <td><?= e($student['gender']) ?></td>
                        <td><?= e($student['date_of_birth']) ?></td>
                        <td><?= e($student['course']) ?></td>
                        <td>
                            <div class="actions">
                                <a href="edit.php?id=<?= (int) $student['id'] ?>">Edit</a>
                                <form class="inline" action="delete.php" method="post" onsubmit="return confirm('Delete this student record?');">
                                    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
                                    <input type="hidden" name="id" value="<?= (int) $student['id'] ?>">
                                    <button type="submit" class="danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

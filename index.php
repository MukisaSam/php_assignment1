<?php
session_start();
require_once __DIR__ . '/includes/helpers.php';
require_once __DIR__ . '/models/Student.php';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$pageTitle = 'All Students';

$model    = new Student();
$students = $model->getAll();

// One-time flash message passed via query string (safe: we only show static labels)
$flash = $_GET['flash'] ?? '';

include __DIR__ . '/includes/header.php';
?>

<?php if ($flash === 'created'): ?>
    <div class="flash flash-success">Student record created successfully.</div>
<?php elseif ($flash === 'updated'): ?>
    <div class="flash flash-success">Student record updated successfully.</div>
<?php elseif ($flash === 'deleted'): ?>
    <div class="flash flash-success">Student record deleted successfully.</div>
<?php endif; ?>

<div class="page-heading">
    <h2>Student Records</h2>
    <a href="create.php" class="btn btn-success">+ Add Student</a>
</div>

<div class="table-wrapper">
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Student No.</th>
                <th>Full Name</th>
                <th>Email</th>
                <th>Course</th>
                <th>Year</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($students)): ?>
            <tr>
                <td colspan="8" class="no-records">No student records found. <a href="create.php">Add the first one.</a></td>
            </tr>
        <?php else: ?>
            <?php foreach ($students as $i => $s): ?>
            <tr>
                <td><?= $i + 1 ?></td>
                <td><?= e($s['student_no']) ?></td>
                <td><?= e($s['first_name'] . ' ' . $s['last_name']) ?></td>
                <td><?= e($s['email']) ?></td>
                <td><?= e($s['course']) ?></td>
                <td><?= e($s['year_of_study']) ?></td>
                <td>
                    <div class="btn-group">
                        <a href="edit.php?id=<?= $s['id'] ?>" class="btn btn-warning">Edit</a>
                        <form method="POST" action="delete.php"
                              onsubmit="return confirm('Delete <?= e(addslashes($s['first_name'] . ' ' . $s['last_name'])) ?>? This cannot be undone.');">
                            <input type="hidden" name="id" value="<?= $s['id'] ?>">
                            <input type="hidden" name="csrf_token" value="<?= e($_SESSION['csrf_token'] ?? '') ?>">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>

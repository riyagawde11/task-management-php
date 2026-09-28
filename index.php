<?php
require_once 'db.php';

$message = "";

// 1. CREATE: Handle adding a new task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_task'])) {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $priority = $_POST['priority'];

    if (!empty($title)) {
        $stmt = $pdo->prepare("INSERT INTO tasks (title, description, priority) VALUES (?, ?, ?)");
        $stmt->execute([$title, $description, $priority]);
        header("Location: index.php?msg=added");
        exit;
    } else {
        $message = "Task title cannot be empty!";
    }
}

// 2. UPDATE: Toggle task status (Pending <-> Completed)
if (isset($_GET['action']) && $_GET['action'] === 'toggle' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("UPDATE tasks SET status = IF(status = 'Pending', 'Completed', 'Pending') WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php");
    exit;
}

// 3. DELETE: Remove a task
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->execute([$id]);
    header("Location: index.php?msg=deleted");
    exit;
}

// 4. READ: Fetch all tasks
$stmt = $pdo->query("SELECT * FROM tasks ORDER BY created_at DESC");
$tasks = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task Manager | PHP & MySQL CRUD</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { background-color: #f8f9fa; }
        .completed-task { text-decoration: line-through; color: #6c757d; }
    </style>
</head>
<body>

<nav class="navbar navbar-dark bg-dark mb-4">
    <div class="container">
        <span class="navbar-brand mb-0 h1">📋 Task & Project Tracker</span>
    </div>
</nav>

<div class="container" style="max-width: 800px;">

    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'added'): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            Task added successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php elseif (isset($_GET['msg']) && $_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Task deleted successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- Add Task Card -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add New Task</h5>
        </div>
        <div class="card-body">
            <form action="index.php" method="POST">
                <div class="mb-3">
                    <label class="form-label font-weight-bold">Task Title *</label>
                    <input type="text" name="title" class="form-control" placeholder="e.g. Build Web Scraper" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Description (Optional)</label>
                    <textarea name="description" class="form-control" rows="2" placeholder="Brief details about the task"></textarea>
                </div>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">Priority</label>
                        <select name="priority" class="form-select">
                            <option value="Low">Low</option>
                            <option value="Medium" selected>Medium</option>
                            <option value="High">High</option>
                        </select>
                    </div>
                    <div class="col-md-6 d-flex align-items-end mb-3">
                        <button type="submit" name="add_task" class="btn btn-success w-100">+ Add Task</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Tasks List -->
    <div class="card shadow-sm">
        <div class="card-header bg-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">All Tasks (<?= count($tasks); ?>)</h5>
        </div>
        <div class="card-body p-0">
            <?php if (empty($tasks)): ?>
                <div class="p-4 text-center text-muted">
                    No tasks found. Add a new task above!
                </div>
            <?php else: ?>
                <div class="list-group list-group-flush">
                    <?php foreach ($tasks as $task): ?>
                        <div class="list-group-item d-flex justify-content-between align-items-center p-3">
                            <div>
                                <h6 class="mb-1 <?= $task['status'] === 'Completed' ? 'completed-task' : '' ?>">
                                    <?= htmlspecialchars($task['title']); ?>
                                </h6>
                                <?php if (!empty($task['description'])): ?>
                                    <p class="mb-1 small text-muted"><?= htmlspecialchars($task['description']); ?></p>
                                <?php endif; ?>
                                <small class="text-muted">Added: <?= date('M d, Y', strtotime($task['created_at'])); ?></small>
                                
                                <!-- Priority Badge -->
                                <?php 
                                    $badge = 'secondary';
                                    if ($task['priority'] === 'High') $badge = 'danger';
                                    elseif ($task['priority'] === 'Medium') $badge = 'warning text-dark';
                                    elseif ($task['priority'] === 'Low') $badge = 'info text-dark';
                                ?>
                                <span class="badge bg-<?= $badge ?> ms-2"><?= $task['priority']; ?></span>

                                <!-- Status Badge -->
                                <span class="badge bg-<?= $task['status'] === 'Completed' ? 'success' : 'secondary' ?> ms-1">
                                    <?= $task['status']; ?>
                                </span>
                            </div>

                            <div class="btn-group">
                                <a href="index.php?action=toggle&id=<?= $task['id']; ?>" class="btn btn-sm btn-outline-<?= $task['status'] === 'Completed' ? 'secondary' : 'success' ?>">
                                    <?= $task['status'] === 'Completed' ? 'Mark Pending' : '✓ Done' ?>
                                </a>
                                <a href="index.php?action=delete&id=<?= $task['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Are you sure you want to delete this task?')">
                                    ✕
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<?php
require 'config.php';

// Add new item
if (isset($_POST['add']) && !empty(trim($_POST['item']))) {
    $text = htmlspecialchars(trim($_POST['item']));
    $stmt = $conn->prepare("INSERT INTO shopping_list (text) VALUES (?)");
    $stmt->bind_param("s", $text);
    $stmt->execute();
    header('Location: index.php');
    exit;
}

// Toggle complete
if (isset($_POST['toggle'])) {
    $id = intval($_POST['toggle']);
    $result = $conn->query("SELECT done FROM shopping_list WHERE id = $id");
    if ($row = $result->fetch_assoc()) {
        $new_done = $row['done'] ? 0 : 1;
        $conn->query("UPDATE shopping_list SET done = $new_done WHERE id = $id");
    }
    header('Location: index.php');
    exit;
}

// Delete item
if (isset($_POST['delete'])) {
    $id = intval($_POST['delete']);
    $conn->query("DELETE FROM shopping_list WHERE id = $id");
    header('Location: index.php');
    exit;
}

// Get all tasks
$tasks = [];
$result = $conn->query("SELECT * FROM shopping_list ORDER BY id DESC");
while ($row = $result->fetch_assoc()) {
    $tasks[] = $row;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>🛒 Shopping List</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f3f3;
            display: flex;
            justify-content: center;
            padding-top: 50px;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 12px;
            width: 400px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.2);
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
        }
        form.add-form {
            display: flex;
            gap: 10px;
        }
        form.add-form input[type="text"] {
            flex: 1;
            padding: 10px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }
        form.add-form button {
            background: #28a745;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 6px;
            cursor: pointer;
        }
        ul {
            list-style: none;
            padding: 0;
            margin-top: 20px;
        }
        li {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #eee;
        }
        .done {
            text-decoration: line-through;
            color: #888;
        }
        form.inline {
            display: inline;
        }
        .buttons {
            display: flex;
            gap: 5px;
        }
        .toggle-btn {
            background: #007bff;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
        .delete-btn {
            background: #dc3545;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>🛒 Shopping List</h2>
        <form class="add-form" method="post">
            <input type="text" name="item" placeholder="Add new item..." required>
            <button type="submit" name="add">Add</button>
        </form>

        <ul>
            <?php foreach ($tasks as $task): ?>
                <li>
                    <span class="<?= $task['done'] ? 'done' : '' ?>">
                        <?= htmlspecialchars($task['text']) ?>
                    </span>
                    <div class="buttons">
                        <form class="inline" method="post">
                            <input type="hidden" name="toggle" value="<?= $task['id'] ?>">
                            <button class="toggle-btn">✔</button>
                        </form>
                        <form class="inline" method="post">
                            <input type="hidden" name="delete" value="<?= $task['id'] ?>">
                            <button class="delete-btn">✖</button>
                        </form>
                    </div>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
</body>
</html>

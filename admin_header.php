<?php

session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {

    header("Location: login.php");
    exit;
}


$admin_name = htmlspecialchars($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 0; background-color: #f9f9f9; }
        .admin-header {
            background-color: #222;
            color: white;
            padding: 10px 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .admin-header h1 { margin: 0; font-size: 24px; }
        .admin-header span { font-size: 16px; }
        .admin-nav {
            background-color: #333;
            padding: 10px 0;
            text-align: center;
        }
        .admin-nav a {
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            font-weight: bold;
        }
        .admin-nav a:hover, .admin-nav a.active {
            background-color: #555;
        }
        .admin-container {
            width: 90%;
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>

    <header class="admin-header">
        <h1>Admin Panel</h1>
        <span>Welcome, <?php echo $admin_name; ?>!</span>
    </header>

    <nav class="admin-nav">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_manage_subjects.php">Manage Subjects</a>
        <a href="admin_manage_notes.php">Manage Notes</a>
        <a href="logout.php" style="color: #ffc107;">Logout</a>
    </nav>

    <div class="admin-container"></div>
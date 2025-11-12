

<?php
session_start();

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] == 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: dashboard.php");
    }
    exit;
}
include 'db_connect.php';

$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $email = $_POST['email'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM users WHERE email = ?";
    
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        
        $user = $result->fetch_assoc();

        
        if (password_verify($password, $user['password'])) {
            
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['full_name'];
            $_SESSION['user_type'] = $user['user_type'];

            if ($user['user_type'] == 'admin') {
        
                header("Location: admin_dashboard.php");
            } else {
                
                header("Location: dashboard.php");
            }
            exit; 
            
        } else {
            
            $error_msg = "Invalid email or password.";
        }
    } else {
        
        $error_msg = "Invalid email or password.";
    }
    
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - E-Notes System</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; }
        nav { background-color: #333; padding: 10px; text-align: center; }
        nav a { color: white; padding: 10px 15px; text-decoration: none; font-weight: bold; }
        nav a:hover { background-color: #555; }
        .container { width: 400px; margin: 50px auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #333; }
        .form-group { margin-bottom: 15px; }
        .form-group label { display: block; margin-bottom: 5px; font-weight: bold; }
        .form-group input { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; }
        .btn { width: 100%; padding: 10px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn:hover { background-color: #0056b3; }
        .message { text-align: center; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-weight: bold; }
        .error { background-color: #f8d7da; color: #721c24; }
    </style>
</head>
<body>
    <nav>
        <a href="index.php">Home</a>
        <a href="login.php">Login</a>
        <a href="register.php">Register</a>
    </nav>

    <div class="container">
        <h2>Login</h2>
        <?php if ($error_msg): ?>
            <div class="message error"><?php echo $error_msg; ?></div>
        <?php endif; ?>
        <form action="login.php" method="POST">
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>
</html>
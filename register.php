<?php

include 'db_connect.php';

            
$success_msg = "";
$error_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $full_name = $_POST['full_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if ($password != $confirm_password) {
        $error_msg = "Passwords do not match!";
    } else {
        $sql_check = "SELECT id FROM users WHERE email = ?";
        
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $email);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            $error_msg = "Error: This email is already registered!";
        } else {
            
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $sql_insert = "INSERT INTO users (full_name, email, password, user_type) VALUES (?, ?, ?, 'student')";
            
            $stmt_insert = $conn->prepare($sql_insert);
            
            $stmt_insert->bind_param("sss", $full_name, $email, $hashed_password);

            if ($stmt_insert->execute()) {
                $success_msg = "Registration successful! You can now login.";
            } else {
                $error_msg = "Error: " . $stmt_insert->error;
            }
            
            $stmt_insert->close();
        }
        
        $stmt_check->close();
    }
    
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - E-Notes System</title>
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
        .btn { width: 100%; padding: 10px; background-color: #28a745; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; }
        .btn:hover { background-color: #218838; }
        .message { text-align: center; padding: 10px; border-radius: 5px; margin-bottom: 15px; font-weight: bold; }
        .success { background-color: #d4edda; color: #155724; }
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
        <h2>Student Registration</h2>
        
        <?php if ($success_msg): ?>
            <div class="message success"><?php echo $success_msg; ?></div>
        <?php endif; ?>
        <?php if ($error_msg): ?>
            <div class="message error"><?php echo $error_msg; ?></div>
        <?php endif; ?>

        <form action="register.php" method="POST">
            <div class="form-group">
                <label for="full_name">Full Name:</label>
                <input type="text" id="full_name" name="full_name" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
            </div>
            <button type="submit" class="btn">Register</button>
        </form>
    </div>

</body>
</html>
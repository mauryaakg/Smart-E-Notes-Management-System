<?php
include 'db_connect.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Notes Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body>
  <div class="container mt-4">
    <header class="bg-primary text-white text-center py-3 rounded">
        <h1>Welcome to Online E-Notes Management System</h1>
    </header>

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4 rounded">
      <div class="container-fluid">
        <a class="navbar-brand" href="#">E-Notes</a>
        <div class="collapse navbar-collapse">
          <ul class="navbar-nav ms-auto">
            <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
            <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
            <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
          </ul>
        </div>
      </div>
    </nav>

    <main class="text-center">
      <div class="card shadow-lg border-0 animate__animated animate__fadeInUp">
        <div class="card-body">
          <h2 class="card-title mb-3 text-primary">About Us</h2>
          <p class="card-text">This platform allows students to easily access notes uploaded by faculty.</p>
          <?php
            if ($conn) {
              echo "<p class='text-success fw-bold'>Database Connection Status: Connected!</p>";
            } else {
              echo "<p class='text-danger fw-bold'>Database Connection Status: Failed!</p>";
            }
          ?>
        </div>
      </div>
    </main>

    <footer class="bg-dark text-white text-center py-2 mt-4 rounded">
        <p>&copy; 2025 JAGATPUR PG COLLEGE</p>
    </footer>
  </div>
</body>
</html>
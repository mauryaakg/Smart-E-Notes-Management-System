<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: login.php");
    exit;
}
include 'db_connect.php';
$admin_name = htmlspecialchars($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">E-Notes Admin</a>
    <div class="collapse navbar-collapse" id="navbarNav">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="admin_dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_manage_subjects.php">Manage Subjects</a></li>
        <li class="nav-item"><a class="nav-link" href="admin_manage_notes.php">Manage Notes</a></li>
        <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4 animate__animated animate__fadeInUp">
  <div class="bg-white p-4 rounded shadow">
    <h2 class="text-center text-primary mb-4">Welcome, <?php echo $admin_name; ?> 👋</h2>
    <p class="text-center mb-4">From here, you can manage the entire system efficiently.</p>

    <!-- Search Bar -->
    <form method="GET" class="d-flex justify-content-center mb-4" style="max-width:600px; margin:auto;">
        <input type="text" name="search" class="form-control me-2" placeholder="Search notes or subjects..." 
               value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
        <button class="btn btn-primary">Search</button>
    </form>

    <?php
    $search = isset($_GET['search']) ? $_GET['search'] : '';

    if (!empty($search)) {
        $sql = "SELECT notes.title, subjects.subject_name, subjects.course_name 
                FROM notes 
                JOIN subjects ON notes.subject_id = subjects.id 
                WHERE notes.title LIKE ? OR subjects.subject_name LIKE ? 
                ORDER BY notes.uploaded_at DESC";
        $stmt = $conn->prepare($sql);
        $search_param = "%$search%";
        $stmt->bind_param("ss", $search_param, $search_param);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "<div class='mt-4'>";
            echo "<h4 class='text-success text-center'>Search Results:</h4>";
            echo "<ul class='list-group'>";
            while($row = $result->fetch_assoc()) {
                echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                echo htmlspecialchars($row['title']);
                echo "<span class='badge bg-info'>" . htmlspecialchars($row['course_name']) . " - " . htmlspecialchars($row['subject_name']) . "</span>";
                echo "</li>";
            }
            echo "</ul></div>";
        } else {
            echo "<p class='text-danger text-center'>No results found for your search.</p>";
        }
        $stmt->close();
    } else {
        echo "<div class='row text-center'>";
        echo "<div class='col-md-4 mb-3'><div class='card shadow border-0 animate__animated animate__fadeInLeft'>
                <div class='card-body'>
                  <h5 class='card-title text-primary'>Manage Subjects</h5>
                  <p class='card-text'>Add, edit, or delete subjects and courses.</p>
                  <a href='admin_manage_subjects.php' class='btn btn-outline-primary'>Go</a>
                </div></div></div>";

        echo "<div class='col-md-4 mb-3'><div class='card shadow border-0 animate__animated animate__fadeInUp'>
                <div class='card-body'>
                  <h5 class='card-title text-success'>Manage Notes</h5>
                  <p class='card-text'>Upload, edit, or remove course notes easily.</p>
                  <a href='admin_manage_notes.php' class='btn btn-outline-success'>Go</a>
                </div></div></div>";

        echo "<div class='col-md-4 mb-3'><div class='card shadow border-0 animate__animated animate__fadeInRight'>
                <div class='card-body'>
                  <h5 class='card-title text-danger'>Logout</h5>
                  <p class='card-text'>End your session securely.</p>
                  <a href='logout.php' class='btn btn-outline-danger'>Logout</a>
                </div></div></div>";
        echo "</div>";
    }

    $conn->close();
    ?>
  </div>
</div>

<footer class="bg-dark text-white text-center py-2 mt-4">
  <p>&copy; 2025 JAGATPUR PG COLLEGE | Admin Panel</p>
</footer>

</body>
</html>
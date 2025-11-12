<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'student') {
    header("Location: login.php");
    exit;
}

include 'db_connect.php';
$user_name = htmlspecialchars($_SESSION['user_name']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow">
  <div class="container-fluid">
    <a class="navbar-brand fw-bold" href="#">E-Notes</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link text-warning" href="logout.php">Logout</a></li>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-4 animate__animated animate__fadeInUp">
  <div class="bg-white p-4 rounded shadow-sm mb-4">
    <h2 class="text-center text-primary mb-3">Welcome, <?php echo $user_name; ?> 👋</h2>
    <p class="text-center">Here are the notes available for your subjects. You can search and download them anytime.</p>
  </div>

  <!-- Search Bar -->
  <form method="GET" class="d-flex justify-content-center mb-4" style="max-width:500px;margin:auto;">
      <input type="text" name="search" class="form-control me-2" placeholder="Search notes by title or subject..."
             value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
      <button class="btn btn-primary">Search</button>
  </form>

  <?php
  $search = isset($_GET['search']) ? $_GET['search'] : '';

  if (!empty($search)) {
      $sql_search = "SELECT notes.*, subjects.subject_name, subjects.course_name 
                     FROM notes 
                     JOIN subjects ON notes.subject_id = subjects.id 
                     WHERE notes.title LIKE ? OR subjects.subject_name LIKE ? 
                     ORDER BY notes.uploaded_at DESC";
      $stmt = $conn->prepare($sql_search);
      $search_param = "%$search%";
      $stmt->bind_param("ss", $search_param, $search_param);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
          echo "<div class='row'>";
          while ($note = $result->fetch_assoc()) {
              echo "<div class='col-md-4 mb-4 animate__animated animate__fadeInUp'>";
              echo "<div class='card shadow-sm border-0 h-100'>";
              echo "<div class='card-body'>";
              echo "<h5 class='card-title text-primary'>" . htmlspecialchars($note['title']) . "</h5>";
              echo "<p class='card-text text-muted'>" . htmlspecialchars($note['subject_name']) . " (" . htmlspecialchars($note['course_name']) . ")</p>";
              echo "<a href='uploads/" . htmlspecialchars($note['file_name']) . "' class='btn btn-success btn-sm' download>Download</a>";
              echo "</div></div></div>";
          }
          echo "</div>";
      } else {
          echo "<p class='text-center text-danger fw-bold'>No matching notes found.</p>";
      }
      $stmt->close();
  } else {
      $sql_subjects = "SELECT * FROM subjects ORDER BY course_name, subject_name";
      $result_subjects = $conn->query($sql_subjects);

      if ($result_subjects->num_rows > 0) {
          while ($subject = $result_subjects->fetch_assoc()) {
              $subject_id = $subject['id'];
              echo "<div class='subject-card bg-white rounded shadow-sm mb-4 animate__animated animate__fadeInUp'>";
              echo "<div class='p-3 border-bottom'>";
              echo "<h4 class='text-dark mb-1'>" . htmlspecialchars($subject['subject_name']) . "</h4>";
              echo "<small class='text-muted'>Course: " . htmlspecialchars($subject['course_name']) . "</small>";
              echo "</div>";

              $sql_notes = "SELECT * FROM notes WHERE subject_id = ? ORDER BY title";
              $stmt_notes = $conn->prepare($sql_notes);
              $stmt_notes->bind_param("i", $subject_id);
              $stmt_notes->execute();
              $result_notes = $stmt_notes->get_result();

              if ($result_notes->num_rows > 0) {
                  echo "<ul class='list-group list-group-flush'>";
                  while ($note = $result_notes->fetch_assoc()) {
                      echo "<li class='list-group-item d-flex justify-content-between align-items-center'>";
                      echo htmlspecialchars($note['title']);
                      echo "<a href='uploads/" . htmlspecialchars($note['file_name']) . "' class='btn btn-sm btn-success' download>Download</a>";
                      echo "</li>";
                  }
                  echo "</ul>";
              } else {
                  echo "<p class='text-center text-muted py-3 mb-0'>No notes uploaded for this subject yet.</p>";
              }

              $stmt_notes->close();
              echo "</div>";
          }
      } else {
          echo "<p class='text-center text-danger fw-bold'>No subjects or notes available yet.</p>";
      }
  }

  $conn->close();
  ?>
</div>

<footer class="bg-dark text-white text-center py-2 mt-4">
  <p>&copy; 2025 JAGATPUR PG COLLEGE | Student Dashboard</p>
</footer>

</body>
</html>
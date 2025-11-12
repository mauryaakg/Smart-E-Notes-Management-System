<?php
include 'admin_header.php';
include 'db_connect.php';

$message = ""; 

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_subject'])) {
    
    $course_name = $_POST['course_name'];
    $subject_name = $_POST['subject_name'];

    if (empty($course_name) || empty($subject_name)) {
        $message = "<p class='msg-error'>Both fields are required.</p>";
    } else {
        
        $sql = "INSERT INTO subjects (course_name, subject_name) VALUES (?, ?)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $course_name, $subject_name);
        
        if ($stmt->execute()) {
            $message = "<p class='msg-success'>Subject added successfully!</p>";
        } else {
            $message = "<p class='msg-error'>Error: " . $stmt->error . "</p>";
        }
        $stmt->close();
    }
}

if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    
    
    $sql = "DELETE FROM subjects WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $delete_id);
    
    if ($stmt->execute()) {
        $message = "<p class='msg-success'>Subject deleted successfully! (All related notes are also deleted)</p>";
    } else {
        $message = "<p class='msg-error'>Error deleting record: " . $stmt->error . "</p>";
    }
    $stmt->close();
}

?>

<style>
    .form-container {
        border-bottom: 2px solid #eee;
        padding-bottom: 20px;
        margin-bottom: 20px;
    }
    .form-group { margin-bottom: 10px; }
    .form-group label { display: inline-block; width: 120px; font-weight: bold; }
    .form-group input[type="text"] { width: 250px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; }
    .btn-submit { background-color: #28a745; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
    .btn-submit:hover { background-color: #218838; }
    
    .subjects-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .subjects-table th, .subjects-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    .subjects-table th { background-color: #f2f2f2; }
    .subjects-table tr:nth-child(even) { background-color: #f9f9f9; }
    .btn-delete { background-color: #d9534f; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
    .btn-delete:hover { background-color: #c9302c; }

    .msg-success { background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; }
    .msg-error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; }
</style>

<h2>Manage Subjects</h2>

<?php echo $message; ?>

<div class="form-container">
    <h3>Add New Subject</h3>
    <form action="admin_manage_subjects.php" method="POST">
        <div class="form-group">
            <label for="course_name">Course Name:</label>
            <input type="text" id="course_name" name="course_name" placeholder="e.g., BCA">
        </div>
        <div class="form-group">
            <label for="subject_name">Subject Name:</label>
            <input type="text" id="subject_name" name="subject_name" placeholder="e.g., Web Technology">
        </div>
        <button type="submit" name="add_subject" class="btn-submit">Add Subject</button>
    </form>
</div>

<h3>Existing Subjects</h3>
<table class="subjects-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Course Name</th>
            <th>Subject Name</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // --- 3. सब्जेक्ट्स को डेटाबेस से लाने का लॉजिक ---
        $sql_select = "SELECT * FROM subjects ORDER BY course_name, subject_name";
        $result = $conn->query($sql_select);

        if ($result->num_rows > 0) {
            // हर रो (Row) को लूप करके दिखाना
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . htmlspecialchars($row["course_name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["subject_name"]) . "</td>";
                echo "<td>
                        <a href='admin_manage_subjects.php?delete_id=" . $row["id"] . "' 
                           class='btn-delete' 
                           onclick=\"return confirm('Are you sure you want to delete this subject? This will also delete ALL notes associated with it.');\">
                           Delete
                        </a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='4'>No subjects found. Please add some.</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>

<?php
?>
    </div> </body>
</html>
<?php
include 'admin_header.php';
include 'db_connect.php';

$message = ""; 
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_note'])) {
    
    $subject_id = $_POST['subject_id'];
    $title = $_POST['title'];

    
    if (isset($_FILES['note_file']) && $_FILES['note_file']['error'] == 0) {
        
        $target_dir = "uploads/"; 
        
        $file_name = uniqid() . '_' . basename($_FILES["note_file"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["note_file"]["tmp_name"], $target_file)) {
            
            $sql = "INSERT INTO notes (subject_id, title, file_name) VALUES (?, ?, ?)";
            
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("iss", $subject_id, $title, $file_name); // i = integer, s = string
            
            if ($stmt->execute()) {
                $message = "<p class='msg-success'>Note uploaded successfully!</p>";
            } else {
                $message = "<p class='msg-error'>Error inserting data into database: " . $stmt->error . "</p>";
                unlink($target_file);
            }
            $stmt->close();

        } else {
            $message = "<p class='msg-error'>Sorry, there was an error uploading your file.</p>";
        }
    } else {
        $message = "<p class='msg-error'>No file selected or error in upload.</p>";
    }
}
if (isset($_GET['delete_note_id'])) {
    $delete_id = $_GET['delete_note_id'];

    $sql_find = "SELECT file_name FROM notes WHERE id = ?";
    $stmt_find = $conn->prepare($sql_find);
    $stmt_find->bind_param("i", $delete_id);
    $stmt_find->execute();
    $result_find = $stmt_find->get_result();
    
    if ($result_find->num_rows == 1) {
        $row = $result_find->fetch_assoc();
        $file_to_delete = "uploads/" . $row['file_name'];

        $sql_delete = "DELETE FROM notes WHERE id = ?";
        $stmt_delete = $conn->prepare($sql_delete);
        $stmt_delete->bind_param("i", $delete_id);

        if ($stmt_delete->execute()) {
            if (file_exists($file_to_delete)) {
                unlink($file_to_delete); 
            }
            $message = "<p class='msg-success'>Note deleted successfully!</p>";
        } else {
            $message = "<p class='msg-error'>Error deleting record: " . $stmt_delete->error . "</p>";
        }
        $stmt_delete->close();
    }
    $stmt_find->close();
}

?>

<style>
    .form-container { border-bottom: 2px solid #eee; padding-bottom: 20px; margin-bottom: 20px; }
    .form-group { margin-bottom: 10px; }
    .form-group label { display: inline-block; width: 120px; font-weight: bold; }
    .form-group input[type="text"], .form-group select { width: 250px; padding: 5px; border: 1px solid #ccc; border-radius: 4px; }
    .btn-submit { background-color: #28a745; color: white; padding: 8px 15px; border: none; border-radius: 4px; cursor: pointer; }
    .btn-submit:hover { background-color: #218838; }
    
    .notes-table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    .notes-table th, .notes-table td { border: 1px solid #ddd; padding: 8px; text-align: left; }
    .notes-table th { background-color: #f2f2f2; }
    .notes-table tr:nth-child(even) { background-color: #f9f9f9; }
    .btn-delete { background-color: #d9534f; color: white; padding: 5px 10px; text-decoration: none; border-radius: 4px; }
    .btn-delete:hover { background-color: #c9302c; }

    .msg-success { background-color: #d4edda; color: #155724; padding: 10px; border-radius: 5px; }
    .msg-error { background-color: #f8d7da; color: #721c24; padding: 10px; border-radius: 5px; }
</style>

<h2>Manage Notes</h2>

<?php echo $message; ?>

<div class="form-container">
    <h3>Upload New Note</h3>
    
    <form action="admin_manage_notes.php" method="POST" enctype="multipart/form-data">
        
        <div class="form-group">
            <label for="subject_id">Subject:</label>
            <select id="subject_id" name="subject_id" required>
                <option value="">-- Select a Subject --</option>
                <?php
                // --- 3. सब्जेक्ट्स को डेटाबेस से लाकर Dropdown में दिखाना ---
                $sql_subjects = "SELECT * FROM subjects ORDER BY course_name, subject_name";
                $result_subjects = $conn->query($sql_subjects);
                if ($result_subjects->num_rows > 0) {
                    while($row = $result_subjects->fetch_assoc()) {
                        // ऑप्शन में हम सब्जेक्ट की ID (value) और नाम (text) दिखाएंगे
                        echo "<option value='" . $row['id'] . "'>"
                             . htmlspecialchars($row['course_name']) . " - " . htmlspecialchars($row['subject_name']) 
                             . "</option>";
                    }
                }
                ?>
            </select>
        </div>
        
        <div class="form-group">
            <label for="title">Note Title:</label>
            <input type="text" id="title" name="title" placeholder="e.g., Unit 1 - Introduction" required>
        </div>
        
        <div class="form-group">
            <label for="note_file">File:</label>
            <input type="file" id="note_file" name="note_file" required>
        </div>
        
        <button type="submit" name="upload_note" class="btn-submit">Upload Note</button>
    </form>
</div>

<h3>Uploaded Notes</h3>
<table class="notes-table">
    <thead>
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Subject</th>
            <th>File Name</th>
            <th>Uploaded At</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
        
        $sql_select = "SELECT notes.id, notes.title, notes.file_name, notes.uploaded_at, 
                              subjects.course_name, subjects.subject_name 
                       FROM notes 
                       JOIN subjects ON notes.subject_id = subjects.id
                       ORDER BY notes.uploaded_at DESC";
                       
        $result = $conn->query($sql_select);

        if ($result->num_rows > 0) {
            while($row = $result->fetch_assoc()) {
                echo "<tr>";
                echo "<td>" . $row["id"] . "</td>";
                echo "<td>" . htmlspecialchars($row["title"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["course_name"]) . " - " . htmlspecialchars($row["subject_name"]) . "</td>";
                echo "<td>" . htmlspecialchars($row["file_name"]) . "</td>";
                echo "<td>" . $row["uploaded_at"] . "</td>";
                echo "<td>
                        <a href='admin_manage_notes.php?delete_note_id=" . $row["id"] . "' 
                           class='btn-delete' 
                           onclick=\"return confirm('Are you sure you want to delete this note?');\">
                           Delete
                        </a>
                      </td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='6'>No notes found. Please upload some.</td></tr>";
        }
        $conn->close();
        ?>
    </tbody>
</table>

<?php
?>
    </div> </body>
</html>
<?php
$servername = "localhost";
$username = "coursweb";
$password = "coursweb";
$dbname = "coursweb";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

// Retrieve all students and their grades for the selected course
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['course_id'])) {
  $course_id = $_GET['course_id'];

  $sql = "SELECT s.num, s.f_name, s.l_name, n.note
          FROM students s
          LEFT JOIN notes n ON s.num = n.student_id AND n.course_id = $course_id";

  $result = mysqli_query($conn, $sql);

  if (!$result) {
    die("Error retrieving student data: " . mysqli_error($conn));
  }
}

// Handle form submission for updating grades
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['course_id'])) {
  $course_id = $_POST['course_id'];
  $student_ids = $_POST['student_id'];
  $grades = $_POST['grade'];

  // Prepare a single SQL statement to update or insert grades for each student
  $sql = "INSERT INTO notes (student_id, course_id, note) VALUES ";
  $sql_values = array();
  for ($i = 0; $i < count($student_ids); $i++) {
    $student_id = $student_ids[$i];
    $grade = mysqli_real_escape_string($conn, $grades[$i]); // Escape string to prevent SQL injection

    // Validate the grade input
    if (!is_numeric($grade)) {
      // Handle invalid input
      echo "Erreur de mise à jour des notes : la note doit être un nombre !";
      exit();
    }
    $sql_values[] = "($student_id, $course_id, $grade)";

  }
  $sql .= implode(", ", $sql_values);
  $sql .= " ON DUPLICATE KEY UPDATE note = VALUES(note)";

  if (mysqli_query($conn, $sql)) {
    echo "Notes mises à jour avec succès !";
  } else {
    echo "Erreur de mise à jour des notes : " . mysqli_error($conn);
  }
}

mysqli_close($conn);
?>

<!DOCTYPE html>
<html>
<head>
  <title>Attribution des notes</title>
</head>
<body>
<a href="index.html">Acceuil</a>
  <a href="courses.php">Retour à la liste des cours</a>
  <h2>Attribution des notes :</h2>

  <?php if (isset($result)): ?>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="hidden" name="course_id" value="<?php echo $course_id; ?>">
    <table>
      <thead>
        <tr>
          <th>Nom</th>
          <th>Prénom</th>
          <th>Note</th>
        </tr>
      </thead>
      <tbody>
        <?php while ($row = mysqli_fetch_assoc($result)): ?>
        <tr>

          <td><?php echo $row['l_name']; ?></td>
          <td><?php echo $row['f_name']; ?></td>
          <td>
            <input type="text" name="grade[]" value="<?php echo $row['note']; ?>">
            <input type="hidden" name="student_id[]" value="<?php echo $row['num']; ?>">
          </td>
        </tr>
        <?php endwhile; ?>
      </tbody>
    </table>
    <input type="submit" name="submit" value="Enregistrer">
  </form>
 

  <?php endif; ?>
</body>
</html>
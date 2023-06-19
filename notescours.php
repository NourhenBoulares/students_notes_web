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

// Get the course ID from the URL parameter
if (isset($_GET['course_id'])) {
  $course_id = $_GET['course_id'];
} else {
  die("Missing course ID parameter");
}

// Retrieve the course name
$course_result = mysqli_query($conn, "SELECT name FROM course WHERE id='$course_id'");
if (!$course_result) {
  die("Error retrieving course name: " . mysqli_error($conn));
}
$course_row = mysqli_fetch_assoc($course_result);
$course_name = $course_row['name'];

// Retrieve the students and their grades for the course
$students_result = mysqli_query($conn, "SELECT students.student_id,students.f_name, students.l_name, notes.note FROM students LEFT JOIN notes ON students.num=notes.student_id AND notes.course_id='$course_id'");
if (!$students_result) {
  die("Error retrieving student grades: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Notes du cours <?php echo $course_name; ?></title>
</head>
<body>
<a href="index.html">Acceuil</a>
  <h1>Notes du cours <?php echo $course_name; ?></h1>

  <table>
    <thead>
      <tr>
		<th>ID Etudiant</th>
        <th>Prénom</th>
        <th>Nom</th>
        <th>Note</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = mysqli_fetch_assoc($students_result)): ?>
        <tr>
		  <td><?php echo $row['student_id']; ?></td>
          <td><?php echo $row['f_name']; ?></td>
          <td><?php echo $row['l_name']; ?></td>
          <td><?php echo $row['note'] !== null ? $row['note'] : 'N/A'; ?></td>
        </tr>
      <?php endwhile; ?>
    </tbody>
  </table>

  <p><a href="attribuernotes.php?course_id=<?php echo $course_id; ?>">Attribuer des notes</a></p>
</body>
</html>
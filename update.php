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
echo "Connected successfully";

// Get the student ID from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_student_id'])) {
  $student_id = $_POST['update_student_id'];

  // Retrieve the student data from the database
  $result = mysqli_query($conn, "SELECT * FROM students WHERE student_id='$student_id'");

  if (!$result) {
    die("Error retrieving student data: " . mysqli_error($conn));
  }

  $student = mysqli_fetch_assoc($result);
}

// Handle form submission for updating a student
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_student'])) {
  $f_name = $_POST["f_name"];
  $l_name = $_POST["l_name"];
  $student_id = $_POST["student_id"];
  $email = $_POST["email"];
  $subscription = $_POST["subscription"];

  // Requête SQL pour mettre à jour les données de l'étudiant
  $sql = "UPDATE students SET f_name='$f_name', l_name='$l_name', email='$email', subscription='$subscription' WHERE student_id='$student_id'";

  if (mysqli_query($conn, $sql)) {
    echo "Données de l'étudiant mises à jour avec succès !";
  } else {
    echo "Erreur de mise à jour d'étudiant : " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Modifier un étudiant</title>
</head>
<body>
  <a href="students.php">Retour à la liste des étudiants</a>
  <h2>Modifier un étudiant :</h2>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="hidden" name="student_id" value="<?php echo $student['student_id']; ?>">
    <label for="f_name">Prénom :</label>
    <input type="text" id="f_name" name="f_name" value="<?php echo $student['f_name']; ?>"><br>

    <label for="l_name">Nom :</label>
    <input type="text" id="l_name" name="l_name" value="<?php echo $student['l_name']; ?>"><br>

    <label for="email">Adresse e-mail :</label>
    <input type="email" id="email" name="email" value="<?php echo $student['email']; ?>"><br>

    <label for="subscription">Date d'inscription :</label>
    <input type="date" id="subscription" name="subscription" value="<?php echo $student['subscription']; ?>"><br>

    <input type="submit" name="update_student" value="Mettre à jour">
  </form>
</body>
</html>

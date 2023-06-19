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


// Get the cours ID from the form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_id'])) {
  $id = $_POST['update_id'];

  // Retrieve the cours data from the database
  $result = mysqli_query($conn, "SELECT * FROM course WHERE id='$id'");

  if (!$result) {
    die("Error retrieving cours data: " . mysqli_error($conn));
  }

  $course = mysqli_fetch_assoc($result);
}

// Handle form submission for updating a course
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_course'])) {
    $id= $_POST["id"];
  $name = $_POST["name"];
  $responsible = $_POST["responsible"];
 
 

  // Requête SQL pour mettre à jour les données de l'étudiant
  $sql = "UPDATE course SET id='$id', name='$name', responsible='$responsible' WHERE id='$id'";

  if (mysqli_query($conn, $sql)) {
    echo "Données de cours mises à jour avec succès !";
  } else {
    echo "Erreur de mise à jour  : " . mysqli_error($conn);
  }
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Modifier un cours</title>
</head>
<body>
    <br>
  <a href="courses.php">Retour à la liste des étudiants</a>
  <h2>Modifier un étudiant :</h2>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <input type="hidden" name="id" value="<?php echo $course['id']; ?>">
    <label for="name">Nom du cours :</label>
    <input type="text" id="name" name="name" value="<?php echo $course['name']; ?>"><br>

    <label for="responsible">Responsable :</label>
    <input type="text" id="responsible" name="responsible" value="<?php echo $course['responsible']; ?>"><br>

    

    <input type="submit" name="update_course" value="Mettre à jour">
  </form>
</body>
</html>

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



//Traitement du formulaire d'ajout d'étudiant
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $f_name = $_POST["f_name"];
  $l_name = $_POST["l_name"];
  $student_id = $_POST["student_id"];
  $email = $_POST["email"];
  $subscription = $_POST["subscription"];

  // Requête SQL d'insertion d'un nouvel étudiant
  $sql = "INSERT INTO students (f_name, l_name, student_id, email, subscription)
  VALUES ('$f_name', '$l_name', '$student_id', '$email', '$subscription')";

  if (mysqli_query($conn, $sql)) {
    echo "Nouvel étudiant ajouté avec succès !";
  } 
  
}
// Retrieve student data from the database and store it in the $students variable

?>

<!DOCTYPE html>
<html>
<head>
  <title>étudiants</title>
</head>
<body>
  <br>
  <a href="index.html">Retour à la page d'accueil</a>
  <h2>Ajouter un nouvel étudiant :</h2>
  <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="f_name">Prénom :</label>
    <input type="text" id="f_name" name="f_name"><br>

    <label for="l_name">Nom :</label>
    <input type="text" id="l_name" name="l_name"><br>

    <label for="student_id">ID de l'étudiant :</label>
    <input type="number" id="student_id" name="student_id"><br>

    <label for="email">Adresse e-mail :</label>
    <input type="email" id="email" name="email"><br>

    <label for="subscription">Date d'inscription :</label>
    <input type="date" id="subscription" name="subscription"><br>

    <input type="submit" value="Ajouter">
  </form>

  <?php
  // Retrieve student data from the database and store it in the $students variable
  $sorting_options = array("student_id", "f_name", "l_name", "subscription");
  $selected_sorting_option = isset($_GET['sort']) ? $_GET['sort'] : $sorting_options[0];

  if (!in_array($selected_sorting_option, $sorting_options)) {
    $selected_sorting_option = $sorting_options[0];
  }

  $students = $conn->query("SELECT * FROM students ORDER BY $selected_sorting_option")->fetch_all(MYSQLI_ASSOC);
  ?>

  <!-- HTML table to display student data -->
  <table>
    <thead>
      <tr>
      <th><a href="?sort=f_name">Prénom</a></th>
    <th><a href="?sort=l_name">Nom</a></th>
    <th><a href="?sort=student_id">ID de l'étudiant</a></th>
    <th><a href="?sort=email">Adresse e-mail</a></th>
    <th><a href="?sort=subscription">Date d'inscription</a></th>
    <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($students as $student): ?>
        <tr>
          <td><?php echo $student['f_name']; ?></td>
          <td><?php echo $student['l_name']; ?></td>
          <td><?php echo $student['student_id']; ?></td>
          <td><?php echo $student['email']; ?></td>
          <td><?php echo $student['subscription']; ?></td>
          <td>
          <!-- Delete button -->
<form name="form2" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <input type="hidden" name="delete_student_id" value="<?php echo $student['student_id']; ?>">
  <input type="submit" value="Supprimer">
</form>
<!-- Update button -->
<form method="post" action="update.php">
  <input type="hidden" name="update_student_id" value="<?php echo $student['student_id']; ?>">
  <input type="submit" value="Modifier">
</form>
<?php endforeach; ?>
</tbody>
</table>
<?php
//Traitement du formulaire de suppression d'étudiant
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_student_id'])) {
  $student_id = $_POST['delete_student_id'];

  // Requête SQL de suppression de l'étudiant correspondant à l'ID
  $sql = "DELETE FROM students WHERE student_id='$student_id'";

  if (mysqli_query($conn, $sql)) {
    echo "Etudiant supprimé avec succès !";
  } else {
    echo "Erreur de suppression d'étudiant : " . mysqli_error($conn);
  }
}
$conn->close();
?>
</body>
</html>
           

<!DOCTYPE html>
<html>
<head>
	<title>Liste des cours</title>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
</head>
<body>
	<header>
		<h1>Liste des cours</h1>
		<nav>
			<a href="index.html">Accueil</a>
		</nav>
	</header>
	<main>
		<section>
			
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				
				<label for="name">Nom :</label>
				<input type="text" name="name" required>
				<label for="responsible">Responsable :</label>
				<input type="text" name="responsible" required>
				<input type="submit" name="submit" value="Ajouter">
			</form>
		</section>
		<section>
			<h2>Liste des cours</h2>
			
				
				
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
                            $name = $_POST["name"];
                            $responsible = $_POST["responsible"];
                        
                            // Check if name and responsible fields are not empty
                            if (!empty($name) && !empty($responsible)) {
                                // Requête SQL d'insertion d'un nouvel étudiant
                                $sql = "INSERT INTO course (name, responsible) VALUES ('$name', '$responsible')";
                            
                                if (mysqli_query($conn, $sql)) {
                                    echo "Nouvel cours ajouté avec succès !";
                                } 
                            } 
                        }
                        
                        
                        // Retrieve course data from the database and store it in the $cours variable
                        $sorting_options = array("id", "name", "responsible");
                        $selected_sorting_option = isset($_GET['sort']) ? $_GET['sort'] : $sorting_options[0];
                        if (!in_array($selected_sorting_option, $sorting_options)) {
                            $selected_sorting_option = $sorting_options[0];
                        }
                        $course = $conn->query("SELECT * FROM course ORDER BY $selected_sorting_option")->fetch_all(MYSQLI_ASSOC);
                        ?>
                        

                        <table>
        <thead>
            <tr>
                <th><a href="?sort=id">Id</a></th>
                <th><a href="?sort=name">Nom du cours</a></th>
                <th><a href="?sort=responsible">Responsable</a></th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($course as $course_item): ?>
            <tr>
                <td><?php echo $course_item['id']; ?></td>
                <td><?php echo $course_item['name']; ?></td>
                <td><?php echo $course_item['responsible']; ?></td>
                <td>
                    <!-- Delete button -->
                    <form name='f1' method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                        <input type="hidden" name="delete_id" value="<?php echo $course_item['id']; ?>">
                        <input type="submit" name="delete_form" value="Supprimer">
                    </form>
                    <!-- Update button -->
                    <form method="post" action="update_cours.php">
                        <input type="hidden" name="update_id" value="<?php echo $course_item['id']; ?>">
                        <input type="submit" value="Modifier">
                   </form>
                   <form method="post" action="notescours.php?course_id=<?php echo $course_item['id']; ?>">
                        <input type="submit" value="Notes">
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <?php
    //Traitement du formulaire de suppression d'étudiant
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_form']) && isset($_POST['delete_id'])) {
        $id = $_POST['delete_id'];
        // Requête SQL de suppression de l'étudiant correspondant à l'ID
        $sql = "DELETE FROM course WHERE id='$id'";
        if (mysqli_query($conn, $sql)) {
            echo "Cours supprimé avec succès !";
        } else {
            echo "Erreur de suppression  : " . mysqli_error($conn);
        }
    }
    $conn->close();
    ?>
</section>

				

			
		</section>
	</main>
</body>
</html>

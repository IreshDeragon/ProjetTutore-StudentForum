<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();

//recupere l id du stage
$id_representant = $_GET['id_representant'];

//recupere le nom, prénom et mail du représentant
$req_donne_representant = $linkpdo-> prepare('SELECT * FROM representant WHERE id_representant=:id_representant');
$req_donne_representant->execute(array('id_representant' => $id_representant));
$row_donne_representant = $req_donne_representant->fetch();

// Si la variable "$_Post" contient des informations alors on les traitres
    if(!empty($_POST)){
    	extract($_POST);
    	if (isset($_POST['modifier'])){

				$req_modifier_representant = $linkpdo->prepare("UPDATE representant SET id_representant=:id_representant, nom_representant=:nom_representant,prenom_representant=:prenom_representant,email_representant=:email_representant WHERE id_representant=:id_representant");

    			$req_modifier_representant->execute(array('id_representant' => $id_representant,
    					'nom_representant' =>$_POST['nom_representant'],
                        'prenom_representant' => $_POST['prenom_representant'],
                        'email_representant' => $_POST['email_representant']));

    			header('Location: /Ptut/profil_entreprise.php');
                exit;
		}
    }
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css" integrity="sha384-Uu6IeWbM+gzNVXJcM9XV3SohHtmWE+3VGi496jvgX1jyvDTXfdK+rfZc8C1Aehk5" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/purecss@1.0.1/build/base-min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta charset="utf-8">
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="squelette.css">
    <title>Forum</title>
</head>
<body>
	<?php include("menu.php"); ?>
	<main><br>
		<h1>Modifier représentant</h1>
    <div class="modifierRepresentant">
    <br>
    <form method="post">

    	Nom du repésentant<br>
    	<input type="nom_representant" placeholder="Nom représentant" pattern="[a-zA-ZÀ-ÿ-\-]{1,}" name="nom_representant" value="<?php echo $row_donne_representant['nom_representant'];?>" required><br><br>

    	Prénom du représentant<br>
    	<input type="prenom_representant" placeholder="Prénom représentant" pattern="[a-zA-ZÀ-ÿ-\-]{1,}" name="prenom_representant" value="<?php echo $row_donne_representant['prenom_representant'];?>" required><br><br>

    	Email du représentant<br>
    	<input type="email" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" size="30" placeholder="Email du représentant" name="email_representant" value="<?php echo $row_donne_representant['email_representant'];?>" required><br><br>

        <br>

        <button type="submit" style="width:100px" name="modifier">Modifier</button>
        <br><br><br>

       
    	<a style="text-decoration: none;color:black;" class="boutonretour" href="profil_entreprise.php">Retour</a>
    	<br><br>

    </form>
    </div>
    <br><br><br>
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
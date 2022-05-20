<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();
// Si la variable "$_Post" contient des informations alors on les traitres
    if(!empty($_POST)){
    	extract($_POST);
    	if (isset($_POST['ajouter'])){

    		//requête qui vérifie si ce représentant existe déjà
			$req_verif_representant = $linkpdo-> prepare('SELECT * FROM representant WHERE id_entreprise =:id_entreprise AND nom_representant =:nom_representant AND prenom_representant =:prenom_representant AND email_representant =:email_representant');
			$req_verif_representant->execute(array('id_entreprise' => $_SESSION['id'],
						'nom_representant' =>$_POST['nom_representant'],
						'prenom_representant' => $_POST['prenom_representant'],
						'email_representant' => $_POST['email_representant']));
   			$row_verif_representant = $req_verif_representant->fetch();   		
		

			//Si la requête précédente n'a rien renvoyer alors on ajoute le représentant dans la base de données 
    		if (empty($row_verif_representant)){
				$req_ajouter_representant = $linkpdo->prepare("INSERT INTO representant (id_entreprise,nom_representant,prenom_representant,email_representant) VALUES(:id_entreprise,:nom_representant, :prenom_representant, :email_representant)");

    			$req_ajouter_representant->execute(array('id_entreprise' => $_SESSION['id'],
    					'nom_representant' =>$_POST['nom_representant'],
                        'prenom_representant' => $_POST['prenom_representant'],
                        'email_representant' => $_POST['email_representant']));

    			header('Location: /Ptut/profil_entreprise.php');
                exit;
			}
			else{
				$erreur = "Le représentant existe déjà pour cette entreprise!";
			}
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
		<h1>Ajout d'un représentant</h1>
    <div class="ajouterrepresentant">
    <br>
    <form method="post">

    	Nom du repésentant<br>
    	<input type="nom_representant" placeholder="Nom représentant" pattern="[a-zA-ZÀ-ÿ-\-]{1,}" name="nom_representant" value="<?php if(isset($nom_representant)){ echo $nom_representant; }?>" required><br><br>

    	Prénom du représentant<br>
    	<input type="prenom_representant" placeholder="Prénom représentant" pattern="[a-zA-ZÀ-ÿ-\-]{1,}" name="prenom_representant" value="<?php if(isset($prenom_representant)){ echo $prenom_representant; }?>" required><br><br>

    	Email du représentant<br>
    	<input type="email" id="email" pattern="[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$" size="30" placeholder="Email du représentant" name="email_representant" value="<?php if(isset($email_representant)){ echo $email_representant; }?>" required><br><br>

        <?php
        if (isset($erreur)){?>
            <div><?= $erreur ?></div>
        <?php } ?>
        <br>

        <button type="submit" style="width:100px" name="ajouter">Ajouter</button>
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
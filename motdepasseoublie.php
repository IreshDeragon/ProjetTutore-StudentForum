<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 

require('librairie.php');
$linkpdo = connexion_serveur_mysql();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="squelette.css">
	<title>Forum</title>
</head>
<body>
	<?php include("menu.php"); ?>
	<main>

		<h2>Mot de passe oublié :</h2>
    	<div class="connexion_etudiant">
    	<br>
    
    	<?php if(!isset($_POST['emailRecuperationMdp']))
    	{    ?>
    		<form method="post" action="motdepasseoublié.php"> 
        		<br><br>

        	  	E-mail contact    :  <input type="email" placeholder="email" name="emailRecuperationMdp" value="" required><br><br>
          
        	  	<button type="submit" name="connexion">Envoyer </button> <button type="button" name="annuler"><a style="text-decoration: none;" href="connexion.php">Annuler </a></button>
          
    		</form>
    	<?php 
    	} 
    	else 
    	{

    	?>



    		<p> Un mail à été envoyé à l'adresse e-mail entrée (si elle est reliée à un compte existant). </p>

    	<?php } ?>

    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br>
	
        
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
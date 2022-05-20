<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();

$reqRDV = $linkpdo->prepare("SELECT entreprise.nom_entreprise AS nom_entreprise, representant.nom_representant AS nom_representant, representant.prenom_representant AS prenom_representant, stage.intitule AS intitule, creneaux.date_horaire AS date_horaire, representant.id_representant AS id_representant, stage.id_stage AS id_stage FROM creneaux, entreprise, representant, stage WHERE creneaux.id_etudiant = :etudiant AND representant.id_entreprise = entreprise.id_entreprise AND creneaux.id_stage = stage.id_stage AND creneaux.id_representant = representant.id_representant");
$reqRDV->execute(array("etudiant" => $_SESSION['id']));

if(isset($_POST["boutonAnnulerCreneau"]))
{
	$reqSuppr = $linkpdo->prepare("CALL supressionCreneau(:etudiant, :representant, :stage, :date_heure);");
	$reqSuppr->execute(array("etudiant" => $_SESSION['id'], "representant" => $_POST['id_representant'], "stage" => $_POST['id_stage_affiche'], "date_heure" => $_POST['date_horaire_creneau_suppr']));
	header("Refresh:0");
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
	<main><br/>

	<div class = "consultationMenu">
		<h1>Mes rendez-vous</h1>
    	<br/><br/>
    	<?php

    		while($data = $reqRDV->fetch())
    		{
    			echo "<div class = \"creneauEtudiant\"> <h3> Entreprise : ".$data['nom_entreprise']."</h3><br/> <b> Intitulé : </b> ".$data['intitule']."<br/> <b> Répresentant : </b> ".$data['nom_representant']." ".$data['prenom_representant']."<br/> <b> Date et heure : </b>".$data['date_horaire']."<br/> 

    			<form class=\"formVoirAnnonce pure-form\" method = \"POST\" action = \"ListeStagesEtudiant.php\"> <input type=\"hidden\" value = \"".$data['id_stage']."\" name=\"id_stage_affiche\" /><button class = \"boutonVoirAnnonce pure-button\" name = \"boutonVoirAnnonce\" type = \"submit\"> Voir l'annonce </button> </form>"."

    			<form class=\"pure-form\" method = \"POST\" action = \"ConsultationMenu.php\"> 
    			<input type=\"hidden\" value = \"".$data['id_representant']."\" name=\"id_representant\" />
    			<input type=\"hidden\" value = \"".$data['id_stage']."\" name=\"id_stage_affiche\" />
    			<input type=\"hidden\" value = \"".$data['date_horaire']."\" name=\"date_horaire_creneau_suppr\" />
    			<button class = \"boutonAnnulerCreneau pure-button\" name = \"boutonAnnulerCreneau\" type = \"submit\"> Annuler le rendez-vous </button> 
    			</form> 
    			</div>";
    		}

    	?>
    	<br/><br/>
	<div>
        
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
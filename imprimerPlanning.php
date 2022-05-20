<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();

require("affichageDuPlanning2.php");

//recupère les post
$id_representant = $_POST['id_representant'];
$id_entreprise = $_POST['id_entreprise'];
$periode = $_POST['periode'];
$date_forum = $_POST['date_forum'];

//récupérer le nom de l'entreprise

$req_nom_entreprise = $linkpdo->prepare('SELECT nom_entreprise FROM entreprise WHERE id_entreprise=:id_entreprise');
$req_nom_entreprise ->execute(array('id_entreprise' => $id_entreprise));
$row_req_nom_entreprise = $req_nom_entreprise->fetch();

//récupérer le nom et prénom du représentant
$req_nom_representant = $linkpdo->prepare('SELECT nom_representant, prenom_representant FROM representant WHERE id_representant=:id_representant');
$req_nom_representant ->execute(array('id_representant' => $id_representant));
$row_req_nom_representant = $req_nom_representant->fetch();

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
	<script src="imprimer.js"></script>
	<title>Forum</title>
</head>
<body>
	<?php include("menu.php"); ?>
	<main><br>
		<h1>Imprimer Planning</h1>
		<div class="planningEntreprise">
    <br>
    <h2><?php echo $date_forum;?></h2><br><br>


				<div id="imprimer">
				<h3><?php echo $row_req_nom_entreprise['nom_entreprise']; ?></h3><br>
				<h3><?php echo $row_req_nom_representant['nom_representant']; ?> <?php echo $row_req_nom_representant['prenom_representant']; ?></h3><br>
				<?php
				try
				{
    				echo tableauImprimer($id_representant,$periode,$date_forum);
				}
				catch(Exception $e)
				{
					echo $e->getMessage();
				}?>
				<br><br>

				</div>

				<input type="button" onclick="print('imprimer');" value="imprimer">
				<br><br>
				<a style="color: black;" href="PlanningAdministrateur.php"><input type="button" value="Retour"></a>
	<br>
</div>
<br><br>
        
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
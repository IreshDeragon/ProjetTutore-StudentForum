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

//requete pour avoir la date et la période
$req_date = $linkpdo->prepare('SELECT * FROM parametres');
$req_date ->execute(array());
$row_req_date = $req_date->fetch();

//requete pour récupérer les représentants
$req_liste_representant = $linkpdo->prepare('SELECT * FROM representant WHERE id_entreprise=:id_entreprise');
$req_liste_representant ->execute(array('id_entreprise' => $_SESSION['id']));
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
		<h1>Planning entreprise</h1>
		<div class="planningEntreprise">
    <br>

    			<?php while ($data = $req_liste_representant->fetch()){
    				?>
				<?php
    			//requete qui regarde si le représentant a un stage
    			$req_repr_stage = $linkpdo->prepare('SELECT * FROM stage WHERE id_representant=:id_representant');
				$req_repr_stage ->execute(array('id_representant' => $data['id_representant']));
				$row_req_repr_stage = $req_repr_stage->fetch();

    			if(!empty($row_req_repr_stage)){
    				?>
    			<div class="overflow">
    			<div class="gridcontainer">
				<div class="gridentreprisetitre"><h3>Entreprise</h3></div>
				<div class="gridrepresentanttitre"><h3>Représentant</h3></div>
				<div class="gridcreneau"><h3>Horaire</h3></div>
				<div class="gridrien"></div>
				<div class="gridrien2"></div>
				<div class="gridrien3"></div><?php
				try
				{
    				echo afficherPlanning2($data['id_representant'],$row_req_date['periode'],$row_req_date['date_forum']);
				}
				catch(Exception $e)
				{
					echo $e->getMessage();
				}
    			?></div></div><br><br><?php
    			}
    			?>
    	<br><br><?php
	} ?>
	<br><br>
</div>
<br><br><br><br><br><br><br><br><br><br><br>
        
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
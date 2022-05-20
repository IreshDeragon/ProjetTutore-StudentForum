<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();

//affiche les stages de l entreprise
$req_affiche_stage = $linkpdo->prepare('SELECT * FROM stage WHERE id_entreprise=:id_entreprise');
$req_affiche_stage ->execute(array('id_entreprise' => $_SESSION['id']));

//requete pour récupérer la date 8j avant
$req_date8j = $linkpdo->prepare('SELECT echeance_8j_avant_date FROM parametres');
$req_date8j ->execute(array());
$row_req_date8j = $req_date8j->fetch();

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
		<h1>Mes offres de stage</h1>
		<div class="mesoffresdestages">
    	<br>
    	<form method="post">
    	<br>
    	<?php if($row_req_date8j['echeance_8j_avant_date']<date('Y-m-d')){ ?>

    		<button type = "submit" disabled>Ajouter un stage</button>

		<?php }else{ ?>

			<a style="color: black;" href="ajouterunstage.php"><input type="button" value="Ajouter un stage"></a>

		<?php } ?>
		<br><br>
		    	<?php 
		        while($row_affiche_stage= $req_affiche_stage->fetch()){

					//requete pour prendre le nom et prénom du représentant du stage
					$req_affiche_representant = $linkpdo->prepare('SELECT * FROM representant WHERE id_representant=:id_representant');
					$req_affiche_representant ->execute(array('id_representant' => $row_affiche_stage['id_representant']));
					$row_affiche_representant = $req_affiche_representant->fetch();?>

					<div class="stage" >

					<u>Intitulé :</u>
					<br>
					<?php echo $row_affiche_stage['intitule']; ?>
					<?php $id_stage=$row_affiche_stage['id_stage'];?>
					<br>
					<u>Représentant :</u>
					<br>
					<?php echo $row_affiche_representant['nom_representant'].' '.$row_affiche_representant['prenom_representant']; ?>
					<br><br><br>

					<?php if($row_req_date8j['echeance_8j_avant_date']>date('Y-m-d')){ ?>

			            <div class="boutonmesoffres">
					    <input type="hidden" name="id_stage" value="<?php echo $id_stage;?>">
					    <a style="text-decoration: none;color:black;" class="boutonretour" href="modifierstage.php?id_stage=<?= $id_stage;?>">Modifier le stage</a>

					<?php }else{ ?>

						<div class="boutonmesoffres">
					    <button type = "submit" disabled>Modifier le stage</button>

					<?php } ?>

					    <a style="text-decoration: none;color:black;" class="boutonretour" href="consulterstageentreprise.php?id_stage=<?= $id_stage;?>">Consulter</a>

					<?php if($row_req_date8j['echeance_8j_avant_date']>date('Y-m-d')){ ?>
						
						    <a style="text-decoration: none;color:black;" class="boutonsupprimer" href="supprimerstage.php?id_stage=<?= $id_stage;?>">Supprimer</a>
							</div>

					<?php }else{ ?>

							<button type = "submit" disabled>Supprimer</button>
							</div>

					<?php } ?>

					</div>
					<br>
					<br>

				<?php } ?>

		
		<br>
		</form>
    	</div>
		<br><br><br><br><br>
	
        
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
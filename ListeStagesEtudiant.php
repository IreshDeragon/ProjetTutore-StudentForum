<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();

/************************************************/

require("affichageDuPlanning2.php");

//requete pour avoir la date et la période
$req_date = $linkpdo->prepare('SELECT * FROM parametres');
$req_date ->execute(array());
$row_req_date = $req_date->fetch();


/****************************************************/


$rendu = "";


if(isset($_POST['boutonVoirAnnonce']))
{
	$reqAffichageStage = $linkpdo->prepare("SELECT stage.intitule AS intitule, stage.description_stage AS description_stage, representant.nom_representant AS nom_representant, representant.prenom_representant AS prenom_representant, stage.id_stage as id_stage, representant.id_representant AS id_representant FROM stage, representant, entreprise WHERE stage.id_stage = :stage AND stage.id_representant = representant.id_representant");
	$reqAffichageStage->execute(array("stage" => (int)$_POST['id_stage_affiche']));
	$dataAffichage = $reqAffichageStage->fetch();

	$id_representant = $dataAffichage['id_representant'];

	//selcetionne la liste des compétences
	$req_liste_competence = $linkpdo->prepare('SELECT * FROM asso_competence_stage WHERE id_stage = :id_stage');
	$req_liste_competence ->execute(array('id_stage' => (int)$_POST['id_stage_affiche']));


	$rendu_competence="";
    while ($data = $req_liste_competence->fetch()) {
	    $rendu_competence = $rendu_competence.$data['libelle_competence'].', ';
    }


	$rendu = "<span><h2>".$dataAffichage['intitule']."</h2><br/><br/><br/></span>";

	$date_forum_moins1 = $row_req_date['date_forum'];

	if(date('Y-m-d')>=date('Y-m-d', strtotime($date_forum_moins1. ' - 1 days'))){

	$rendu = $rendu."<h3>Description</h3>".$dataAffichage['description_stage']."<br/><br/><br/><h3>Representant</h3>".$dataAffichage['nom_representant']." ".$dataAffichage['prenom_representant']."<br/><br/><br/><h3>Compétences requises</h3>".$rendu_competence."<br/><br/>
		<br>
		<form class=\"formReserverRDV pure-form\" method = \"POST\" action = \"ListeStagesEtudiant.php\"> 
  				<input type=\"hidden\" value = \"".$dataAffichage['id_stage']."\"name=\"id_stage_reserve\"/>
  				<input type=\"hidden\" value = \"".$dataAffichage['id_representant']."\"name=\"id_representant_stage_reserve\"/>
  				<button class = \"boutonReserverRDV pure-button\" name = \"boutonReserverRDV\" type = \"submit\" disabled> Réserver un entretien </button>
  		</form>";
  	}else{
  		$rendu = $rendu."<h3>Description</h3>".$dataAffichage['description_stage']."<br/><br/><br/><h3>Representant</h3>".$dataAffichage['nom_representant']." ".$dataAffichage['prenom_representant']."<br/><br/><br/><h3>Compétences requises</h3>".$rendu_competence."<br/><br/>
		<br>
		<form class=\"formReserverRDV pure-form\" method = \"POST\" action = \"ListeStagesEtudiant.php\"> 
  				<input type=\"hidden\" value = \"".$dataAffichage['id_stage']."\"name=\"id_stage_reserve\"/>
  				<input type=\"hidden\" value = \"".$dataAffichage['id_representant']."\"name=\"id_representant_stage_reserve\"/>
  				<button class = \"boutonReserverRDV pure-button\" name = \"boutonReserverRDV\" type = \"submit\"> Réserver un entretien </button>
  		</form>";
  	}
}

if(isset($_POST['rechercher']))
{
    $reqListeStages = $linkpdo->prepare("SELECT stage.intitule AS intitule, entreprise.nom_entreprise AS nom_entreprise, stage.id_stage AS id_stage FROM stage, entreprise,asso_competence_stage WHERE stage.id_entreprise = entreprise.id_entreprise AND stage.id_stage = asso_competence_stage.id_stage AND entreprise.valider = 1 AND (entreprise.nom_entreprise LIKE CONCAT('%',:recherche,'%') OR asso_competence_stage.libelle_competence LIKE CONCAT('%',:recherche,'%') OR stage.intitule LIKE CONCAT('%',:recherche,'%'))");
    $reqListeStages->execute(array("recherche" => $_POST['recherche']));
}
else
{
	$reqListeStages = $linkpdo->prepare("SELECT stage.intitule AS intitule, entreprise.nom_entreprise AS nom_entreprise, stage.id_stage AS id_stage FROM stage, entreprise WHERE stage.id_entreprise = entreprise.id_entreprise AND entreprise.valider = 1");
	$reqListeStages->execute();
}





if(isset($_POST['boutonReserverRDV']))
{
	$reqReservationRDV = $linkpdo->prepare("INSERT INTO creneaux(id_etudiant, id_representant, id_stage) VALUES (:etudiant, :representant, :stage)");
	try
	{
		$reqReservationRDV->execute(array("etudiant" => $_SESSION['id'],	"representant" => $_POST['id_representant_stage_reserve'],		"stage" => $_POST['id_stage_reserve']));
		$message = "Entretien réservé";
		echo '<script type="text/javascript">window.alert("'.$message.'");</script>';
		header("Refresh:0");
	}
	catch (Exception $e)
	{

		$message = $e->getMessage();
			$message = substr($message, 40, strlen($message)-40);

		if(substr($message, 0,-26)=="olation: 1062 Duplicate entry"){
			$message = "Vous avez déjà un créneau pour ce stage";
			echo '<script type="text/javascript">window.alert("'.$message.'");</script>';
		}
		else{
			echo '<script type="text/javascript">window.alert("'.$message.'");</script>';
		}
		header("Refresh:0");
	}
}




?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="utf-8">
	<link rel="stylesheet" href="https://unpkg.com/purecss@2.0.6/build/pure-min.css" integrity="sha384-Uu6IeWbM+gzNVXJcM9XV3SohHtmWE+3VGi496jvgX1jyvDTXfdK+rfZc8C1Aehk5" crossorigin="anonymous">
	<link rel="stylesheet" href="https://unpkg.com/purecss@1.0.1/build/base-min.css">
	<link rel="preconnect" href="https://fonts.gstatic.com">
	<link href="https://fonts.googleapis.com/css2?family=Roboto+Slab&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="squelette.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Forum</title>
</head>
<body>
	<?php include("menu.php"); ?>
	<main><br>
<div class = "ListeStagesEtudiant">
    <div class="affichageListe">
    	<form class="pure-form" method="POST" action="ListeStagesEtudiant.php">
    		<input type="text" placeholder = "Recherche" name="recherche"/>
    		<button class = "pure-button" name = "rechercher" type = "submit">Rechercher</button>
    	</form> 



    	<?php


    	while($data = $reqListeStages->fetch())
    	{
    		echo "<div class=\"elementListeStage\"><h3>".$data['intitule']."</h3><br/>Entreprise : ".$data['nom_entreprise']."<br/>  
  			<form class=\"formVoirAnnonce pure-form\" method = \"POST\" action = \"ListeStagesEtudiant.php\"> 
  				<input type=\"hidden\" value = \"".$data['id_stage']."\"name=\"id_stage_affiche\"/>
  					<button class = \"boutonVoirAnnonce pure-button\" name = \"boutonVoirAnnonce\" type = \"submit\"> Voir l'annonce </button>
  				</form> <br/>
  			</div>";
    	}


    	?>

    </div>

    <div class = "affichageStage">

    	<?php echo $rendu; ?> 
		<br><br>

		<?php
			if(isset($id_representant))
			{

			//requete qui regarde si le représentant a un stage

			$req_repr_stage = $linkpdo->prepare('SELECT * FROM stage WHERE id_representant=:id_representant');
			$req_repr_stage ->execute(array('id_representant' => $id_representant));
			$row_req_repr_stage = $req_repr_stage->fetch();

				if(!empty($row_req_repr_stage))
				{
		?>
				<div class="overflowstage">
				<div class="gridcontainer">
				<div class="gridentreprisetitre"><h3>Entreprise</h3></div>
				<div class="gridrepresentanttitre"><h3>Représentant</h3></div>
				<div class="gridcreneau"><h3>Horaire</h3></div>
				<div class="gridrien"></div>
				<div class="gridrien2"></div>
				<div class="gridrien3"></div>

				<?php
					echo afficherPlanning2($id_representant,$row_req_date['periode'],$row_req_date['date_forum']);
				?>
				</div></div><br><br>
    			<br><br>
    		<?php
				} 


			}
		?>
	<br>

    </div>
	
</div>
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
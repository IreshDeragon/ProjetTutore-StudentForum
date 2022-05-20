<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();

$req_profil = $linkpdo->prepare('SELECT * FROM etudiant WHERE etudiant.id_etudiant = :id_etudiant');
if($_SESSION['utilisateur']=="etudiant")
{
	$req_profil->execute(array('id_etudiant' => $_SESSION['id']));
}
else
{
	$req_profil->execute(array('id_etudiant' => $_POST['id_etudiant_a_afficher']));
}



$rendu = "";
	while($data = $req_profil->fetch())
	{

		$rendu = $rendu."<img class = \"pdpEtudiant\" src=\"".$data['lien_photo_etudiant']."\" />
		<h3> Nom : </h3>".$data['nom_etudiant']."<br/>
		<h3> Prenom : </h3>".$data['prenom_etudiant']."<br/>
		<br/><div class = \"affichageCV\"><h3>Curicullum Vitae</h3><br/><iframe src=\"".$data['lien_cv_etudiant']."\" height=\"800\" width=\"600\"></iframe></div>
		<br/><br/><br/>
		";

	}

	
	if($_SESSION['utilisateur']=="etudiant" && isset($_POST['modifier_photo']))
	{
		$rendu = $rendu."
		<form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">
 			Selectionnez la photo (moins de 500 ko) :<br/>
 			<input class = \"pure-button\" type=\"file\" name=\"fileToUpload\"><br/>
 			<button class = \"boutonUploadImage pure-button\"   type = \"submit\" name=\"submit\">Uploader</button>
		</form>
		<br/><br/><br/>
		<form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">
 			Selectionnez le CV (moins de 500 ko) :<br/>
 			<input class = \"pure-button\" type=\"file\" name=\"fileToUpload\"><br/>
 			<button class = \"boutonUploadImage pure-button\"   type = \"submit\" name=\"submit\">Uploader</button>
		</form>
		<br/><br/><br/>
		<form action=\"affichageProfilEtudiant.php\" method=\"post\"><br/>
 			<button class = \"boutonUploadImage pure-button\"   type = \"submit\" name=\"annuler\">Annuler</button>
		</form>
		";
	}
	if($_SESSION['utilisateur']=="etudiant" && !isset($_POST['modifier_photo']))
	{
		$rendu = $rendu."
		<form action=\"affichageProfilEtudiant.php\" method=\"post\">
 			<input class = \"pure-button\" type=\"submit\" value=\"Changer la photo ou le CV\" name=\"modifier_photo\">
		</form>";
	}

//requete pour savoir si l etudiant est déjà accepté pour cette offre de stage
	if($_SESSION['utilisateur']=="entreprise"){
		$req_verif_accepte = $linkpdo-> prepare('SELECT * FROM acceptes WHERE id_etudiant =:id_etudiant AND id_stage =:id_stage');
		$req_verif_accepte->execute(array('id_etudiant' => $_POST['id_etudiant_a_afficher'],
					'id_stage' =>$_POST['id_stage_a_afficher']));
		$row_verif_accepte = $req_verif_accepte->fetch();
	}

//requete pour ajouter l etudiant potentiellement accepté dans la table association acceptes et mettre +1 dans nbetudiantaccepte pour l entreprise

	if(isset($_POST['accepte']) AND empty($row_verif_accepte)){

		$req_accepte = $linkpdo->prepare('INSERT INTO acceptes (id_etudiant, id_stage) VALUES(:id_etudiant,:id_stage)');
		$req_accepte->execute(array('id_etudiant' => $_POST['id_etudiant_a_afficher'],
									'id_stage' => $_POST['id_stage_a_afficher']));

		$req_accepte_entreprise = $linkpdo->prepare('UPDATE stage SET nbetudiantaccepte = nbetudiantaccepte +1 WHERE id_stage=:id_stage');
		$req_accepte_entreprise->execute(array('id_stage' => $_POST['id_stage_a_afficher']));

		header('Location: /Ptut/PlanningEntreprise.php');
	    exit;
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
		<div class="consultationProfil">
			<?php
				echo $rendu;
			?>

			<?php if($_SESSION['utilisateur']=="entreprise" AND empty($row_verif_accepte)){?>
				<form method="post">
					<input type="hidden" name="id_etudiant_a_afficher" value="<?php echo $_POST['id_etudiant_a_afficher'];?>"/>
					<input type="hidden" name="id_stage_a_afficher" value="<?php echo $_POST['id_stage_a_afficher'];?>"/>
					<button type="submit" name="accepte">Potentiellement accepté</button>
				</form>

			<?php }?>

		</div>	
    	<br><br>
	<br>
</div>
<br><br>
        
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
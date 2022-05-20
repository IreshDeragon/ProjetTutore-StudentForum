<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();



/************************* requête pour l'affichage des entreprises ***************************/


$reqEntreprises = $linkpdo->prepare('SELECT entreprise.id_entreprise, nom_entreprise, count(stage.id_stage) as compteur, entreprise.valider FROM entreprise LEFT JOIN stage ON stage.id_entreprise = entreprise.id_entreprise WHERE stage.id_entreprise = entreprise.id_entreprise OR stage.id_entreprise IS NULL GROUP BY entreprise.id_entreprise;');

$reqEntreprises->execute();

$rendu = "<div class='gestionEntrepriseoverflowtableauListeEntreprise'>
<div class='gestionEntreprisetableauListeEntreprise'>
<table> 
  <tr>
    <th>Nom Entreprise</th>
    <th>Représentants</th>
    <th>Nombre de stages proposés</th>
    <th>Informations validées</th>
  </tr>";

while($rowEntreprise = $reqEntreprises->fetch())
{
	
	$reqRepresentant = $linkpdo->prepare('SELECT nom_representant, prenom_representant FROM representant WHERE representant.id_entreprise = :id_entreprise;');
	$reqRepresentant->execute(array('id_entreprise' => $rowEntreprise['id_entreprise']));
	$nomsRepresentants = "";

	while($rowRepresentant = $reqRepresentant->fetch())
	{
		$nomsRepresentants = $nomsRepresentants.$rowRepresentant['nom_representant']." ".$rowRepresentant['prenom_representant']."<br/> ";
	}

	if($rowEntreprise['valider'] == 1)
	{
		$infosValides = "oui";
	}
	else
	{
		$infosValides = "non";
	}

	$rendu = $rendu."<tr><td><a style='text-decoration:none;color:black;' href='consulterEntreprise.php?id_entreprise=".$rowEntreprise['id_entreprise']."'>".$rowEntreprise['nom_entreprise']."</a></td><td>".$nomsRepresentants."</td><td>".$rowEntreprise['compteur']."</td><td>".$infosValides."</td></tr>";
}
$rendu = $rendu."</table></div></div>";


/************************* requête pour l'affichage des entreprises ***************************/





/************************* requête pour l'ajouts d'espaces entreprise ***************************/

if(isset($_POST['loginNouvelleEntreprise']) && isset($_POST['mdpNouvelleEntreprise']) && isset($_POST['ajouterNouvelleEntreprise']) && isset($_POST['nomNouvelleEntreprise']))
{
	$reqAjoutEntreprise = $linkpdo->prepare('INSERT INTO entreprise(id_entreprise, nom_entreprise, login_entreprise, mdp_entreprise) VALUES (0, :nom, :login, :mdp)');
	$reqAjoutEntreprise->execute(array(
		'nom' => $_POST['nomNouvelleEntreprise'],
		'login' => $_POST['loginNouvelleEntreprise'],
		'mdp' => md5($_POST['mdpNouvelleEntreprise'])
	));
}

/************************* requête pour l'ajouts d'espaces entreprise ***************************/

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
		
		
		<div class="gestionEntreprise">
    	<br/>
    		<h1>Liste des Entreprises</h1>
    		<br/>
    	<div class="tableauEntreprises">

 			<?php echo $rendu; ?>
		</div>
		
		<br/>
		<br/>

		<h1>Créer un espace Entreprise</h1>

		<div class="ajoutEspaceEntreprise">
			<form method="POST" action="gestionEntreprise.php">
				<input type="text" placeholder="Nom de l'Entreprise" name="nomNouvelleEntreprise" required><br><br>
				<input type="login" placeholder="Login" name="loginNouvelleEntreprise" required><br><br>
          		<input type="password" placeholder="Mot de passe" name="mdpNouvelleEntreprise" required><br>
          		<br/>
          		<button type="submit" name="ajouterNouvelleEntreprise"> Ajouter </button>
			</form>

			<?php 
			if(isset($_POST['loginNouvelleEntreprise']) && isset($_POST['mdpNouvelleEntreprise']) && isset($_POST['ajouterNouvelleEntreprise']) && isset($_POST['nomNouvelleEntreprise']))
			{
				echo 'Espace créé avec succès !';
			}
			?>
		</div>

		<br/><br/>

		<br/><br/><br/><br/>
    </div>

    <br/><br/>
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
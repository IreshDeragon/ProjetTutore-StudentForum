<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();

$rendu = '';


/************************* construction des statistiques en fonction du choix fait dans le select ***************************/

if(isset($_POST['Stats']) && $_POST['Stats'] == '')
{
	$rendu = '';
}


//////////////////// LANGAGES ET OUTILS ///////////////////////


if(isset($_POST['Stats']) && $_POST['Stats'] == 'Langages')
{
	$req = $linkpdo->prepare('SELECT stage.intitule, stage.langage_outil, entreprise.nom_entreprise FROM stage, entreprise WHERE stage.id_entreprise = entreprise.id_entreprise;');
	$req->execute();
	
	$rendu = "<table> 
  	<tr>
    	<th>Nom Entreprise</th>
    	<th>Intitule du stage</th>
    	<th>Langages et outils utilisés</th>
  	</tr>";

	while($row = $req->fetch())
	{
		$rendu = $rendu."<tr><td>".$row['nom_entreprise']."</td><td>".$row['intitule']."</td><td>".$row['langage_outil']."</td></tr>";
	}
	$rendu = $rendu."</table>";
}



//////////////////// COMPETENCES ///////////////////////



if(isset($_POST['Stats']) && $_POST['Stats'] == 'Competence')
{
	$req = $linkpdo->prepare('SELECT stage.intitule, stage.competence1, stage.competence2, stage.competence3, entreprise.nom_entreprise FROM stage, entreprise WHERE stage.id_entreprise = entreprise.id_entreprise;');
	$req->execute();
	
	$rendu = "<table> 
  	<tr>
    	<th>Nom Entreprise</th>
    	<th>Intitule du stage</th>
    	<th>Competences requises</th>
  	</tr>";

	while($row = $req->fetch())
	{
		$rendu = $rendu."<tr><td>".$row['nom_entreprise']."</td><td>".$row['intitule']."</td><td>".$row['competence1']."<br/>".$row['competence2']."<br/>".$row['competence3']."</td></tr>";
	}
	$rendu = $rendu."</table>";
}




//////////////////// NOMBRES D'ETUDIANTS VUS ///////////////////////






if(isset($_POST['Stats']) && $_POST['Stats'] == 'NbEtuVus')
{
	$req = $linkpdo->prepare('SELECT entreprise.nom_entreprise, (SELECT count(*)FROM representant WHERE representant.id_entreprise = entreprise.id_entreprise) as nbetudiantvu FROM entreprise;');
	$req->execute();
	
	$rendu = "<table> 
  	<tr>
    	<th>Nom Entreprise</th>
    	<th>Nombre d'étudiants vus</th>
  	</tr>";

	while($row = $req->fetch())
	{
		if($row['nom_entreprise'] == NULL)
		{
				$nbetudiantvu = 0;
		}



		$rendu = $rendu."<tr><td>".$row['nom_entreprise']."</td><td>".$row['nbetudiantvu']."</td></tr>";
	}
	$rendu = $rendu."</table>";
}


//////////////////// nb étudiant potentiellement accepté par stage///////////////////////


if(isset($_POST['Stats']) && $_POST['Stats'] == 'accepte')
{
	$req_accepte = $linkpdo->prepare('SELECT stage.intitule, stage.nbetudiantaccepte, entreprise.nom_entreprise FROM stage, entreprise WHERE stage.id_entreprise = entreprise.id_entreprise;');
	$req_accepte->execute();
	
	$rendu = "<table> 
  	<tr>
    	<th>Nom Entreprise</th>
    	<th>Intitule du stage</th>
    	<th>Nombre d'étudiant potentiellement accepté</th>
  	</tr>";

	while($row = $req_accepte->fetch())
	{
		$rendu = $rendu."<tr><td>".$row['nom_entreprise']."</td><td>".$row['intitule']."</td><td>".$row['nbetudiantaccepte']."</td></tr>";
	}
	$rendu = $rendu."</table>";
}


//////////////////// NOMBRE DE STAGES PROPOSES ///////////////////////





if(isset($_POST['Stats']) && $_POST['Stats'] == 'NbStages')
{
	$req = $linkpdo->prepare('SELECT entreprise.nom_entreprise, count(stage.id_stage) as compteur FROM entreprise LEFT JOIN stage ON stage.id_entreprise = entreprise.id_entreprise WHERE stage.id_entreprise = entreprise.id_entreprise OR stage.id_entreprise IS NULL GROUP BY entreprise.nom_entreprise;');
	$req->execute();
	
	$rendu = "<table> 
  	<tr>
    	<th>Nom Entreprise</th>
    	<th>Nombre de stages proposés</th>
  	</tr>";

	while($row = $req->fetch())
	{
		$rendu = $rendu."<tr><td>".$row['nom_entreprise']."</td><td>".$row['compteur']."</td></tr>";
	}
	$rendu = $rendu."</table>";
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
		
		
		<div class="Statistiques">
    	<br/>
    		<h1>Statistiques du forum</h1>
    		<br/>
    	<div class="tableauStatistiques">

    		<form method="POST" action="Statistiques.php">
 				<select name="Stats">
    				<option value="">--Selectionnez la statistique à afficher--</option>
    				<option value="Langages">Langages et outils utilisés</option>
    				<option value="Competence">Compétences requises</option>
    				<option value="NbEtuVus">Nombre d'etudiants vus</option>
    				<option value="accepte">Nombre d'etudiants potentiellement accepté</option>
    				<option value="NbStages">Nombre de stages proposés</option>
				</select><br><br>

				<button type="submit" name="AfficherStats"> Afficher La Statistique </button>
			</form><br>
        
			<?php echo $rendu; ?>

        </div>

        <br/><br/>
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
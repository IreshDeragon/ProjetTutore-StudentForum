<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();

//recupere l id du stage
$id_stage = $_GET['id_stage'];

//recupere les données du stage
$req_donne_stage = $linkpdo-> prepare('SELECT * FROM stage WHERE id_stage=:id_stage');
$req_donne_stage->execute(array('id_stage' => $id_stage));
$row_donne_stage = $req_donne_stage->fetch();

//recupere le nom et prénom du représentant
$req_donne_representant = $linkpdo-> prepare('SELECT * FROM representant WHERE id_representant=:id_representant');
$req_donne_representant->execute(array('id_representant' => $row_donne_stage['id_representant']));
$row_donne_representant = $req_donne_representant->fetch();

//selcetionne la liste des compétences
$req_liste_competence = $linkpdo->prepare('SELECT * FROM asso_competence_stage WHERE id_stage = :id_stage');
$req_liste_competence ->execute(array('id_stage' => $id_stage));


// Si la variable "$_Post" contient des informations alors on les traitres
    if(!empty($_POST)){
    	extract($_POST);
    	if (isset($_POST['modifier'])){

            //requete pour prendre l'id du représentant
        	$req_representant = $linkpdo-> prepare('SELECT * FROM representant WHERE nom_representant=:nom_representant AND prenom_representant=:prenom_representant');
			$req_representant->execute(array('nom_representant' => $_POST['nom_representant'],
				'prenom_representant' =>$_POST['prenom_representant']));
   			$row_representant = $req_representant->fetch();

   			$id_representant = $row_representant['id_representant'];

   			if(!empty($id_representant)){

	    			//modifier le stage dans la bdd
					$req_modifier_stage = $linkpdo->prepare("UPDATE stage SET id_representant=:id_representant, intitule=:intitule,description_stage=:description_stage,duree_stage_semaine=:duree_stage_semaine,langage_outil=:langage_outil,nbetudiantaccepte=:nbetudiantacceptee,competence1=:competence1,competence2=:competence2,competence3=:competence3 WHERE id_stage=:id_stage AND id_entreprise=:id_entreprise");
	    			$req_modifier_stage->execute(array('id_representant' => $id_representant,
	    						'intitule' => $_POST['intitule'],
								'description_stage' => $_POST['description_stage'],
								'duree_stage_semaine' => $_POST['duree_stage_semaine'],
								'langage_outil' => $_POST['langage_outil'],
								'nbetudiantacceptee' => $_POST['nbetudiantacceptee'],
								'competence1' => $_POST['competence1'],
								'competence2' => $_POST['competence2'],
								'competence3' => $_POST['competence3'],
                                'id_stage' => $id_stage,
                                'id_entreprise' => $_SESSION['id']));
	    			header('Location: /Ptut/Mesoffresdestages.php');
	                exit;
			}
			else{
				$erreur = "Le représentant n'existe pas dans la base de données !";
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
		<h1>Modifier offre de stage</h1>
    <div class="ajouterunstage">
    <br>
    <form method="post">

    	Intitulé<br>
    	<input type="intitule" placeholder="Intitulé" name="intitule" value="<?php echo $row_donne_stage['intitule'];?>" required><br><br>

    	Nom du repésentant (le représentant doit être ajouté au préalable)<br>
    	<input type="nom_representant" placeholder="Nom représentant" pattern="[a-zA-ZÀ-ÿ-\-]{1,}" name="nom_representant" value="<?php echo $row_donne_representant['nom_representant'];?>" required><br><br>

    	Prénom du représentant<br>
    	<input type="prenom_representant" placeholder="Prénom représentant" pattern="[a-zA-ZÀ-ÿ-\-]{1,}" name="prenom_representant" value="<?php echo $row_donne_representant['prenom_representant'];?>" required><br><br>

    	Description du stage<br>
    	<textarea class="descriptiondustage" type="description_stage" placeholder="Description" rows="7" cols="48" name="description_stage" required><?php echo $row_donne_stage['description_stage'];?></textarea><br><br>

    	Langages et outils<br>
    	<input type="langage_outil" size="30" placeholder="Langages et/ou outils de développement" name="langage_outil" value="<?php echo $row_donne_stage['langage_outil'];?>" required><br><br>

    	Nombre d'étudiants accepté<br>
    	<input type="nbetudiantacceptee" placeholder="Nombre d'étudiant accepté" name="nbetudiantacceptee" pattern="[0-9]{1,3}" value="<?php echo $row_donne_stage['nbetudiantaccepte'];?>" required><br><br>

    	Durée du stage(en semaine)<br>
    	<input type="duree_stage_semaine" placeholder="Durée du stage (en semaine)" pattern="[0-9]{1,3}" name="duree_stage_semaine" value="<?php echo $row_donne_stage['duree_stage_semaine'];?>" required><br><br>

    	<div>
                Compétences<br>
                    <p><?php 
                
                while ($data = $req_liste_competence->fetch()) {
                    echo $data['libelle_competence'].', ';
                }
                
            ?></p><br><br>
		</div>

        <?php
        if (isset($erreur)){?>
            <div><?= $erreur ?></div>
        <?php } ?>
        <br>

        <button type="submit" style="width:100px" name="modifier">Modifier</button>
        <br><br><br>

       
    	<a style="text-decoration: none;color:black;" class="boutonretour" href="Mesoffresdestages.php">Retour</a>
    	<br><br>

    </form>
    </div>
    <br><br><br>
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
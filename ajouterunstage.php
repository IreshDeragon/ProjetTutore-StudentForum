<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();
$req_liste_representants = $linkpdo->prepare('SELECT * from representant WHERE representant.id_entreprise = :id_entreprise');
$req_liste_representants->execute(array("id_entreprise" => $_SESSION['id']));


// Si la variable "$_Post" contient des informations alors on les traitres
    if(!empty($_POST)){
    	extract($_POST);

        
    	if (isset($_POST['ajouter'])){
    		//trim supprime les espaces avant et après
        	$intitule = trim($intitule);
         	$description_stage = trim($description_stage);
         	$duree_stage_semaine =trim($duree_stage_semaine);
         	$langage_outil =trim($langage_outil);
         	$nbetudiantacceptee =trim($nbetudiantacceptee);


            

        	//requete pour prendre l'id du représentant
        	$req_representant = $linkpdo-> prepare("SELECT * FROM representant WHERE CONCAT(representant.nom_representant,' ',representant.prenom_representant) LIKE CONCAT('%',:nom_prenom_representant,'%')");
			$req_representant->execute(array('nom_prenom_representant' => $_POST['representant']));
            $row_representant = $req_representant->fetch();

   			$id_representant = $row_representant['id_representant'];

   			//Si le représentant existe
   			if(!empty($id_representant)){

        		//requête qui vérifie si ce stage n'existe pas déjà
				$req_verif = $linkpdo-> prepare('SELECT * FROM stage WHERE id_representant =:id_representant AND id_entreprise =:id_entreprise AND intitule =:intitule AND description_stage =:description_stage AND duree_stage_semaine =:duree_stage_semaine AND langage_outil =:langage_outil AND nbetudiantaccepte =:nbetudiantacceptee');
				$req_verif->execute(array('id_representant' => $id_representant,
							'id_entreprise' =>$_SESSION['id'],
							'intitule' => $_POST['intitule'],
							'description_stage' => $_POST['description_stage'],
							'duree_stage_semaine' => $_POST['duree_stage_semaine'],
							'langage_outil' => $_POST['langage_outil'],
							'nbetudiantacceptee' => $_POST['nbetudiantacceptee']));
	   			$row_verif = $req_verif->fetch();   		
			

				//Si la requête précédente n'a rien renvoyé(si le stage n'existe pas) alors on ajoute le stage dans la base de données 
	    		if (empty($row_verif)){
					$req_ajouter_stage = $linkpdo->prepare("INSERT INTO stage (id_representant,id_entreprise,intitule,description_stage,duree_stage_semaine,langage_outil,nbetudiantaccepte) VALUES(:id_representant,:id_entreprise, :intitule, :description_stage, :duree_stage_semaine, :langage_outil, :nbetudiantacceptee)");

	    			$req_ajouter_stage->execute(array('id_representant' => $id_representant,
	    						'id_entreprise' => $_SESSION['id'],
	    						'intitule' => $_POST['intitule'],
								'description_stage' => $_POST['description_stage'],
								'duree_stage_semaine' => $_POST['duree_stage_semaine'],
								'langage_outil' => $_POST['langage_outil'],
								'nbetudiantacceptee' => $_POST['nbetudiantacceptee']));

                    if (isset($_SESSION['tableau_competence'])){
                        foreach ($_SESSION['tableau_competence'] as $key => $value) {
                            $req_verif_asso = $linkpdo-> prepare('SELECT * FROM asso_competence_stage WHERE id_stage = (SELECT id_stage FROM stage WHERE id_representant =:id_representant AND id_entreprise =:id_entreprise AND intitule =:intitule AND description_stage =:description_stage AND duree_stage_semaine =:duree_stage_semaine AND langage_outil =:langage_outil AND nbetudiantaccepte =:nbetudiantacceptee) AND libelle_competence = LOWER(:libelle_competence)');
                            $req_verif_asso->execute(array('id_representant' => $id_representant,
                            'id_entreprise' =>$_SESSION['id'],
                            'intitule' => $_POST['intitule'],
                            'description_stage' => $_POST['description_stage'],
                            'duree_stage_semaine' => $_POST['duree_stage_semaine'],
                            'langage_outil' => $_POST['langage_outil'],
                            'nbetudiantacceptee' => $_POST['nbetudiantacceptee'],
                            'libelle_competence' => $value));
                            $row_verif_asso = $req_verif_asso->fetch();

                            if (empty($row_verif_asso)){
                            $req_asso_competence = $linkpdo-> prepare('INSERT INTO asso_competence_stage (id_stage, libelle_competence) values ((SELECT id_stage FROM stage WHERE id_representant =:id_representant AND id_entreprise =:id_entreprise AND intitule =:intitule AND description_stage =:description_stage AND duree_stage_semaine =:duree_stage_semaine AND langage_outil =:langage_outil AND nbetudiantaccepte =:nbetudiantacceptee), LOWER(:libelle_competence))');
                            $req_asso_competence->execute(array('id_representant' => $id_representant,
                            'id_entreprise' =>$_SESSION['id'],
                            'intitule' => $_POST['intitule'],
                            'description_stage' => $_POST['description_stage'],
                            'duree_stage_semaine' => $_POST['duree_stage_semaine'],
                            'langage_outil' => $_POST['langage_outil'],
                            'nbetudiantacceptee' => $_POST['nbetudiantacceptee'],
                            'libelle_competence' => $value));
                            }

                        }
                    }
	    			header('Location: /Ptut/Mesoffresdestages.php');
	                exit;
				}
				else{
					$erreur = "L'offre de stage existe déjà !";
				}
			}
			else{
				$erreur = "Le représentant n'existe pas dans la base de données !";
			}
        }
    }
//selcetionne la liste des compétences

if (isset($_POST['ajouter_competence']) AND isset($_POST['competence_ajoute'])){
    array_push($_SESSION['tableau_competence'], $_POST['competence_ajoute']);

}
else{

    $_SESSION['tableau_competence'] = [];
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
		<h1>Création d'un nouveau stage</h1>
    <div class="ajouterunstage">
    <br>

        <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
            <label for="competence">Ajouter Compétence</label><br>
            <input type= "text" list="competenceAjoute" name="competence_ajoute" id="competence">
            <datalist id="competenceAjoute">
                <?php 
                    $req_competence = $linkpdo-> prepare('SELECT libelle_competence FROM asso_competence_stage');
                    $req_competence->execute();
                    while ($res_competence = $req_competence->fetch()){
                        echo "<option value=\"".$res_competence['libelle_competence']."\" >";
                    }
    
                ?>
            </datalist>
            <input type="submit" name="ajouter_competence" value="Ajouter Compétence">
        </form>
        <br>
    <form method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">

    	Intitulé<br>
    	<input type="intitule" placeholder="Intitulé" name="intitule" value="<?php if(isset($intitule)){ echo $intitule; }?>" required><br><br>
        
        <div>
            <label for="representant">Représentant</label>
            <br>
            <select name="representant" id="representant">
                <?php
                        while($row = $req_liste_representants->fetch())
                        {
                            $prenom=$row['prenom_representant'];
                            $nom=$row['nom_representant'];
                            echo "<option value\"".$row['id_representant']."\"> ".$nom." ".$prenom."</option>";

                        }
                ?>
            </select>
        </div><br>

    	Description du stage<br>
    	<textarea class="descriptiondustage" type="description_stage" placeholder="Description" rows="7" cols="48" name="description_stage" required><?php if(isset($description_stage)){ echo $description_stage; }?></textarea><br><br>

    	Langages et outils<br>
    	<input type="langage_outil" size="30" placeholder="Langages et/ou outils de développement" name="langage_outil" value="<?php if(isset($langage_outil)){ echo $langage_outil; }?>" required><br><br>

    	Nombre d'étudiants accepté<br>
    	<input type="nbetudiantacceptee" placeholder="Nombre d'étudiant accepté" name="nbetudiantacceptee" pattern="[0-9]{1,3}" value="<?php if(isset($nbetudiantacceptee)){ echo $nbetudiantacceptee; }?>" required><br><br>

    	Durée du stage(en semaine)<br>
    	<input type="duree_stage_semaine" placeholder="Durée du stage (en semaine)" pattern="[0-9]{1,3}" name="duree_stage_semaine" value="<?php if(isset($duree_stage_semaine)){ echo $duree_stage_semaine; }?>" required><br><br>

    	<div>
			<label for="competence1">Compétences :</label>
			<p><?php 
                if (isset($_SESSION['tableau_competence'])){
                    foreach ($_SESSION['tableau_competence'] as $key => $value) {
                        echo $value.', ';
                    }
                }
            ?></p>
        </div>



        <?php
        if (isset($erreur)){?>
            <div><br><?= $erreur ?></div>
        <?php } ?>
        <br>

        <button type="submit" style="width:100px" name="ajouter">Créer stage</button>
        <br><br>

       
    	<a style="text-decoration: none;color:black;" class="boutonretour" href="Mesoffresdestages.php">Retour</a>
    	<br><br>

    </form>
        
    
    </div>
    <br><br><br>
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
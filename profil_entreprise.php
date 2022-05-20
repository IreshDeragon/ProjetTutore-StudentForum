<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();
//selectionne le profil de l entreprise
$req_affiche_profil = $linkpdo->prepare('SELECT * FROM entreprise WHERE id_entreprise=:id_entreprise');
$req_affiche_profil ->execute(array('id_entreprise' => $_SESSION['id']));
$row_affiche_profil = $req_affiche_profil->fetch();

//affiche les représentant de l entreprise
$req_affiche_representant = $linkpdo->prepare('SELECT * FROM representant WHERE id_entreprise=:id_entreprise');
$req_affiche_representant ->execute(array('id_entreprise' => $_SESSION['id']));

// Si la variable "$_Post" contient des informations alors on les traitres
    if(!empty($_POST)){
    	extract($_POST);
        
    	if (isset($_POST['valider'])){

			//modifie le profil de l entreprise
			$req_modifier_profil = $linkpdo->prepare("UPDATE entreprise SET nom_entreprise=:nom_entreprise, presentation=:presentation WHERE id_entreprise=:id_entreprise");

			$req_modifier_profil->execute(array('nom_entreprise' => $_POST['nom_entreprise'],
						'presentation' => $_POST['presentation'],
                        'id_entreprise' => $_SESSION['id']));
	    			
			header('Location: /Ptut/profil_entreprise.php');
            exit;
		}
        elseif(isset($_POST['annuler'])){
            header('Location: /Ptut/profil_entreprise.php');
            exit;
        }
    }
//ajouter représentant
    if(isset($_POST['ajouterRepresentant'])){
        header('Location: /Ptut/ajouterrepresentant.php');
        exit;
    }

//requete pour récupérer la date 8j avant
$req_date8j = $linkpdo->prepare('SELECT echeance_8j_avant_date FROM parametres');
$req_date8j ->execute(array());

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
		<h1>Profil</h1>
    <div class="profil_entreprise">
    <br>
    <form method="post">

        <?php
        $reqImage = $linkpdo->prepare("SELECT entreprise.lien_logo_entreprise FROM entreprise WHERE entreprise.id_entreprise = :id_entreprise");
        $reqImage->execute(array("id_entreprise" => $_SESSION['id']));
        while($data = $reqImage->fetch())
        {
            echo "<img class = \"logoEntreprise\" src=\"".$data['lien_logo_entreprise']."\"><br>";
        }
        ?>
    	Nom<br>
    	<input type="nom_entreprise" placeholder="Nom" <?php if(!isset($_POST['modifier'])){?> disabled="disabled" <?php } ?> name="nom_entreprise" value="<?php if(isset($row_affiche_profil['nom_entreprise'])){ echo $row_affiche_profil['nom_entreprise']; }?>"><br><br>

    	Présentation<br>
    	<textarea type="presentation" placeholder="Présentation" rows="7" cols="48" <?php if(!isset($_POST['modifier'])){?> disabled="disabled" <?php } ?> name="presentation" required><?php if(isset($row_affiche_profil['presentation'])){ echo $row_affiche_profil['presentation']; }?></textarea><br><br>

        <?php
        if (isset($erreur)){?>
            <div><?= $erreur ?></div>
        <br>
        <?php } ?><br>

        <?php if($req_date8j>date('Y-m-d')){ ?>

            <button type="submit" style="width:150px" <?php if(isset($_POST['modifier'])){?> class="cacher" <?php } ?>name="modifier">Modifier le profil</button>

            <button type="submit" style="width:100px" <?php if(!isset($_POST['modifier'])){?> class="cacher" <?php } ?>name="valider">Valider</button>

            <button type="annuler" style="width:100px" <?php if(!isset($_POST['modifier'])){?> class="cacher" <?php } ?>name="annuler">Annuler</button>
            <br>

        <?php } ?>

        <br>

        <b>Représentants :</b><br><br>

        <?php if($req_date8j>date('Y-m-d')){ ?>

            <button type="submit" style="width:150px" name="ajouterRepresentant">Ajouter représentant</button>


            <br><br><br>

        <?php } ?>

        
        <?php if($req_date8j>date('Y-m-d')){

            while($row_affiche_representant= $req_affiche_representant->fetch()){
                echo '-'.$row_affiche_representant['nom_representant'].' '.$row_affiche_representant['prenom_representant'].'<br>'.$row_affiche_representant['email_representant'].'<br>';
                ?><br><input type="hidden" name="id_representant" value="<?php echo $row_affiche_representant['id_representant'];?>">
                    <a style="text-decoration: none;color:black;" class="boutonretour" href="modifierRepresentant.php?id_representant=<?= $row_affiche_representant['id_representant'];?>">Modifier le représentant</a>
                    <a style="text-decoration: none;color:black;" class="boutonsupprimer" href="SupprimerRepresentant.php?id_representant=<?= $row_affiche_representant['id_representant'];?>">supprimer</a><br><br><?php
            }
        }
        else{
            while($row_affiche_representant= $req_affiche_representant->fetch()){
                echo '-'.$row_affiche_representant['nom_representant'].' '.$row_affiche_representant['prenom_representant'].'<br>'.$row_affiche_representant['email_representant'].'<br>';
                ?><br><input type="hidden" name="id_representant" value="<?php echo $row_affiche_representant['id_representant'];?>">
                    <br><?php
            }
        } ?>

        </form>
        <br/>        
        <?php
        if(isset($_POST['modifierLogo']))
        {
            echo "
            <form action=\"upload.php\" method=\"post\" enctype=\"multipart/form-data\">
                Selectionnez la photo (moins de 500 ko) :<br/>
                <input class = \"pure-button\" type=\"file\" name=\"fileToUpload\"><br/>
                <button class = \"boutonUploadImage pure-button\"   type = \"submit\" name=\"submit\">Uploader</button>
            </form>

            <form action=\"profil_entreprise.php\" method=\"post\">
                <button class = \"boutonUploadImage pure-button\" type = \"submit\" name=\"annuler\">Annuler</button>
            </form>
            ";

        }
        else
        {
            echo "
            <form action=\"profil_entreprise.php\" method=\"post\">
                <button class = \"boutonUploadImage pure-button\"   type = \"submit\" name=\"modifierLogo\">Modifier le logo</button>
            </form>";
        }
        
        ?>
        <br>
        <br>

    
    </div>
    <br><br><br>
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
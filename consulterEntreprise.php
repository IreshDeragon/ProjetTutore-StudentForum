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
$id_entreprise = $_GET['id_entreprise'];

//selectionne le profil de l entreprise
$req_affiche_profil = $linkpdo->prepare('SELECT * FROM entreprise WHERE id_entreprise=:id_entreprise');
$req_affiche_profil ->execute(array('id_entreprise' => $id_entreprise));
$row_affiche_profil = $req_affiche_profil->fetch();

//affiche les représentant de l entreprise
$req_affiche_representant = $linkpdo->prepare('SELECT * FROM representant WHERE id_entreprise=:id_entreprise');
$req_affiche_representant ->execute(array('id_entreprise' => $id_entreprise));


//valider l'entreprise
// Si la variable "$_Post" contient des informations alors on les traitres
    if(!empty($_POST)){
        extract($_POST);
        
        if (isset($_POST['valider'])){

            //modifie le profil de l entreprise
            $req_valider_entreprise = $linkpdo->prepare("UPDATE entreprise SET valider=:valider WHERE id_entreprise=:id_entreprise");

            $req_valider_entreprise->execute(array('valider' => 1,
                        'id_entreprise' => $id_entreprise));
                    
            header('Location: /Ptut/gestionEntreprise.php');
            exit;
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
		<h1>Profil de l'entreprise</h1>
    <div class="profil_entreprise">
    <br>
    <form method="post">

        Nom<br>
        <input type="nom_entreprise" placeholder="Nom" <?php if(!isset($_POST['modifier'])){?> disabled="disabled" <?php } ?> name="nom_entreprise" value="<?php if(isset($row_affiche_profil['nom_entreprise'])){ echo $row_affiche_profil['nom_entreprise']; }?>"><br><br>

        Présentation<br>
        <textarea type="presentation" placeholder="Présentation" rows="7" cols="48" <?php if(!isset($_POST['modifier'])){?> disabled="disabled" <?php } ?> name="presentation" required><?php if(isset($row_affiche_profil['presentation'])){ echo $row_affiche_profil['presentation']; }?></textarea><br><br>

        <?php
        if (isset($erreur)){?>
            <div><?= $erreur ?></div>
        <br>
        <?php } ?>
        Repésentants :<br>
        <?php 
            while($row_affiche_representant= $req_affiche_representant->fetch()){
                echo $row_affiche_representant['nom_representant'].' '.$row_affiche_representant['prenom_representant'];
                ?><br><?php
        } ?>
        <br><br>

        <button type="submit" style="width:150px" <?php if($row_affiche_profil['valider']==1){?> class="cacher" <?php } ?>name="valider">Valider l'entreprise</button>
        
        <br>
        
        <br>
        <br>

    </form>
    </div>
    <br><br><br>
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
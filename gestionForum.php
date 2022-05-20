<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();

//selectionne la date et la période
$req_affiche_date = $linkpdo->prepare('SELECT * FROM parametres');
$req_affiche_date ->execute(array());
$row_affiche_date = $req_affiche_date->fetch();

if (isset($_POST['valider'])){
	//modifie la date
	$req_modifier_date = $linkpdo->prepare("UPDATE parametres SET date_forum=:date_forum, periode=:periode");

	$req_modifier_date->execute(array('date_forum' => $_POST['Date'],
				'periode' => $_POST['periode']));
			
	header('Location: /Ptut/gestionForum.php');
    exit;
}
elseif(isset($_POST['annuler'])){
    header('Location: /Ptut/gestionForum.php');
    exit;
}

//requete qui vérifie si les informations des entreprises sont déjà validé
$req_verif_validé = $linkpdo->prepare('SELECT * FROM parametres');
$req_verif_validé ->execute(array());
$row_req_verif_validé = $req_verif_validé->fetch();

//requete pour ouvrir les inscriptions (valide les infos des entreprises)
if (isset($_POST['validerinformation'])){
    

    echo "plop";   
    $req_validé_info = $linkpdo->prepare("UPDATE parametres SET infos_entreprise_valide=1");

    $req_validé_info->execute();
            
    header('Location: /Ptut/gestionForum.php');
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
		<h1>Gestion du Forum de stage</h1>
		<div class="profil_entreprise">
    <br>
    <form method="post">

    	Date du forum<br>
    	<input type="Date" placeholder="Date" <?php if(!isset($_POST['modifier'])){?> disabled="disabled" <?php } ?> name="Date" value="<?php echo $row_affiche_date['date_forum'];?>"><br><br>
    	<?php $periode = $row_affiche_date['periode'];?>
    	<label for="periode">Moment dans la journée</label>
			<br>
			<select name="periode" id="periode" <?php if(!isset($_POST['modifier'])){?> disabled="disabled" <?php } ?>>
                <option <?php if($periode=="matin" ){?> selected <?php } ?>value="matin">Matin</option>
                <option <?php if($periode=="aprem" ){?> selected <?php } ?>value="aprem">Aprem</option>
                <option <?php if($periode=="journee" ){?> selected <?php } ?>value="journee">Journee</option>
            </select>

        <?php
        if (isset($erreur)){?>
            <div><?= $erreur ?></div>
        <br>
        <?php } ?><br><br>

        <button type="submit" style="width:150px" <?php if(isset($_POST['modifier'])){?> class="cacher" <?php } ?>name="modifier">Modifier la date</button>

        <button type="submit" style="width:100px" <?php if(!isset($_POST['modifier'])){?> class="cacher" <?php } ?>name="valider">Valider</button>

        <button type="annuler" style="width:100px" <?php if(!isset($_POST['modifier'])){?> class="cacher" <?php } ?>name="annuler">Annuler</button>

        <br>
        <br>
            <button type="submit" style="width:150px" <?php if($row_req_verif_validé['infos_entreprise_valide']==1 OR $row_affiche_date['date_forum']<date("Y-m-d") OR date("Y-m-d")<$row_affiche_date['echeance_8j_avant_date']){?> disabled <?php } ?>name="validerinformation">Ouvrir les inscriptions étudiantes</button>
        
        <br>
        <br>
        <br>

    </form>
    </div>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
    <br>
	
        
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
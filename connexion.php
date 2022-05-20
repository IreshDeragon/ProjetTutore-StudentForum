<?php
//Démarre la session
session_start();
// S'il y a une session alors on ne retourne plus sur cette page  
if (isset($_SESSION['id'])){
    header('Location: /Ptut/Accueil.php');
    exit;
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();

    // Si la variable "$_Post" contient des informations alors on les traitres
    if(!empty($_POST)){
    	extract($_POST);
    	if (isset($_POST['connexion'])){
        	$login = trim($login);
          $mdp = trim($mdp);
          $utilisateur = trim($utilisateur);

          if($utilisateur=="etudiant"){   
          // On fait une requête pour savoir si le nom, le prénom et le mot de passe existe bien !
            if(!empty($login) AND !empty($mdp)){
              $req_etudiant = $linkpdo-> prepare("SELECT * FROM etudiant WHERE login_etudiant=:login AND mdp_etudiant=:mdp");
              $req_etudiant->execute(array('login' => $_POST['login'],
              'mdp' => md5($_POST['mdp'])));
              $row_etudiant = $req_etudiant->fetch();

              if(empty($row_etudiant)){
                $erreur_connexion = "Le login ou le mot de passe est incorrect !";
              }
              else{
                $_SESSION['id'] = $row_etudiant['id_etudiant'];
                $_SESSION['utilisateur'] = "etudiant";
                header('Location: /Ptut/Accueil.php');
                exit;
              }
            }
          }

          if($utilisateur=="entreprise"){   
          // On fait une requête pour savoir si le nom, le prénom et le mot de passe existe bien !
            if(!empty($login) AND !empty($mdp)){
              $req_entreprise = $linkpdo-> prepare("SELECT * FROM entreprise WHERE login_entreprise=:login AND mdp_entreprise=:mdp");
              $req_entreprise->execute(array('login' => $_POST['login'],
              'mdp' => md5($_POST['mdp'])));
              $row_entreprise = $req_entreprise->fetch();

              if(empty($row_entreprise)){
                $req_organisateur = $linkpdo-> prepare("SELECT * FROM organisateur WHERE login_organisateur=:login AND mdp_organisateur=:mdp");
                $req_organisateur->execute(array('login' => $_POST['login'],
                'mdp' => md5($_POST['mdp'])));
                $row_organisateur = $req_organisateur->fetch();

                if(empty($row_organisateur)){

                  $erreur_connexion = "Le login ou le mot de passe est incorrect !";
                }
                else{
                  $_SESSION['id'] = $row_organisateur['id_organisateur'];
                  $_SESSION['utilisateur'] = "organisateur";
                  header('Location: /Ptut/Accueil.php');
                  exit;
                }
              }
              else{
                $_SESSION['id'] = $row_entreprise['id_entreprise'];
                $_SESSION['utilisateur'] = "entreprise";
                header('Location: /Ptut/Accueil.php');
                exit;
              }
            }
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
		<h2>Connexion :</h2>
    <div class="connexion_etudiant">
    <br>
    <form method="post" class="pure-form">

         
          <input type="radio" name="utilisateur" id="etudiant" class="utilisateur pure-button" <?php if(!empty($_POST['utilisateur']) AND $_POST['utilisateur']=="etudiant"){?> checked <?php }?> <?php if(empty($_POST['utilisateur'])) {?> checked <?php }?>value="etudiant">
          <label for="etudiant">Etudiant</label>
          
          <input type="radio" name="utilisateur" id="entreprise" class="utilisateur pure-button" <?php if(!empty($_POST['utilisateur']) AND $_POST['utilisateur']=="entreprise"){?> checked <?php }?> value="entreprise">
          <label for="entreprise">Entreprise</label>
          <br><br>

          <input type="text" placeholder="login" name="login" value="<?php if(isset($login)){ echo $login; }?>" required><br><br>
          <input type="password" placeholder="Mot de passe" name="mdp" value="<?php if(isset($mdp)){ echo $mdp; }?>" required><br>
          <?php
            if (isset($erreur_connexion)){?>
              <div><?= $erreur_connexion ?></div>
          <?php } ?>
            <br>
          <button type="submit" name="connexion" class="pure-button">Se connecter</button>
          <br><br>
    </form>

    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br>

	</main>
	<?php include("footer.php"); ?>
</body>
</html>
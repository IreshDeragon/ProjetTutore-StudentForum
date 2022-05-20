<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();
//$id_representant =0;
//recupere l id du stage
if (isset($_GET['id_representant'])){
    $id_representant = $_GET['id_representant'];
}
if (isset($_POST['id_representant'])){
    $id_representant = $_POST['id_representant'];
}

//recupere le nom, prénom et mail du représentant
$req_donne_representant = $linkpdo-> prepare('SELECT * FROM representant WHERE id_representant=:id_representant');
$req_donne_representant->execute(array('id_representant' => $id_representant));
$row_donne_representant = $req_donne_representant->fetch();

// Si la variable "$_Post" contient des informations alors on les traitres
    if(!empty($_POST)){
    	extract($_POST);
    	if (isset($_POST['supprimer'])){

            try{
                $req_supprimer_representant = $linkpdo->prepare("DELETE FROM representant WHERE id_representant = :id_representant");
                $req_supprimer_representant->execute(array('id_representant'=>$id_representant));
                header('Location: /Ptut/profil_entreprise.php');
                exit;
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
		<h1>Supprimer représentant</h1>
    <div class="supprimerRepresentant">
    <br>
    
        <p>
            Vous-les vous réelement supprimer le représentant <?php echo $row_donne_representant['nom_representant'].' '.$row_donne_representant['prenom_representant'].' ?'; ?>
        </p><br><br>

        <form action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
            <input type="hidden" name="id_representant" value="<?php echo $id_representant ?>">
                
            <div class="supprimerRepresentantBoutons">
                <button type="submit" style="width:100px"   name="supprimer">Confirmer suppression</button>
                <a style="text-decoration: none;color:black;" class="boutonretour" href="profil_entreprise.php">Retour</a>
            </div>
            
        
        </form>
        
        

       
    	

    </form>
    </div>
    <br><br><br>
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
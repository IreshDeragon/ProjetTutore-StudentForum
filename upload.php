



<?php

//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();




$file_type = $_FILES['fileToUpload']['type'];


if($file_type == "application/pdf")
{

  	//supprimer le fichier existant

	//requete

  	$reqSupprCV = $linkpdo->prepare("SELECT etudiant.lien_cv_etudiant from etudiant WHERE id_etudiant = :id_etudiant");
	$reqSupprCV->execute(array("id_etudiant" => $_SESSION['id']));

   	while($data = $reqSupprCV->fetch())
   	{
		$file_pointer = $data['lien_cv_etudiant']; 
	   
		// unlink() pour supprimer le fichier
		if (!unlink($file_pointer)) 
		{ 
      echo "mise à jour impossible";
		} 
		else 
		{ 
      echo "misa à jour du CV...";
		} 




		$target_dir = "./uploads/etu_CV/";
		$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
		$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
	}
  $reqMAJCV = $linkpdo->prepare("UPDATE etudiant SET lien_cv_etudiant = :lien_cv_etudiant WHERE id_etudiant = :id_etudiant");
}





else
{	
	//suppression du fichier photo actuel

	//requête pour les étudiants
  if($_SESSION['utilisateur'] == 'etudiant')
  {
	  $reqSupprPhoto = $linkpdo->prepare("SELECT etudiant.lien_photo_etudiant from etudiant WHERE id_etudiant = :id_etudiant");
	  $reqSupprPhoto->execute(array("id_etudiant" => $_SESSION['id']));
    $target_dir = "./uploads/etu_photos/";
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    while($data = $reqSupprPhoto->fetch())
    {
      $file_pointer = $data['lien_photo_etudiant']; 
       
      // unlink() pour supprimer le fichier
      if (!unlink($file_pointer)) 
      { 
        echo "misa à jour de la photo...";
      } 
      else 
      { 
        echo "mise à jour impossible";
      } 


      //préparation des requêtes de modification de données des étudiants


    }
    $reqMAJPhoto = $linkpdo->prepare("UPDATE etudiant SET lien_photo_etudiant = :lien_photo WHERE id_etudiant = :id");
  }

  //requêtes pour les entreprises
  elseif ($_SESSION['utilisateur'] == 'entreprise') 
  {
     $reqSupprPhoto = $linkpdo->prepare("SELECT entreprise.lien_logo_entreprise from entreprise WHERE id_entreprise = :id_entreprise");
     $reqSupprPhoto->execute(array("id_entreprise" => $_SESSION['id']));    
     $target_dir = "./uploads/entreprise_logos/";

    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    while($data = $reqSupprPhoto->fetch())
    {
      $file_pointer = $data['lien_logo_entreprise']; 
       
      // unlink() pour supprimer le fichier
      if (!unlink($file_pointer)) 
      { 
        echo "misa à jour de la photo...";
      } 
      else 
      { 
        echo "mise à jour impossible";
      } 

      //préparation des requêtes de modification de données des entreprises

      $reqMAJPhoto = $linkpdo->prepare("UPDATE entreprise SET lien_logo_entreprise = :lien_photo WHERE id_entreprise = :id");
    }

	}
}



$uploadOk = 1;

//On contrôle si l'image est bien une image valide avec une taille récupérable ou un pdf

if(isset($_POST["submit"])) 
{
  $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);

  if($check !== false) 
  {

    echo "File is an image - " . $check["mime"] . ".";
    $uploadOk = 1;
    $reqMAJPhoto->execute(array("id" => $_SESSION['id'],	"lien_photo" => $target_file));
  } 

  else 
  {

  	if($file_type == "application/pdf")
  	{
  		echo "File is a pdf";
  		$uploadOk = 1;
  		$reqMAJCV->execute(array("id_etudiant" => $_SESSION['id'],	"lien_cv_etudiant" => $target_file));

  	}
  	else
  	{
    	echo "File is not an image or a pdf.";
    	$uploadOk = 0;
  	}

  }
}







// restriction d'existence
if (file_exists($target_file)) {
  echo "Sorry, file already exists.";
  $uploadOk = 0;


}

// Restriction de la taille
if ($_FILES["fileToUpload"]["size"] > 500000) {
  echo "Sorry, your file is too large.";
  $uploadOk = 0;
}



// Restriction des formats
if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
&& $imageFileType != "gif" && $file_type != "application/pdf") {
  echo "Sorry, only JPG, JPEG, PNG, GIF and pdf files are allowed.";
  $uploadOk = 0;
}





// si une erreur a été rencontrée
if ($uploadOk == 0) 
{
  echo "Sorry, your file was not uploaded.";
} 
// si tout est ok, on essaye d'uploader le fichier
else 
{
	//upload du nouveau fichier


  	if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) 
  	{
  		echo "The file ". htmlspecialchars( basename( $_FILES["fileToUpload"]["name"])). " has been uploaded.";
  	} 
  	else 
  	{
    	echo "Sorry, there was an error uploading your file.";
  	}
    if($_SESSION['utilisateur'] == 'etudiant')
    {
  	 header("Location:affichageProfilEtudiant.php");
    }
    else
    {
      header("Location:profil_entreprise.php");
    }
}
?>
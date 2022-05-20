<?php
//Démarre la session
session_start();
//Vérifie si secretaire connecté, si pas connecté(pas de session) on redirige vers la page connexion 
if(!isset($_SESSION['id'])){ 
	header('Location: /Ptut/connexion.php');
}
require('librairie.php');
$linkpdo = connexion_serveur_mysql();

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
		<h1>Mentions légales</h1>
    <br><br>
    <div class="planningEntreprise">
    <h2>Université Toulouse III - Paul Sabatier</h2><br><br>
    Université Toulouse III - Paul Sabatier<br>
	118 route de Narbonne<br>
	31062 TOULOUSE CEDEX 9<br>
	téléphone +33 (0)5 61 55 66 11<br><br>
	Siret : 193113842 00010
    <br><br>
    <h2>Technologies et conception</h2><br><br>
    Application développée par : Emery Antoine, Cortial Nicolas, Blanc Jérémie<br>
    Pour une navigation optimale sur le site, nous vous recommandons d'utiliser :<br>
	Firefox 6 ou supérieur<br>
	Safari 4 ou supérieur<br>
	Chrome 1 ou supérieur
    <br><br>
    <h2>Traitement de données à caractère personnel</h2><br><br>
    Les développeurs s'engagenr à ce que les traitements de données à caractère personnel, effectués à partir de son site internet institutionnel et de ses sites annexes, soient conformes à la réglementation relative à la protection des données à caractère personnel, dont la loi n° 78-17 du 6 janvier 1978 relative à l'informatique, aux fichiers et aux libertés, dite « loi informatique et libertés », ainsi que le règlement (UE) 2016/679 du Parlement européen et du Conseil du 27 avril 2016 relatif à la protection des personnes physiques à l’égard du traitement des données à caractère personnel et à la libre circulation de ces données, dit « Règlement général sur la protection des données ».<br>

	Est qualifiée de «donnée à caractère personnel», toute information se rapportant à une personne physique identifiée directement ou indirectement, notamment par référence à un identifiant, tel qu'un nom, un numéro d'identification, des données de localisation, un identifiant en ligne, ou à un ou plusieurs éléments spécifiques propres à son identité physique, physiologique, génétique, psychique, économique, culturelle ou sociale.<br>


	Toute opération automatisée ou non automatisée portant sur des données à caractère personnel est constitutive d’un « traitement », ce qui concerne notamment l’accès, la collecte, l'enregistrement, l'organisation, la structuration, la conservation, l'adaptation, la modification, l'extraction, la consultation, l'utilisation, la mise à disposition, l'interconnexion, la limitation, l'effacement, la destruction.
	<br><br>
	<h2>Excercie des droits relatifs aux traitements de données à caractère personnel</h2><br><br>
	Tout traitement de vos données à caractère personnel effectué sur le site internet institutionnel de l'Université Toulouse III - Paul Sabatier et sur ses sites annexes vous confère les droits suivants :<br>

	droit à l’information ;<br>
	droit d'accès aux données ;<br>
	droit de rectification des données ;<br>
	droit à la limitation du traitement ;<br>
	droit à la portabilité des données ;<br>
	droit d'opposition au traitement.<br>
	Pour exercer vos droits, vous pouvez solliciter le Délégué à la protection des données en envoyant un e-mail à dpo@univ-tlse3.fr

	Si vous n’obtenez pas de réponse à vos requêtes dans un délai raisonnable, vous pouvez porter une réclamation auprès de la Commission nationale de l’informatique et des libertés.

	Enfin, la diffusion publique de données à caractère personnel sur le site internet institutionnel de l'Université Toulouse III - Paul Sabatier et sur ses sites annexes, telles qu’informations nominatives ou encore images d’individus, ne saurait en aucun cas conférer à tout un chacun une liberté de les collecter et/ou de les exploiter, et ce quelle qu’en soit la finalité.
<br><br>
	<h2>Droits d'auteurs et copyright</h2><br><br>
	L'ensemble de ce site relève de la législation française et internationale sur le droit d'auteur et la propriété intellectuelle. Tous les droits de reproduction sont réservés, y compris pour les documents téléchargeables et les représentations iconographiques et photographiques.<br>

	Seules les copies à usage privé et non-commercial (gratuité de la diffusion, respect de l’intégrité des documents reproduits) sont autorisées sous réserve des droits de propriété intellectuelle dont il est fait mention.  Les logos de l'établissement sont proposés en libre téléchargement. Cependant l'usage du logo doit correspondre à une citation de l'établissement dans un article de presse ou pour un événement dans lequel l'université est impliquée. L'usage des logos ne doit jamais nuire à l'image de marque de l'établissement. Tout contrevenant s'expose à des poursuites judiciaires.<br>

	À l'exception de l'iconographie, la reproduction des pages de ce site sur un support papier est autorisée, sous réserve du respect des trois conditions suivantes :<br>

	gratuité de la diffusion,<br>
	respect de l'intégrité des documents reproduits (aucune modification, ni altération d'aucune sorte),<br>
	citation explicite de notre site comme source et mention que les droits de reproduction sont réservés et strictement limités.<br>
	La reproduction de tout ou partie de ce site sur un support électronique est autorisée pour un usage privé sous réserve de l'ajout de façon claire et lisible de la source : URL de notre site et de la mention "Droits réservés". Les informations utilisées ne doivent l'être qu'à des fins personnelles, associatives ou professionnelles ; toute utilisation à des fins commerciales ou publicitaires est exclue. La mise à disposition sur un serveur est interdite.
	<br><br>

	<h2>Liens Hypertextes</h2><br><br>
	Le site de l'université Toulouse III - Paul Sabatier autorise la mise en place d'un lien hypertexte pointant vers son contenu, sous réserve que les informations soient utilisées à des fins personnelles, associatives ou professionnelles.<br>
	Toute utilisation à des fins commerciales ou publicitaires est exclue.<br>

	Attention ! Cette autorisation ne s'applique pas aux sites internet diffusant des informations à caractère polémique, pornographique, xénophobe ou pouvant, dans une plus large mesure porter atteinte à la sensibilité du plus grand nombre. Pour d'autres utilisations, veuillez consulter le contact éditorial.<br>

	Certains liens hypertextes vous font quitter le site. Ces sites cibles ne sont pas sous le contrôle de l'université Toulouse III - Paul Sabatier (UT3). Elle n'est donc pas responsable du contenu de ces sites, des liens qu'ils contiennent ni des changements ou mises à jour qui leur ont été apportés. Les risques liés à l'utilisation de ces sites incombent donc pleinement à l'utilisateur.
	<br><br>
	<h2>Crédits</h2><br><br>
</div><br>
	
        
	</main>
	<?php  include("footer.php"); ?>
</body>
</html>
<?php 
/*
	fonction qui permet de créer un objet PDO pour se connecter à la base de données

	@param :

	@return :
*/
function connexion_serveur_mysql(){
	try {
        $linkpdo = new PDO("mysql:host=localhost;dbname=forum", 'root', '');
        $linkpdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $linkpdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }
    catch (PDOException $e) {
        die('Erreur : ' . $e->getMessage());
    } 
    return $linkpdo;
}

/*
    function qui permet d'afficher toutes les compétences dans un une balise <select>

    @param : $req -> requête sql qui a toutes les compétences 
             $libelle -> nom de la compétence pré-sélectionner

    @return : 
*/
function afficher_competence($req,$libelle){
        
        while($row = $req->fetch()){
            $libelle=$row[0];
            if($row[0]=="$libelle"){//si le libelle est égale au libelle qu'on veut pré-selectionnner alors
                echo "<option value=$row[0] selected> $libelle </option>";
            }
            else{
                echo "<option value=$row[0]> $libelle </option>";
            }
        }
}


?>
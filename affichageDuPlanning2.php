<?php 

	function afficherPlanning2($idRepresentant,$periode, $dateforum)
	{

	$linkpdo = connexion_serveur_mysql();

		//requete pour avoir le nom du représentant
    	$req_representant = $linkpdo->prepare('SELECT * FROM representant WHERE id_representant=:id_representant');
		$req_representant ->execute(array('id_representant' => $idRepresentant));
		$row_req_representant = $req_representant->fetch();

		//requete pour avoir le nom de l'entreprise
    	$req_entreprise = $linkpdo->prepare('SELECT * FROM entreprise WHERE id_entreprise=:id_entreprise');
		$req_entreprise ->execute(array('id_entreprise' => $row_req_representant['id_entreprise']));
		$row_req_entreprise = $req_entreprise->fetch();

		$req_etudiant = $linkpdo->prepare('SELECT etudiant.id_etudiant AS "id_etudiant", creneaux.id_stage AS "id_stage" FROM etudiant, creneaux WHERE creneaux.id_representant =:id_representant AND creneaux.id_etudiant=etudiant.id_etudiant ORDER BY creneaux.date_horaire');
		$req_etudiant ->execute(array('id_representant' => $idRepresentant));
		$row_req_etudiant= [];
		while($data = $req_etudiant->fetch())
		{
			$row_req_etudiant[] = $data;
		}		


	$rendu ="";
	$dateforum = strtotime($dateforum);

	/*Faire les exceptions*/

	$heure =array(strtotime("08:00:00"), strtotime("08:30:00"),strtotime("09:00:00"),strtotime("09:30:00"),strtotime("10:00:00"),strtotime("10:30:00"),strtotime("11:00:00"),strtotime("11:30:00"), strtotime("14:00:00"),strtotime("14:30:00"),strtotime("15:00:00"),strtotime("15:30:00"),strtotime("16:00:00"),strtotime("16:30:00"));

	$tableauData = [];
	$req = $linkpdo->prepare('SELECT * FROM creneaux WHERE id_representant = :representant');
	$req->execute(array('representant' => $idRepresentant));

	while ($data = $req->fetch()){
		$tableauData[] = $data;
	}
	$numCreneauIndisponible = 0;
	$rendu = $rendu."<div class='gridentreprisenom'>".$row_req_entreprise['nom_entreprise']."</div>
							<div class='gridrepresentantnom'>".$row_req_representant['nom_representant']."</div>";
	if ($periode == "matin" OR $periode == "journee" ){

		for ($i =0; $i<8; $i++){
			$creneauxPris =false;
			foreach ($tableauData as $key => $value) {
				if ($value['date_horaire'] == date('Y-m-d',$dateforum)." ".date('H:i:s',$heure[$i])){
					$creneauxPris =true;
				}
			}

			$rendu = $rendu."
				<div class='gridheure".$i."'>".date('H:i',$heure[$i])."</div>";

			if ($creneauxPris == true){
				$rendu = $rendu."<div class='gridcreneauxindisponible' class='gridcreneaux".$i."'>
				<form method=\"POST\" action=\"affichageProfilEtudiant.php\">
				<input type=\"hidden\" name=\"id_etudiant_a_afficher\" value=\"".$row_req_etudiant[$numCreneauIndisponible]['id_etudiant']."\"/>
				<input type=\"hidden\" name=\"id_stage_a_afficher\" value=\"".$row_req_etudiant[$numCreneauIndisponible]['id_stage']."\"/>
				<button class = \"caseEDT\"type=\"submit\" value = \"\" name=\"allerAProfilEtudiant\">
				</button></form>
				</div>";
				$numCreneauIndisponible++;
			}
			else {
				$rendu = $rendu."<div class='gridcreneauxdisponible' class='gridcreneaux".$i."'>
				</div>";
			}
		}
	}

	if($periode == "matin" AND $periode != "journee" ){
		for ($i =8; $i<14; $i++){

			$rendu = $rendu."<div class='gridmatin".$i."'></div>";
		}
	}

	if($periode == "aprem" AND $periode != "journee" ){
		for ($i =0; $i<8; $i++){

			$rendu = $rendu."<div class='gridmatin".$i."'></div>";
		}
	}

	if ($periode == "aprem" OR $periode == "journee" ){

		for ($i =8; $i<14; $i++){
			$creneauxPris =false;
			foreach ($tableauData as $key => $value) {
				if ($value['date_horaire'] == date('Y-m-d',$dateforum)." ".date('H:i:s',$heure[$i])){
					$creneauxPris =true;
				}
			}

			$rendu = $rendu."
				<div class='gridheure".$i."'>".date('H:i',$heure[$i])."</div>";

			if ($creneauxPris == true){
				$rendu = $rendu."<div class='gridcreneauxindisponible' class='gridcreneaux".$i."'>
				<form method=\"POST\" action=\"affichageProfilEtudiant.php\">
				<input type=\"hidden\" name=\"id_etudiant_a_afficher\" value=\"".$row_req_etudiant[$numCreneauIndisponible]['id_etudiant']."\"/>
				<input type=\"hidden\" name=\"id_stage_a_afficher\" value=\"".$row_req_etudiant[$numCreneauIndisponible]['id_stage']."\"/>
				<button class = \"caseEDT\"type=\"submit\" value = \"\" name=\"allerAProfilEtudiant\">
				</button>
				</form>
				</div>";
				$numCreneauIndisponible++;
			}
			else {
				$rendu = $rendu."<div class='gridcreneauxdisponible' class='gridcreneaux".$i."'>
				</div>";
			}
		}
	}

	return $rendu;

}































function tableauImprimer($idRepresentant,$periode, $dateforum)
	{

	$linkpdo = connexion_serveur_mysql();

		//requete pour avoir le nom du représentant
    	$req_representant = $linkpdo->prepare('SELECT * FROM representant WHERE id_representant=:id_representant');
		$req_representant ->execute(array('id_representant' => $idRepresentant));
		$row_req_representant = $req_representant->fetch();

		//requete pour avoir le nom de l'entreprise
    	$req_entreprise = $linkpdo->prepare('SELECT * FROM entreprise WHERE id_entreprise=:id_entreprise');
		$req_entreprise ->execute(array('id_entreprise' => $row_req_representant['id_entreprise']));
		$row_req_entreprise = $req_entreprise->fetch();

		$req_etudiant = $linkpdo->prepare('SELECT etudiant.id_etudiant AS "id_etudiant", creneaux.id_stage AS "id_stage" FROM etudiant, creneaux WHERE creneaux.id_representant =:id_representant AND creneaux.id_etudiant=etudiant.id_etudiant ORDER BY creneaux.date_horaire');
		$req_etudiant ->execute(array('id_representant' => $idRepresentant));
		$row_req_etudiant= [];
		while($data = $req_etudiant->fetch())
		{
			$row_req_etudiant[] = $data;
		}		


	$rendu ="";
	$dateforum = strtotime($dateforum);

	/*Faire les exceptions*/

	$heure =array(strtotime("08:00:00"), strtotime("08:30:00"),strtotime("09:00:00"),strtotime("09:30:00"),strtotime("10:00:00"),strtotime("10:30:00"),strtotime("11:00:00"),strtotime("11:30:00"), strtotime("14:00:00"),strtotime("14:30:00"),strtotime("15:00:00"),strtotime("15:30:00"),strtotime("16:00:00"),strtotime("16:30:00"));

	$tableauData = [];
	$req = $linkpdo->prepare('SELECT * FROM creneaux WHERE id_representant = :representant');
	$req->execute(array('representant' => $idRepresentant));

	while ($data = $req->fetch()){
		$tableauData[] = $data;
	}
	$rendu = $rendu."<table><tr><td><b>Horaire</td><td><b>Etudiant</td><td><b>Présent</td>";
	if ($periode == "matin"){

		for ($i =0; $i<8; $i++){
			$creneauxPris =false;
			foreach ($tableauData as $key => $value) {
				if ($value['date_horaire'] == date('Y-m-d',$dateforum)." ".date('H:i:s',$heure[$i])){
					$creneauxPris =true;
				}
			}

			$rendu = $rendu."
				<tr><td>".date('H:i',$heure[$i])."</td>";

			if ($creneauxPris == true){

				//requete pour avoir le nom et le prénom de l'étudiant
				$req_nom_etudiant = $linkpdo->prepare('SELECT nom_etudiant,prenom_etudiant FROM etudiant WHERE id_etudiant=:id_etudiant');
				$req_nom_etudiant ->execute(array('id_etudiant' => $value['id_etudiant']));
				$row_req_nom_etudiant = $req_nom_etudiant->fetch();

				$rendu = $rendu."<td>".$row_req_nom_etudiant['nom_etudiant']." ".$row_req_nom_etudiant['prenom_etudiant']."</td>";
			}
			else {
				$rendu = $rendu."<td>Aucun</td>";
			}

			$rendu = $rendu."<td></td></tr>";
		}
	}

	if ($periode == "aprem"){

		for ($i =8; $i<14; $i++){
			$creneauxPris =false;
			foreach ($tableauData as $key => $value) {
				if ($value['date_horaire'] == date('Y-m-d',$dateforum)." ".date('H:i:s',$heure[$i])){
					$creneauxPris =true;
				}
			}

			$rendu = $rendu."
				<tr><td>".date('H:i',$heure[$i])."</td>";

			if ($creneauxPris == true){

				//requete pour avoir le nom et le prénom de l'étudiant
				$req_nom_etudiant = $linkpdo->prepare('SELECT nom_etudiant,prenom_etudiant FROM etudiant WHERE id_etudiant=:id_etudiant');
				$req_nom_etudiant ->execute(array('id_etudiant' => $value['id_etudiant']));
				$row_req_nom_etudiant = $req_nom_etudiant->fetch();

				$rendu = $rendu."<td>".$row_req_nom_etudiant['nom_etudiant']." ".$row_req_nom_etudiant['prenom_etudiant']."</td>";
			}
			else {
				$rendu = $rendu."<td>Aucun</td>";
			}

			$rendu = $rendu."<td></td></tr>";
		}
	}

	if ($periode == "journee"){

		for ($i =0; $i<14; $i++){
			$creneauxPris =false;
			foreach ($tableauData as $key => $value) {
				if ($value['date_horaire'] == date('Y-m-d',$dateforum)." ".date('H:i:s',$heure[$i])){
					$creneauxPris =true;
				}
			}

			if($i==8){
				$rendu = $rendu."<td><b>Pause</td><td><b>Pause</td><td><b>Pause</td>";
			}

			$rendu = $rendu."
				<tr><td>".date('H:i',$heure[$i])."</td>";

			if ($creneauxPris == true){

				//requete pour avoir le nom et le prénom de l'étudiant
				$req_nom_etudiant = $linkpdo->prepare('SELECT nom_etudiant,prenom_etudiant FROM etudiant WHERE id_etudiant=:id_etudiant');
				$req_nom_etudiant ->execute(array('id_etudiant' => $value['id_etudiant']));
				$row_req_nom_etudiant = $req_nom_etudiant->fetch();

				$rendu = $rendu."<td>".$row_req_nom_etudiant['nom_etudiant']." ".$row_req_nom_etudiant['prenom_etudiant']."</td>";
			}
			else {
				$rendu = $rendu."<td>Aucun</td>";
			}

			$rendu = $rendu."<td> </td></tr>";
		}
	}

	$rendu = $rendu."</table>";

	return $rendu;

}
	
?>
<header>
		<div class="forum"><img id="image_header" alt="Image iut Paul Sabatier" src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ0CYPWC3zHHL1t0HkiqjkllElh8j-iBqiDsw&usqp=CAU"><h1><br>Forum de stage</h1></div>
		<div class="menu">
			<?php 
				if (isset($_SESSION['id'])){
					if($_SESSION['utilisateur']=="etudiant"){ ?>
						<div class="item item1">
							<a style="font-size: 1.1em;" href="Accueil.php">Accueil</a>
						</div>
						<div class="item item2">
							<a style="font-size: 1.1em;" href="ListeStagesEtudiant.php">Liste des stages</a>
						</div>
						<div class="item item2">
							<a style="font-size: 1.1em;" href="affichageProfilEtudiant.php">Profil</a>
						</div>
						<div class="item item3">
							<a style="font-size: 1.1em;" href="ConsultationMenu.php">Mes rendez-vous</a>
						</div>
						<div class="item item4">
							<a style="font-size: 1.1em;" href="deconnexion.php">Déconnexion</a>
						</div>
					<?php }elseif ($_SESSION['utilisateur']=="entreprise") { ?>
						<div class="item item1">
							<a style="font-size: 1.1em;" href="Accueil.php">Accueil</a>
						</div>
						<div class="item item2">
							<a style="font-size: 1.1em;" href="PlanningEntreprise.php">Planning</a>
						</div>
						<div class="item item2">
							<a style="font-size: 1.1em;" href="Mesoffresdestages.php">Mes offres de stages</a>
						</div>
						<div class="item item3">
							<a style="font-size: 1.1em;" href="profil_entreprise.php">Profil</a>
						</div>
						<div class="item item4">
							<a style="font-size: 1.1em;" href="deconnexion.php">Déconnexion</a>
						</div>
					<?php }elseif ($_SESSION['utilisateur']=="organisateur") { ?>
						<div class="item item1">
							<a style="font-size: 1.1em;" href="Accueil.php">Accueil</a>
						</div>
						<div class="item item2">
							<a style="font-size: 1.1em;" href="PlanningAdministrateur.php">Planning</a>
						</div>
						<div class="item item2">
							<a style="font-size: 1.1em;" href="gestionEntreprise.php">Entreprises</a>
						</div>
						<div class="item item3">
							<a style="font-size: 1.1em;" href="gestionForum.php">Gestion du forum</a>
						</div>
						<div class="item item4">
							<a style="font-size: 1.1em;" href="Statistiques.php">Statistiques</a>
						</div>
						<div class="item item5">
							<a style="font-size: 1.1em;" href="deconnexion.php">Déconnexion</a>
						</div>
					<?php }
				}else{ ?>
					<br><br>
				<?php } ?>

		</div>
</header>
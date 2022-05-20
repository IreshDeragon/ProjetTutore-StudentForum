<?php
session_start();
require('librairie.php');
$linkpdo = connexion_serveur_mysql();
session_destroy();
header('location: /Ptut/connexion.php'); // Ici il faut mettre la page sur lequel l'utilisateur sera redirigé.
﻿exit;
?>
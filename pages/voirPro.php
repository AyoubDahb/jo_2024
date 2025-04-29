<?php
require_once("modele/modeleUser.class.php");
$mod = new ModeleUser($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
$listePros = $mod->getAllProUsers();

include("./vue/vue_les_profilsPro.php");

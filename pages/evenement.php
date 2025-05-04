<?php

$lesCategories = $c_Categories->selectAllCategories();
$lesEvenements = $c_Event->selectAllEvenements();

$lEvenement = null;
if (isset($_GET['action']) && isset($_GET['idevenement'])) {
    $action = $_GET['action'];
    $idevenement = $_GET['idevenement'];
    switch ($action) {
        case "sup":
            if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'clientPro') {
                $c_Event->deleteEvenement($idevenement);
            }
            break;
        case "edit":
            if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'clientPro') {
                $lEvenement = $c_Event->selectWhereEvenement($idevenement);
            }
            break;
        case "reserver":
            if ($_SESSION['role'] === 'clientPart') {
                $c_Event->reserverEvenement($_SESSION['iduser'], $idevenement);
            }
            break;
        case "annuler":
            if ($_SESSION['role'] === 'clientPart') {
                $c_Event->annulerReservation($_SESSION['iduser'], $idevenement);
            }
            break;
    }
}

require_once("vue/vue_insert_evenement.php");
if (isset($_POST['Valider']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'clientPro')) {
    $c_Event->insertEvenement($_POST);
}

if (isset($_POST['Modifier']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'clientPro')) {
    $c_Event->updateEvenement($_POST);
    header("Location: index.php?page=1");
}

$lesEvenements = $c_Event->selectAllEvenements();
require_once("vue/vue_les_evenements.php");

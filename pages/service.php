<?php
$lesTypeservices = $c_TypeService->selectAllTypeservices();
$lesServices = $c_Service->selectAllServices();

$leService = null;
if (isset($_GET['action']) && isset($_GET['idservice'])) {
    $action = $_GET['action'];
    $idservice = $_GET['idservice'];
    switch ($action) {
        case "sup":
            if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'clientPro') {
                $c_Service->deleteService($idservice);
            }
            break;
        case "edit":
            if ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'clientPro') {
                $leService = $c_Service->selectWhereService($idservice);
            }
            break;
        case "reserver":
            if ($_SESSION['role'] === 'clientPart') {
                $c_Service->reserverService($_SESSION['iduser'], $idservice);
            }
            break;
        case "annuler":
            if ($_SESSION['role'] === 'clientPart') {
                $c_Service->annulerReservation($_SESSION['iduser'], $idservice);
            }
            break;
    }
}

require_once("vue/vue_insert_service.php");
if (isset($_POST['Valider']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'clientPro')) {
    $c_Service->insertService($_POST);
}
if (isset($_POST['Modifier']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'clientPro')) {
    $c_Service->updateService($_POST);
    header("Location: index.php?page=2");
}

$lesServices = $c_Service->selectAllServices();
require_once("vue/vue_les_services.php");

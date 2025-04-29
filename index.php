<?php
session_start();

// 1) Chargement de la config et des contrôleurs
require_once "controleur/config_bdd.php";
require_once "controleur/controleurCategorie.class.php";
require_once "controleur/controleurEvent.class.php";
require_once "controleur/controleurService.class.php";
require_once "controleur/controleurTypeService.class.php";
require_once "controleur/controleurUser.class.php";

// 2) Instanciation des contrôleurs
$c_Categories   = new ControleurCategorie($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
$c_Event        = new ControleurEvent($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
$c_Service      = new ControleurService($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
$c_TypeService  = new ControleurTypeService($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
$c_User         = new ControleurUser($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);

// 3) Déclenchement de la suppression s’il y a action=supprimerPro
if (isset($_GET['action']) && $_GET['action'] === 'supprimerPro' && isset($_GET['iduser'])) {
    $c_User->supprimerProfessionnel((int)$_GET['iduser']);
    // on redirige vers page 7 pour voir la liste après suppression
    header('Location: index.php?page=7');
    exit;
}

// 4) Détermination de la page à charger via page=...
isset($_GET['page']) ? $page = $_GET['page'] : $page = 0;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Jeux Olympiques 2024</title>
    <link rel="icon" href="images/olympic.ico">
    <link href="styles.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet">
</head>

<body>
    <header>
        <?php require_once "composants/navbar.php"; ?>
    </header>

    <main>
        <?php
        switch ($page) {
            case 0:
                require_once "pages/home.php";
                break;
            case 1:
                require_once "pages/evenement.php";
                break;
            case 2:
                require_once "pages/service.php";
                break;
            case 3:
                require_once "pages/inscription.php";
                break;
            case 4:
                require_once "pages/connexion.php";
                break;
            case 5:
                require_once "pages/deconnexion.php";
                break;
            case 6:
                require_once "pages/profil.php";
                break;
            case 7:
                // Liste des pros accessible seulement aux admins
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    $c_User->voirProfessionnels();  // inclut maintenant vue_les_profilsPro.php
                } else {
                    echo "<p>Accès refusé</p>";
                }
                break;
            default:
                echo "<p>Page introuvable</p>";
        }
        ?>
    </main>

    <footer>
        <?php require_once "composants/footer.php"; ?>
    </footer>

    <script src="js/script.js" defer></script>
</body>

</html>
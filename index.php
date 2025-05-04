<?php
// Démarrage de la session
session_start();

// 1) Inclusion des fichiers de configuration et des contrôleurs
require_once "controleur/config_bdd.php";
require_once "controleur/controleurCategorie.class.php";
require_once "controleur/controleurEvent.class.php";
require_once "controleur/controleurService.class.php";
require_once "controleur/controleurTypeService.class.php";
require_once "controleur/controleurUser.class.php";

// 2) Instanciation des contrôleurs
$c_Categories  = new ControleurCategorie($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
$c_Event       = new ControleurEvent($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
$c_Service     = new ControleurService($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
$c_TypeService = new ControleurTypeService($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
$c_User        = new ControleurUser($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);

// 3) Gestion des actions globales (exemple : suppression d'un utilisateur)
if (isset($_GET['action']) && $_GET['action'] === 'supprimerPro' && isset($_GET['iduser'])) {
    $c_User->supprimerProfessionnel((int)$_GET['iduser']);
    header('Location: index.php?page=7');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'supprimerPart' && isset($_GET['iduser'])) {
    $c_User->supprimerParticulier((int)$_GET['iduser']);
    exit;
}

// 4) Détermination de la page à afficher (par défaut : page d'accueil)
$page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Jeux Olympiques 2024</title>
    <link rel="icon" href="images/olympic.ico">
    <link href="styles.css" rel="stylesheet">
    <link href="css/home.css" rel="stylesheet">
</head>

<body>
    <header>
        <?php
        // Inclusion de la barre de navigation
        require_once "composants/navbar.php";
        ?>
    </header>

    <main>
        <?php
        // Chargement de la page en fonction de la valeur de $page
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
                require_once "pages/monprofil.php";
                break;
            case 7:
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    $c_User->voirProfessionnels();
                } else {
                    echo "<p style='text-align:center;color:red'>Accès refusé</p>";
                }
                break;
            case 8:
                require_once "pages/mdp_oublie.php";
                break;
            case 9:
                require_once "pages/mes_reservations.php";
                break;
            case 10:
                require_once "pages/gestion_utilisateurs.php";
                break;
            case 11:
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    $c_User->voirParticuliers();
                } else {
                    echo "<p style='text-align:center;color:red'>Accès refusé</p>";
                }
                break;
            case 12:
                if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
                    require_once "pages/rechercher_client.php";
                } else {
                    echo "<p style='text-align:center;color:red'>Accès refusé</p>";
                }
                break;
            default:
                echo "<p style='text-align:center;color:red'>Page introuvable</p>";
        }
        ?>
    </main>

    <footer>
        <?php
        // Inclusion du pied de page
        require_once "composants/footer.php";
        ?>
    </footer>

    <script src="js/script.js" defer></script>
</body>

</html>
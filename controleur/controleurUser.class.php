<?php
require_once("modele/modeleUser.class.php");

class ControleurUser
{
    private $mod;

    public function __construct($serveur, $serveur2, $bdd, $user, $mdp, $mdp2)
    {
        $this->mod = new ModeleUser($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
    }

    // -----------------------------
    // Inscription Client Particulier
    // -----------------------------
    public function insertClientPar(array $tab)
    {
        // Validation
        foreach ($tab as $k => $v) {
            $tab[$k] = trim(htmlspecialchars($v));
            if ($v === "") {
                echo "<p style='text-align:center'>Veuillez remplir tous les champs</p>";
                return;
            }
        }

        if (isset($_POST['InscriptionPart'])) {
            $email = $tab['email'];
            if (is_array($this->mod->checkUser($email))) {
                echo "<p style='text-align:center'>Email déjà existant</p>";
                return;
            }

            // Délègue le hash et l'insertion au modèle
            $this->mod->insertClientPar($tab);
            echo "<p style='text-align:center'>Utilisateur enregistré !</p>";
        }
    }

    // -----------------------------
    // Inscription Client Professionnel
    // -----------------------------
    public function insertClientPro(array $tab)
    {
        // Validation
        foreach ($tab as $k => $v) {
            $tab[$k] = trim(htmlspecialchars($v));
            if ($v === "") {
                echo "<p style='text-align:center'>Veuillez remplir tous les champs</p>";
                return;
            }
        }

        if (isset($_POST['InscriptionPro'])) {
            $email = $tab['email'];
            if (is_array($this->mod->checkUser($email))) {
                echo "<p style='text-align:center'>Email déjà existant</p>";
                return;
            }

            // Délègue le hash et l'insertion au modèle
            $this->mod->insertClientPro($tab);
            echo "<p style='text-align:center'>Professionnel enregistré !</p>";
        }
    }

    // -----------------------------
    // Connexion
    // -----------------------------
    public function selectUser($email, $mdp)
    {
        return $this->mod->selectUser($email, $mdp);
    }

    // -----------------------------
    // Gestion des professionnels (Mission 7)
    // -----------------------------
    public function voirProfessionnels()
    {
        $listePros = $this->mod->getAllProUsers();
        include "./vue/vue_les_profilsPro.php";
    }

    public function supprimerProfessionnel(int $iduser)
    {
        $this->mod->supprimerProEtAnnonces($iduser);
        header("Location: index.php?page=7");
        exit;
    }

    // -----------------------------
    // Méthodes de sélection Services/Catégories
    // -----------------------------
    public function selectAllHotels()
    {
        return $this->mod->selectAllHotels();
    }

    public function selectAllRestaurants()
    {
        return $this->mod->selectAllRestaurants();
    }

    public function selectAllSports()
    {
        return $this->mod->selectAllSports();
    }

    public function selectAllCultures()
    {
        return $this->mod->selectAllCultures();
    }

    public function selectAllLoisirs()
    {
        return $this->mod->selectAllLoisirs();
    }

    // -----------------------------
    // Méthodes Utilisateur
    // -----------------------------
    public function checkUser($email)
    {
        return $this->mod->checkUser($email);
    }

    public function findByRole($role, $iduser)
    {
        return $this->mod->findByRole($role, $iduser);
    }

    public function selectClientPart($email)
    {
        return $this->mod->selectClientPart($email);
    }

    public function selectClientPro($email)
    {
        return $this->mod->selectClientPro($email);
    }
}

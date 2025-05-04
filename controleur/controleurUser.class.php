<?php
require_once("modele/modeleUser.class.php");

use Dompdf\Dompdf;
use Dompdf\Options;

class ControleurUser
{
    private $mod;

    public function __construct($serveur, $serveur2, $bdd, $user, $mdp, $mdp2)
    {
        // Instanciation du modèle utilisateur
        $this->mod = new ModeleUser($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
    }

    // -----------------------------
    // Inscription Client Particulier
    // -----------------------------
    public function insertClientPar(array $tab)
    {
        // Validation des champs obligatoires
        foreach ($tab as $k => $v) {
            $tab[$k] = trim(htmlspecialchars($v));
            if ($v === "") {
                echo "<p style='text-align:center'>Veuillez remplir tous les champs</p>";
                return;
            }
        }

        // Vérification de l'unicité de l'email
        if (isset($_POST['InscriptionPart'])) {
            $email = $tab['email'];
            if (is_array($this->mod->checkUser($email))) {
                echo "<p style='text-align:center'>Email déjà existant</p>";
                return;
            }

            // Insertion dans la base de données
            $this->mod->insertClientPar($tab);
            echo "<p style='text-align:center'>Utilisateur enregistré !</p>";
        }
    }

    // -----------------------------
    // Inscription Client Professionnel
    // -----------------------------
    public function insertClientPro(array $tab)
    {
        // Validation des champs obligatoires
        foreach (['nom', 'prenom', 'email', 'mdp', 'tel', 'num_Siret', 'adresse', 'code_postal'] as $f) {
            $tab[$f] = trim(htmlspecialchars($tab[$f] ?? ''));
            if ($tab[$f] === '') {
                echo "<p style='text-align:center;color:red'>Le champ « {$f} » est obligatoire.</p>";
                return;
            }
        }

        // Vérification des critères spécifiques (exemple : format du SIRET)
        if (!preg_match('/^\d{14}$/', $tab['num_Siret'])) {
            echo "<p style='text-align:center;color:red'>SIRET invalide (14 chiffres).</p>";
            return;
        }

        // Vérification de l'unicité de l'email
        if (is_array($this->mod->checkUser($tab['email']))) {
            echo "<p style='text-align:center;color:red'>Email déjà utilisé.</p>";
            return;
        }

        // Insertion dans la base de données
        $this->mod->insertClientPro($tab);
        echo "<p style='text-align:center;color:green'>Professionnel enregistré !</p>";
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
        $listePros = array_filter($this->mod->getAllUsers(), function ($user) {
            return $user['role'] === 'clientPro';
        });

        include "./vue/vue_les_profilsPro.php";
    }

    public function supprimerProfessionnel(int $iduser)
    {
        $this->mod->supprimerProEtAnnonces($iduser);
        header("Location: index.php?page=7");
        exit;
    }

    // -----------------------------
    // Gestion des particuliers
    // -----------------------------
    public function voirParticuliers()
    {
        $listeParticuliers = $this->mod->getAllPartUsers();
        include "./vue/vue_les_profilsPart.php";
    }

    public function supprimerParticulier(int $iduser)
    {
        $this->mod->supprimerPartEtInscriptions($iduser);
        header("Location: index.php?page=11");
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

    // Méthode pour récupérer tous les utilisateurs
    public function getAllUsers()
    {
        return $this->mod->getAllUsers();
    }

    // Méthode pour mettre à jour un utilisateur
    public function updateUser(array $data)
    {
        // Validation des champs obligatoires
        foreach (['nom', 'email', 'tel'] as $field) {
            if (!isset($data[$field]) || trim($data[$field]) === '') {
                throw new \Exception("Le champ « $field » est obligatoire.");
            }
        }

        // Convertir les champs vides en null pour les champs optionnels
        foreach (['prenom', 'num_Siret', 'adresse'] as $optionalField) {
            if (!isset($data[$optionalField]) || trim($data[$optionalField]) === '') {
                $data[$optionalField] = null;
            }
        }

        // Mise à jour dans la base de données
        $this->mod->updateUser($data); // Transmet toutes les données au modèle

        // Rechargez les données après la mise à jour
        return $this->mod->getAllUsers();
    }

    public function countValidatedReservations($iduser)
    {
        $eventReservations = $this->mod->countEventReservations($iduser);
        $serviceReservations = $this->mod->countServiceReservations($iduser);
        return $eventReservations + $serviceReservations;
    }

    public function calculateTotalReservationAmount($iduser)
    {
        $serviceAmount = $this->mod->calculateServiceReservationAmount($iduser);
        return $serviceAmount; // Seul le montant des services est pris en compte
    }

    // Génération d'un PDF contenant la liste des utilisateurs
    public function generatePDF($users)
    {
        require_once 'vendor/autoload.php';

        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Génération du contenu HTML pour le PDF
        $html = '<h1>Liste des utilisateurs</h1><table border="1" cellpadding="5">';
        $html .= '<thead><tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Rôle</th>
                    <th>Numéro SIRET</th>
                    <th>Adresse</th>
                  </tr></thead><tbody>';

        foreach ($users as $user) {
            $html .= '<tr>
                        <td>' . htmlspecialchars($user['nom']) . '</td>
                        <td>' . htmlspecialchars($user['prenom'] ?? '') . '</td>
                        <td>' . htmlspecialchars($user['email']) . '</td>
                        <td>' . htmlspecialchars($user['tel']) . '</td>
                        <td>' . htmlspecialchars($user['role']) . '</td>
                        <td>' . htmlspecialchars($user['num_Siret'] ?? '') . '</td>
                        <td>' . htmlspecialchars($user['adresse'] ?? '') . '</td>
                      </tr>';
        }

        $html .= '</tbody></table>';

        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Téléchargement du fichier PDF
        $dompdf->stream('Liste_Utilisateurs.pdf', ['Attachment' => true]);
    }
}

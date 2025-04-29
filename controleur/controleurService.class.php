<?php
require_once 'modele/modeleService.class.php';

class ControleurService
{
    private $mod;

    public function __construct($serveur, $serveur2, $bdd, $user, $mdp, $mdp2)
    {
        // on instancie le modèle Service
        $this->mod = new ModeleService($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
    }

    /**
     * Insère un nouveau service lié à l’utilisateur connecté.
     *
     * @param array $tab Issu de $_POST : libelle, adresse, prix, tel, idtypeservices et email_user
     */
    public function insertService(array $tab)
    {
        // 1) Validation minimale
        foreach (['libelle', 'adresse', 'prix', 'tel', 'idtypeservices', 'email_user'] as $field) {
            if (empty($tab[$field])) {
                throw new \Exception("Le champ « {$field} » est obligatoire.");
            }
        }

        // 2) Appel au modèle
        $this->mod->insertService($tab, $tab['email_user']);

        // 3) Redirection (ou retourne vrai pour un appel AJAX)
        header('Location: index.php?page=2'); // vers la liste des services
        exit;
    }

    /**
     * Récupère tous les services.
     *
     * @return array
     */
    public function selectAllServices(): array
    {
        return $this->mod->selectAllServices();
    }

    /**
     * Supprime un service par son ID.
     *
     * @param int $idservice
     */
    public function deleteService(int $idservice): void
    {
        $this->mod->deleteService($idservice);
        header('Location: index.php?page=2');
        exit;
    }

    /**
     * Récupère un service pour affichage ou modification.
     *
     * @param int $idservice
     * @return array|null
     */
    public function selectWhereService(int $idservice)
    {
        return $this->mod->selectWhereService($idservice);
    }

    /**
     * Met à jour un service existant.
     *
     * @param array $tab Issu de $_POST : idservice, libelle, adresse, prix, tel, email_user, idtypeservices
     */
    public function updateService(array $tab)
    {
        // Validation minimale
        foreach (['idservice', 'libelle', 'adresse', 'prix', 'tel', 'idtypeservices', 'email_user'] as $field) {
            if (empty($tab[$field])) {
                throw new \Exception("Le champ « {$field} » est obligatoire.");
            }
        }

        // Appel au modèle
        $this->mod->updateService($tab);

        // Redirection vers la liste ou vers le détail
        header('Location: index.php?page=2');
        exit;
    }
}

<?php
require_once("modele/modeleMere.class.php");

class ModeleService
{
    private $pdo;

    public function __construct($serveur, $serveur2, $bdd, $user, $mdp, $mdp2)
    {
        $this->pdo = ModeleMere::getPdo($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
    }

    /**
     * Insère un nouveau service et le lie à l'email du professionnel.
     *
     * @param array  $tab        Contient libelle, adresse, prix, tel, idtypeservices
     * @param string $email_user Email du pro (récupérée depuis $_SESSION)
     */
    public function insertService(array $tab, string $email_user)
    {
        $sql = "
            INSERT INTO Service
                (libelle, adresse, prix, tel, email, image, idtypeservices)
            VALUES
                (:libelle, :adresse, :prix, :tel, :email_user, :image, :idtypeservices)
        ";

        $donnees = [
            ':libelle'        => $tab['libelle'],
            ':adresse'        => $tab['adresse'],
            ':prix'           => $tab['prix'],
            ':tel'            => $tab['tel'],
            ':email_user'     => $email_user,         // email du pro connecté
            ':image'          => '',                  // pas d'image pour l'instant
            ':idtypeservices' => $tab['idtypeservices'],
        ];

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($donnees);
    }

    /**
     * Retourne tous les services.
     *
     * @return array|null
     */
    public function selectAllServices()
    {
        $sql = "SELECT * FROM Service;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime un service.
     *
     * @param int $idservice
     */
    public function deleteService(int $idservice): void
    {
        $sql = "DELETE FROM Service WHERE idservice = :idservice;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idservice' => $idservice]);
    }

    /**
     * Récupère un service pour édition.
     *
     * @param int $idservice
     * @return array|null
     */
    public function selectWhereService(int $idservice)
    {
        $sql = "SELECT * FROM Service WHERE idservice = :idservice;";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':idservice' => $idservice]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour un service existant.
     *
     * @param array $tab Contient idservice, libelle, adresse, prix, tel, email, idtypeservices
     */
    public function updateService(array $tab): void
    {
        $sql = "
            UPDATE Service
            SET
                libelle        = :libelle,
                adresse        = :adresse,
                prix           = :prix,
                tel            = :tel,
                email          = :email,
                idtypeservices = :idtypeservices
            WHERE idservice = :idservice
        ";

        $donnees = [
            ':idservice'      => $tab['idservice'],
            ':libelle'        => $tab['libelle'],
            ':adresse'        => $tab['adresse'],
            ':prix'           => $tab['prix'],
            ':tel'            => $tab['tel'],
            ':email'          => $tab['email'],           // si nécessaire
            ':idtypeservices' => $tab['idtypeservices'],
        ];

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($donnees);
    }

    public function isReserved($iduser, $idservice)
    {
        $sql = "SELECT COUNT(*) FROM Louer WHERE iduser = :iduser AND idservice = :idservice";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':iduser' => $iduser, ':idservice' => $idservice]);
        return $stmt->fetchColumn() > 0;
    }

    public function getReservations($iduser)
    {
        $sql = "SELECT s.* FROM Louer l JOIN Service s ON l.idservice = s.idservice WHERE l.iduser = :iduser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':iduser' => $iduser]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function reserverService($iduser, $idservice)
    {
        $sql = "INSERT INTO Louer (iduser, idservice, heureD, heureF) 
                VALUES (:iduser, :idservice, NOW(), NULL)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':iduser' => $iduser,
            ':idservice' => $idservice
        ]);
    }

    public function annulerReservation($iduser, $idservice)
    {
        $sql = "DELETE FROM Louer WHERE iduser = :iduser AND idservice = :idservice";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':iduser' => $iduser,
            ':idservice' => $idservice
        ]);
    }
}

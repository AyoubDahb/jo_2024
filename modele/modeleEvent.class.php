<?php
require_once("modele/modeleMere.class.php");

class ModeleEvent
{
    private $pdo;
    public function __construct($serveur, $serveur2, $bdd, $user, $mdp, $mdp2)
    {
        $this->pdo = ModeleMere::getPdo($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
    }
    //////////// Evenements //////////////

    public function insertEvenement($tab)
    {
        $requete = "insert into Evenement values (null, :type, :dateEvent, :nomEvenement, :description, :adresse, :horraireD, :horraireF, :capacite, :idcategorie)";
        $donnees = array(
            ":type" => $tab['type'],
            ":dateEvent" => $tab['dateEvent'],
            ":nomEvenement" => $tab['nomEvenement'],
            ":description" => $tab['description'],
            ":adresse" => $tab['adresse'],
            ":horraireD" => $tab['horraireD'],
            ":horraireF" => $tab['horraireF'],
            ":capacite" => $tab['capacite'],
            ":idcategorie" => $tab['idcategorie'],
        );
        if ($this->pdo != null) {
            // on prépare la requete 
            $insert = $this->pdo->prepare($requete);
            $insert->execute($donnees);
        }
    }


    public function selectAllEvenements()
    {
        $requete = "SELECT * FROM Evenement;";

        if ($this->pdo != null) {
            // on prépare la requete 
            $select  = $this->pdo->prepare($requete);
            $select->execute();
            //extraction de tous les clients
            $lesEvenements = $select->fetchAll();
            return $lesEvenements;
        } else {
            return null;
        }
    }

    public function selectWhereEvenement($idevenement)
    {
        $requete = "select * from Evenement where idevenement = :idevenement;";
        if ($this->pdo != null) {
            $donnees = array(":idevenement" => $idevenement);
            //on prepare la requete
            $select = $this->pdo->prepare($requete);
            $select->execute($donnees);
            //extraction du service
            return $select->fetch();
        } else {
            return null;
        }
    }

    public function deleteEvenement($idevenement)
    {
        $requete = "delete from Evenement where idevenement = :idevenement;";
        $donnees = array(":idevenement" => $idevenement);
        if ($this->pdo != null) {
            //on prepare la requete
            $delete = $this->pdo->prepare($requete);
            $delete->execute($donnees);
        }
    }

    public function updateEvenement($tab)
    {
        $requete = "update Evenement set type=:type, dateEvent=:dateEvent, nomEvenement=:nomEvenement, description=:description, adresse=:adresse, horraireD=:horraireD, horraireF=:horraireF, capacite=:capacite, idcategorie=:idcategorie  where idevenement=:idevenement;";
        $donnees = array(
            ":type" => $tab['type'],
            ":dateEvent" => $tab['dateEvent'],
            ":nomEvenement" => $tab['nomEvenement'],
            ":description" => $tab['description'],
            ":adresse" => $tab['adresse'],
            ":horraireD" => $tab['horraireD'],
            ":horraireF" => $tab['horraireF'],
            ":capacite" => $tab['capacite'],
            ":idcategorie" => $tab['idcategorie'],
            ":idevenement" => $tab['idevenement']
        );
        if ($this->pdo != null) {
            //on prepare la requete
            $insert = $this->pdo->prepare($requete);
            $insert->execute($donnees);
        }
    }

    public function isReserved($iduser, $idevenement)
    {
        $sql = "SELECT COUNT(*) FROM Inscription WHERE iduser = :iduser AND idevenement = :idevenement";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':iduser' => $iduser, ':idevenement' => $idevenement]);
        return $stmt->fetchColumn() > 0;
    }

    public function getReservations($iduser)
    {
        $sql = "SELECT e.* FROM Inscription i JOIN Evenement e ON i.idevenement = e.idevenement WHERE i.iduser = :iduser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':iduser' => $iduser]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function reserverEvenement($iduser, $idevenement)
    {
        $sql = "INSERT INTO Inscription (iduser, idevenement, dateD, commentaire, statut) 
                VALUES (:iduser, :idevenement, NOW(), '', 'Réservé')";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':iduser' => $iduser,
            ':idevenement' => $idevenement
        ]);
    }

    public function annulerReservation($iduser, $idevenement)
    {
        $sql = "DELETE FROM Inscription WHERE iduser = :iduser AND idevenement = :idevenement";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':iduser' => $iduser,
            ':idevenement' => $idevenement
        ]);
    }
}

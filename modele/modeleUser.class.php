<?php
require_once("modele/modeleMere.class.php");

class ModeleUser
{
    private $pdo;

    public function __construct($serveur, $serveur2, $bdd, $user, $mdp, $mdp2)
    {
        $this->pdo = ModeleMere::getPdo($serveur, $serveur2, $bdd, $user, $mdp, $mdp2);
    }

    // -----------------------------
    // Insertion Client Particulier
    // -----------------------------
    public function insertClientPar(array $tab)
    {
        // Hash du mot de passe
        $tab['mdp'] = password_hash($tab['mdp'], PASSWORD_DEFAULT);

        // Insertion dans user
        $sql = "INSERT INTO user (nom, email, mdp, tel, role)
                VALUES (:nom, :email, :mdp, :tel, 'clientPart')";
        $this->pdo->prepare($sql)->execute([
            ':nom'   => $tab['nom'],
            ':email' => $tab['email'],
            ':mdp'   => $tab['mdp'],
            ':tel'   => $tab['tel'],
        ]);

        // Insertion dans Client_Particulier
        $id = $this->pdo->lastInsertId();
        $this->pdo
            ->prepare("INSERT INTO Client_Particulier (iduser, prenom) VALUES (?, ?)")
            ->execute([$id, $tab['prenom']]);
    }

    // -----------------------------
    // Insertion Client Professionnel
    // -----------------------------
    public function insertClientPro(array $tab)
    {
        // Hash du mot de passe
        $tab['mdp'] = password_hash($tab['mdp'], PASSWORD_DEFAULT);

        // Insertion dans user
        $sql = "INSERT INTO user (nom, email, mdp, tel, role)
                VALUES (:nom, :email, :mdp, :tel, 'clientPro')";
        $this->pdo->prepare($sql)->execute([
            ':nom'   => $tab['nom'],
            ':email' => $tab['email'],
            ':mdp'   => $tab['mdp'],
            ':tel'   => $tab['tel'],
        ]);

        // Insertion dans Client_Pro
        $id = $this->pdo->lastInsertId();
        $this->pdo
            ->prepare("INSERT INTO Client_Pro (iduser, num_Siret, adresse) VALUES (?, ?, ?)")
            ->execute([$id, $tab['num_Siret'], $tab['adresse']]);
    }

    // -----------------------------
    // Vérification de la connexion
    // -----------------------------
    public function selectUser($email, $mdp_saisi)
    {
        $email = strtolower(trim($email));
        $stmt  = $this->pdo->prepare("SELECT * FROM user WHERE LOWER(email) = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($mdp_saisi, $user['mdp'])) {
            return $user;
        }
        return null;
    }

    // -----------------------------
    // Méthode utilitaire checkUser
    // -----------------------------
    public function checkUser($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // -----------------------------
    // Récupération de tous les pros
    // -----------------------------
    public function getAllProUsers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM vueClientPro");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------
    // Suppression pro + annonces
    // -----------------------------
    public function supprimerProEtAnnonces(int $iduser): void
    {
        // 1) Supprimer les commentaires
        $this->pdo
            ->prepare("DELETE FROM Commenter   WHERE iduser = ?")
            ->execute([$iduser]);

        // 2) Supprimer les inscriptions
        $this->pdo
            ->prepare("DELETE FROM Inscription WHERE iduser = ?")
            ->execute([$iduser]);

        // 3) Supprimer les locations
        $this->pdo
            ->prepare("DELETE FROM Louer       WHERE iduser = ?")
            ->execute([$iduser]);

        // 4) Supprimer les pubs
        $this->pdo
            ->prepare("DELETE FROM Pub         WHERE iduser = ?")
            ->execute([$iduser]);

        // 5) Récupérer l’email du pro pour supprimer ses services
        $stmt = $this->pdo->prepare("SELECT email FROM user WHERE iduser = ?");
        $stmt->execute([$iduser]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row && !empty($row['email'])) {
            $this->pdo
                ->prepare("DELETE FROM Service WHERE email = ?")
                ->execute([$row['email']]);
        }

        // 6) Appeler la procédure pour supprimer Client_Pro et user
        $this->pdo
            ->prepare("CALL deleteClientPro(?)")
            ->execute([$iduser]);
    }


    // -----------------------------
    // Autres sélections (vues)
    // -----------------------------
    public function selectAllHotels()
    {
        return $this->pdo->query("SELECT * FROM vueHotels")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function selectAllRestaurants()
    {
        return $this->pdo->query("SELECT * FROM vueRestaurants")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function selectAllSports()
    {
        return $this->pdo->query("SELECT * FROM vueSport")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function selectAllCultures()
    {
        return $this->pdo->query("SELECT * FROM vueCulture")->fetchAll(PDO::FETCH_ASSOC);
    }
    public function selectAllLoisirs()
    {
        return $this->pdo->query("SELECT * FROM vueLoisirs")->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------
    // Recherche user par role
    // -----------------------------
    public function findByRole($role, $iduser)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM user WHERE role = ? AND iduser = ?");
        $stmt->execute([$role, $iduser]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // -----------------------------
    // Sélection clientPart/Pro pour profil
    // -----------------------------
    public function selectClientPart($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM vueClientPart WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function selectClientPro($email)
    {
        $stmt = $this->pdo->prepare("SELECT * FROM vueClientPro WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}

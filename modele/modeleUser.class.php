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
        // hash du mot de passe
        $tab['mdp'] = password_hash($tab['mdp'], PASSWORD_DEFAULT);

        // appel de la proc stockée à 9 params
        $stmt = $this->pdo->prepare("CALL insertClientPro(
        :nom, :email, :mdp, :tel, :role,
        :num_Siret, :adresse, :prenom, :code_postal
    )");
        $stmt->execute([
            ':nom'         => $tab['nom'],
            ':email'       => $tab['email'],
            ':mdp'         => $tab['mdp'],
            ':tel'         => $tab['tel'],
            ':role'        => 'clientPro',
            ':num_Siret'   => $tab['num_Siret'],
            ':adresse'     => $tab['adresse'],
            ':prenom'      => $tab['prenom'],
            ':code_postal' => $tab['code_postal'],
        ]);
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
    // Récupération de tous les particuliers
    // -----------------------------
    public function getAllPartUsers()
    {
        $stmt = $this->pdo->prepare("SELECT * FROM vueClientPart");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // -----------------------------
    // Suppression particulier + inscriptions
    // -----------------------------
    public function supprimerPartEtInscriptions(int $iduser): void
    {
        // 1) Supprimer les commentaires
        $this->pdo
            ->prepare("DELETE FROM Commenter WHERE iduser = ?")
            ->execute([$iduser]);

        // 2) Supprimer les inscriptions
        $this->pdo
            ->prepare("DELETE FROM Inscription WHERE iduser = ?")
            ->execute([$iduser]);

        // 3) Appeler la procédure pour supprimer Client_Particulier et user
        $this->pdo
            ->prepare("CALL deleteClientPar(?)")
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
        if ($role === 'clientPro') {
            $sql = "SELECT u.*, cp.num_Siret, cp.adresse, cp.prenom 
                    FROM user u 
                    LEFT JOIN Client_Pro cp ON u.iduser = cp.iduser 
                    WHERE u.iduser = ? AND u.role = 'clientPro'";
        } elseif ($role === 'clientPart') {
            $sql = "SELECT u.*, cp.prenom 
                    FROM user u 
                    LEFT JOIN Client_Particulier cp ON u.iduser = cp.iduser 
                    WHERE u.iduser = ? AND u.role = 'clientPart'";
        } else {
            $sql = "SELECT * FROM user WHERE role = ? AND iduser = ?";
        }

        $stmt = $this->pdo->prepare($sql);

        if ($role === 'clientPro' || $role === 'clientPart') {
            $stmt->execute([$iduser]);
        } else {
            $stmt->execute([$role, $iduser]);
        }

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

    // -----------------------------
    // Récupération de tous les utilisateurs
    // -----------------------------
    public function getAllUsers()
    {
        $sql = "SELECT DISTINCT user.iduser, user.nom, user.email, user.tel, user.role, 
                   Client_Particulier.prenom, Client_Pro.num_Siret, Client_Pro.adresse
            FROM user 
            LEFT JOIN Client_Particulier ON user.iduser = Client_Particulier.iduser
            LEFT JOIN Client_Pro ON user.iduser = Client_Pro.iduser
            WHERE user.role != 'admin'"; // Exclure les administrateurs
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Supprime les doublons basés sur l'iduser
        $uniqueUsers = [];
        foreach ($users as $user) {
            $uniqueUsers[$user['iduser']] = $user;
        }

        return array_values($uniqueUsers);
    }

    // -----------------------------
    // Mise à jour des informations d'un utilisateur
    // -----------------------------
    public function updateUser(array $data)
    {
        $iduser = $data['iduser'] ?? null;
        if (!$iduser) {
            throw new \Exception("L'ID utilisateur est obligatoire pour la mise à jour.");
        }

        // Mise à jour des champs de la table `user`
        $sql = "UPDATE user SET 
                    nom = :nom, 
                    email = :email, 
                    tel = :tel 
                WHERE iduser = :iduser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':nom' => $data['nom'],
            ':email' => $data['email'],
            ':tel' => $data['tel'],
            ':iduser' => $iduser
        ]);

        // Mise à jour des champs spécifiques selon le rôle
        $role = $data['role'] ?? null;
        if ($role === 'clientPart') {
            $sql = "UPDATE Client_Particulier SET 
                        prenom = :prenom 
                    WHERE iduser = :iduser";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':prenom' => $data['prenom'] ?? null,
                ':iduser' => $iduser
            ]);
        } elseif ($role === 'clientPro') {
            $sql = "UPDATE Client_Pro SET 
                        prenom = :prenom,
                        num_Siret = :num_Siret, 
                        adresse = :adresse 
                    WHERE iduser = :iduser";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                ':prenom' => $data['prenom'] ?? null,
                ':num_Siret' => $data['num_Siret'] ?? null,
                ':adresse' => $data['adresse'] ?? null,
                ':iduser' => $iduser
            ]);
        }
    }

    // Compte le nombre de réservations validées pour un utilisateur
    public function countValidatedReservations($iduser)
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM Inscription 
                WHERE iduser = :iduser AND statut = 'Réservé'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':iduser' => $iduser]);
        return $stmt->fetchColumn();
    }

    public function countEventReservations($iduser)
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM Inscription 
                WHERE iduser = :iduser AND statut = 'Réservé'";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':iduser' => $iduser]);
        return $stmt->fetchColumn();
    }

    public function countServiceReservations($iduser)
    {
        $sql = "SELECT COUNT(*) AS total 
                FROM Louer 
                WHERE iduser = :iduser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':iduser' => $iduser]);
        return $stmt->fetchColumn();
    }

    public function calculateServiceReservationAmount($iduser)
    {
        $sql = "SELECT SUM(s.prix) AS total 
                FROM Louer l
                JOIN Service s ON l.idservice = s.idservice
                WHERE l.iduser = :iduser";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([':iduser' => $iduser]);
        return $stmt->fetchColumn() ?: 0;
    }
}

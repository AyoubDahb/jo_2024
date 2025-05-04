<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?page=0');
    exit;
}

if (isset($_GET['action']) && $_GET['action'] === 'download_pdf') {
    $users = $c_User->getAllUsers(); // Récupère tous les utilisateurs
    $c_User->generatePDF($users);
    exit; // Arrête l'exécution après la génération du PDF
}

// Gestion de la modification des informations utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['iduser']) && isset($_POST['role'])) {
    $userToEdit = $c_User->findByRole($_POST['role'], $_POST['iduser']);
    if (!$userToEdit) {
        echo "<p style='color:red; text-align:center;'>Utilisateur introuvable.</p>";
    } else {
        // Affichage du formulaire de modification
        echo '<form method="post" action="index.php?page=10">';
        echo '<input type="hidden" name="iduser" value="' . htmlspecialchars($userToEdit['iduser']) . '">';
        echo '<input type="hidden" name="role" value="' . htmlspecialchars($userToEdit['role']) . '">';
        echo '<label>Nom: <input type="text" name="nom" value="' . htmlspecialchars($userToEdit['nom']) . '" required></label>';
        echo '<label>Prénom: <input type="text" name="prenom" value="' . htmlspecialchars($userToEdit['prenom'] ?? '') . '"></label>';
        echo '<label>Email: <input type="email" name="email" value="' . htmlspecialchars($userToEdit['email']) . '" required></label>';
        echo '<label>Téléphone: <input type="text" name="tel" value="' . htmlspecialchars($userToEdit['tel']) . '" required></label>';
        if ($userToEdit['role'] === 'clientPro') {
            echo '<label>Numéro SIRET: <input type="text" name="num_Siret" value="' . htmlspecialchars($userToEdit['num_Siret'] ?? '') . '"></label>';
            echo '<label>Adresse: <input type="text" name="adresse" value="' . htmlspecialchars($userToEdit['adresse'] ?? '') . '"></label>';
        }
        echo '<button type="submit" name="updateUser">Enregistrer</button>';
        echo '</form>';
    }
}

// Mise à jour des informations utilisateur
if (isset($_POST['updateUser'])) {
    foreach (['nom', 'email', 'tel'] as $field) {
        if (empty(trim($_POST[$field]))) {
            echo "<p style='color:red; text-align:center;'>Le champ « $field » est obligatoire.</p>";
            return;
        }
    }

    // Convertir les champs vides en null
    foreach (['prenom', 'num_Siret', 'adresse'] as $optionalField) {
        if (isset($_POST[$optionalField]) && trim($_POST[$optionalField]) === '') {
            $_POST[$optionalField] = null;
        }
    }

    $c_User->updateUser($_POST);

    // Rechargez les données après la mise à jour
    $users = $c_User->getAllUsers();
    header('Location: index.php?page=10');
    exit;
}

$users = $c_User->getAllUsers(); // Récupère tous les utilisateurs
?>

<main>
    <h2>Gestion des utilisateurs</h2>

    <table class="table-affiche">
        <thead>
            <tr>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Email</th>
                <th>Téléphone</th>
                <th>Rôle</th>
                <th>Numéro SIRET</th>
                <th>Adresse</th>
                <th>Réservations</th>
                <th>Montant Total</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($users)): ?>
                <tr>
                    <td colspan="10" style="text-align: center;">Aucun utilisateur trouvé.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($users as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['nom']) ?></td>
                        <td><?= htmlspecialchars($user['prenom'] ?? '') ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['tel']) ?></td>
                        <td><?= htmlspecialchars($user['role']) ?></td>
                        <td><?= htmlspecialchars($user['num_Siret'] ?? '') ?></td>
                        <td><?= htmlspecialchars($user['adresse'] ?? '') ?></td>
                        <td><?= $c_User->countValidatedReservations($user['iduser']) ?></td>
                        <td><?= number_format($c_User->calculateTotalReservationAmount($user['iduser']), 2) ?> €</td>
                        <td>
                            <form method="post" action="index.php?page=10" style="display: inline;">
                                <input type="hidden" name="iduser" value="<?= $user['iduser'] ?>">
                                <input type="hidden" name="role" value="<?= $user['role'] ?>">
                                <button type="submit">Modifier</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Bouton pour télécharger en PDF -->
    <form method="post" action="index.php?page=10&action=download_pdf" style="text-align: center; margin-top: 20px;">
        <button type="submit">Télécharger en PDF</button>
    </form>
</main>
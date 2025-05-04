<?php
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php?page=0');
    exit;
}

$clients = $c_User->getAllUsers(); // Récupère tous les utilisateurs
?>

<main>
    <h2>Liste des clients</h2>

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
            </tr>
        </thead>
        <tbody>
            <?php if (empty($clients)): ?>
                <tr>
                    <td colspan="7" style="text-align: center;">Aucun client trouvé.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($clients as $client): ?>
                    <tr>
                        <td><?= htmlspecialchars($client['nom']) ?></td>
                        <td><?= htmlspecialchars($client['prenom'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['email']) ?></td>
                        <td><?= htmlspecialchars($client['tel']) ?></td>
                        <td><?= htmlspecialchars($client['role']) ?></td>
                        <td><?= htmlspecialchars($client['num_Siret'] ?? '') ?></td>
                        <td><?= htmlspecialchars($client['adresse'] ?? '') ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</main>
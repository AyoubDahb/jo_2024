<h2>Liste des utilisateurs professionnels</h2>

<table class="table-affiche">
    <tr>
        <td>Nom</td>
        <td>Email</td>
        <td>Mot de passe</td>
        <td>Tél</td>
        <td>Numéro Siret</td>
        <td>Adresse</td>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <td>Action</td>
        <?php endif; ?>
    </tr>

    <?php foreach ($listePros as $unClientPro): ?>
        <tr>
            <td><?= htmlspecialchars($unClientPro["nom"]) ?></td>
            <td><?= htmlspecialchars($unClientPro["email"]) ?></td>
            <td>••••••••</td> <!-- On masque le hash du mot de passe -->
            <td><?= htmlspecialchars($unClientPro["tel"]) ?></td>
            <td><?= htmlspecialchars($unClientPro["num_Siret"] ?? '') ?></td>
            <td><?= htmlspecialchars($unClientPro["adresse"] ?? '') ?></td>

            <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                <td>
                    <a href="index.php?action=supprimerPro&iduser=<?= $unClientPro["iduser"] ?>"
                        onclick="return confirm('Supprimer ce professionnel et toutes ses annonces ?');">
                        Supprimer
                    </a>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>
</main>
<h2>Liste des utilisateurs particuliers</h2>

<table class="table-affiche">
    <tr>
        <td>Nom</td>
        <td>Prénom</td>
        <td>Email</td>
        <td>Mot de passe</td>
        <td>Tél</td>
        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
            <td>Action</td>
        <?php endif; ?>
    </tr>

    <?php foreach ($listeParticuliers as $unClientPart): ?>
        <tr>
            <td><?= htmlspecialchars($unClientPart["nom"]) ?></td>
            <td><?= htmlspecialchars($unClientPart["prenom"]) ?></td>
            <td><?= htmlspecialchars($unClientPart["email"]) ?></td>
            <td>••••••••</td> <!-- On masque le hash du mot de passe -->
            <td><?= htmlspecialchars($unClientPart["tel"]) ?></td>

            <?php if (isset($_SESSION["role"]) && $_SESSION["role"] === "admin"): ?>
                <td>
                    <a href="index.php?action=supprimerPart&iduser=<?= $unClientPart["iduser"] ?>"
                        onclick="return confirm('Supprimer ce particulier et toutes ses données associées ?');">
                        Supprimer
                    </a>
                </td>
            <?php endif; ?>
        </tr>
    <?php endforeach; ?>
</table>
</main>
<?php
// vue/vue_insert_service.php

// La session est déjà démarrée par index.php,
// on vérifie simplement que l'utilisateur est connecté.
if (!isset($_SESSION['email'])) {
    header('Location: index.php?page=4');
    exit;
}

// Stocke l'email du pro connecté
$email_user = $_SESSION['email'];
?>

<main>
    <h2>Insertion d'un service</h2>

    <form method="post" action="">
        <table class="table-insert">
            <tr>
                <td>Libellé</td>
                <td>
                    <input type="text" name="libelle"
                        value="<?= ($leService != null) ? htmlspecialchars($leService['libelle']) : '' ?>">
                </td>
            </tr>
            <tr>
                <td>Adresse</td>
                <td>
                    <input type="text" name="adresse"
                        value="<?= ($leService != null) ? htmlspecialchars($leService['adresse']) : '' ?>">
                </td>
            </tr>
            <tr>
                <td>Prix (moyen)</td>
                <td>
                    <input type="number" name="prix"
                        value="<?= ($leService != null) ? htmlspecialchars($leService['prix']) : '' ?>">
                </td>
            </tr>
            <tr>
                <td>Téléphone</td>
                <td>
                    <input type="text" name="tel"
                        value="<?= ($leService != null) ? htmlspecialchars($leService['tel']) : '' ?>">
                </td>
            </tr>
            <tr>
                <td>Id type-service</td>
                <td>
                    <select name="idtypeservices">
                        <?php foreach ($lesTypeservices as $unTypeservice): ?>
                            <option value="<?= $unTypeservice['idtypeservices'] ?>">
                                <?= htmlspecialchars($unTypeservice['libelle']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </td>
            </tr>
            <tr>
                <td>
                    <input class="boutonP" type="reset" name="Annuler" value="Annuler">
                </td>
                <td>
                    <input class="boutonP" type="submit"
                        <?= ($leService != null)
                            ? 'name="Modifier" value="Modifier"'
                            : 'name="Valider" value="Valider"' ?>>
                    <?= ($leService != null)
                        ? "<input type='hidden' name='idservice' value='" . intval($leService['idservice']) . "'>"
                        : "" ?>
                </td>
            </tr>
        </table>

        <!-- Champ caché pour lier le service à l’email du pro connecté -->
        <input type="hidden" name="email_user" value="<?= htmlspecialchars($email_user) ?>">
    </form>
</main>
<?php
// pages/mdp_oublie.php

// On suppose que session_start() et l'instanciation de $c_User
// sont déjà faits dans index.php avant l'inclusion.

$error   = "";
$success = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = strtolower(trim($_POST['email'] ?? ""));

    if ($c_User->checkUser($email)) {
        // Ici tu pourrais envoyer un mail ou générer un token...
        $success = "Le mot de passe est bien réinitialisé.";
    } else {
        $error = "Le mail est introuvable.";
    }
}
?>

<main>
    <h2>Mot de passe oublié</h2>

    <?php if ($error): ?>
        <p style="color:red; text-align:center;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <?php if ($success): ?>
        <p style="color:green; text-align:center;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <form method="post" action="index.php?page=8">
        <table class="table-insert" style="margin:auto;">
            <tr>
                <td>Email :</td>
                <td>
                    <input
                        type="email"
                        name="email"
                        required
                        value="<?= htmlspecialchars($_POST['email'] ?? "") ?>">
                </td>
            </tr>
            <tr>
                <td colspan="2" style="text-align:center;">
                    <button type="submit">Réinitialiser</button>
                </td>
            </tr>
        </table>
    </form>
</main>
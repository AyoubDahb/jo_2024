<?php
// pages/monprofil.php

// 1) Sécurité : on s’assure que la session est démarrée et que l’utilisateur est connecté
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['email'])) {
    header('Location: index.php?page=4');
    exit;
}

// 2) Récupération du rôle et de l’email depuis la session
$role  = $_SESSION['role'];
$email = $_SESSION['email'];

// 3) Récupération des données utilisateur selon le rôle
//    $c_User est instancié dans index.php avant l'inclusion de cette vue.
if ($role === 'clientPart') {
    $user = $c_User->selectClientPart($email);
} elseif ($role === 'clientPro') {
    $user = $c_User->selectClientPro($email);
} else {
    // admin ou autre : on récupère juste les champs basiques
    $user = $c_User->checkUser($email);
}
?>
<main>
    <h2>Mon Profil</h2>

    <table class="table-affiche">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <?php if ($role === 'clientPro'): ?>
                <th>Numéro SIRET</th>
                <th>Adresse</th>
                <th>Code postal</th>
            <?php endif; ?>
        </tr>
        <tr>
            <td><?= htmlspecialchars($user['nom'] ?? '') ?></td>
            <td><?= htmlspecialchars($user['prenom'] ?? '') ?></td>
            <td><?= htmlspecialchars($user['email'] ?? '') ?></td>
            <td><?= htmlspecialchars($user['tel'] ?? '') ?></td>
            <?php if ($role === 'clientPro'): ?>
                <td><?= htmlspecialchars($user['num_Siret'] ?? '') ?></td>
                <td><?= htmlspecialchars($user['adresse'] ?? '') ?></td>
                <td><?= htmlspecialchars($user['code_postal'] ?? '') ?></td>
            <?php endif; ?>
        </tr>
    </table>
</main>
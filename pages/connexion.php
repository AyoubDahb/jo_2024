<?php
// pages/connexion.php
// (session_start() et $c_User sont déjà en-tête d’index.php)

$error = "";
$email = "";

// Si on vient du formulaire POST…
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connexion'])) {
    // Normalise l’email en minuscules
    $email = strtolower(trim($_POST['email']));
    $mdp   = trim($_POST['mdp']);

    // Vérifie en base
    $unUser = $c_User->selectUser($email, $mdp);

    if ($unUser) {
        // Succès
        $_SESSION['email']  = $unUser['email'];
        $_SESSION['role']   = $unUser['role'];
        $_SESSION['iduser'] = $unUser['iduser'];
        header("Location: index.php?page=0");
        exit;
    } else {
        $error = "❌ Email ou mot de passe incorrect.";
    }
}
?>

<h2>Se connecter</h2>

<?php if ($error): ?>
    <p class="error" style="color:red;text-align:center;">
        <?= htmlspecialchars($error) ?>
    </p>
<?php endif; ?>

<form method="post" action="index.php?page=4">
    <table class="table-insert" style="margin:auto;">
        <tr>
            <td>Email</td>
            <td>
                <input type="email"
                    name="email"
                    required
                    value="<?= htmlspecialchars($email) ?>">
            </td>
        </tr>
        <tr>
            <td>Mot de passe</td>
            <td>
                <input type="password" name="mdp" required>
            </td>
        </tr>
        <tr>
            <td colspan="2" style="text-align:center;">
                <button type="submit" name="connexion">
                    Se connecter
                </button>
            </td>
        </tr>
    </table>
</form>
<p style="text-align:center; margin-top:1em;">
    <a href="index.php?page=8">Mot de passe oublié ?</a>
</p>
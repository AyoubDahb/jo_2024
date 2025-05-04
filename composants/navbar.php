<nav class="navigation_navbar">
    <div class="navbar-container">
        <ul class="navbar-menu">
            <li class="navbar-item">
                <a href="index.php?page=0" class="navbar-link">
                    <img class="image-logo" src="images/logo.png" alt="logo jeux olympiques" />
                </a>
            </li>
            <li class="navbar-item">
                <a href="index.php?page=1" class="navbar-link">Évènements</a>
            </li>
            <li class="navbar-item">
                <a href="index.php?page=2" class="navbar-link">Autres services</a>
            </li>
            <li class="navbar-item">
                <?php if (isset($_SESSION['email'])): ?>
                    <a href="index.php?page=6" class="navbar-link">Mon profil</a>
                <?php else: ?>
                    <a href="index.php?page=3" class="navbar-link">S'inscrire</a>
                <?php endif; ?>
            </li>
            <li class="navbar-item">
                <?php if (isset($_SESSION['email'])): ?>
                    <a href="index.php?page=5" class="navbar-link">Se Déconnecter</a>
                <?php else: ?>
                    <a href="index.php?page=4" class="navbar-link">Se connecter</a>
                <?php endif; ?>
            </li>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li class="navbar-item"><a href="index.php?page=7" class="navbar-link">Gérer les professionnels</a></li>
                <li class="navbar-item"><a href="index.php?page=11" class="navbar-link">Gérer les particuliers</a></li>
                <li class="navbar-item"><a href="index.php?page=10" class="navbar-link">Gérer les utilisateurs</a></li>
                <li class="navbar-item"><a href="index.php?page=12" class="navbar-link">Rechercher client</a></li>
            <?php endif; ?>

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'clientPart'): ?>
                <li class="navbar-item"><a href="index.php?page=9" class="navbar-link">Mes Réservations</a></li>
            <?php endif; ?>

            <?php if (!empty($_SESSION['email'])): ?>
                <li class="navbar-item">
                    <p class="identification-nav"><?= $_SESSION['email'] ?> / <?= $_SESSION['role'] ?></p>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
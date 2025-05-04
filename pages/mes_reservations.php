<?php
if (!isset($_SESSION['iduser']) || $_SESSION['role'] !== 'clientPart') {
    header('Location: index.php?page=4');
    exit;
}

// Gestion des actions d'annulation
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'annuler_evenement' && isset($_GET['idevenement'])) {
        $c_Event->annulerReservation($_SESSION['iduser'], $_GET['idevenement']);
        header('Location: index.php?page=9');
        exit;
    } elseif ($_GET['action'] === 'annuler_service' && isset($_GET['idservice'])) {
        $c_Service->annulerReservation($_SESSION['iduser'], $_GET['idservice']);
        header('Location: index.php?page=9');
        exit;
    }
}

// Récupération des réservations
$mesReservationsEvenements = $c_Event->getReservations($_SESSION['iduser']);
$mesReservationsServices = $c_Service->getReservations($_SESSION['iduser']);

// Filtrage des résultats en fonction de la recherche
$search = isset($_GET['search']) ? strtolower(trim($_GET['search'])) : '';
if ($search && strlen($search) >= 2) {
    $mesReservationsEvenements = array_filter($mesReservationsEvenements, function ($reservation) use ($search) {
        return strpos(strtolower($reservation['adresse']), $search) === 0;
    });
    $mesReservationsServices = array_filter($mesReservationsServices, function ($reservation) use ($search) {
        return strpos(strtolower($reservation['libelle']), $search) === 0;
    });
}
?>

<main class="mes-reservations">
    <h2>Mes Réservations</h2>

    <!-- Formulaire de recherche -->
    <form method="get" action="index.php" style="text-align: center; margin-bottom: 20px;">
        <input type="hidden" name="page" value="9">
        <input type="text" name="search" placeholder="Rechercher (min. 2 lettres)" value="<?= htmlspecialchars($search) ?>">
        <button type="submit">Rechercher</button>
    </form>

    <section>
        <h3 style="text-align: center;">Évènements</h3><br>
        <table class="table-affiche">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Date</th>
                    <th>Adresse</th>
                    <th>Description</th>
                    <th>Capacité</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($mesReservationsEvenements)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Aucun évènement trouvé.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($mesReservationsEvenements as $reservation): ?>
                        <tr>
                            <td><?= htmlspecialchars($reservation['nomEvenement']) ?></td>
                            <td><?= htmlspecialchars($reservation['dateEvent']) ?></td>
                            <td><?= htmlspecialchars($reservation['adresse']) ?></td>
                            <td><?= htmlspecialchars($reservation['description']) ?></td>
                            <td><?= htmlspecialchars($reservation['capacite']) ?></td>
                            <td>
                                <a href="index.php?page=9&action=annuler_evenement&idevenement=<?= $reservation['idevenement'] ?>">
                                    <button class="btn-annuler">Annuler</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section><br>

    <section>
        <h3 style="text-align: center;">Services</h3><br>
        <table class="table-affiche">
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Prix</th>
                    <th>Téléphone</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($mesReservationsServices)): ?>
                    <tr>
                        <td colspan="6" style="text-align: center;">Aucun service trouvé.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($mesReservationsServices as $reservation): ?>
                        <tr>
                            <td><?= htmlspecialchars($reservation['libelle']) ?></td>
                            <td><?= htmlspecialchars($reservation['adresse']) ?></td>
                            <td><?= htmlspecialchars($reservation['prix']) ?> €</td>
                            <td><?= htmlspecialchars($reservation['tel']) ?></td>
                            <td><?= htmlspecialchars($reservation['email']) ?></td>
                            <td>
                                <a href="index.php?page=9&action=annuler_service&idservice=<?= $reservation['idservice'] ?>">
                                    <button class="btn-annuler">Annuler</button>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>
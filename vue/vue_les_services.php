<h2> Liste des services </h2>

<table class="table-affiche">
    <tr>
        <td> Id Service </td>
        <td> Libellé </td>
        <td> Adresse </td>
        <td> Prix (moyen) </td>
        <td> Téléphone </td>
        <td> Email </td>
        <td> Type de service </td>
        <td> Opérations </td>
    </tr>

    <?php
    foreach ($lesServices as $unService) {
        echo
        "<tr>
            <td>" . $unService["idservice"] . "</td>
            <td>" . $unService["libelle"] . "</td>
            <td>" . $unService["adresse"] . "</td>
            <td>" . $unService["prix"] . " €</td>
            <td>" . $unService["tel"] . "</td>
            <td>" . $unService["email"] . "</td>
            <td>" . $unService["idtypeservices"] . "</td>";

        echo "<td>";
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'clientPart') {
            $isReserved = $c_Service->isReserved($_SESSION['iduser'], $unService['idservice']);
            if ($isReserved) {
                echo "<form method='post' action='index.php?page=2&action=annuler&idservice=" . $unService['idservice'] . "'>";
                echo "<button class='btn-annuler' type='submit'>Annuler</button>";
                echo "</form>";
            } else {
                echo "<form method='post' action='index.php?page=2&action=reserver&idservice=" . $unService['idservice'] . "'>";
                echo "<button class='btn-reserver' type='submit'>Réserver</button>";
                echo "</form>";
            }
        } elseif (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'clientPro')) {
            echo "<a class='img-dif' href='index.php?page=2&action=sup&idservice=" . $unService['idservice'] . "'>";
            echo "<img src='images/Delete.png' height='30' width='30'>";
            echo "</a>";
            echo "<a class='img-dif' href='index.php?page=2&action=edit&idservice=" . $unService['idservice'] . "'>";
            echo "<img src='images/Edit.png' height='30' width='30'>";
            echo "</a>";
        }
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
</main>
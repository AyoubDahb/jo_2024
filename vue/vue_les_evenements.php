<h2> Liste des évènements </h2>

<table class="table-affiche">
    <tr>
        <td> Id Evenement </td>
        <td> Type </td>
        <td> Date Evenement </td>
        <td> Nom Evenement </td>
        <td> Description </td>
        <td> Adresse </td>
        <td> Horraire Début </td>
        <td> Horraire Fin </td>
        <td> Capacite </td>
        <td> Idcategorie </td>
        <td> Opérations </td>
    </tr>

    <?php
    foreach ($lesEvenements as $unEvenement) {
        echo
        "<tr>
                <td>" . $unEvenement["idevenement"] . "</td>
                <td>" . $unEvenement["type"] . "</td>
                <td>" . $unEvenement["dateEvent"] . "</td>
                <td>" . $unEvenement["nomEvenement"] . "</td>
                <td>" . $unEvenement["description"] . "</td>
                <td>" . $unEvenement["adresse"] . "</td>
                <td>" . $unEvenement["horraireD"] . "</td>
                <td>" . $unEvenement["horraireF"] . "</td>
                <td>" . $unEvenement["capacite"] . "</td>
                <td>" . $unEvenement["idcategorie"] . "</td>";

        echo "<td>";
        if (isset($_SESSION['role']) && $_SESSION['role'] === 'clientPart') {
            $isReserved = $c_Event->isReserved($_SESSION['iduser'], $unEvenement['idevenement']);
            if ($isReserved) {
                echo "<a class='img-dif' href='index.php?page=1&action=annuler&idevenement=" . $unEvenement['idevenement'] . "'>";
                echo "<button>Annuler</button>";
                echo "</a>";
            } else {
                echo "<a class='img-dif' href='index.php?page=1&action=reserver&idevenement=" . $unEvenement['idevenement'] . "'>";
                echo "<button>Réserver</button>";
                echo "</a>";
            }
        } elseif (isset($_SESSION['role']) && ($_SESSION['role'] === 'admin' || $_SESSION['role'] === 'clientPro')) {
            echo "<a class='img-dif' href='index.php?page=1&action=sup&idevenement=" . $unEvenement['idevenement'] . "'>";
            echo "<img src='images/Delete.png' height='30' width='30'>";
            echo "</a>";
            echo "<a class='img-dif' href='index.php?page=1&action=edit&idevenement=" . $unEvenement['idevenement'] . "'>";
            echo "<img src='images/Edit.png' height='30' width='30'>";
            echo "</a>";
        }
        echo "</td>";
        echo "</tr>";
    }
    ?>
</table>
</main>
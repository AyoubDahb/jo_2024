<main>
    <form method="post" action="index.php?page=3&type=Professionnel" autocomplete="off">
        <h2>Inscription Professionnel</h2>
        <table class="table-insert">
            <tr>
                <td>Nom :</td>
                <td>
                    <input type="text" name="nom" required
                        pattern="[A-Z][a-zA-Z]*"
                        title="Majuscule + lettres, sans espace">
                </td>
            </tr>
            <tr>
                <td>Prénom :</td>
                <td>
                    <input type="text" name="prenom" required
                        pattern="[A-Z][a-zA-Z]*"
                        title="Majuscule + lettres, sans espace">
                </td>
            </tr>
            <tr>
                <td>Email :</td>
                <td><input type="email" name="email" required></td>
            </tr>
            <tr>
                <td>Mot de passe :</td>
                <td><input type="password" name="mdp" required></td>
            </tr>
            <tr>
                <td>Tél :</td>
                <td>
                    <input type="tel" name="tel" required
                        pattern="[0-9+\s\-]{5,15}"
                        title="Chiffres, +, –, espaces (5–15 char.)">
                </td>
            </tr>
            <tr>
                <td>Numéro SIRET :</td>
                <td>
                    <input type="text" name="num_Siret" required
                        pattern="\d{14}" maxlength="14"
                        title="Exactement 14 chiffres">
                </td>
            </tr>
            <tr>
                <td>Code postal :</td>
                <td>
                    <input type="text" name="code_postal" required
                        pattern="\d{5}" maxlength="5"
                        title="Exactement 5 chiffres">
                </td>
            </tr>
            <tr>
                <td>Adresse :</td>
                <td>
                    <input type="text" name="adresse" required
                        pattern="^\d+ .+"
                        title="Commence par un ou plusieurs chiffres + espace">
                </td>
            </tr>
            <tr>
                <td>
                    <input class="boutonP" type="reset" value="Annuler">
                </td>
                <td>
                    <input class="boutonP" type="submit" name="InscriptionPro" value="Inscription">
                </td>
            </tr>
        </table>
    </form>
</main>
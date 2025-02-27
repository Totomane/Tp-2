<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Supprimer un Commentaire de haineux</title>
    <link rel="stylesheet" type="text/css" href="bitnami.css">
</head>
<body>

<header>
    <h1>Suppression d'un Commentaire</h1>
</header>

<div class="container">
    <form method="post" action="Moderation.php">
        <label for="login">Identifiant :</label><br>
        <input type="text" name="login" required><br><br>

        <label for="mdp">Mot de passe :</label><br>
        <input type="password" name="mdp" required><br><br>

        <label for="id_com">ID du Commentaire :</label><br>
        <input type="number" name="id_com" required><br><br>

        <input type="submit" value="Supprimer">
    </form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!empty($_POST['login']) && !empty($_POST['mdp']) && !empty($_POST['id_com'])) {

        function getDatabaseConnection() {
            try {
                return new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
            } catch (Exception $e) {
                die('Erreur : ' . $e->getMessage());
            }
        }

        $bdd = getDatabaseConnection();

        $login = $_POST['login'];
        $mdp = $_POST['mdp'];
        $id_com = $_POST['id_com'];
        $req = "SELECT id_aut, role FROM utilisateurs WHERE login = :login AND mdp = :mdp";
        $stmt = $bdd->prepare($req);
        $stmt->execute(['login' => $login, 'mdp' => $mdp]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $id_aut = $user['id_aut'];
            $role = $user['role'];
            $req_comment = "SELECT pseudo FROM commentaires WHERE id_com = :id_com";
            $stmt = $bdd->prepare($req_comment);
            $stmt->execute(['id_com' => $id_com]);
            $comment = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($comment) {
                if ($role === 'modérateur' ) {
                    $req_delete = "DELETE FROM commentaires WHERE id_com = :id_com";
                    $stmt = $bdd->prepare($req_delete);
                    $stmt->execute(['id_com' => $id_com]);

                    echo "<p style='color: green;'>Commentaire supprimé avec succès !</p>";
                } else {
                    echo "<p style='color: red;'>Vous n'avez pas l'autorisation de supprimer ce commentaire.</p>";
                }
            } else {
                echo "<p style='color: red;'>Commentaire introuvable.</p>";
            }
        } else {
            echo "<p style='color: red;'>Identifiants incorrects.</p>";
        }


        }
    }
?>
</body>
</html>
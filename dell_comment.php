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
<?php
function getDatabaseConnection()
{
    try {
        return new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

function getUser($bdd, $login, $mdp)
{
    $req = "SELECT id_aut, role, mdp FROM utilisateurs WHERE login = :login";
    $stmt = $bdd->prepare($req);
    $stmt->execute(['login' => $login]);
    $user = $stmt->fetch();
    if ($user && $mdp == $user['mdp']) {
        return $user;
    }
    return false;
}


function commentExists($bdd, $id_com)
{
    $checkComment = $bdd->prepare("SELECT 1 FROM commentaires WHERE id_com = :id_com");
    $checkComment->execute(['id_com' => $id_com]);
    return $checkComment->fetch() !== false;
}

$bdd = getDatabaseConnection();
$msg = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = htmlspecialchars($_POST['login']);
    $mdp = htmlspecialchars($_POST['mdp']);
    $id_com = $_POST['id_com'];

    if ($login && $mdp && $id_com) {
        $user = getUser($bdd, $login, $mdp);

        if ($user) {
            if ($user['role'] === 'modérateur' || $user['role'] === 'auteur') {
                if (commentExists($bdd, $id_com)) {
                    try {
                        $stmt = $bdd->prepare("DELETE FROM commentaires WHERE id_com = :id_com");
                        $stmt->execute(['id_com' => $id_com]);
                        $msg = "Commentaire supprimé avec succès !";
                    } catch (Exception $e) {
                        $msg = "Erreur pdt la suppression";
                    }
                } else {
                    $msg = "Le commentaire n'existe pas";
                }
            } else {
                $msg = "Vous n'avez pas les droits pour supprimer ce commentaire.";
            }
        } else {
            $msg = "Identifiants incorrects.";
        }
    } else {
        $msg = "Veuillez remplir tous les champs.";
    }
}


if (!empty($_GET['id_com'])): ?>
    <div class="container">
        <form action="dell_comment.php" method="post">
            <input type="hidden" name="id_com" value="<?php echo (int) $_GET['id_com']; ?>">
            <label for="login">Identifiant :</label><br>
            <input type="text" name="login" required><br><br>
            <label for="mdp">Mot de passe :</label><br>
            <input type="password" name="mdp" required><br><br>
            <input type="submit" value="Supprimer">
        </form>
    </div>
<?php endif;

if (!empty($msg)) {
    echo htmlspecialchars($msg);
}
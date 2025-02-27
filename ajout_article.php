<!DOCTYPE html>
<html>
<script>
    function validateForm() {
        var title = document.getElementById('title').value;
        var content = document.getElementById('content').value;
        if (title.length >= 255 || title == '') {
            alert('Le titre est trop long >255');
            return false;
        }
        if (content == '') {
            alert('Tu dois écrire qq chose');
            return false;
        }
        return true;
</script>
<head>
  <meta charset="utf-8" />
    <link rel="stylesheet" href="bitnami.css" />
  <title>Ajouter un Article</title>
</head>
<body>
<header>
    <h1>Ajouter un Nouvel Article</h1>
</header>
<div class="container">

    <form method="post" action="ajout_article.php">
        <label for="login">Identifiant :</label><br>
        <input type="text" name="login" required><br><br>

      Mot de passe : <br>
      <input type="password" name="mdp" required><br><br>
      Titre de larticle : <br>
      <input type="text" name="titre" required><br><br>
      Contenu de larticle : <br>
      <textarea name="corps" rows="5" cols="40" required></textarea><br><br>
  
  <input type="submit" value="Ajouter l'article"><br>
</form>

<?php
if (!empty($_POST['login']) && !empty($_POST['mdp']) && !empty($_POST['titre']) && !empty($_POST['corps'])) {

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }

    $login = $_POST['login'];
    $mdp = $_POST['mdp'];
    $titre = $_POST['titre'];
    $corps = $_POST['corps'];

    $req = "SELECT id_aut, role FROM Utilisateurs WHERE login = ? AND mdp = ?";
    $stmt = $bdd->prepare($req);
    $stmt->execute([$login, $mdp]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        if ($user['role'] == 'auteur') {
            $req = "INSERT INTO Articles (id_aut, titre, corps, date_crea, date_modif) VALUES (?, ?, ?, NOW(), NOW())";
            $stmt = $bdd->prepare($req);
            $stmt->execute([$user['id_aut'], $titre, $corps]);

            echo "<p>Tu ajouté un article</p>";
        } else {
            echo "<p>Erreur : Vous n'avez pas les droits</p>";
        }
    } else {
        echo "<p>Identifiants incorrects.</p>";
    }
}
?>

</body>
</html>

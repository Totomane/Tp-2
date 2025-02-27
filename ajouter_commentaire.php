<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    function getDatabaseConnection() {
        try {
            return new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
        } catch (Exception $e) {
            die('Erreur : ' . $e->getMessage());
        }
    }

    $bdd = getDatabaseConnection();
    $id_article = $_POST['id_article'];
    $pseudo = $_POST['pseudo'];
    $commentaire = $_POST['commentaire'];

    $date_crea = date("Y-m-d H:i:s");
    $req = "INSERT INTO Commentaires (id_art, pseudo, contenu, date_crea) VALUES (?, ?, ?, ?)";
    $stmt = $bdd->prepare($req);
    $stmt->execute([$id_article, $pseudo, $commentaire, $date_crea]);
    header('Location: ct_article.php?id_art=' . $id_article);
    exit;
}

?>
<link rel="stylesheet" href="bitnami.css">
<form class="commentaire" action="ajouter_commentaire.php" method="post">
    <input type="hidden" name="id_article" value="<?php echo $_GET['id_art'] ?>">
    <label for="pseudo">Pseudo</label>
    <input type="text" name="pseudo">
    <label for="commentaire">Commentaire</label>
    <textarea name="commentaire" id="" cols="55" rows="0.5"></textarea>
    <input type="submit">
</form>

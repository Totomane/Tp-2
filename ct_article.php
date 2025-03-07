<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8" />
    <title>Description de l'article</title>
    <link rel="stylesheet" href="bitnami.css" />
<?php
function getDatabaseConnection() {
    try {
        return new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
    } catch (Exception $e) {
        die('Erreur : ' . $e->getMessage());
    }
}

function getArticle($bdd, $articleId) {
    $query = "SELECT * FROM articles WHERE id_art = :id_art";
    $stmt = $bdd->prepare($query);
    $stmt->execute(['id_art' => $articleId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getAuteurByID($bdd,$id_aut)
{
    //nom,prenom
    $query = "SELECT * FROM utilisateurs WHERE id_aut = :id_aut";
    $stmt = $bdd->prepare($query);
    $stmt->execute(['id_aut' => $id_aut]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getCommentaires($bdd, $articleId) {
    $query = "SELECT * FROM Commentaires WHERE id_art = :id_art ORDER BY date_crea ASC";
    $stmt = $bdd->prepare($query);
    $stmt->execute(['id_art' => $articleId]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['id_art'])) {
    $articleId = $_GET['id_art'];
    $bdd = getDatabaseConnection();

    $article = getArticle($bdd, $articleId);
    if ($article) {
        echo "<h2>" . htmlspecialchars($article['titre']) . "</h2>";
        echo "<p><strong>Auteur de l'article  : ". getAuteurByID($bdd,$article['id_aut'])['nom']. " " . getAuteurByID($bdd,$article['id_aut'])['prenom'] ."</strong><br>";
        echo "<p><strong>Publié le :</strong> " . date("d/m/Y H:i", strtotime($article['date_crea'])) . "</p>";
        echo "<p><strong>Dernière modification :</strong> " . date("d/m/Y H:i", strtotime($article['date_modif'])) . "</p>";
        echo "<p><strong>Contenu :</strong><br>" . htmlspecialchars($article['corps']) . "</p>";
        echo "<h3>Commentaires :</h3>";

        $commentaires = getCommentaires($bdd, $articleId);
        if (!empty($commentaires)) {
            foreach ($commentaires as $commentaire) {
                echo "<div><li><b>" . $commentaire['pseudo'] . "</b>(" . date("d/m/Y H:i", strtotime($commentaire['date_crea'])) . ")<br>";
                echo htmlspecialchars($commentaire['contenu']) . "</li><br><a href='dell_comment.php?id_com=" . $commentaire['id_com'] . "'>Supprimer</a></div>";
            }
        } else {
            echo "<p>aucun commentaire pour celui la </p>";
        }
    } else {
        echo "<p>Article introuvable !!!</p>";
    }
} else {
    echo "<p>ID de l'article is not the oneee</p>";
}
include 'ajouter_commentaire.php';
?>

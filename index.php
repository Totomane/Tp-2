<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8" />
    <link rel="stylesheet" href="bitnami2.css" />
  <title>Derniers Articles</title>
</head>
<body>
<header>
    <h1>Les 5 Derniers Articles</h1>
</header>

<?php

try {
    $bdd = new PDO('mysql:host=localhost;dbname=blog;charset=utf8', 'root', '');
} catch (Exception $e) {
    die('Erreur : ' . $e->getMessage());
}

$req = "SELECT titre, corps, date_crea FROM Articles ORDER BY date_crea DESC LIMIT 5";
$res = $bdd->query($req);


if ($res) {
    echo "<table border='1'>"; 
    echo "<tr>";
    echo "<th>Titre</th>"; 
    echo "<th>Résumé</th>";
    echo "<th>Date de publication</th>";
    echo "</tr>";
    
    while ($article = $res->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($article['titre']) . "</td>";
        
        if (strlen($article['corps']) > 150) {
            echo "<td>" . htmlspecialchars(substr($article['corps'], 150)) . "... </td>";
        } else {
            echo "<td>" . htmlspecialchars($article['corps']) . "</td>";
        }

        echo "<td>" . date("d/m/Y H:i", strtotime($article['date_crea'])) . "</td>";
        echo "</tr>";
    }

    echo "</table>";
    echo "<br>";
} else {
    echo "<p>Aucun article trouvé <3333</p>";
}

$res->closeCursor();
?>

</body>
</html>

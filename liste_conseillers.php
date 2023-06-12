<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pip";


try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie à la base de données";
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données : " . $e->getMessage();
}
?></br></br>
<?php
echo "Liste des conseillers";
?> 

    </br>
    </br>
<?php
// Exemple de requête pour récupérer des données
$query = "SELECT * FROM conseillers";
$stmt = $conn->query($query);

// Affichage des données récupérées
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    echo "ID : " . $row['id'] . "<br>";
    echo "Nom : " . $row['nom'] . "<br>";
    echo "Email : " . $row['email'] . "<br>";
    echo "<br>";
}
?>
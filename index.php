<?php

// Création d'un PDO 
$pdo = new PDO('mysql:host=localhost;dbname=boncommande', 'root', 'mdp');

// mettre ensuite pour le type de caractères comme en HTML
$pdo->exec('SET NAMES UTF8');

// Requête SQL
$query = $pdo->prepare
(
     'SELECT
     	orderNumber, 
     	orderDate, 
     	shippedDate,
     	status
		FROM orders
		ORDER BY orderNumber'
);

// Exécute la requête SQL
$query->execute();

// Va enregistrer dans variable orders ce que j’ai récupéré par ma requête SQL
// Créé un tableau associatif où les keys sont les noms des colonnes et les values le contenu des cellules du tableau de la base de données
$orders = $query->fetchAll(PDO::FETCH_ASSOC);

// inclus le HTML
include 'index.phtml';

?>
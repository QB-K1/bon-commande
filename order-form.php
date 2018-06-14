<?php

// Création d'un PDO 
$pdo = new PDO('mysql:host=localhost;dbname=boncommande', 'root', 'mdp');

// mettre ensuite pour le type de caractères comme en HTML
$pdo->exec('SET NAMES UTF8');

// Requête SQL pour infos clients
$queryTop = $pdo->prepare
(
     'SELECT
     	customerName, 
     	contactLastName, 
     	contactFirstName,
     	addressLine1,
     	city
		FROM customers INNER JOIN orders ON customers.customerNumber = orders.customerNumber
		WHERE orderNumber = ?'
);

// Exécute la requête pour infos clients
$queryTop->execute(array($_GET['orderNumber']));

// Va enregistrer dans variable orders ce que j’ai récupéré par ma requête SQL
// Créé un tableau associatif où les keys sont les noms des colonnes et les values le contenu des cellules du tableau de la base de données
$ordersTop = $queryTop->fetchAll(PDO::FETCH_ASSOC);

// Requête SQL pour tableau milieu
$queryMiddle = $pdo->prepare
(
     'SELECT
     	productName, 
     	priceEach, 
     	quantityOrdered,
     	(quantityOrdered * priceEach) AS totalPrice
		FROM orderdetails INNER JOIN products ON orderdetails.productCode = products.productCode
		WHERE orderNumber = ?
		ORDER BY productName'
);

// Exécute la requête SQL pour tableau milieu
$queryMiddle->execute(array($_GET['orderNumber']));

// Va enregistrer dans variable orders ce que j’ai récupéré par ma requête SQL
// Créé un tableau associatif où les keys sont les noms des colonnes et les values le contenu des cellules du tableau de la base de données
$ordersMiddle = $queryMiddle->fetchAll(PDO::FETCH_ASSOC);

// Requête SQL pour sommes fin de tableau
$queryBottom = $pdo->prepare
(
     'SELECT
     	SUM(quantityOrdered * priceEach) AS priceHT, 
          SUM(quantityOrdered * priceEach * 0.2) AS priceTVA, 
          SUM(quantityOrdered * priceEach + quantityOrdered * priceEach * 0.2) AS priceTTC
		FROM orderdetails INNER JOIN products ON orderdetails.productCode = products.productCode
		WHERE orderNumber = ?'
);

// Exécute la requête SQL pour sommes fin de tableau
$queryBottom->execute(array($_GET['orderNumber']));

// Va enregistrer dans variable orders ce que j’ai récupéré par ma requête SQL
// Créé un tableau associatif où les keys sont les noms des colonnes et les values le contenu des cellules du tableau de la base de données
$ordersBottom = $queryBottom->fetchAll(PDO::FETCH_ASSOC);

// inclus le HTML
include 'order-form.phtml';

?>
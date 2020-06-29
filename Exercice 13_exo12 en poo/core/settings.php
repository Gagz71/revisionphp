<?php


//Création d'une fonction permettant de retourner une connexion à la bdd
//C'est ici qu'on peut changer les paramètres de connexion à la bdd 
function getDb(){

    $dbName = 'revision_php';
    $dbUser = 'root';
    $dbPassword = '';
    return new PDO('mysql:host=localhost;dbname=' . $dbName .';charset=utf8', $dbUser, $dbPassword);
}

<?php 

//Inclusions physiques des fichiers de classes nécessaires 
require 'core/DTO/Animal.php';
require 'core/DAO/AnimalManager.php';
require 'core/settings.php';

//Importation des classes nécessaires
use App\DTO\Animal;
use App\DAO\AnimalManager;

//Try-catch pour capturer éventuelles exceptions
try{

    //Récupération de l'instance de connexion à la bdd
    $animalManager = new AnimalManager(getDb());

    //appel des variables 
    if(
        isset($_POST['name']) &&
        isset($_POST['species']) &&
        isset($_POST['birthdate'])
    ){
        //Bloc des vérifs
        //Vérif du nom
        if(!preg_match('/^[a-z\'\- áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]{2,50}$/i', $_POST['name'])){
            $errors[] = 'Nom d\'animal invalide';
        }
    
        //Vérif de l'espèce
        if(!preg_match('/^[a-z\'\- áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]{2,150}$/i', $_POST['species'])){
            $errors[] = 'Espèce animal invalide';
        }

        //Si pas d'erreurs
        if(!isset($errors)){

            //Création d'un nouvel animal
            $newAnimal = new Animal();
            //Hydratation du nouvel animal
            $newAnimal->setName($_POST['name']);
            $newAnimal->setSpecies($_POST['species']);
            $newAnimal->setBirthdate(new DateTime());

            

            //Sauvegarde en bdd
            $statut = $animalManager->save($newAnimal);

            //Statut contient true si la sauvegarde de l'utilisateur a fonctionnée, sinon false
            if($statut){
                $success = "Animal créée avec succès !";
            } else{
                $errors[] = 'Problème avec la base de données, veuillez ré-essayer plus tard.';
            }

            

        }
    }
    //Récupération de tous les animaux
    $animals = $animalManager->findAll();

} catch(Exception $e){
    die('Problème PHP: ' . $e->getMessage());
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Révision insertion bdd POO</title>
    <style>

    body{font-family: sans-serif;margin:0;padding:0;}

    p.error{
        color: red;
        text-align: center;
        font-size: 1.4rem;
    }

    p.success{
        color: green;
        text-align: center;
        font-size: 1.4rem;
    }

    h1.main-title{
            text-align: center;
    }

    div.container{
        margin: 40px;
    }

    div.container table{
        margin: auto;
        border: medium solid #6495ed;
        border-collapse: collapse;
        text-align: center;
    }

    div.container table tr th {
        border: thin solid #6495EE;
        padding: 10px;
        min-width: 150px;
        background-color: #D0E3FA;
    }

    div.container table tr td {
        border: 1px solid #6495EE;
        padding: 8px;
    }

    </style>
</head>
<body>
    <!--Mon Formulaire-->
    <h1 class="main-title">Ajouter un nouvel animal dans la base de données</h1>

    <?php

    //Affichage des messages de succès s'ils existent
    if(isset($success)){
        echo '<p class="success">' . $success . '</p>';
    } else{
    ?>

        <form action="newAnimal.php" method="POST">Insérer un animal : 
        <?php 

        //Affichage des messages d'erreurs s'ils existent
        if(isset($errors)){
            foreach($errors as $error){
                echo '<p class="error">'. $error. '</p>';
            }
        }

        ?>
            <input type="text" name="name" placeholder="nom">
            <input type="text" name="species" placeholder="espèce">
            <input type="date" name="birthdate" placeholder="date de naissance">
            <input type="submit" value="Insérer dans la bdd">
        </form>
        <?php
    }
    ?>

    <!--Ma liste d'animaux-->
    <h1 class="main-title"">Animaux déjà enregistrés</h1>
    
    <div class="container">
    

        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Espèce</th>
                    <th>Date de naissance</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach($animals as $animal){
                    ?>
                    <tr>
                        <td><?php echo htmlspecialchars($animal->getName()); ?></td>
                        <td><?php echo htmlspecialchars($animal->getSpecies()); ?></td>
                        <td><?php echo $animal->getBirthdate()->format('d/m/Y'); ?></td>
                    </tr>
                    <?php
                }
                ?>


            </tbody>
        </table>

    </div>
    
    
      
    
    
    

</body>
</html>
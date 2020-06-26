<?php

//1ère étape: appel des variables
if(
    isset($_POST['name']) &&
    isset($_POST['species']) &&
    isset($_POST['birthdate']) 
){
    //2è étape: bloc des vérifs

    if(!preg_match('/^[a-z\'\- áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]{2,50}$/i', $_POST['name'])){
        $errors[] = 'Nom d\'animal invalide';
    }

    if(!preg_match('/^[a-z\'\- áàâäãåçéèêëíìîïñóòôöõúùûüýÿæœÁÀÂÄÃÅÇÉÈÊËÍÌÎÏÑÓÒÔÖÕÚÙÛÜÝŸÆŒ]{2,150}$/i', $_POST['species'])){
        $errors[] = 'Espèce animal invalide';
    }

   

    //3è étape: Si pas d'erreurs
    if(!isset($errors)){

        //Connexion à la bdd
        try{
            $bdd = new PDO('mysql:host=localhost; dbname=revision_php; charset=utf8', 'root', '');

            //affichage des erreurs SQL s'il y en a
            $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(Exception $e){
            die('Problème de connexion à la BDD : ' . $e->getMessage());
        }

        //Requête préparée pour insertion d'un nouvel animal 
        $response = $bdd->prepare('INSERT INTO animals(name, species, birthdate) VALUES(?, ?, ?)');

        //Exécution de la requête
        $response->execute([
            $_POST['name'],
            $_POST['species'],
            $_POST['birthdate']
        ]);

        //si l'insertion a bien fonctionné
        if($response->rowCount() > 0){
            //Création message de succès
            $successMessage = 'Votre nouvel animal a bien été enregistré !';
        } else{
            $errors[] = "Problème avec la base de données, veuillez ré-essayer !";
        }

        //Fermeture de la requête
        $response->closeCursor();

    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exercice Révision PHP BDD Insertion Form</title>
</head>
<body>

    <?php

    //Sil y a des erreurs on les affiche
    if(isset($errors)){
        foreach($errors as $error){
            echo '<p style="color:red;">'. $error . '</p>';
        } 
    }

    //S'il n'y a pas d'erreur et que successMessage existe, on l'affiche et on masque le formulaire
    if(isset($successMessage)){
        echo '<p style="color:green;">'. htmlspecialchars($successMessage) . '</p>';
    } else{
        ?>

        <!--Mon Formulaire-->
        <h1 style="text-align: center">Ajouter un nouvel animal dans la base de données</h1>
        <form action="" method="POST">Insérer un animal : 
            <input type="text" name="name" placeholder="nom">
            <input type="text" name="species" placeholder="espèce">
            <input type="date" name="birthdate" placeholder="date de naissance">
            <input type="submit" value="Insérer dans la bdd">
        </form>
        <?php
    }
    ?>

</body>
</html>
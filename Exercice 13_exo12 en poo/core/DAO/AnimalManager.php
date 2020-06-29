<?php

//Déclaration du namespace
namespace App\DAO;

//Importation des classes nécessaires
use \App\DTO\Animal;
use \PDO;
use \DateTime;


/**
 * Création d'une classe DAO permettant de gérer les objets de la classe 'Animal'
 */
class AnimalManager{

    /**
     * Attribut permettant stockage d'une instance de connexion à la bdd (PDO)
     */
    private $db;

    /**
     * Getter $db
     */
    public function getDb(){
        return $this->db;
    }

    /**
     * Setter $db
     */
    public function setDb(PDO $db){
        $this->db = $db;
    }

    /**
     * Constructeur servant à hydrater $db avec une instance de connexion PDO
     */
    public function __construct($db){
        $this->setDb($db);
    }


    /**
     * Création d'une méthode permettant conversion d'un tableau de données en objet de la class Animal
     */
    public function buildDomainObject(array $animalInfos){

        //Création d'un nouvel animal
        $newAnimal = new Animal();

        //Hydratation de l'animal avec les infos récupérés dans bdd
        $newAnimal->setId($animalInfos['id']);
        $newAnimal->setName($animalInfos['name']);
        $newAnimal->setSpecies($animalInfos['species']);
        $newAnimal->setBirthdate(new DateTime($animalInfos['birthdate']));

        return $newAnimal;
    }

    /**
     * Création d'une méthode permettant de sauvegarder un objet de la classe 'Animal' dans la bdd dans la table 'animals'
     */
    public function save(Animal $animalToSave){

        //Requête SQL préparé pour insertion
        $createNewAnimal = $this->getDb()->prepare('INSERT INTO animals(name, species, birthdate) VALUES(?, ?, ?)');

        //Execution de l'insertion en liant les marqueurs avec données de l'animal à sauvegarder
        return $createNewAnimal->execute([
            $animalToSave->getName(), 
            $animalToSave->getSpecies(),
            $animalToSave->getBirthdate()->format('Y-m-d H:i:s')
        ]);
    }


    /**
     * Création d'une méthode permettant la récupération de la liste de tous les animaux
     */
    public function findAll(){

        //Requête SQL direct pour récupération de toutes les infos de tous les animaux
        $getAnimals = $this->getDb()->query('SELECT * FROM animals');

        //Récupération des animaux sous forme d'array assoc
        $animalsList = $getAnimals->fetchAll(PDO::FETCH_ASSOC);

        //Création nouveau tableau vide qui contiendra tout les objets demandés sous forme d'objets de la classe Animal (car $animalsInfos est un tableau qu'on doit convertir en objets)
        $animalsObjects = [];

        //Pour chaque sous tableau d'animaux, on créer un objet "Animal" que l'on ajoute au tableau $animalsObjects
        foreach($animalsList as $animalInfos){

            //Ajout du nouvel animal hydraté dans le tableau $animalsObjects
            $animalsObjects[] = $this->buildDomainObject($animalInfos);
        }

        //Méthode retourne le tableau contenant tous les animaux sous forme d'objets de la classe Animal
        return $animalsObjects;
    }


}
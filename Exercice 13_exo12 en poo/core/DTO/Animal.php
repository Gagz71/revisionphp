<?php 

//Déclaration du namespace
namespace App\DTO;

//Importation des classes nécessaires
use \Exception;
use \DateTime;

/**
 * Création d'une classe DTO matérialisant les animaux du sites
 */
class Animal{

    //Création des attributs
    private $id;
    private $name;
    private $species;
    private $birthdate;

    /**
     * Getters
     */
    public function getId(){
        return $this->id;
    }

    public function getName(){
        return $this->name;
    }

    public function getSpecies(){
        return $this->species;
    }

    public function getBirthdate(){
        return $this->birthdate;
    }


    /**
     * Setters
     */
    public function setId(int $id){
        if(!preg_match('/^[1-9][0-9]{0,10}$/', $id)){
            throw new Exception('id doit être valide');
        }
        $this->id = $id;
    }

    public function setName(string $name){
        if(!preg_match('/^.{1,50}$/i', $name)){
            throw new Exception('Nom invalide. Doit contenir entre 1 et 50 caractères.');
        }
        $this->name = $name;
    }

    public function setSpecies(string $species){
        if(!preg_match('/^.{1,150}$/i', $species)){
            throw new Exception('Espèce invalide. Doit contenir entre 1 et 150 caractères.');
        }
        $this->species = $species;
    }

    public function setBirthdate(DateTime $birthdate){
        $this->birthdate = $birthdate;
    }
}

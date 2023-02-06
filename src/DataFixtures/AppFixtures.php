<?php 


namespace App\DataFixtures;

use Faker\Factory;
use Faker\Generator;
use App\Entity\Ingredient;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;


class AppFixtures extends Fixture
{
//On peut aussi générer des noms de produits aléatoires grace à faker
    /**
     * @var  Generator
     */
    private Generator $faker;
   
//Constructeur du faker avec fr_FR pour avoir des données en français
    public function __construct()
    {
        
        $this->faker = Factory::create('fr_FR');//localise les données en français
    }
//fonction créant 50 instances de l'objet ingrédient
    public function load(ObjectManager $manager): void
    {
        for($i = 1; $i<=50; $i++)
        { 
            $ingredient = new Ingredient();
            $ingredient->setName($this->faker->word()) //Génère un mot aléatoire
            //on génère aléatoirement un prix compris entre 0 et 100
                        ->setPrice(mt_rand(0,100));

            $manager->persist($ingredient);

        }
       

        $manager->flush();
    }
}

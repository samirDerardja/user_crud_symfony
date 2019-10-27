<?php

namespace App\DataFixtures;

use Faker;
use App\Entity\User;
use Faker\Provider\DateTime;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

 
class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {


        // On configure dans quelles langues nous voulons nos données
        $faker = Faker\Factory::create('fr_FR');
 
        // on créé 5 utilisateurs aléatoire
        for ($i = 0; $i < 30; $i++) {

            //on instancie un nouvel utilisateur ainsi que la date du jour
            $user = new User(); 
            $dateNow = new \DateTime();
            // $dateTimeBirthDay = new \DateTime('1982-09-10');
            
         

            $user->setFirstName($faker->firstName);
            $user->setLastName($faker->lastName);
            $user->setBirthDate($faker->dateTimeBetween($startDate = '-60 years', $endDate = 'now', $timezone = null));
            // apres avoir crée une date aléatoire, je la fait passer dans une variable et la passe en a parametre
            //pour calculer la difference
            $newAge = $user->getBirthDate();
            $user->setAgeUser($dateNow->diff($newAge, true)-> y);
            // on persiste notre objet
            $manager->persist($user);
        }
         // et on envoie pour effectuer  la modification
        $manager->flush();
    }
}


<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\SubCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        // Création de l'utilateur Administrateur
        $damien = new User();

        // Hash du mot de passe de l'Administrateur
        $hashDamien = $this->encoder->encodePassword($damien, 'password');

        $damien->setUsername('Damien')
               ->setEmail('damien@d-riviere.fr')
               ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(10)) . '</p>')
               ->setHash($hashDamien);

        // Création de plusieurs utilisateurs random
        for($i = 1; $i <= 10; $i++) {
            $user = new User();

            // Hash du mot de passe des utilisateurs
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setUsername($faker->firstName)
                 ->setEmail($faker->email)
                 ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(10)) . '</p>')
                 ->setHash($hash);

            $manager->persist($user);
        }

        // Instanciation des Category
        $test = new Category();
        $preview = new Category();
        $news = new Category();

        // Instanciation des SubCategory
        $ps4 = new SubCategory();
        $switch = new SubCategory();
        $one = new SubCategory();
        $pc = new Subcategory();   

        // Création des Category
        $test->setTitle("Test")
             ->setStyle("badge stylish-color");
        $preview->setTitle("Preview")
                ->setStyle("badge stylish-color");
        $news->setTitle("News")
             ->setStyle("badge stylish-color");

        $category = array($test, $preview, $news);
        
        // Création des SubCategory
        $ps4->setTitle("PS4")
            ->setStyle("badge badge-primary");
        $switch->setTitle("Switch")
               ->setStyle("badge badge-danger");
        $one->setTitle("ONE")
            ->setStyle("badge badge-success");
        $pc->setTitle("PC")
           ->setStyle("badge stylish-color-dark");

        $subCategory = array($ps4, $switch, $one, $pc);

        // Création des articles
        for ($i = 1; $i <= 15; $i++) {
            $article = new Article();

            $title = $faker->sentence(5);
            $coverImage = $faker->imageUrl(1920, 1080);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(10)) . '</p>';
            $introduction = $faker->paragraph(3);

            $article->setTitle($title)
                    ->setContent($content)
                    ->setCoverImage($coverImage)
                    ->setCategory($randomCategory = $category[array_rand($category)])
                    ->addSubCategory($randomSubCategory = $subCategory[array_rand($subCategory)])
                    ->setIntroduction($introduction)
                    ->setAuthor($damien);

            $manager->persist($article);
        }   
        
        // On persist les category et les subcategory
        $manager->persist($test, $preview, $news, $ps4, $switch, $one, $pc, $damien);
    
        $manager->flush();
    }
}

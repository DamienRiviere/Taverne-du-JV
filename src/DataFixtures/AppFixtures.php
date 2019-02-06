<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\SubCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr-FR');

        $test = new Category();
        $preview = new Category();
        $news = new Category();

        $ps4 = new SubCategory();
        $switch = new SubCategory();
        $one = new SubCategory();
        $pc = new Subcategory();   

        $test->setTitle("Test")
             ->setStyle("badge stylish-color");
        $preview->setTitle("Preview")
                ->setStyle("badge stylish-color");
        $news->setTitle("News")
             ->setStyle("badge stylish-color");

        $category = array($test, $preview, $news);
        
        $ps4->setTitle("PS4")
            ->setStyle("badge badge-primary");
        $switch->setTitle("Switch")
               ->setStyle("badge badge-danger");
        $one->setTitle("ONE")
            ->setStyle("badge badge-success");
        $pc->setTitle("PC")
           ->setStyle("badge stylish-color-dark");

        $subCategory = array($ps4, $switch, $one, $pc);

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
                    ->setCreatedAt(new \DateTime())
                    ->setIntroduction($introduction);

            $manager->persist($article);
        }   
        
        $manager->persist($test, $preview, $news, $ps4, $switch, $one, $pc);
    
        $manager->flush();
    }
}

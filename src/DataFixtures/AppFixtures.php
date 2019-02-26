<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Role;
use App\Entity\User;
use App\Entity\Forum;
use App\Entity\Topic;
use App\Entity\Article;
use App\Entity\Category;
use App\Entity\Moderation;
use App\Entity\SubCategory;
use App\Entity\CommentTopic;
use App\Entity\CommentArticle;
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

        // Rôle administrateur
        $adminRole = new Role();
        $adminRole->setTitle('ROLE_ADMIN');

        $manager->persist($adminRole);

        // Rôle modérateur
        $moderatorRole = new Role();
        $moderatorRole->setTitle('ROLE_MODERATOR');

        $manager->persist($moderatorRole);

        // Rôle auteur
        $authorRole = new Role();
        $authorRole->setTitle('ROLE_AUTHOR');

        $manager->persist($authorRole);

        // Création de l'utilisateur Administrateur
        $damien = new User();
        $damien->setUsername('Damien')
               ->setEmail('damien@d-riviere.fr')
               ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
               ->setHash($this->encoder->encodePassword($damien, 'password'))
               ->setPicture('https://www.manga-news.com/public/images/pix/serie/9164/the-arms-peddler-visual-8.jpg')
               ->addUserRole($adminRole);
        
        $manager->persist($damien);

        // Création d'un utilisateur Modérateur
        $moderateur = new User();
        $moderateur->setUsername('Moderateur')
                   ->setEmail('moderateur@d-riviere.fr')
                   ->setHash($this->encoder->encodePassword($moderateur, 'password'))
                   ->addUserRole($moderatorRole)
                   ->setPicture('http://image.jeuxvideo.com/avatar-md/default.jpg');

        $manager->persist($moderateur);

        // Création d'un utilisateur Auteur
        $auteur = new User();
        $auteur->setUsername('Auteur')
               ->setEmail('auteur@d-riviere.fr')
               ->setHash($this->encoder->encodePassword($auteur, 'password'))
               ->addUserRole($authorRole)
               ->setPicture('http://image.jeuxvideo.com/avatar-md/default.jpg');

        $manager->persist($auteur);

        // Création de plusieurs utilisateurs random
        for($i = 1; $i <= 10; $i++) {
            $user = new User();

            // Hash du mot de passe des utilisateurs
            $hash = $this->encoder->encodePassword($user, 'password');

            $user->setUsername($faker->firstName)
                 ->setEmail($faker->email)
                 ->setDescription('<p>' . join('</p><p>', $faker->paragraphs(3)) . '</p>')
                 ->setHash($hash)
                 ->setPicture('http://image.jeuxvideo.com/avatar-md/default.jpg');

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

        $manager->persist($test, $preview, $news);
        
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

        $manager->persist($ps4, $switch, $one, $pc);

        $comPublier = new Moderation();
        $comPublier->setStatut('Commentaire publié');

        $manager->persist($comPublier);

        $comSignaler = new Moderation();
        $comSignaler->setStatut('Commentaire signalé');

        $manager->persist($comSignaler);

        $comModerer = new Moderation();
        $comModerer->setStatut('Commentaire modéré');

        $manager->persist($comModerer);

        // Création des articles
        for ($i = 1; $i <= 30; $i++) {
            $article = new Article();

            $title = $faker->sentence(5);
            $coverImage = $faker->imageUrl(1920, 1080);
            $content = '<p>' . join('</p><p>', $faker->paragraphs(10)) . '</p>';
            $introduction = $faker->paragraph(2);

            $article->setTitle($title)
                    ->setContent($content)
                    ->setCoverImage($coverImage)
                    ->setCategory($randomCategory = $category[array_rand($category)])
                    ->addSubCategory($randomSubCategory = $subCategory[array_rand($subCategory)])
                    ->setIntroduction($introduction)
                    ->setAuthor($damien);

            // Création des commentaires
            for($c = 1; $c <= 5; $c++) {
                $commentArticle = new CommentArticle();
                $commentArticle->setContent('<p>' . join('</p><p>', $faker->paragraphs(1)) . '</p>')
                               ->setUser($damien)
                               ->setArticle($article)
                               ->setModeration($comPublier);
                $manager->persist($commentArticle);
            }

            $manager->persist($article);
        } 
        
        // Création des forums Nintendo
        $switch = new Forum();
        $switch->setTitle('Switch')
               ->setPlatformTitle('Nintendo')
               ->setPlatformLogo('fab fa-nintendo-switch')
               ->setPlatformStyle('danger-color-dark')
               ->setPlatformType('Hybride');

        $manager->persist($switch);

        $wiiU = new Forum();
        $wiiU->setTitle('Wii U')
             ->setPlatformTitle('Nintendo')
             ->setPlatformLogo('fab fa-nintendo-switch')
             ->setPlatformStyle('danger-color-dark')
             ->setPlatformType('Console de salon');

        $manager->persist($wiiU);
        
        $wii = new Forum();
        $wii->setTitle('Wii')
             ->setPlatformTitle('Nintendo')
             ->setPlatformLogo('fab fa-nintendo-switch')
             ->setPlatformStyle('danger-color-dark')
             ->setPlatformType('Console de salon');

        $manager->persist($wii);
        
        $gamecube = new Forum();
        $gamecube->setTitle('Gamecube')
                 ->setPlatformTitle('Nintendo')
                 ->setPlatformLogo('fab fa-nintendo-switch')
                 ->setPlatformStyle('danger-color-dark')
                 ->setPlatformType('Console de salon');

        $manager->persist($gamecube);
        
        $nintendo64 = new Forum();
        $nintendo64->setTitle('Nintendo 64')
                   ->setPlatformTitle('Nintendo')
                   ->setPlatformLogo('fab fa-nintendo-switch')
                   ->setPlatformStyle('danger-color-dark')
                   ->setPlatformType('Console de salon');

        $manager->persist($nintendo64);

        $snes = new Forum();
        $snes->setTitle('Super NES')
             ->setPlatformTitle('Nintendo')
             ->setPlatformLogo('fab fa-nintendo-switch')
             ->setPlatformStyle('danger-color-dark')
             ->setPlatformType('Console de salon');
    
        $manager->persist($snes);
        
        $nes = new Forum();
        $nes->setTitle('NES')
            ->setPlatformTitle('Nintendo')
            ->setPlatformLogo('fab fa-nintendo-switch')
            ->setPlatformStyle('danger-color-dark')
            ->setPlatformType('Console de salon');

        $manager->persist($nes);

        $n3ds = new Forum();
        $n3ds->setTitle('3DS')
             ->setPlatformTitle('Nintendo')
             ->setPlatformLogo('fab fa-nintendo-switch')
             ->setPlatformStyle('danger-color-dark')
             ->setPlatformType('Console portable');

        $manager->persist($n3ds);

        $ds = new Forum();
        $ds->setTitle('DS')
           ->setPlatformTitle('Nintendo')
           ->setPlatformLogo('fab fa-nintendo-switch')
           ->setPlatformStyle('danger-color-dark')
           ->setPlatformType('Console portable');

        $manager->persist($ds);

        $gba = new Forum();
        $gba->setTitle('Gameboy Advance')
            ->setPlatformTitle('Nintendo')
            ->setPlatformLogo('fab fa-nintendo-switch')
            ->setPlatformStyle('danger-color-dark')
            ->setPlatformType('Console portable');

        $manager->persist($gba);

        $gbc = new Forum();
        $gbc->setTitle('Gameboy Color')
            ->setPlatformTitle('Nintendo')
            ->setPlatformLogo('fab fa-nintendo-switch')
            ->setPlatformStyle('danger-color-dark')
            ->setPlatformType('Console portable');

        $manager->persist($gbc);

        $gb = new Forum();
        $gb->setTitle('Gameboy')
           ->setPlatformTitle('Nintendo')
           ->setPlatformLogo('fab fa-nintendo-switch')
           ->setPlatformStyle('danger-color-dark')
           ->setPlatformType('Console portable');

        $manager->persist($gb);

        // Création des forums Playstation
        $ps4 = new Forum();
        $ps4->setTitle('Playstation 4')
            ->setPlatformTitle('Playstation')
            ->setPlatformLogo('fab fa-playstation')
            ->setPlatformStyle('primary-color-dark')
            ->setPlatformType('Console de salon');

        $manager->persist($ps4);

        $ps3 = new Forum();
        $ps3->setTitle('Playstation 3')
            ->setPlatformTitle('Playstation')
            ->setPlatformLogo('fab fa-playstation')
            ->setPlatformStyle('primary-color-dark')
            ->setPlatformType('Console de salon');

        $manager->persist($ps3);

        $ps2 = new Forum();
        $ps2->setTitle('Playstation 2')
            ->setPlatformTitle('Playstation')
            ->setPlatformLogo('fab fa-playstation')
            ->setPlatformStyle('primary-color-dark')
            ->setPlatformType('Console de salon');

        $manager->persist($ps2);

        $ps1 = new Forum();
        $ps1->setTitle('Playstation 1')
            ->setPlatformTitle('Playstation')
            ->setPlatformLogo('fab fa-playstation')
            ->setPlatformStyle('primary-color-dark')
            ->setPlatformType('Console de salon');

        $manager->persist($ps1);

        $psVita = new Forum();
        $psVita->setTitle('PS Vita')
               ->setPlatformTitle('Playstation')
               ->setPlatformLogo('fab fa-playstation')
               ->setPlatformStyle('primary-color-dark')
               ->setPlatformType('Console portable');

        $manager->persist($psVita);

        $psp = new Forum();
        $psp->setTitle('PSP')
            ->setPlatformTitle('Playstation')
            ->setPlatformLogo('fab fa-playstation')
            ->setPlatformStyle('primary-color-dark')
            ->setPlatformType('Console portable');

        $manager->persist($psp);

        // Création des forums Xbox
        $xboxOne = new Forum();
        $xboxOne->setTitle('Xbox One')
                ->setPlatformTitle('Xbox')
                ->setPlatformLogo('fab fa-xbox')
                ->setPlatformStyle('success-color-dark')
                ->setPlatformType('Console de salon');

        $manager->persist($xboxOne);

        $xbox360 = new Forum();
        $xbox360->setTitle('Xbox 360')
                ->setPlatformTitle('Xbox')
                ->setPlatformLogo('fab fa-xbox')
                ->setPlatformStyle('success-color-dark')
                ->setPlatformType('Console de salon');

        $manager->persist($xbox360);

        $xbox = new Forum();
        $xbox->setTitle('Xbox')
             ->setPlatformTitle('Xbox')
             ->setPlatformLogo('fab fa-xbox')
             ->setPlatformStyle('success-color-dark')
             ->setPlatformType('Console de salon');

        $manager->persist($xbox);

        // Création des forums PC
        $pcJeux = new Forum();
        $pcJeux->setTitle('Jeux')
               ->setPlatformTitle('PC')
               ->setPlatformLogo('fas fa-desktop')
               ->setPlatformStyle('bg-dark')
               ->setPlatformType('PC');

        $manager->persist($pcJeux);

        $pcHardware = new Forum();
        $pcHardware->setTitle('Hardware')
                   ->setPlatformTitle('PC')
                   ->setPlatformLogo('fas fa-desktop')
                   ->setPlatformStyle('bg-dark')
                   ->setPlatformType('PC');

        $manager->persist($pcHardware);

        $forum = [$switch, $wiiU, $wii, $gamecube, $nintendo64, $snes, $nes, $n3ds, $ds, $gba, $gbc, $gb, $ps4, $ps3, $ps2, $ps1, $psVita, $psp, $xboxOne, $xbox360, $xbox, $pcJeux, $pcHardware];

        // Création des topics
        for($i = 1; $i <= 30; $i++)
        {
            $topic = new Topic();

            $title = $faker->sentence(5);
            $message = '<p>' . join('</p><p>', $faker->paragraphs(1)) . '</p>';

            $topic->setTitle($title)
                  ->setMessage($message)
                  ->setForum($switch)
                  ->setUser($damien);

            $manager->persist($topic);

            // Création des commentaires du topic
            for($c = 1; $c <= 30; $c++)
            {
                $comment = new CommentTopic();
                
                $content = '<p>' . join('</p><p>', $faker->paragraphs(1)) . '</p>';

                $comment->setContent($content)
                        ->setTopic($topic)
                        ->setUser($damien)
                        ->setModeration($comPublier);

                $manager->persist($comment);
            }
        }
    
        $manager->flush();
    }
}

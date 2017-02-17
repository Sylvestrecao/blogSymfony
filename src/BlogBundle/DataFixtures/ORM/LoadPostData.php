<?php
// src/BlogBundle/DataFixtures/ORM/LoadPostData.php

namespace BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BlogBundle\Entity\Post;
use BlogBundle\Entity\Category;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
class LoadPostData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $postsData = array(
            array(
                'name'       => 'Road trip en Alaska, carte postale',
                'content'    => 'Toujours en Alaska, tellement de choses à voir. La nature, en puissance évidemment, les routes panoramiques et les paysages de malade ! 
                L’endroit rêvé pour faire un road trip. Je t’écris du parc national de Wrangell St Elias, au sud de Tok. On observe les caribous en migrations, 
                il y en a des centaines c’est trop beau. Le comble de voir des caribous pour la première fois, ayant déjà passé 1 an au Canada ?
                L’hiver arrive à grand pas et ça se sent ! Le froid (mais on a connu pire*), la neige qui commence à tenir, les ours qui malheureusement semblent s’être 
                reculés dans les montagnes à la recherche d’une tanière pour hiberner, les lacs sont gelés et les rivières peinent à s’écouler de plus en plus. 
                Il y a quelques saumons en retard qui essaient encore désespérément de remonter la rivière (sont-ils fous ces saumons ?) et les aigles sont par 
                dizaines autour des cours d’eaux à l’affût d’un casse-croûte ! On les a vu attraper des saumons !!',
            ),
            array(
                'name'       => 'Un superbe road trip dans les Alpes bavaroises !',
                'content'    => 'Nous avons fait un superbe road trip d’une semaine en Bavière, tout au Sud de l’Allemagne, du côté des Alpes !
                Le timing et la saison, en été (début septembre) étaient parfaits. En 1 semaine, vous pouvez explorer le Parc National de Berchtesgaden (4 jours) et 
                la région de Garmisch-Partenkirchen (3 jours). Pour finir en beauté avec le fameux château de Neuschwanstein ! Les Alpes bavaroises sont à moins de 2h 
                de Munich, la capitale de la Bavière, tout au Sud de l’Allemagne. Il y a par là-bas de beaux sommets qui avoisinent les 3 000 mètres, quelques lacs 
                cristallins et de très belles routes alpines ! C’est parti pour un itinéraire bien cool dans les Alpes bavaroises, avec les belles adresses trouvées 
                sur la route, nos plus beaux spots, lacs et montagnes à visiter !',
            ),
            array(
                'name'       => 'Tokyo — Inspiration photographique, belles adresses et guide pratique',
                'content'    => 'Nous rentrons d’un superbe voyage de 15 jours au Japon, à la découverte de trois villes emblématiques : l’hyper-contemporaine Tokyo, 
                la traditionnelle Kyoto et la «food-wahou» Osaka ! Exploration initiatique et merveilleuse de la culture nippone ! Des ambiances humaines, morales, 
                psychologiques, photographiques très fortes, nous appelant à ressentir le «Wa» — philosophie de l’équilibre reliant les Japonais les uns aux autres, 
                et au monde. C’est un beau reportage que nous avons réalisé pour Travel by Air France, les guides de voyage de la compagnie. Air France est avec 
                nous depuis les tout débuts du blog, dès 2007, c’est avec d’autant plus de plaisir que nous travaillons ensemble régulièrement !
                On vous emmène d’abord visiter la magnétique et incroyable Tokyo, suivez le guide !',
            )
        );


        // Get our userManager, you must implement `ContainerAwareInterface`
        $userManager = $this->container->get('fos_user.user_manager');
        // Create our user and set details
        $user = $userManager->createUser();
        $user->setUsername('Auteur');
        $user->setEmail('auteur@domain.com');
        $user->setPlainPassword('auteur');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_AUTEUR'));

        // New Category
        $category = new Category();
        $category->setName('Category 4');
        $category->setNumberPost(0);

        foreach ($postsData as $postData) {
            $post = new Post();
            $post->setUser($user);
            $post->setCategory($category);
            $post->setName($postData['name']);
            $post->setContent($postData['content']);
            
            $manager->persist($post);
        }
        $manager->flush();

    }
}
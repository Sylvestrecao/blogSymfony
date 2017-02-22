<?php
// src/BlogBundle/DataFixtures/ORM/LoadCommentData.php

namespace BlogBundle\DataFixtures\ORM;

use BlogBundle\Entity\CommentResponse;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BlogBundle\Entity\Post;
use BlogBundle\Entity\Comment;
use BlogBundle\Entity\Category;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
class LoadCommentData implements FixtureInterface, ContainerAwareInterface
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
        // Get our userManager, you must implement `ContainerAwareInterface`
        $userManager = $this->container->get('fos_user.user_manager');
        // Create our user and set details
        $user = $userManager->createUser();
        $user->setUsername('Rédacteur');
        $user->setEmail('rédacteur@domain.com');
        $user->setPlainPassword('rédacteur');
        $user->setEnabled(true);
        $user->setRoles(array('ROLE_AUTEUR'));
        // New Category
        $category = new Category();
        $category->setName('Category 3');
        $category->setNumberPost(0);

        $post = new Post();
        $post->setName("Road trip en Écosse : North Coast 500, le guide !");
        $post->setContent("500 miles de bonheur dans les Highlands. La North Coast 500 est une route de 500 miles (800 km) qui longe les côtes touuut au 
        Nord de l’Ecosse, entre falaises, plages et lochs grandioses, d’Inverness à Torridon. C’est un peu le far North… Wick, John O’Groats, Durness, 
        Lochinver, Applecross,… Et des paysages sauvages à couper le souffle ! Comme pour notre premier road trip en Ecosse, on a aussi déniché des super adresses ! 
        De belles expériences qui font partie intégrante du road trip selon nous. Parce que connaître les bons lieux (l’hôtel cool, la cabane paumée, le ptit resto de 
        fruits de mer…), c’est ce qui rend le voyage inoubliable ! On a donc exploré, creusé, cherché, photographié et avec toutes nos trouvailles on vous a préparé 
        ce guide spécial road trip dans le Nord de l’Écosse, sur la mythique North Coast 500 ! — Enjoy !");
        $post->setUser($user);
        $post->setCategory($category);


        // Set Comments
        $commentsData = array(
            array(
                'username' => 'John',
                'email'    => 'john@mail.com',
                'content'  => 'This article is awesome, nice job! :)'
            ),
            array(
                'username' => 'Alex',
                'email'    => 'alex@mail.com',
                'content'  => 'I love your content.'
            ),
            array(
                'username' => 'Rachel',
                'email'    => 'rachel@mail.com',
                'content'  => 'Waiting for the next article.'
            )
        );

        foreach ($commentsData as $commentData) {
            $comment = new Comment();
            $comment->setPost($post);
            $comment->setUsername($commentData['username']);
            $comment->setEmail($commentData['email']);
            $comment->setContent($commentData['content']);

            $commentResponse = new CommentResponse();
            $commentResponse->setContent("I am the response of comment");
            $commentResponse->setComment($comment);

            $manager->persist($comment);
            $manager->persist($commentResponse);
        }
        $manager->persist($post);
        $manager->flush();

    }
}
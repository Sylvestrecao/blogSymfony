<?php
// src/BlogBundle/DataFixtures/ORM/LoadCategoryData.php

namespace BlogBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use BlogBundle\Entity\Category;
class LoadCategoryData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $category = new Category();
        $category->setName('Category 1');
        $category->setNumberPost(0);
        $manager->persist($category);

        $category = new Category();
        $category->setName('Category 2');
        $category->setNumberPost(0);
        $manager->persist($category);
        
        $manager->flush();

    }
}
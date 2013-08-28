<?php

namespace Sylius\Bundle\CoreBundle\DataFixtures\ORM;

use Doctrine\Common\Persistence\ObjectManager;
use Galoo\Bundle\ShopBundle\Model\Shop;

/**
 * Shop fixtures.
 *
 * @author Laszlo Horvath <pentarim@gmail.com>
 */
class LoadShopData extends DataFixture
{
    /**
     * {@inheritdoc}
     */
    public function load(ObjectManager $manager)
    {
        for ($i = 1; $i <= 15; $i++) {
            
            $user = $this->getReference('Sylius.User-'.$i);
                 
            $shop = new Shop();

            $shop->setUser($user);
            $shop->setName(strtolower($user->getFirstname()));
            $shop->setTitle($user->getFirstname() . '`s shop');
            $shop->setDescription($this->faker->sentence);
            $shop->setEnabled(true);
            $shop->setCreatedAt(new \DateTime());
            
            $keywords = array();
            for ($x = 1; $x < rand(3,6); $x++) {
                $keywords[] = $this->faker->word;
            }
            $shop->setMetaKeywords(implode(',', $keywords));
            
            $shop->setMetaDescription($this->faker->paragraph);
            //$user->setEnabled($this->faker->boolean());
            
            $manager->persist($shop);

            $this->setReference('Sylius.Shop-'.$i, $shop);
        }

        $manager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function getOrder()
    {
        return 2;
    }
}

<?php
/*
 * This file is part of the a Mlankatech (PTY) LTD Project.
 *
 * (c) Mfana Ronald Conco <ronald.conco@mlankatech.co.za>
 *
 * For the full copyright and license information, please view the LICENSE.
 *
 * Created At: 2016/09/30
 */

namespace AppBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use AppBundle\Entity\User;

class LoadAdminUserMxolisi extends AbstractFixture implements OrderedFixtureInterface,ContainerAwareInterface
{

    private $container;
    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {
        $service = $this->container->get('app.user.user_service');

        $user = new User();
        $user->setFirstName("Mxolisi");
        $user->setLastName("Khutama");
        $user->setEmail("mxolisi@theideahub.co.za");
        $user->setPlainPassword('654321');
        $user->setMsisdn('0721101642');
        $user->setGender($this->getReference('gender-female'));
        $user->setStatus($this->getReference('status-active'));
        $user->setUserGroup($this->getReference('group-admin'));
        $user->setIsAdmin(true);

        $service->createFixtureAdmin($user);
        $this->addReference('user-admin-mxolisi',$user);
    }

    /**
     * Get the order of this fixture
     *
     * @return integer
     */
    public function getOrder()
    {
        return 3;
    }
}
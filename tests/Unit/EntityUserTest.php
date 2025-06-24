<?php

namespace App\Tests\Unit;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\User;

class EntityUserTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $user = new User();
        $role = ["ROLE_USER"];

        $user->setRoles($role)
            ->setPassword('123456')
            ->setFirstname('Prénom')
            ->setLastname('Nom')
            ->setEmail('email@email.com')
            ->setUsername('usernameTest')
            ->setDateAdd(new \DateTimeImmutable())
        ;

        $errors = $container->get('validator')->validate($user);

        $this->assertCount(0, $errors);

    }

}

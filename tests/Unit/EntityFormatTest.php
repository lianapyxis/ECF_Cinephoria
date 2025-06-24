<?php

namespace App\Tests\Unit;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Entity\Format;

class EntityFormatTest extends KernelTestCase
{
    public function testEntityIsValid(): void
    {
        self::bootKernel();

        $container = static::getContainer();
        $em = self::$kernel->getContainer()->get('doctrine')->getManager();

        $format = new Format();
        $format->setTitle('Format 1')
            ->setDateAdd(new \DateTimeImmutable())
        ;

        $errors = $container->get('validator')->validate($format);

        $this->assertCount(0, $errors);

    }

}

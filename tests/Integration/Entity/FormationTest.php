<?php

use App\Entity\Formation;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidationRulesIntegrationTest extends KernelTestCase
{
    public function testControleDateAjoutFormation()
    {
        $formation1 = new Formation;
        $formation1->setPublishedAt(new DateTime("2022-04-01"));
        $this->assertErrors($formation1, 0);
    }
    
    public function assertErrors(Formation $formation, int $nbErreursAttendues)
    {
        self::bootKernel();
        $validator = self::getContainer()->get(ValidatorInterface::class);
        $error = $validator->validate($formation);
        $this->assertCount($nbErreursAttendues, $error);
    }
}

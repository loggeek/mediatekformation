<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Formation;

final class FormationUnitTest extends TestCase
{
    public function testGetPublishedAtString()
    {
        $formation = new Formation();
        $formation->setPublishedAt(new DateTime("2022-03-02"));
        $this->assertEquals("02/03/2022", $formation->getPublishedAtString());
    }
}

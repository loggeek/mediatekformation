<?php

use PHPUnit\Framework\TestCase;
use App\Entity\Formation;

final class FormationUnitTest extends TestCase
{
    public function test_getPublishedAtString()
    {
        $formation = new Formation();
        $formation->setPublishedAt(DateTime::createFromFormat("d/m/Y", "02/03/2022"));
        $this->assertEquals("02/03/2022", $formation->getPublishedAtString());
    }
}

<?php

use App\Entity\Formation;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class FormationRepositoryTest extends KernelTestCase
{
    private function getRepository(): FormationRepository
    {
        self::bootKernel();

        return self::getContainer()->get(FormationRepository::class);
    }

    private function createFormation(): Formation
    {
        $formation = new Formation();
        $formation->setPublishedAt(new \DateTime('2022-04-01'));
        $formation->setTitle("TEST");
        $formation->setDescription("TEST");

        return $formation;
    }

    public function testAdd()
    {
        $repository = $this->getRepository();
        $formation = $this->createFormation();
        
        $nbFormations = $repository->count([]);
        $repository->add($formation, true);
        
        $this->assertEquals($nbFormations + 1, $repository->count([]), "Erreur lors de l'ajout");
        
        $repository->remove($formation, true);
    }

    public function testRemove()
    {
        $repository = $this->getRepository();
        $formation = $this->createFormation();
        
        $repository->add($formation, true);
        $nbFormations = $repository->count([]);
        $repository->remove($formation, true);
        
        $this->assertEquals($nbFormations - 1, $repository->count([]), 'Erreur lors de la suppresion');
    }
    
    public function testFindAllOrderBy()
    {
        $repository = $this->getRepository();
        
        $formations = $repository->findAllOrderBy('id', 'ASC');
        
        $this->assertEquals('Eclipse n°8 : Déploiement', $formations[0]->getTitle());
    }
    
    public function testfindAllLasted()
    {
        $repository = $this->getRepository();
        $formation = $this->createFormation();
        
        $repository->add($formation, true);
        $formations = $repository->findAllLasted(1);
        
        $this->assertEquals(new \DateTime('2022-04-01'), $formations[0]->getPublishedAt());
        
        $repository->remove($formation, true);
    }

    public function testFindAllForOnePlaylist()
    {
        $repository = $this->getRepository();
        
        $formations = $repository->findAllForOnePlaylist(1);
        
        $this->assertEquals("Eclipse n°1 : installation de l'IDE", $formations[0]->getTitle());
    }
}

<?php

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class CategorieRepositoryTest extends KernelTestCase
{
    private function getRepository(): CategorieRepository
    {
        self::bootKernel();

        return self::getContainer()->get(CategorieRepository::class);
    }
    
    private function createCategorie(): Categorie
    {
        $categorie = new Categorie();
        $categorie->setName('TEST');

        return $categorie;
    }
    
    public function testAdd()
    {
        $repository = $this->getRepository();
        $nbCategories = $repository->count([]);
        
        $categorie = $this->createCategorie();
        $repository->add($categorie, true);
        
        $this->assertEquals($nbCategories + 1, $repository->count([]), "Erreur lors de l'ajout");
        
        $repository->remove($categorie, true);
    }
    
    public function testRemove()
    {
        $repository = $this->getRepository();
        $categorie = $this->createCategorie();
        
        $repository->add($categorie, true);
        $nbCategories = $repository->count([]);
        $repository->remove($categorie, true);
        
        $this->assertEquals($nbCategories - 1, $repository->count([]), 'Erreur lors de la suppresion');
    }
    
    public function testFindAllOrderBy()
    {
        $repository = $this->getRepository();
        
        $categories = $repository->findAllOrderBy('id', 'ASC');
        
        $this->assertEquals('Java', $categories[0]->getName());
    }

    public function testFindAllForOnePlaylist()
    {
        $repository = $this->getRepository();
        
        $categories = $repository->findAllForOnePlaylist(1);
        
        $this->assertEquals('C#', $categories[0]->getName());
    }
}

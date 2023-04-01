<?php

use App\Entity\Playlist;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class PlaylistRepositoryTest extends KernelTestCase
{
    private function getRepository(): PlaylistRepository
    {
        self::bootKernel();

        return self::getContainer()->get(PlaylistRepository::class);
    }
    
    private function createPlaylist(): Playlist
    {
        $playlist = new Playlist();
        $playlist->setName('TEST');
        $playlist->setDescription('TEST');

        return $playlist;
    }
    
    public function testAdd()
    {
        $repository = $this->getRepository();
        $nbPlaylists = $repository->count([]);
        
        $playlist = $this->createPlaylist();
        $repository->add($playlist, true);
        
        $this->assertEquals($nbPlaylists + 1, $repository->count([]), "Erreur lors de l'ajout");
        
        $repository->remove($playlist, true);
    }
    
    public function testRemove()
    {
        $repository = $this->getRepository();
        $playlist = $this->createPlaylist();
        
        $repository->add($playlist, true);
        $nbPlaylists = $repository->count([]);
        $repository->remove($playlist, true);
        
        $this->assertEquals($nbPlaylists - 1, $repository->count([]), 'Erreur lors de la suppresion');
    }
    
    public function testFindAllOrderByName()
    {
        $repository = $this->getRepository();
        
        $playlists = $repository->findAllOrderByName('ASC');
        
        $this->assertEquals('Cours Curseurs', $playlists[3]->getName());
    }

    public function testFindAllOrderByNbFormations()
    {
        $repository = $this->getRepository();
        
        $playlists = $repository->findAllOrderByNbFormations('DESC');
        
        $this->assertEquals('Bases de la programmation (C#)', $playlists[0]->getName());
    }
}

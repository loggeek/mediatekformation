<?php
namespace App\Controller\admin;

use App\Entity\Playlist;
use App\Form\PlaylistFormAjout;
use App\Form\PlaylistFormModif;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use App\Repository\PlaylistRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur back-office des playlists.
 */
class AdminPlaylistsController extends AbstractController
{
    private const PAGE_PLAYLISTS_ADMIN =  "admin/playlists.html.twig";
    private const ROUTE_PLAYLISTS_ADMIN = "admin.playlists";
    
    /**
     *
     * @var PlaylistRepository
     */
    private $playlistRepository;
    
    /**
     *
     * @var FormationRepository
     */
    private $formationRepository;
    
    /**
     *
     * @var CategorieRepository
     */
    private $categorieRepository;
    
    public function __construct(
        PlaylistRepository $playlistRepository,
        CategorieRepository $categorieRepository,
        FormationRepository $formationRespository
    ) {
        $this->playlistRepository = $playlistRepository;
        $this->categorieRepository = $categorieRepository;
        $this->formationRepository = $formationRespository;
    }
    
    /**
     * @Route("/admin/playlists", name="admin.playlists")
     * @return Response
     */
    public function index(): Response
    {
        $playlists = $this->playlistRepository->findAllOrderByName('ASC');
        $categories = $this->categorieRepository->findAll();
        
        return $this->render(self::PAGE_PLAYLISTS_ADMIN, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }

    /**
    * @Route("/admin/playlists/tri/{champ}/{ordre}", name="admin.playlists.sort")
    * @param type $champ
    * @param type $ordre
    * @return Response
    */
    public function sort($champ, $ordre): Response
    {
        switch ($champ) {
            case "nbformations":
                $playlists = $this->playlistRepository->findAllOrderByNbFormations($ordre);
                break;
            case "name":
            default:
                $playlists = $this->playlistRepository->findAllOrderByName($ordre);
        }
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_PLAYLISTS_ADMIN, [
            'playlists' => $playlists,
            'categories' => $categories
        ]);
    }
    
    /**
     * @Route("/admin/playlists/recherche/{champ}/{table}", name="admin.playlists.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response
    {
        $valeur = $request->get("recherche");
        $playlists = $this->playlistRepository->findByContainValueJoin($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        
        return $this->render(self::PAGE_PLAYLISTS_ADMIN, [
            'playlists' => $playlists,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }
    
    /**
     * @Route("/admin/playlists/suppr/{id}", name="admin.playlist.suppr")
     * @param Playlist $playlist
     * @return Response
     */
    public function suppr(Playlist $playlist): Response
    {
        $this->playlistRepository->remove($playlist, true);

        return $this->redirectToRoute(self::ROUTE_PLAYLISTS_ADMIN);
    }

    /**
     * @Route("/admin/playlists/modif/{id}", name="admin.playlist.modif")
     * @param Playlist $playlist
     * @param Request $request
     * @return Response
     */
    public function modif(Playlist $playlist, Request $request): Response
    {
        $formPlaylist = $this->createForm(PlaylistFormModif::class, $playlist);
        $formPlaylist->handleRequest($request);
        
        if ($formPlaylist->isSubmitted() && $formPlaylist->isValid()) {
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute(self::ROUTE_PLAYLISTS_ADMIN);
        }

        return $this->render('admin/playlist.modif.html.twig', [
                    'playlist' => $playlist,
                    'formPlaylist' => $formPlaylist->createView(),
        ]);
    }

    /**
     * @Route("/admin/playlists/ajout", name="admin.playlist.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response
    {
        $playlist = new Playlist();
        $formPlaylistAdd = $this->createForm(PlaylistFormAjout::class, $playlist);
        $formPlaylistAdd->handleRequest($request);
        
        if ($formPlaylistAdd->isSubmitted() && $formPlaylistAdd->isValid()) {
            $this->playlistRepository->add($playlist, true);
            return $this->redirectToRoute(self::ROUTE_PLAYLISTS_ADMIN);
        }

        return $this->render('admin/playlist.ajout.html.twig', [
                    'playlist' => $playlist,
                    'formPlaylistAdd' => $formPlaylistAdd->createView(),
        ]);
    }
}

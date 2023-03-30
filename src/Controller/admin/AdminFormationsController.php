<?php
namespace App\Controller\admin;

use App\Entity\Formation;
use App\Form\FormationForm;
use App\Repository\CategorieRepository;
use App\Repository\FormationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Contrôleur back-office des formations.
 */
class AdminFormationsController extends AbstractController
{
    private const PAGE_FORMATIONS_ADMIN = "admin/formations.html.twig";
    private const ROUTE_FORMATIONS_ADMIN = "admin.formations";
    
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
    
    public function __construct(FormationRepository $formationRepository, CategorieRepository $categorieRepository)
    {
        $this->formationRepository = $formationRepository;
        $this->categorieRepository= $categorieRepository;
    }
    
    /**
     * @Route("/admin/formations", name="admin.formations")
     */
    public function index(): Response
    {
        $formations = $this->formationRepository->findAll();
        $categories = $this->categorieRepository->findAll();

        return $this->render(self::PAGE_FORMATIONS_ADMIN, [
            'formations' => $formations,
            'categories' => $categories,
        ]);
    }
    
    /**
     * @Route("/admin/formations/tri/{champ}/{ordre}/{table}", name="admin.formations.sort")
     * @param type $champ
     * @param type $ordre
     * @param type $table
     * @return Response
     */
    public function sort($champ, $ordre, $table=""): Response
    {
        $formations = $this->formationRepository->findAllOrderByJoin($champ, $ordre, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_FORMATIONS_ADMIN, [
            'formations' => $formations,
            'categories' => $categories
        ]);
    }
    
    /**
     * @Route("/admin/formations/recherche/{champ}/{table}", name="admin.formations.findallcontain")
     * @param type $champ
     * @param Request $request
     * @param type $table
     * @return Response
     */
    public function findAllContain($champ, Request $request, $table=""): Response
    {
        $valeur = $request->get("recherche");
        $formations = $this->formationRepository->findByContainValueJoin($champ, $valeur, $table);
        $categories = $this->categorieRepository->findAll();
        return $this->render(self::PAGE_FORMATIONS_ADMIN, [
            'formations' => $formations,
            'categories' => $categories,
            'valeur' => $valeur,
            'table' => $table
        ]);
    }
    
    /**
     * @Route("/admin/formations/suppr/{id}", name="admin.formation.suppr")
     * @param Formation $formation
     * @return Response
     */
    public function suppr(Formation $formation): Response
    {
        $this->formationRepository->remove($formation, true);

        return $this->redirectToRoute(self::ROUTE_FORMATIONS_ADMIN);
    }

    /**
     * @Route("/admin/formations/modif/{id}", name="admin.formation.modif")
     * @param Formation $formation
     * @param Request $request
     * @return Response
     */
    public function modif(Formation $formation, Request $request): Response
    {
        $formFormation = $this->createForm(FormationForm::class, $formation);
        $formFormation->handleRequest($request);
        
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute(self::ROUTE_FORMATIONS_ADMIN);
        }

        return $this->render('admin/formation.modif.html.twig', [
            'formation' => $formation,
            'formFormation' => $formFormation->createView(),
        ]);
    }

    /**
     * @Route("/admin/formations/ajout", name="admin.formation.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response
    {
        $formation = new Formation();
        $formFormation = $this->createForm(FormationForm::class, $formation);
        $formFormation->handleRequest($request);
        
        if ($formFormation->isSubmitted() && $formFormation->isValid()) {
            $this->formationRepository->add($formation, true);
            return $this->redirectToRoute(self::ROUTE_FORMATIONS_ADMIN);
        }

        return $this->render('admin/formation.ajout.html.twig', [
            'formation' => $formation,
            'formFormation' => $formFormation->createView(),
        ]);
    }
}

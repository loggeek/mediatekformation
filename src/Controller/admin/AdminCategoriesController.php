<?php

namespace App\Controller\admin;

use App\Entity\Categorie;
use App\Form\CategorieForm;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminCategoriesController extends AbstractController
{
    private const PAGE_CATEGORIES_ADMIN = "admin/categories.html.twig";
    private const ROUTE_CATEGORIES_ADMIN = "admin.categories";

    /**
     * @var type CategorieRepository
     */
    private $categorieRepository;
    
        public function __construct(CategorieRepository $categorieRepository)
    {
        $this->categorieRepository = $categorieRepository;
    }

    /**
     * @Route("/admin/categories", name="admin.categories")
     * @return Response
     */
    public function index(): Response
    {
        $categories = $this->categorieRepository->findAllOrderBy('name', 'ASC');

        return $this->render(self::PAGE_CATEGORIES_ADMIN, [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/categories/tri/{champ}/{ordre}", name="admin.categories.sort")
     * @param type $champ
     * @param type $ordre
     * @return Response
     */
    public function sort($champ, $ordre): Response
    {
        $categories = $this->categorieRepository->findAllOrderBy($champ, $ordre);

        return $this->render(self::PAGE_CATEGORIES_ADMIN, [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/admin/categories/suppr/{id}", name="admin.categorie.suppr")
     * @param Categorie $categorie
     * @return Response
     */
    public function suppr(Categorie $categorie): Response
    {
        $this->categorieRepository->remove($categorie, true);

        return $this->redirectToRoute(self::ROUTE_CATEGORIES_ADMIN);
    }

    /**
     * @Route("/admin/categories/ajout", name="admin.categorie.ajout")
     * @param Request $request
     * @return Response
     */
    public function ajout(Request $request): Response
    {
        $categorie = new Categorie();
        $formCategorie = $this->createForm(CategorieForm::class, $categorie);
        $formCategorie->handleRequest($request);
        
        if ($formCategorie->isSubmitted() && $formCategorie->isValid()) {
            $this->categorieRepository->add($categorie, true);

            return $this->redirectToRoute(self::ROUTE_CATEGORIES_ADMIN);
        }

        return $this->render('admin/categorie.ajout.html.twig', [
            'categorie' => $categorie,
            'formCategorie' => $formCategorie->createView(),
        ]);
    }
}

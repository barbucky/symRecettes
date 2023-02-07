<?php

namespace App\Controller;

use App\Repository\IngredientRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientRepository $repository): Response
    {
        $ingredients = $repository->findAll();
        # dd($ingredients); "dd" correspond à un var_dump pour twig #

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients #je déclare que taper ingredients dans la vue ingrédient index correspond à la variable $ingredients
            #donc en tapant ingredients dans la vue, on fait appel à repository->find all
        ]);
    }
}

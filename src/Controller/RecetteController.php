<?php

namespace App\Controller;

use App\Entity\Recette;
use App\Form\RecetteType;
use App\Repository\RecetteRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RecetteController extends AbstractController
{
    #[Route('/recette', name: 'recette.index', methods:['GET'])]
    public function index(RecetteRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $recettes = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            5
        ); 
        return $this->render('pages/recette/index.html.twig', [
            'recettes' => $recettes
        ]);
    }
    #[Route('/recette/creation', name: 'recette.new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $recette = new Recette();

        $form = $this->createForm(RecetteType::class, $recette);
        
        $form -> handleRequest($request);
    /**
     * Ainsi, si le formulaire est soumis (cliqué sur le bouton submit)
     * et si il est valide (c'est à dire qu'il respecte les constraints entrées dans IngredientType)
     * alors on récupère les données
    */
        if($form->isSubmitted() && $form->isValid()){
        #On récupère la data du formulaire
            $recette = $form->getData();

        #On met cette data en base de données.
        #Tout d'abord, on persiste la donnée (on lui 'dit' qu'elle doit s'ajouter en BDD)
            $manager->persist($recette);

        #Puis on pousse la donnée préparée en BDD
            $manager->flush();

         #Message de confirmation de création
            $this->addFlash(
                'success',
                'Votre recette a été créée avec succès!'
            );
        
        /**
         * Gère la redirection de l'user vers la liste ingrédients après validation création 
         * (empêche de pouvoir rafraichir sur la création et interférer avec la bdd par ex)
         */
            return $this->redirectToRoute('recette.index');
            

    }

        return $this->render('pages/recette/new.html.twig', [
        'form'=>$form->createView() #Permet de créer, générer la vue       

        ]); 
    } 
    
    #[Route('/recette/edition{id}', name:'recette.edit', methods:['GET', 'POST'])]
    public function edit(Recette $recette, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(RecetteType::class, $recette);
        
        $form -> handleRequest($request);
    /**
     * Ainsi, si le formulaire est soumis (cliqué sur le bouton submit)
     * et si il est valide (c'est à dire qu'il respecte les constraints entrées dans IngredientType)
     * alors on récupère les données
    */
        if($form->isSubmitted() && $form->isValid()){
        #On récupère la data du formulaire
            $recette = $form->getData();

        #On met cette data en base de données.
        #Tout d'abord, on persiste la donnée (on lui 'dit' qu'elle doit s'ajouter en BDD)
            $manager->persist($recette);

        #Puis on pousse la donnée préparée en BDD
            $manager->flush();

         #Message de confirmation de création
            $this->addFlash(
                'success',
                'Votre recette a été modifiée avec succès!'
            );

            return $this->redirectToRoute('recette.index');
    }

    return $this->render('pages/recette/edit.html.twig', [
        'form'=>$form->createView() #Permet de créer, générer la vue       

        ]); 
   }
   #[Route('/recette/supression/{id}', name:'recette.delete', methods:['GET'])]
   public function delete(Recette $recette, EntityManagerInterface $manager): Response
   {
        $manager->remove($recette);
        $manager->flush();  
        $this->addFlash(
            'success',
            'Votre recette a été supprimée avec succès!'
        );
        return $this->redirectToRoute('recette.index');  
   }
}

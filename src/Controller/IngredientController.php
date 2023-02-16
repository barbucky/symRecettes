<?php

namespace App\Controller;

use App\Entity\Ingredient;
#!!! Bien penser à importer la classe du formulaire créé dans le controleur!!! 
use App\Form\IngredientType;
#!!!                                    !!!
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class IngredientController extends AbstractController
{
/**
 * Thos fonction displays all ingredients from the database
*/
    #[Route('/ingredient', name: 'app_ingredient', methods:['GET']) ]
    public function index(IngredientRepository $repository, PaginatorInterface $paginator, Request $request): Response
    {
        $ingredients = $paginator->paginate(
            $repository->findAll(),
            $request->query->getInt('page', 1),
            10
        );

        
        # dd($ingredients); "dd" correspond à un var_dump pour twig #

        return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' => $ingredients #je déclare que taper ingredients dans la vue ingrédient index correspond à la variable $ingredients
            #donc en tapant ingredients dans la vue, on fait appel à repository->find all
        ]);
    }
    /**
     * This controller display a form to create an ingredient
     */
    #[Route('/ingredient/nouveau', name:'ingredient.new', methods:['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $manager): Response
    {
        $ingredient = new Ingredient();

        $form = $this->createForm(IngredientType::class, $ingredient);
        
        $form -> handleRequest($request);
    /**
     * Ainsi, si le formulaire est soumis (cliqué sur le bouton submit)
     * et si il est valide (c'est à dire qu'il respecte les constraints entrées dans IngredientType)
     * alors on récupère les données
    */
        if($form->isSubmitted() && $form->isValid()){
        #On récupère la data du formulaire
            $ingredient = $form->getData();

        #On met cette data en base de données.
        #Tout d'abord, on persiste la donnée (on lui 'dit' qu'elle doit s'ajouter en BDD)
            $manager->persist($ingredient);

        #Puis on pousse la donnée préparée en BDD
            $manager->flush();
        
        #Message de confirmation d'ajout
            $this->addFlash(
                'success',
                'L\'ingrédient a bien été ajouté à la liste!');

        /**
         * Gère la redirection de l'user vers la liste ingrédients après validation création 
         * (empêche de pouvoir rafraichir sur la création et interférer avec la bdd par ex)
         */
            return $this->redirectToRoute('app_ingredient');
            
            
        }

        return $this->render('pages/ingredient/new.html.twig', [
            'form'=>$form->createView() #Permet de créer, générer la vue       

        ]);    

    }

    /**
     * This controller allow to update an ingredient 
     */
    #[Route('/ingredient/edition/{id}', name: 'ingredient.edit', methods:['GET', 'POST']) ]
    /**
     * Plusieurs façons de gérer l'update. 
     * La plus commune, paar intercéption du repository, lieu où est centralisé
     * tout ce qui touche à l'entity, ici Ingredient:
     *  CAS CLASSIQUE: 
     * public function edit(IngredientRepository $repository, int $id): Response
     * { $ingredient=$repository->findOneBy(["id" => $id]);
     *   $form = $this->createForm(IngredientType::class, $ingrdient);
     */

/**
 * CAS USAGE paramConverter
 * Grâce à symfony on peut simplement récuperer l'objet (Ingredient) en paramètre
 * Comme il existe le paramètre id dans l'objet Ingredient, 
 * il va fetch automatiquement l'élément qui a la valeur correspondante 
 * au placeholder (ici {id}) placé dans la route
 * 
 * 
 */
    public function edit(Ingredient $ingredient, Request $request, EntityManagerInterface $manager): Response
    {
        #dd($ingredient);
        $form = $this->createForm(IngredientType::class, $ingredient);
        $form -> handleRequest($request);    
        if($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();

            $manager->persist($ingredient);        
            $manager->flush();
        
        #Message de confirmation de modif
            $this->addFlash(
                'success',
                'La modification de votre ingrédient a bien été enregistrée !');

        /**
         * Gère la redirection de l'user vers la liste ingrédients après validation création 
         * (empêche de pouvoir rafraichir sur la création et interférer avec la bdd par ex)
         */
            return $this->redirectToRoute('app_ingredient');
            
            
        }
        return $this->render('pages/ingredient/edit.html.twig',[

        'form' => $form->createView()]);        
    }

    #[Route('/ingredient/suppression/{id}', name: 'ingredient.delete', methods:['GET'])]
    public function delete(EntityManagerInterface $manager, Ingredient $ingredient): Response
    {
        $manager->remove($ingredient);
        $manager->flush();

        $this->addFlash(
            'success',
            'Votre ingrédient a été supprimé avec succès !');


        return $this->redirectToRoute('app_ingredient');
    }
}

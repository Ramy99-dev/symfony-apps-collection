<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\ArticleRepository;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Article;


class ArticleController extends AbstractController
{
    #[Route('/article', name: 'app_article')]
    public function index(ArticleRepository $articleRepository): Response
    {

        $articles = $articleRepository->findAll();
       
        
        return $this->render('article/index.html.twig', [
            'articles' => $articles,
        ]);
    }
    #[Route('/article/new',name:'new_article')]
    public function new(Request $request , EntityManagerInterface $entityManager):Response
    {
        $article = new Article();
        $form = $this->createFormBuilder($article)
                     ->add('name',TextType::class)
                     ->add('price',TextType::class)
                     ->add('save',SubmitType::class , ['label'=>'Create Article'])
                     ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $article = $form->getData();
            $entityManager->persist($article);
            $entityManager->flush();
            return $this->redirectToRoute('app_article');
        }

        return $this->render('article/create.html.twig',['form'=>$form]);
    }
    #[Route('/article/{id}', name: 'app_single_article')]
    public function show( $id , ArticleRepository $articleRepository  ): Response
    {

        $article = $articleRepository->findBy(array("id"=>$id))[0];
       
        
        return $this->render('article/show.html.twig', [
            'article' => $article,
        ]);
    }
    
    
}

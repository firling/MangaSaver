<?php

namespace App\Controller;

use App\Entity\MangaList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class CreateController extends AbstractController
{
    #[Route('/create', name: 'create')]
    public function index(Request $request): Response
    {
        $mangaList = new MangaList();
        $userId = $this->getUser()->getId();
        $mangaList->setUserId($userId);

        $form = $this->createFormBuilder($mangaList)
            ->add('name', TextType::class, ['label' => 'Name of the Manga'])
            ->add('episode_url', TextType::class, ['label' => 'Episode\'s URL'])
            ->add('episode_num', IntegerType::class, ['label' => 'Episode\'s num'])
            ->getForm();

        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($mangaList);
            $entityManager->flush();
            // do anything else you need here, like send an email

            return $this->redirectToRoute('home');
        }

        return $this->render('create/index.html.twig', [
            'mangaListForm' => $form->createView(),
        ]);
    }
}

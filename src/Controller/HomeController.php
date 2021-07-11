<?php

namespace App\Controller;

use App\Entity\MangaList;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\MangaListRepository;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'home')]
    public function index(MangaListRepository $mangaListRepository): Response
    {
        $userId = $this->getUser()->getId();
        $mangas = $mangaListRepository->findBy(
            ['user_id' => $userId],
        );

        return $this->render('home/index.html.twig', [
            'mangas' => $mangas,
            'warning' => '',
            'success' => '',
        ]);
    }

    #[Route('/update', name: 'update')]
    public function update(Request $request, MangaListRepository $mangaListRepository): Response
    {
        $id = $request->get('id');
        $episodeNum = $request->get('episode_num');
        $episodeUrl = $request->get('episode_url');
        $autoUpdateUrl = $request->get('auto_update_url');

        $em = $this->getDoctrine()->getManager();
        $manga = $em->getRepository(MangaList::class)->find($id);

        if (!$manga) {
            throw $this->createNotFoundException(
                'No manga found for id '.$id
            );
        }

        $userId = $this->getUser()->getId();
        if ($manga->getUserId() != $userId) {
            throw $this->createNotFoundException(
                'This is doesn\'t match with your user id'
            );
        }

        $oldNum = $manga->getEpisodeNum();

        $warning = "";

        $manga->setEpisodeNum($episodeNum);
        if(isset($autoUpdateUrl)) {
            $oldUrl = $manga->getEpisodeUrl();
            if (strpos($oldUrl, $oldNum)) {
                $newUrl = str_replace(strval($oldNum), $episodeNum, $oldUrl);
                $manga->setEpisodeUrl($newUrl);
            } else {
                $manga->setEpisodeUrl($episodeUrl);
                $warning = "Manga has been but the URL auto update didn't work.";
            }
        } else {
            $manga->setEpisodeUrl($episodeUrl);
        }

        $em->flush();

        $mangas = $mangaListRepository->findBy(
            ['user_id' => $userId],
        );

        return $this->render('home/index.html.twig', [
            'mangas' => $mangas,
            'warning' => $warning,
            'success' => 'Manga has been updated successfully'
        ]);
    }
    
    #[Route('/delete/{id}', name: 'delete')]
    public function delete(Request $request): Response
    {
        $id = $request->get('id');

        $em = $this->getDoctrine()->getManager();
        $manga = $em->getRepository(MangaList::class)->find($id);

        if (!$manga) {
            throw $this->createNotFoundException(
                'No manga found for id '.$id
            );
        }

        $userId = $this->getUser()->getId();
        if ($manga->getUserId() != $userId) {
            throw $this->createNotFoundException(
                'This is doesn\'t match with your user id'
            );
        }

        $em->remove($manga);
        $em->flush();   

        return $this->redirectToRoute('home');
    }
}

<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\Sheet;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sheets", name="sheet")
 */
class SheetController extends AbstractController
{
    /**
     * @Route("/", name="_index", methods={"GET"})
     */
    public function getAllSheet(): JsonResponse
    {
        $sheet = $this->getDoctrine()->getRepository(Sheet::class)->findAll();
        return new JsonResponse($sheet);
    }

    /**
     * @Route("/{id}", name="_show", requirements={"id": "/d+"}, methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function getOneSheet($id): JsonResponse
    {
        $sheet = $this->getDoctrine()->getRepository(Sheet::class)->find($id);
        return new JsonResponse($sheet, 200);
    }

    /**
     * @Route("/add", name="_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $sheet = new Sheet();
        $language = $this->getDoctrine()
            ->getRepository(Language::class)
            ->find($request->request->get('language'));
        $user = $this->getDoctrine()
        ->getRepository(User::class)
        ->find($request->request->get('user'));
        $entityManager = $this->getDoctrine()
            ->getManager();
        $sheet->setTitle($request->request->get('title'))
            ->setDescription($request->request->get('description'))
            ->setContent($request->request->get('content'))
            ->setCreateAt(new \DateTime())
            ->setLanguage($language)
            ->setUser($user);
        $entityManager->persist($sheet);
        $entityManager->flush();

        return new JsonResponse('Votre sheet à bien était créer', 201);
    }

    /**
     * @Route("/update/{id}", name="_update", requirements={"id": "\d+"}, methods={"PUT"})
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request,int $id): JsonResponse
    {
        $sheet = $this->getDoctrine()
            ->getRepository(Sheet::class)
            ->find($id);
        $entityManager = $this->getDoctrine()
            ->getManager();
        $sheet->setTitle($request->request->get('title'))
            ->setDescription($request->request->get('description'))
            ->setContent($request->request->get('content'))
            ->setUpdateAt(new \DateTime());
        if ($sheet->getLanguage()->getId() !== $request->request->get('language')) {
            $language = $this->getDoctrine()
                ->getRepository(Language::class)
                ->find($request->request->get('language'));
            $sheet->setLanguage($language);
        }
        $entityManager->persist($sheet);
        $entityManager->flush();
        return new JsonResponse('Le sheet à bien était modifier', 200);
    }

    /**
     * @param $id
     * @Route("/delete/{id}", name="_delete", requirements={"id": "\d+"}, methods={"DELETE"})
     * @return JsonResponse
     */
    public function delete($id):JsonResponse
    {
        $sheet = $this->getDoctrine()->getRepository(Sheet::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($sheet);
        $entityManager->flush();

        return new JsonResponse('Le sheet à bien était supprimé', 200);
    }
}

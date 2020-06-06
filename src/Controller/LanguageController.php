<?php

namespace App\Controller;

use App\Entity\Language;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/language", name="language")
 */
class LanguageController extends AbstractController
{
    /**
     * @Route("/", name="_index", methods={"GET"})
     */
    public function getAllLanguage(): JsonResponse
    {
        $languages = $this->getDoctrine()
            ->getRepository(Language::class)
            ->findAll();

        return new JsonResponse($languages);
    }

    /**
     * @Route("/{id}", name="_show", requirements={"id", "/d+"} methods={"GET"})
     * @param $id
     * @return JsonResponse
     */
    public function getOneLanguage($id): JsonResponse
    {
        $language = $this->getDoctrine()->getRepository(Language::class)->find($id);
        return new JsonResponse($language);
    }

    /**
     * @Route("/add", name="_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function createLanguage(Request $request): JsonResponse
    {
        $language = new Language();
        $entityManager = $this->getDoctrine()
            ->getManager();
        $language->setName($request->request->get('name'))
            ->setColor('blue')
            ->setIsValid(false)
            ->setCreateAt(new \DateTime());
        $entityManager->persist($language);
        $entityManager->flush();
        return new JsonResponse('Votre language à bien était créer', 201);
    }

    /**
     * @Route("/update/{id}", name="_update", requirements={"id", "\d+"}, methods={"PUT"})
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $language = $this->getDoctrine()
            ->getRepository(Language::class)
            ->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $language->setName($request->request->get('name'))
            ->setColor($request->request->get('color'))
            ->setImage($request->request->get('name'))
            ->setUpdateAt(new \DateTime());
        $entityManager->persist($language);
        $entityManager->flush();
        return new JsonResponse('Votre langage à bien était modifier');
    }
    /**
     * @param $id
     * @Route("/delete/{id}", name="_delete", requirements={"id", "\d+"}, methods={"DELETE"})
     * @return JsonResponse
     */
    public function delete(int $id): JsonResponse
    {
        $sheet = $this->getDoctrine()->getRepository(Language::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($sheet);
        $entityManager->flush();

        return new JsonResponse('Le langage à bien était supprimé', 200);
    }
}

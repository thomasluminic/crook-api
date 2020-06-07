<?php

namespace App\Controller;

use App\Entity\Language;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/language", name="language")
 */
class LanguageController extends BaseController
{
    /**
     * @Route("/", name="_index", methods={"GET"})
     */
    public function getAllLanguage(): JsonResponse
    {
        return new JsonResponse($this->findAll(self::LANGUAGE_REPOSITORY));
    }

    /**
     * @Route("/{id}", name="_show", requirements={"id": "[0-9]+"}, methods={"GET"})
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function getOneLanguage(int $id): JsonResponse
    {
            $language = $this->findOne(self::LANGUAGE_REPOSITORY, $id, 'langage');
            if ($language) {
                return new JsonResponse($language);
            }
            exit;
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
     * @Route("/update/{id}", name="_update", requirements={"id": "[0-9]+"}, methods={"PUT"})
     * @param Request $request
     * @param int $id
     * @throws \Exception
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $language = $this->findOne(self::LANGUAGE_REPOSITORY, $id, self::TYPE_LANGUAGE);
        if ($language) {
            $entityManager = $this->getDoctrine()->getManager();
            $language->setName($request->request->get('name'))
                ->setColor($request->request->get('color'))
                ->setImage($request->request->get('name'))
                ->setUpdateAt(new \DateTime());
            $entityManager->persist($language);
            $entityManager->flush();
            return new JsonResponse('Votre langage à bien était modifier');
        }
        exit;
    }

    /**
     * @param $id
     * @Route("/delete/{id}", name="_delete", requirements={"id": "[0-9]+"}, methods={"DELETE"})
     * @throws \Exception
     * @return JsonResponse
     */
    public function deleteLanguage(int $id): JsonResponse
    {
        $isRemoved = $this->delete(self::LANGUAGE_REPOSITORY, $id, self::TYPE_LANGUAGE);
        if ($isRemoved) {
            return new JsonResponse('Le '. self::TYPE_LANGUAGE .' à bien était supprimer');
        }
        exit;
    }
}

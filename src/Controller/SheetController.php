<?php

namespace App\Controller;

use App\Entity\Sheet;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/sheets", name="sheet")
 */
class SheetController extends BaseController
{
    /**
     * @Route("/", name="_index", methods={"GET"})
     */
    public function getAllSheet(): JsonResponse
    {
        return new JsonResponse($this->findAll(self::SHEET_REPOSITORY));
    }

    /**
     * @Route("/{id}", name="_show", requirements={"id": "/d+"}, methods={"GET"})
     * @param $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function getOneSheet($id): JsonResponse
    {
        return new JsonResponse($this->findOne(self::SHEET_REPOSITORY, $id, self::TYPE_SHEET));
    }

    /**
     * @Route("/add", name="_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     * @throws \Exception
     */
    public function create(Request $request): JsonResponse
    {
        $language = $this->findOne(
            self::LANGUAGE_REPOSITORY,
            $request->request->get('language'),
            self::TYPE_SHEET);
        if ($language) {
            $user = $this->getUser();
            $sheet = new Sheet();
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
        exit;
    }

    /**
     * @Route("/update/{id}", name="_update", requirements={"id": "[0-9]+"}, methods={"PUT"})
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $sheet = $this->findOne(self::SHEET_REPOSITORY, $id, self::TYPE_SHEET);
        if ($sheet) {
            $entityManager = $this->getDoctrine()
                ->getManager();
            $sheet->setTitle($request->request->get('title'))
                ->setDescription($request->request->get('description'))
                ->setContent($request->request->get('content'))
                ->setUpdateAt(new \DateTime());
            if ($sheet->getLanguage()->getId() !== $request->request->get('language')) {
                $language = $this->findOne(self::LANGUAGE_REPOSITORY, $id, self::TYPE_LANGUAGE);
                $sheet->setLanguage($language);
            }
            $entityManager->persist($sheet);
            $entityManager->flush();
            return new JsonResponse('Le sheet à bien était modifier', 200);
        }
        exit;
    }

    /**
     * @param $id
     * @Route("/delete/{id}", name="_delete", requirements={"id": "[0-9]+"}, methods={"DELETE"})
     * @throws \Exception
     * @return JsonResponse
     */
    public function deleteSheet(int $id): JsonResponse
    {
        $isRemoved = $this->delete(self::SHEET_REPOSITORY, $id, self::TYPE_SHEET);
        if ($isRemoved) {
            return new JsonResponse('Le '. self::TYPE_SHEET .' à bien était supprimer');
        }
        exit;
    }
}

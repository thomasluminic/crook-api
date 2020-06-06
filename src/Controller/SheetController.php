<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\Sheet;
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
     * @Route("/add", name="_create", methods={"PUT"})
     */
    public function create(Request $request): JsonResponse
    {

    }
}

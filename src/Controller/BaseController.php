<?php

namespace App\Controller;

use App\Entity\Language;
use App\Entity\Sheet;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class BaseController extends AbstractController
{
    const SHEET_REPOSITORY = Sheet::class;
    const USER_REPOSITORY = User::class;
    const LANGUAGE_REPOSITORY = Language::class;
    const TYPE_SHEET = 'sheet';
    const TYPE_USER = 'utilisateur';
    const TYPE_LANGUAGE = 'langage';

    public function findAll(string $repository): object
    {
        return $this->getDoctrine()->getRepository($repository)->findAll();
    }

    public function findOne(string $repository, int $id, string $type): object
    {
        $findOne = $this->getDoctrine()->getRepository($repository)->find($id);
        if (!$findOne) {
            throw new \Exception($type, 404);
        }
        return $findOne;
    }

    public function delete(string $repository, int $id, string $type): bool
    {
        $remove = $this->findOne($repository, $id, $type);
        if ($remove) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($remove);
            $entityManager->flush();

            return true;
        }
        exit;
    }

}

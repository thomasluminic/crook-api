<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users", name="users")
 */
class UserController extends BaseController
{
    private UserPasswordEncoderInterface $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->passwordEncoder = $passwordEncoder;
    }

    /**
     * @Route("/", name="_index")
     * @return JsonResponse
     */
    public function getAllUsers(): JsonResponse
    {
        return new JsonResponse($this->findAll(self::USER_REPOSITORY));
    }

    /**
     * @Route("/{id}", name="_show", requirements={"id": "[0-9]+"}, methods={"GET"})
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     */
    public function getOneUser(int $id): JsonResponse
    {
        return new JsonResponse($this->findOne(self::USER_REPOSITORY, $id, self::TYPE_USER));
    }

    /**
     * @Route("/add", name="_create", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request): JsonResponse
    {
        $user = new User();
        $entityManager = $this->getDoctrine()->getManager();
        $user->setPseudo($request->request->get('pseudo'))
            ->setEmail($request->request->get('email'))
            ->setPassword($this->encodePassword($user, $request->request->get('password')))
            ->setCreateAt(new \DateTime());
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse('Votre compte à bien était créer', 201);
    }

    /**
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     * @throws \Exception
     * @Route("/edit/{id}", name="_edit", requirements={"id": "[0-9]+"}, methods={"PUT"})
     */
    public function edit(Request $request, int $id): JsonResponse
    {
        $user = $this->findOne(self::USER_REPOSITORY, $id, self::TYPE_USER);
        if ($user) {
            $entityManager = $this->getDoctrine()
                ->getManager();
            $user->setPseudo($request->request->get('pseudo'))
                ->setEmail($request->request->get('email'))
                ->setUpdateAt(new \DateTime());
            $entityManager->persist($user);
            $entityManager->flush();
            return new JsonResponse('L\'utilisateur à bien était modifier');
        }
        exit;
    }

    /**
     * @param $id
     * @Route("/delete/{id}", name="_delete", requirements={"id": "[0-9]+"}, methods={"DELETE"})
     * @return JsonResponse
     * @throws \Exception
     */
    public function deleteUser(int $id): JsonResponse
    {
        $isRemoved = $this->delete(self::USER_REPOSITORY, $id, self::TYPE_USER);
        if ($isRemoved) {
            return new JsonResponse('L\'utilisateur à bien était supprimer');
        }
        exit;
    }

    public function encodePassword($user, string $password): string
    {
        return $this->passwordEncoder->encodePassword($user, $password);
    }
}

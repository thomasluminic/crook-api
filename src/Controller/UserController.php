<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/users", name="users")
 */
class UserController extends AbstractController
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
        $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        return new JsonResponse($users);
    }

    /**
     * @Route("/{id}", name="_show", requirements={"id": "/d+"}, methods={"GET"})
     * @param int $id
     * @return JsonResponse
     */
    public function getOneUser(int $id): JsonResponse
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        return new JsonResponse($user);
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
     * @Route("/edit", name="_edit", methods={"PUT"})
     * @return JsonResponse
     */
    public function edit(Request $request): JsonResponse
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($request->request->get('id'));
        $entityManager = $this->getDoctrine()
            ->getManager();
        $user->setPseudo($request->request->get('pseudo'))
            ->setEmail($request->request->get('email'))
            ->setUpdateAt(new \DateTime());
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse('L\'utilisateur à bien était modifier');
    }

    /**
     * @param $id
     * @Route("/delete/{id}", name="_delete", requirements={"id": "/d+"}, methods={"DELETE"})
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        $user = $this->getDoctrine()->getRepository(User::class)->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return new JsonResponse('L\'utilisateur à bien était modifier');
    }

    public function encodePassword($user, $password)
    {
        return $this->passwordEncoder->encodePassword($user, $password);
    }
}

<?php

namespace App\Controller;

use App\Entity\ApiToken;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

#[Route('/api')]
class SecurityController extends AbstractController
{

    private UserRepository $userRepository;
    private EntityManagerInterface $entityManager;

    public function __construct(UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
    }

    #[Route('/login', name: 'login', methods: ["POST"])]
    public function loginAction(Request $request, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $data = json_decode($request->getContent(), true);
        $email = $data['email'] ?? null;
        $password = $data['password'] ?? null;
        if (!$email || !$password) {
            return $this->json([
                'message' => 'Email or password cannot be null.',
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        $user = $this->userRepository->findOneBy(['email' => $email]);
        if (!$user) {
            return $this->json([
                'message' => "Bad credentials.",
                // 'message' => "No user with email $email.",
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        $isValid = $passwordEncoder->isPasswordValid($user, $password);
        if (!$isValid) {
            return $this->json([
                'message' => "Bad credentials.",
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        $token = new ApiToken($user);
        $this->entityManager->persist($token);
        $this->entityManager->flush();
        return $this->json([
            'token' => $token->getToken(),
            'user' => $user->getRoles()
        ], JsonResponse::HTTP_OK, [], ['groups' => 'default']);
    }


    #[Route('/register', name: 'create_user', methods: ["POST"])]
    public function createUser(Request $request, UserPasswordHasherInterface $passwordEncoder): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        $form->submit($data);
        if ($form->isSubmitted() && !$form->isValid()) {
            return $this->json(['errors' => $form->getErrors(true, true)], JsonResponse::HTTP_BAD_REQUEST);
        }
        $clearPassword = $data['password'];
        $user->setPassword($passwordEncoder->hashPassword(
            $user,
            $clearPassword
        ));
        $api = new ApiToken($user);
        $user->addApiToken($api);
        $this->entityManager->persist($user);
        $this->entityManager->flush();
        return $this->json([
            'token' => $api->getToken(),
            'user' => $user
        ], JsonResponse::HTTP_CREATED, [], ['groups' => ['default']]);
    }

    // /**
    //  * @Route("/login/test", name="app_login_test")
    //  */
    // public function loginTest(): Response
    // {
    //     return $this->json(['test' => "ok"]);
    // }
    // /**
    //  * @Route("/login", name="app_login")
    //  */
    // public function login(AuthenticationUtils $authenticationUtils): Response
    // {
    //     // if ($this->getUser()) {
    //     //     return $this->redirectToRoute('target_path');
    //     // }

    //     dd("FDP");
    //     // get the login error if there is one
    //     $error = $authenticationUtils->getLastAuthenticationError();
    //     // last username entered by the user
    //     $lastUsername = $authenticationUtils->getLastUsername();

    //     return $this->json(['last_username' => $lastUsername, 'error' => $error]);
    // }

    // /**
    //  * @Route("/logout", name="app_logout")
    //  */
    // public function logout()
    // {
    //     throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    // }
}

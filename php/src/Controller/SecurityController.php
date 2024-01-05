<?php

namespace App\Controller;

use App\Entity\User;
use OpenApi\Annotations as OA;
use App\Repository\UserRepository;
use App\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 *  
 * @OA\Post(
 *  path="/api/v1/login_check",
 *  tags={"Securities"},
 *  @OA\RequestBody(
 *      request="Login",
 *      description="Corp de la requete",
 *      required=true,
 *      @OA\JsonContent(
 *          @OA\Property(type="string", property="username", example="coucou@exemple.com"),
 *          @OA\Property(type="string", property="password", required=true, example="emileA15ans"),
 *      )
 *  ), 
 * 
 *  @OA\Response(
 *      response="200",
 *      description="Authentification",
 *      @OA\JsonContent(
 *          allOf={@OA\Schema(ref="#/components/schemas/Success")},
 *          @OA\Property(type="objet", property="data", ref="#/components/schemas/Login"),
 *      ),
 *  ),
 * 
 *  @OA\Response( response="400", ref="#/components/responses/BadRequest" ),
 *  @OA\Response( response="403", ref="#/components/responses/ForBidden" ),
 *  @OA\Response( response="404", ref="#/components/responses/NotFound" ),
 * 
 * )
 * 
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/register", name="security_register", methods={"POST"})
     * 
     * @param Request $request
     * @param ValidatorInterface $validator
     * @param UserPasswordHasherInterface $passwordEncoder
     * @return JsonResponse
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {
        // Decode JSON data
        $data = json_decode($request->getContent(), true);

        // Validate data using Symfony's validator
        $user = new User();
        $user->setEmail($data['email']);
        $user->setRoles(['ROLE_USER']);
        $user->setPassword($data['password']);

        // Valider l'entité User
        if ($response = $this->validateEntity($user)) {
            return $this->json($response, $response["status"]);
        }

        // Encode the password
        $encodedPassword = $passwordHasher->hashPassword($user, $user->getPassword());
        $user->setPassword($encodedPassword);

        // Save the user to the database (you may need to adjust this based on your setup)
        $entityManager = $this->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        // creation du token et initialisation dans le attribut password
        $user->setToken($this->jWTManager->create($user));
        $response = $this->statusCode(Response::HTTP_CREATED, $user);

        return $this->json($response, $response["status"], [], ["groups" => "read:auth:item"]);
    }

    /**
     * Réinitialise les données de connecxion de l'utilisateur.
     *
     * @Route("/users/update", name="security_reset", methods={"PUT"})
     * 
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @return JsonResponse
     */
    public function reset(Request $request, UserPasswordHasherInterface $passwordHasher): JsonResponse
    {

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $data = json_decode($request->getContent());

        /** @var UserInterface $user */
        $user = $this->getUser();

        // Créer une instance pour vérifier les nouvelles données
        $userForCheck = new User();
        $userForCheck->setEmail($data->newemail ?? $user->getEmail());
        $userForCheck->setPassword($data->newpassword ?? $user->getPassword());
        $userForCheck->setRoles(['ROLE_USER']);

        // Valider l'entité User
        if ($response = $this->validateEntity($userForCheck)) {
            return $this->json($response, $response["status"]);
        }

        // Persistez les données dans la base de données.
        if (isset($data->newemail)) {
            $user->setEmail($data->newemail);
        }

        if (isset($data->newpassword)) {
            $user->setPassword($passwordHasher->hashPassword($user, $data->newpassword));
        }

        $this->getManager()->persist($user);
        $this->getManager()->flush();

        // Créer le token et l'initialiser dans l'attribut password
        $user->setToken($this->jWTManager->create($user));
        $response = $this->statusCode(Response::HTTP_OK, $user);

        return $this->json($response, $response["status"], [], ["groups" => "read:auth:item"]);
    }

    /**
     * Réinitialise le mot de passe de l'utilisateur.
     *
     * @Route("/users/recovery", name="security_reset_password", methods={"POST"})
     * 
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function resetPassword(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): JsonResponse
    {
        // Decode JSON data
        $data = json_decode($request->getContent(), true);

        // Récupérer les données de la requête
        $email = $data['username'];
        $password = $data['password'];
        $repassword = $data['repassword'];

        // Vérifier si le mot de passe et la confirmation du mot de passe sont identiques
        if ($password !== $repassword) {
            $response = $this->statusCode(Response::HTTP_UNPROCESSABLE_ENTITY, ['message' => 'Les mots de passe ne correspondent pas.']);
            return $this->json($response, $response['status']);
        }


        // Récupérer l'utilisateur par email
        $user = $userRepository->findOneBy(['email' => $email]);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND, ['message' => 'Utilisateur non trouvé.']);
            return $this->json($response, $response['status']);
        }

        // Valider l'entité User
        $user->setPassword($password);
        if ($response = $this->validateEntity($user)) {
            return $this->json($response, $response['status']);
        }

        // Encoder et définir le nouveau mot de passe
        $hashPassword = $passwordHasher->hashPassword($user, $password);
        $user->setPassword($hashPassword);

        // Enregistrer les modifications dans la base de données
        $this->getManager()->flush();

        // Créer le token et l'initialiser dans l'attribut password
        $user->setToken($this->jWTManager->create($user));
        $response = $this->statusCode(Response::HTTP_OK, $user);

        return $this->json($response, $response['status'], [], ["groups" => "read:auth:item"]);
    }

    /**
     * Supprime l'utilisateur en fonction de l'e-mail et du mot de passe fournis.
     * 
     * @Route("/users", name="security_delete", methods={"DELETE"})
     *
     * @param Request $request
     * @param UserPasswordHasherInterface $passwordHasher
     * @param UserRepository $userRepository
     * @return JsonResponse
     */
    public function delete(Request $request, UserPasswordHasherInterface $passwordHasher, UserRepository $userRepository): JsonResponse
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        // Decode JSON data
        $data = json_decode($request->getContent(), true);


        // Récupérer les données de la requête
        $email = $data['username'];
        $password = $data['password'];

        // Récupérer l'utilisateur par email
        $user = $userRepository->findOneBy(['email' => $email]);

        // Vérifier si l'utilisateur existe
        if (!$user) {
            $response = $this->statusCode(Response::HTTP_NOT_FOUND, ['message' => 'Utilisateur non trouvé.']);
            return $this->json($response, $response['status']);
        }

        // Vérifier si le mot de passe fourni correspond au mot de passe de l'utilisateur
        if (!$passwordHasher->isPasswordValid($user, $password)) {
            $response = $this->statusCode(Response::HTTP_UNAUTHORIZED, ['message' => 'Mot de passe incorrect.']);
            return $this->json($response, $response['status']);
        }

        // Vérifier si l'utilisateur à supprimer est le même que l'utilisateur actuellement authentifié
        $currentUser = $this->getUser();
        if ($user->getId() !== $currentUser->getId()) {
            throw new AccessDeniedException('Vous n\'êtes pas autorisé à supprimer cet utilisateur.');
        }

        // Supprimer l'utilisateur de la base de données
        $entityManager = $this->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        $response = $this->statusCode(Response::HTTP_OK, ['message' => 'Utilisateur supprimé avec succès.']);
        return $this->json($response, $response['status']);
    }


    /**
     * List Users
     *
     * Cette méthode permet de récupérer la liste des utilisateurs.
     *
     * @Route("/users", name="list_users", methods={"GET"})
     * 
     * @param UserRepository $userRepository Le repository des utilisateurs.
     * @return JsonResponse La réponse au format JSON contenant la liste des utilisateurs.
     */
    public function list(UserRepository $userRepository): JsonResponse
    {
        $users = $userRepository->findAll();
        $response = $this->statusCode(Response::HTTP_OK, $users);
        return $this->json($response, $response["status"], [], ["groups" => "read:auth:list"]);
    }
}

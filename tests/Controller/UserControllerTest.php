<?php

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserControllerTest extends WebTestCase
{
    public function testDeleteUser(): void
    {
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'testicule@gmail.com', // Remplacez 'username' par le nom d'utilisateur approprié
            'PHP_AUTH_PW' => 'couCou@1234',   // Remplacez 'password' par le mot de passe approprié
        ]);

        // Créer un nouvel utilisateur pour les besoins du test
        $entityManager = $client->getContainer()->get('doctrine')->getManager();
        $user = new App\Entity\User();
        // Définir les propriétés de l'utilisateur si nécessaire
        $entityManager->persist($user);
        $entityManager->flush();

        // Récupérer l'ID de l'utilisateur créé pour le test
        $userId = $user->getId();

        // Envoyer une requête DELETE pour supprimer l'utilisateur
        $client->request('DELETE', '/users/' . $userId, [], [], ['CONTENT_TYPE' => 'application/json']);

        // Vérifier que la réponse est OK (200)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Vérifier que l'utilisateur a bien été supprimé de la base de données
        $deletedUser = $entityManager->getRepository(App\Entity\User::class)->find($userId);
        $this->assertNull($deletedUser, 'L\'utilisateur devrait être supprimé de la base de données.');
    }
}

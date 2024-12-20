<?php

namespace App\Services;

use App\Exception\DTOError;
// use Symfony\Component\Validator\Validation;
// use Symfony\Component\Validator\Mapping\ClassMetadata;
// use Symfony\Component\Validator\Mapping\Loader\AnnotationLoader;

class DTOServices
{
    static function initializer(string $className, ...$params): object
    {
        // Vérifie si la classe existe
        if (!class_exists($className)) {
            throw new \InvalidArgumentException("La classe spécifiée ($className) n'existe pas.");
        }
        try {
            // Crée une nouvelle instance de la classe spécifiée avec les paramètres fournis
            $object = new $className(...$params);
        } catch (\Throwable $th) {
            // Lancez votre exception personnalisée
            throw new DTOError($th);
        }
        // Retourne l'objet initialisé
        return $object;
    }

    // static function validator(string $className, $dto)
    // {
    //     // Créez une instance du Validator
    //     $validator = Validation::createValidatorBuilder()
    //         ->enableAnnotationMapping() // Activer la cartographie d'annotations
    //         ->getValidator();

    //     // Créez un chargeur d'annotations pour charger les contraintes à partir des annotations de classe
    //     $loader = new AnnotationLoader();

    //     // Chargez les contraintes à partir des annotations de classe DTO
    //     $classMetadata = new ClassMetadata($className);
    //     $loader->loadClassMetadata($classMetadata);

    //     // Validation de l'objet DTO par rapport aux contraintes récupérées
    //     $errors = $validator->validate($dto, null, $classMetadata);

    //     // Vérifiez s'il y a des erreurs de validation
    //     if (count($errors) > 0) {
    //         // Il y a des erreurs de validation
    //         $violatedConstraints = [];
    //         foreach ($errors as $error) {
    //             $violatedConstraints[] = [
    //                 'field' => $error->getPropertyPath(),
    //                 'message' => $error->getMessage(),
    //             ];
    //         }
    //         return $violatedConstraints;
    //     }
    //     return [];
    // }
}

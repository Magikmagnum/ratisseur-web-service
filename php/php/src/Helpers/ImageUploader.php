<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;


enum MessageErreur: string
{
    case REPERTOIRE_INEXISTANT = 'Le répertoire "%s" est inexistant.';
    case FICHIER_INTROUVABLE = 'Le fichier "%s" est introuvable dans le répertoire.';
    case CREATION_REPERTOIRE_IMPOSSIBLE = 'Impossible de créer le répertoire "%s".';
    case SUPPRESSION_FICHIER_IMPOSSIBLE = 'Impossible de supprimer le fichier "%s".';
    case TELECHARGEMENT_ECHOUE = 'Le téléchargement du fichier a échoué : %s';
}
class ImageUploader
{

    /**
     * Uploder une image existante.
     *
     * @param UploadedFile $uploadedFile
     * @param string $directory Répertoire de destination.
     * @param string|null $customName Nom personnalisé pour la nouvelle image.
     * @param string|null $oldImageName Nom de l'ancienne image à remplacer.
     * @return string Nom de la nouvelle image.
     * @throws \RuntimeException En cas d'erreur.
     */
    public function upload(UploadedFile $uploadedFile, string $directory, ?string $customName = null, ?string $oldImageName = null): string
    {
        // Supprime l'ancienne image
        if ($oldImageName) {
            $this->delete($oldImageName, $directory);
        }

        // Sauvegader la nouvelle image
        return $this->save($uploadedFile, $directory, $customName);
    }

    /**
     * Sauvegader une image en définissant son nom et son chemin.
     *
     * @param UploadedFile $uploadedFile
     * @param string|null $customDirectory Sous-répertoire personnalisé dans le répertoire d'upload. Si null, le répertoire par défaut est utilisé.
     * @param string|null $customName Nom personnalisé (sans extension). Si null, le nom original est utilisé.
     * @return string Nom relatif du fichier (à stocker en base de données).
     * @throws \RuntimeException En cas d'erreur pendant l'upload.
     */
    public function save(UploadedFile $uploadedFile, string $directory, ?string $customName = null): string
    {
        // Génère un nom basé sur le nom personnalisé ou le nom original
        $originalFilename = $customName ?? pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);
        // Génère un nom unique pour éviter les conflits
        $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        try {
            // Crée le répertoire de destination s'il n'existe pas
            if (!is_dir($directory) && !mkdir($directory, 0755, true) && !is_dir($directory)) {
                throw new \RuntimeException(sprintf(MessageErreur::CREATION_REPERTOIRE_IMPOSSIBLE->value, $directory));
            }
            // Déplace le fichier dans le répertoire de destination
            $uploadedFile->move($directory, $newFilename);
            // Retourne le chemin relatif (chemin dans le sous-répertoire s'il existe)
            return $newFilename;
        } catch (FileException $e) {
            throw new \RuntimeException(sprintf(MessageErreur::TELECHARGEMENT_ECHOUE->value, $e->getMessage()));
        }
    }

    /**
     * Supprime une image du répertoire donné.
     *
     * @param string $imageName Nom de l'image à supprimer.
     * @param string $directory Répertoire de destination.
     * @return bool
     * @throws \RuntimeException En cas d'erreur.
     */
    public function delete(string $imageName, string $directory): bool
    {
        // Vérifie si le répertoire existe
        if (!is_dir($directory)) {
            throw new \RuntimeException(sprintf(MessageErreur::REPERTOIRE_INEXISTANT->value, $directory));
        }

        // Construit le chemin complet du fichier
        $filePath = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . $imageName;

        // Vérifie si le fichier existe
        if (!file_exists($filePath)) {
            throw new \RuntimeException(sprintf(MessageErreur::FICHIER_INTROUVABLE->value, $imageName));
        }

        // Supprime le fichier
        if (!unlink($filePath)) {
            throw new \RuntimeException(sprintf(MessageErreur::SUPPRESSION_FICHIER_IMPOSSIBLE->value, $filePath));
        }

        return true;
    }
}

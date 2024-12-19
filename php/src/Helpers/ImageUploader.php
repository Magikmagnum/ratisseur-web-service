<?php

namespace App\Helpers;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class ImageUploader
{
    private string $uploadDirectory;

    public function __construct()
    {
        $this->uploadDirectory = "public/uploads/";
    }

    /**
     * Upload une image en définissant son nom et son chemin.
     *
     * @param UploadedFile $uploadedFile
     * @param string|null $customName Nom personnalisé (sans extension). Si null, le nom original est utilisé.
     * @param string|null $customDirectory Sous-répertoire personnalisé dans le répertoire d'upload. Si null, le répertoire par défaut est utilisé.
     * @return string Nom relatif du fichier (à stocker en base de données).
     * @throws \RuntimeException En cas d'erreur pendant l'upload.
     */
    public function upload(UploadedFile $uploadedFile, ?string $customName = null, ?string $customDirectory = null): string
    {
        // Génère un nom basé sur le nom personnalisé ou le nom original
        $originalFilename = $customName ?? pathinfo($uploadedFile->getClientOriginalName(), PATHINFO_FILENAME);

        // Génère un nom unique pour éviter les conflits
        $newFilename = $originalFilename . '-' . uniqid() . '.' . $uploadedFile->guessExtension();

        try {
            // Détermine le répertoire de destination
            $directory = $this->uploadDirectory;
            if ($customDirectory) {
                $directory .= '/' . trim($customDirectory, '/');
            }

            // Crée le répertoire de destination s'il n'existe pas
            if (!is_dir($directory)) {
                if (!mkdir($directory, 0755, true) && !is_dir($directory)) {
                    throw new \RuntimeException(sprintf('Unable to create directory "%s".', $directory));
                }
            }

            // Déplace le fichier dans le répertoire de destination
            $uploadedFile->move($directory, $newFilename);

            // Retourne le chemin relatif (chemin dans le sous-répertoire s'il existe)
            return ($customDirectory ? $customDirectory . '/' : '') . $newFilename;
        } catch (FileException $e) {
            throw new \RuntimeException('File upload failed: ' . $e->getMessage());
        }
    }
}

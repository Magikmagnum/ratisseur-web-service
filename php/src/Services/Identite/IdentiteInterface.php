<?php 

interface IdentiteInterface {
    public function detailIdentites(int $id);
    public function listerLesIdentites();
    public function listerLesIdentitesUtilisateur();
    public function creerUneIdentite(Request $request);
    public function modifierUneIdentite(int $id, Request $request);
    public function supprimerUneIdentite(int $id);
}
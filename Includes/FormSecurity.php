<?php
// VALIDATION + SECURITE + NETTOYAGE

if(session_status() !== PHP_SESSION_ACTIVE)
// On vérifie si la session n'est pas déjà démarrée
// Si la session n'est pas démarrée, on la démarre
{
   return; // Si pas de session, on sort
}



function cleanData(string $data): string
{
    // + Cette fonction permet de nettoyer les données saisies par l'utilisateur
    // + en retirant les espaces, les antislashs et en convertissant les caractères spéciaux.
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
} // fin de la fonction cleanData

// + Fonction qui permet d’afficher un message d’erreur pour un champ spécifique d’un formulaire
// + si ce champ a généré une erreur (stockée dans le tableau $error).

// Fonction création de token:

function creationCSRFToken() {
    if(!isset($_SESSION['csrf_token'])){
        // Si il n'y a pas encore de token CSRF dans la session
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        // Créé le token et mets le dans la session
    }

    return $_SESSION['csrf_token'];
        // Puis retourne le pour le formulaire

}

// Fonction pour vérifier le token CSRF
function verifierCSRFToken($token) 
{
    return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token);
    // vérifie si le token existe dans la session
    // hash_equals() compare de manière sécurisée le token (évite les attaques timing)
    // C'est le même token sauf qu'il est dans le session et dans le formulaire
    // Retourne true si les tokens correspondent, false sinon
}
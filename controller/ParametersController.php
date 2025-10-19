<?php

use App\Includes\DebugBar;
use App\Models\Parameters;
use App\Models\User;

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

class ParametersController
{
    private DebugBar $debug;

    public function __construct()
    {
        $this->debug = DebugBar::getInstance();
    }

    // === POUR LA MODIFICATION DE L'EMAIL DANS LES PARAMÈTRES DU COMPTE ===
    public function modifierEmail()
    {
        require_once APP_ROOT . '/../includes/FormSecurity.php';      // Pour verifierCSRFToken()
        require_once APP_ROOT . '/../includes/MessageFlash.php';     // Pour stocker les messages d'erreur/succès

        // Vérifier que l'utilisateur est connecté ( Même vérification que dans monEspace() et parametres() )
        // $_SESSION["logged"] : variable créée dans AuthController@traitementConnexion() quand l'utilisateur se connecte
        // $_SESSION["user_id"] : l'ID de l'utilisateur en base de données, aussi créé lors de la connexion
        // ORIGINE : dans AuthController@traitementConnexion(), on fait :
        //   $_SESSION["logged"] = true;
        //   $_SESSION["user_id"] = $utilisateur['id']; (l'ID vient de la table users)
        if (!isset($_SESSION["logged"]) || !isset($_SESSION["user_id"])) {
            header("Location: /connexion");
            exit;
        }

        $errors = []; // tableau d'erreurs

        // MEME VÉRIFICATION QUE DANS traitementInscription()
        if ($_SERVER["REQUEST_METHOD"] === "POST") { // Vérification si la méthode est POST

            // RÉCUPÉRATION DES DONNÉES (même technique que dans inscription)
            $csrf_token = $_POST["csrf_token"] ?? ""; // ?? "" : si $_POST["csrf_token"] n'existe pas, on met une chaîne vide (évite les erreurs)
            $nouvel_email = trim($_POST["email"] ?? ""); /// trim() : supprime les espaces avant/après 

            // Vérification token CSRF 
            // verifierCSRFToken() : VOTRE fonction dans FormSecurity.php
            // Elle compare le token du formulaire avec celui stocké en session
            // Si différents = quelqu'un essaie de pirater le formulaire
            if (!verifierCSRFToken($csrf_token)) { // Fonction existante 
                $errors['csrf'] = "Token de sécurité invalide. Veuillez réessayer.";
            }

            // VALIDATION EMAIL
            // Si l'email est vide
            if (empty($nouvel_email)) {
                $errors["email"] = "Veuillez entrer une adresse e-mail";
            } else {
                // Nettoyer les données utilisateur
                // cleanData() : MA fonction dans FormSecurity.php
                $nouvel_email = cleanData($nouvel_email);
                // Vérifier format et taille
                if (strlen($nouvel_email) < 5 || strlen($nouvel_email) > 50) {
                    $errors["email"] = "Votre adresse e-mail n'a pas une taille adaptée";
                    // filter_var() : fonction PHP qui vérifie si l'email est valide
                } elseif (!filter_var($nouvel_email, FILTER_VALIDATE_EMAIL)) {
                    $errors["email"] = "Votre adresse e-mail n'est pas valide";
                } else {
                    // Vérification si l'adresse e-mail existe déjà
                    // Elle fait un SELECT dans la table users pour chercher cet email

                    // Si l'email existe ET que ce n'est pas l'utilisateur actuel
                    // $user['id'] : l'ID du propriétaire de cet email en base
                    // $_SESSION['user_id'] : l'ID de l'utilisateur connecté (créé lors de la connexion)
                    $user = User::emailExists($nouvel_email);  // <- APPEL AU MODEL
                    if ($user && $user['id'] !== $_SESSION['user_id']) {
                        $errors["email"] = "Cet email est déjà utilisé";
                    }
                }
            }

            // Si pas d'erreurs, procéder à la modification
            if (empty($errors)) {
                // APPEL AU MODEL 

                $success = Parameters::updateUserEmail($_SESSION['user_id'], $nouvel_email);  // 
                // $success : true si l'UPDATE a fonctionné, false si erreur en base
                // On stocke le message dans $_SESSION pour le récupérer après la redirection
                // "success-email" : clé qu'on utilisera dans afficheMessageFlash("success-email")
                if ($success) {
                    $_SESSION["modification-email"] = "Votre adresse e-mail a été mise à jour avec succès.";
                    header("Location: /parametres");
                    exit;
                } else {
                    $errors['general'] = "Erreur lors de la mise à jour de l'adresse e-mail.";
                }
            }

            // Stocker erreurs en session et rediriger
            // Si il y a des erreurs, on les stocke en session pour les afficher après redirection
            // Il sera récupéré par MessagesErrorsFormulaire() dans parametres.php
            if (!empty($errors)) {
                $_SESSION["error"] = $errors; // Stockage des erreurs
            }

            header("Location: /parametres");
            exit;
        }
    }
    // === MODIFICATION DU MOT DE PASSE ===
    public function modifierMotDePasse()
    {
        require_once APP_ROOT . '/../includes/FormSecurity.php';
        require_once APP_ROOT . '/../includes/MessageFlash.php';

        if (!isset($_SESSION["logged"]) || !isset($_SESSION["user_id"])) {
            header("Location: /connexion");
            exit;
        }

        $errors = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") { 
            $csrf_token = $_POST["csrf_token"] ?? "";
            $mot_de_passe_actuel = $_POST["current-password"] ?? "";
            $nouveau_mot_de_passe = $_POST["new-password"] ?? "";

            if (!verifierCSRFToken($csrf_token)) {
                $errors['csrf'] = "Token de sécurité invalide. Veuillez réessayer.";
            }

            if (empty($mot_de_passe_actuel)) {
                $errors['current-password'] = "Veuillez entrer votre mot de passe actuel";
            }

            if (empty($nouveau_mot_de_passe)) {
                $errors['new-password'] = "Veuillez entrer un nouveau mot de passe";
            } elseif (strlen($nouveau_mot_de_passe) < 8 || strlen($nouveau_mot_de_passe) > 40) {
                $errors['new-password'] = "Votre mot de passe n'a pas une taille adaptée";
            } elseif (
                !preg_match('/[A-Z]/', $nouveau_mot_de_passe) || // 
                !preg_match('/[a-z]/', $nouveau_mot_de_passe) ||
                !preg_match('/[0-9]/', $nouveau_mot_de_passe) ||
                !preg_match('/[!@#$%^&*(),.?":{}|<>+]/', $nouveau_mot_de_passe)
            ) {
                $errors['new-password'] = "Votre mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial";
            }
            // Si pas d'erreurs
            if (empty($errors)) {
                // Récupère les informations de l'utilisateur 
                $user = User::getUserById($_SESSION['user_id']);
                //L'utilisateur existe-t-il encore en base ? Le mot de passe saisi($mot_de_passe_actuel) correspond-il au hash stocké ( $user['password'])?
                if (!$user || !password_verify($mot_de_passe_actuel, $user['password'])) {
                    $errors['current-password'] = "Mot de passe actuel incorrect";
                }
            }

            if (empty($errors)) {
                $nouveau_mot_de_passe_hash = password_hash($nouveau_mot_de_passe, PASSWORD_DEFAULT);
                $success = Parameters::updateUserPassword($_SESSION['user_id'], $nouveau_mot_de_passe_hash);

                if ($success) {
                    $_SESSION["modification-password"] = "Votre mot de passe a été mis à jour avec succès.";
                    header("Location: /parametres");
                    exit;
                } else {
                    $errors['general'] = "Erreur lors de la mise à jour du mot de passe.";
                }
            }

            if (!empty($errors)) {
                $_SESSION["error"] = $errors;
            }

            header("Location: /parametres");
            exit;
        }
    }
}
/* 
// VÉRIFICATION DU MOT DE PASSE ACTUEL (SÉCURITÉ CRITIQUE)
if (empty($errors)) {
    //  ^^^^^^^^^^^^^^^
    //  Cette condition s'exécute SEULEMENT si aucune erreur de validation n'a été trouvée
    //  (token CSRF valide, champs pas vides, nouveau mot de passe respecte les règles)

    // RÉCUPÉRATION DES DONNÉES UTILISATEUR DEPUIS LA BASE
    $user = User::getUserById($_SESSION['user_id']);
    //      ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
    //      Récupère TOUTES les infos de l'utilisateur connecté depuis la table 'users'
    //      $_SESSION['user_id'] = l'ID stocké lors de la connexion (ex: 42)
    //      $user contiendra : ['id' => 42, 'email' => 'jean@mail.com', 'password' => '$2y$10$...', ...]

    // DOUBLE VÉRIFICATION DE SÉCURITÉ
    if (!$user || !password_verify($mot_de_passe_actuel, $user['password'])) {
        //  ^^^^^^^    ^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^^
        //  |          |
        //  |          └── VÉRIFICATION 2 : Le mot de passe saisi correspond-il au hash stocké ?
        //  └── VÉRIFICATION 1 : L'utilisateur existe-t-il encore en base ?

        $errors['current-password'] = "Mot de passe actuel incorrect";
    }
} */

// EXPLICATION DÉTAILLÉE DES VÉRIFICATIONS :
// ==========================================

// VÉRIFICATION 1 : !$user
// ------------------------
// Vérifie si l'utilisateur existe encore en base de données
// - Si $user = FALSE → L'utilisateur a été supprimé entre temps → ERREUR
// - Si $user = array(...) → L'utilisateur existe → Continue la vérification

// VÉRIFICATION 2 : !password_verify($mot_de_passe_actuel, $user['password'])
// --------------------------------------------------------------------------
// password_verify() est une fonction PHP qui compare :
// - $mot_de_passe_actuel : ce que l'utilisateur a tapé (en clair)
// - $user['password'] : le hash stocké en base (ex: "$2y$10$abcd1234...")
//
// Retourne :
// - TRUE si les mots de passe correspondent → Authentification réussie
// - FALSE si ils ne correspondent pas → Mot de passe incorrect

// EXEMPLES CONCRETS :
// ===================

// CAS 1 : Mot de passe correct
// $mot_de_passe_actuel = "MonMotDePasse123!"
// $user['password'] = "$2y$10$abcd1234..." (hash de "MonMotDePasse123!")
// password_verify() retourne TRUE
// !TRUE = FALSE → Pas d'erreur

// CAS 2 : Mot de passe incorrect  
// $mot_de_passe_actuel = "MauvaisMotDePasse"
// $user['password'] = "$2y$10$abcd1234..." (hash de "MonMotDePasse123!")
// password_verify() retourne FALSE
// !FALSE = TRUE → ERREUR "Mot de passe actuel incorrect"

// CAS 3 : Utilisateur inexistant (très rare)
// $user = FALSE (utilisateur supprimé entre la connexion et cette action)
// !FALSE = TRUE → ERREUR "Mot de passe actuel incorrect"

// POURQUOI CETTE SÉCURITÉ EST CRUCIALE :
// ======================================
// Cette vérification empêche qu'une personne qui aurait accès à une session
// active (ordinateur laissé ouvert, vol de cookie, etc.) puisse changer 
// le mot de passe sans connaître l'ancien. C'est une protection standard
// dans tous les systèmes de gestion de compte sérieux.
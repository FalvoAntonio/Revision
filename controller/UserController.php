<?php
// Le Controller traite la logique métier. Il reçoit les données du formulaire, les valide, et appelle le Model.
if (session_status() !== PHP_SESSION_ACTIVE)
// On vérifie si la session n'est pas déjà démarrée
// Si la session n'est pas démarrée, on la démarre
{
    session_start();
    // On utilise session_start() pour démarrer la session
    // Cela permet de pouvoir utiliser les variables de session
    // Si la session est déjà démarrée, on ne fait rien
    // Cela permet d'éviter les erreurs liées à la session
}

use App\Includes\DebugBar;
use App\Models\User;

// === CONTROLLER POUR LA GESTION DES UTILISATEURS ===
class UserController
{
    private DebugBar $debug;

    public function __construct()
    {
        $this->debug = DebugBar::getInstance();
    }

    // === PAGE INSCRIPTION ===
    public function inscription()
    {
        require_once APP_ROOT . '/../includes/MessageFlash.php';
        $tabTitle = "Inscription";
        $additionalCss = ["/assets/css/05-auth-signup.css", "/assets/css/07-forms-base.css", "/assets/css/04-captcha.css"];
        render('auth.inscription', compact('tabTitle', 'additionalCss'));
    }

    // === TRAITEMENT INSCRIPTION ===
    public function traitementInscription()
    {
        // === VERIFICATION DU FORMULAIRE D'INSCRIPTION===

        require_once APP_ROOT . '/../includes/FormSecurity.php';
        require_once APP_ROOT . '/../includes/CaptchaValidator.php';
        require_once APP_ROOT . '/../includes/PHP_Mailer.php';
        // On inclut le fichier FormSecurity.php qui contient la fonction cleanData
        // Fonction cleanData Variables pour stocker les données nettoyées

        $formulaire = $mail = $motdepasse = $prenom = $nom = $numerotel = "";

        // Tableau d'erreurs
        $error = [];

        // Vérification si le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["signup-form"])) { // Vérification que la requête est de type POST et que le bouton
            // a été cliqué ("inscription.php")
        

            // === TOUTE VOTRE VALIDATION EMAIL ===
            // VALIDATION EMAIL
            if (empty($_POST["email"])) { // name="email" --> "inscription.php"
                $error["mail"] = "Veuillez entrer une adresse e-mail"; 
            } else {
                // Nettoyer les données utilisateur
                $mail = trim($_POST["email"]); // Supprime les espaces inutiles
                $mail = stripslashes($mail); // Supprime les antislashs
                $mail = htmlspecialchars($mail); // Protection contre les failles XSS
                // Vérifier format et taille
                if (strlen($mail) < 5 || strlen($mail) > 50) {
                    $error["mail"] = "Votre adresse e-mail n'a pas une taille adaptée";
                } elseif (!filter_var($mail, FILTER_VALIDATE_EMAIL)) {
                    $error["mail"] = "Votre adresse e-mail n'est pas valide";
                } else {
                    // Vérification si l'adresse e-mail existe déjà
                    $user = User::emailExists($mail);  // <- APPEL AU MODEL
                    if ($user) {
                        $error["mail"] = "Cet email est déjà utilisé";
                    }
                }
            }

            // === TOUTE MA VALIDATION MOT DE PASSE ===
            if (empty($_POST["password"])) { // Si le champ mot de passe est vide
                $error["motdepasse"] = "Veuillez entrer un mot de passe";
            } else {
                $motdepasse = trim($_POST["password"]);
                // Vérifier longueur
                if (strlen($motdepasse) < 8 || strlen($motdepasse) > 40) {
                    $error["motdepasse"] = "Votre mot de passe n'a pas une taille adaptée";
                    // Vérifier complexité (majuscule + minuscule + chiffre + caractère spécial)
                } elseif (!preg_match('/[A-Z]/', $motdepasse) || !preg_match('/[a-z]/', $motdepasse) || !preg_match('/[0-9]/', $motdepasse) || !preg_match('/[!@#$%^&*(),.?":{}|<>+]/', $motdepasse)) {
                    $error["motdepasse"] = "Votre mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial";
                }
            }

            // === TOUTE MA VALIDATION PRENOM ===
            if (empty($_POST["firstname"])) {
                $error["prenom"] = "Veuillez entrer un prénom";
            } else {
                $prenom = trim($_POST["firstname"]);
                $prenom = stripslashes($prenom);
                $prenom = htmlspecialchars($prenom);
                if (strlen($prenom) < 2 || strlen($prenom) > 30) {
                    $error["prenom"] = "Votre prénom n'a pas une taille adaptée";
                } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s-]+$/', $prenom)) {
                    $error["prenom"] = "Votre prénom ne doit contenir que des lettres, des espaces et des tirets";
                }
            }

            // === TOUTE MA VALIDATION NOM ===
            if (empty($_POST["lastname"])) {
                $error["nom"] = "Veuillez entrer un nom";
            } else {
                $nom = cleanData($_POST["lastname"]);  // Utilise la fonction de nettoyage
                if (strlen($nom) < 2 || strlen($nom) > 30) { 
                    $error["nom"] = "Votre nom n'a pas une taille adaptée";
                } elseif (!preg_match('/^[a-zA-ZÀ-ÿ\s-]+$/', $nom)) {
                    $error["nom"] = "Votre nom ne doit contenir que des lettres, des espaces et des tirets";
                }
            }

            // === TOUTE MA VALIDATION TELEPHONE ===
            if (empty($_POST["phone"])) {
                $error["numerotel"] = "Veuillez entrer un numéro de téléphone";
            } else {
                $numerotel = trim($_POST["phone"]);
                $numerotel = stripslashes($numerotel);
                $numerotel = htmlspecialchars($numerotel);
                // Accepte: chiffres, espaces, tirets, + optionnel au début
                if (!preg_match('/^\+?[0-9\s-]+$/', $numerotel)) {
                    $error["numerotel"] = "Votre numéro de téléphone n'est pas valide";
                }
            }

            // === TOUTE MA VALIDATION PREFIX ===
            if (empty($_POST["prefix"])) {
                $error["prefix"] = "Veuillez entrer un préfixe";
            } else {
                $prefix = cleanData($_POST["prefix"]);
                // Format: + suivi de 1 à 4 chiffres (ex: +33, +1)
                if (!preg_match('/^\+\d{1,4}$/', $prefix)) {
                    $error["prefix"] = "Le préfixe doit commencer par + suivi de 1 à 4 chiffres";
                }
            }

            // === GESTION DU CAPTCHA ===
            $recaptchaResponse = $_POST['g-recaptcha-response'] ?? ''; // Récupère la réponse du reCAPTCHA
            $secretKey = '6LcfPX0rAAAAACQpe0kL6-rFS4rHhMQ8z4d2byA7'; // Clé secrète fournie par Google
            $result = verifyRecaptcha($recaptchaResponse, $secretKey); // Envoie la réponse du captcha et
            // la clé à google pour vérifier si l'utilisateur est un humain
            if (isset($result['error'])) { // 
                $error["captcha"] = "Erreur lors de l'envoi du captcha";
            } elseif ($result['success'] === false) {
                $error["captcha"] = "Vous ne seriez pas un bot ?";
            }

            // === SI PAS D'ERREURS ===
            if (empty($error)) {
                /* Création d'un token pour mon envoie de mail */
                $token = bin2hex(random_bytes(50));
                $expiry_date = date('Y-m-d H:i:s', strtotime('+24 hours'));

                // Crypter le mot de passe
                $motdepasse = password_hash($motdepasse, PASSWORD_DEFAULT);

                // Insérer l'utilisateur (Création utilisateur) via modèle
                $user_id = User::insertUser($prenom, $nom, $mail, $numerotel, $prefix, $motdepasse);  // <- APPEL AU MODEL

                // Insérer le token de confirmation via modèle
                User::insertEmailToken($user_id, $token, $expiry_date);  // <- APPEL AU MODEL

                $miseEnFormMail = renderMail("/../view/Mails/confirmation-inscription.php", ['token' => $token]);
                // "str_replace" est une fonction PHP qui remplace du texte dans une chaine de caractères
                // * str_replace('QUOI_REMPLACER', 'PAR_QUOI', 'DANS_QUEL_TEXTE')

                $emailEnvoye = EnvoyerMail($mail, "Inscription", $miseEnFormMail);
                // Message de succès et redirection
                if ($emailEnvoye) {
                    $_SESSION["Message-confirmation-envoi-mail"] = "Inscription réussie ! Vérifiez votre email.";
                } else {
                    $_SESSION["Message-confirmation-envoi-mail"] = "Inscription réussie mais erreur d'envoi d'email.";
                }

                header('Location: /connexion');
                exit;
            } else {
                // Stocker les erreurs en session
                $_SESSION["error"] = $error;
                header('Location: /inscription'); // Retourne au formulaire
                exit; // Stop l'execution
            }
        }
    }
    // === TRAITEMENT CONFIRMATION EMAIL ===
    public function confirmerEmail()
    {
        // 3. Je récupère le token depuis l'URL
        // Quand quelqu'un clique sur le lien, l'URL ressemble à :
        // Confirmation-Mail.php?token=abc123def456...
        $token = $_GET['token'] ?? ''; // Récupère le token de l'URL
        // ?? '' signifie : si $_GET['token'] n'existe pas, alors $token = ''

        $message = "";
        $success = false;

        // 4. Je vérifie que le token n'est pas vide
        if (!empty($token)) {
            // 5. Je cherche ce token dans ma base de données
            $confirmation = User::ChercherLeToken($token);


            // 6. Si j'ai trouvé le token ET qu'il est valide
            if ($confirmation) {

                // 7. Je marque le token comme utilisé
                User::TokenValide($confirmation);

                $success = true;
                $message = "Félicitations ! Votre email a été confirmé avec succès.";
            } else {
                // Token invalide ou expiré
                $success = false;
                $message = "Ce lien de confirmation est invalide ou a expiré.";
            }
        } else {
            $success = false;
            $message = "Aucun token de confirmation fourni.";
        }
        $tabTitle = "Confirmation Email";
        render('auth.confirmation-mail', compact('tabTitle', 'success', 'message'));
    }

    public function supprimerCompte()
    {

        require_once APP_ROOT . '/../includes/FormSecurity.php';      // était service/Forme.php
        require_once APP_ROOT . '/../includes/MessageFlash.php';     // pour les messages d'erreur

        // Vérifier que l'utilisateur est connecté
        // Si l'utilisateur n'est pas connecté, rediriger vers la page de connexion
        if (!isset($_SESSION["logged"]) || !isset($_SESSION["user_id"])) {
            header("Location: /connexion");
            exit;
        }
        $message = '';
        $errors = [];

        // Traitement du formulaire
        if ($_SERVER["REQUEST_METHOD"] === "POST") { // Vérification que la requête est de type POST
            $csrf_token = $_POST["csrf_token"] ?? ""; // Récupération du token CSRF

            // Vérification token CSRF
            if (!verifierCSRFToken($csrf_token)) { // Appel de la fonction de vérification
                $errors['csrf'] = "Token de sécurité invalide. Veuillez réessayer.";
            }

            // Si pas d'erreurs, procéder à la suppression
            if (empty($errors)) { // Si aucune erreur n'a été détectée
                $success = User::deleteUser($_SESSION['user_id']);  // <- APPEL AU MODEL

                if ($success) {
                    // Détruire la session
                    session_destroy(); // Termine la session en cours
                    session_start(); // Redémarre une nouvelle session vide

                    // Message de confirmation
                    $_SESSION["suppression-compte"] = "Votre compte a été supprimé avec succès.";

                    header("Location: /");
                    exit;
                } else {
                    $errors['general'] = "Erreur lors de la suppression du compte.";
                }
            }

            // Stocker erreurs en session et rediriger
            if (!empty($errors)) {
                $_SESSION["error"] = $errors;
            }

            header("Location: /parametres");
            exit;
        }
    }

  
}

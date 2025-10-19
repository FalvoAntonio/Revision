<?php

use App\Includes\DebugBar;
use App\Models\User;

// ? Vérification de la session: (!C'est la deuxième étape)
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
class AuthController
{


    // === PAGE LOGIN ===
    public function connexion()
    {
        require_once APP_ROOT . '/../includes/MessageFlash.php';
        $tabTitle = "Connexion - Nails Endless Beauty";
        $additionalCss = ["/assets/css/11-auth-login.css", "/assets/css/07-forms-base.css",];
        render('auth.login', compact('tabTitle', 'additionalCss'));
    }
    public function traitementConnexion()
    {


        // ? Ajouter une redirection si l'utilisateur est déjà connecté
        if (isset($_SESSION["logged"]) && $_SESSION["logged"] === true)
        // On vérifie si l'utilisateur est déjà connecté
        // Si l'utilisateur est déjà connecté, on le redirige vers la page d'accueil
        {
            header("Location: /");
            exit;
            // On utilise header() pour rediriger l'utilisateur vers la page d'accueil
            // On utilise exit pour arrêter l'exécution du script après la redirection
        }

        // ? Déclaration des variables: (!C'est la première étape que je fais toujours dans un script PHP)

        $error = [];
        $email = $pass = "";

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // On vérifie si la méthode de la requête est POST et si le bouton de connexion a été cliqué
            // Si la méthode de la requête est POST, cela signifie que le formulaire a été soumis
            if (empty($_POST["email"]))
            // On vérifie si le champ email est vide
            // Si le champ email est vide, on ajoute un message d'erreur dans le tableau $error
            {
                $error["email"] = "Veuillez saisir votre email.";
                // On ajoute un message d'erreur dans le tableau $error
            } else {
                $email = trim($_POST["email"]);
                // trim() permet de supprimer les espaces en début et fin de chaîne
                // Cela permet d'éviter les erreurs liées aux espaces inutiles
            }
            if (empty($_POST["mdp"]))
            // On vérifie si le champ mot de passe est vide
            // Si le champ mot de passe est vide, on ajoute un message d'erreur dans le tableau $error
            // Si le champ mot de passe n'est pas vide, on continue le traitement
            // "mdp" est le nom du champ mot de passe dans le formulaire

            {
                $error["mdp"] = "Veuillez saisir votre mot de passe.";
                // On ajoute un message d'erreur dans le tableau $error 
            } else {
                $pass = trim($_POST["mdp"]);
                // trim() permet de supprimer les espaces en début et fin de chaîne
                // Cela permet d'éviter les erreurs liées aux espaces inutiles
            } // Fin de la vérification des champs

            if (empty($error))
            // On vérifie si le tableau $error est vide
            // Si le tableau $error est vide, cela signifie que tous les champs sont valides

            {
                // *  Protection contre le bruteforce.

                // ? Connexion à la base de données:
                // ? Récupérer l'utilisateur dans la base de données:
                //Je dois vérifier si l'adresse mail correspond à l'utilisateur, si un utilisateur à cette adresse mail


                $utilisateur = User::UsersEmail($email); // Je récupère l'utilisateur avec son email
                // var_dump($utilisateur, $email, $_POST);
                //Vérifier si l'email est bonne
                // vérifier si l'email à était confirmer !
                if ($utilisateur) {
                    if ($utilisateur["login_attempts"] >= 5) // Si 5 tentatives ou plus ont été faites
                    // "login_attempts" : l'utilisateur a-t-il confirmé son email ?
                    {
                        $lastattempts = new DateTime($utilisateur["last_login_attempts"]); // Récupère la date de la dernière tentative
                        $now = new DateTime("-5 minutes"); // Récupère la date actuelle moins 15 minutes
                        if ($lastattempts > $now) { // Si la dernière tentative est plus récente que 15 minutes
                            $error["tentative"] = "Veuillez ressayer plus tard"; // On ajoute un message d'erreur dans le tableau $error
                        } else { // Si la dernière tentative est plus ancienne que 15 minutes
                            // Voir les requêtes dans model
                            User::TentativesConnexionReset($email);  // On réinitialise le nombre de tentatives
                            // login_attempts : compte le nombre d'échecs de connexion
                            // last_login_attempts : timestamp de la dernière tentative
                        }
                    }

                    if (empty($error)) {

                        //Si le mot de passe est bon on va vérifier si c'est le même que dans la BDD.
                        if (password_verify($pass, $utilisateur["password"]))
                        // On utilise password_verify() pour vérifier si le mot de passe saisi correspond au mot de passe haché dans la base de données
                        {
                            if ($utilisateur["email_verified"] === 1)
                            // Je vérifie si l'email est confirmé, pour cela elle doit être = à 1 (TRUE)
                            {
                                // Authentifier l'utilisateur:
                                $_SESSION["logged"] = true;
                                // Je stock toutes les informations de l'utilisateur dans la session
                                // On crée une session pour l'utilisateur
                                $_SESSION["user_id"] = $utilisateur["id"]; 
                                $_SESSION["user_email"] = $utilisateur["email"]; //
                                $_SESSION["user_firstname"] = $utilisateur["firstname"];
                                $_SESSION["user_lastname"] = $utilisateur["lastname"];
                                $_SESSION["user_role"] = $utilisateur["role"];
                                // On reset le compteur de tentative à zéro
                                User::TentativesConnexionReset($email);

                                // On crée une session et redirige l'utilisateur vers la page mon
                                header("Location: /mon-espace");
                                exit;
                                // On redirige l'utilisateur vers la page d'accueil
                            } else {
                                $error["email"] = "Veuillez confirmer votre email.";
                            }
                        } else {
                            $error["mdp"] = "Mot de passe incorrect.";
                            $tentative = $utilisateur["login_attempts"] + 1; // On incrémente le nombre de tentatives
                            // last_login_attempts : Quand a eu lieu la dernière tentative ?

                            User::AugmenterNbrTentatives($email, $tentative); // On met à jour le nombre de tentatives
                            // petite secu supplémentaire au bruteforce, 3 secondes avant d'afficher la page
                            // pour répérer le spam intensif des bots.
                            sleep(3);
                            // Si le mot de passe est incorrect, on ajoute un message d'erreur dans le tableau $error
                        }
                    }
                } else {
                    $error["email"] = "Aucun utilisateur trouvé avec cet email.";
                    // Si aucun utilisateur n'est trouvé avec l'email saisi, on ajoute un message d'erreur dans le tableau $error
                }
            }
        }
        $_SESSION["error"] = $error;
        header('Location: /connexion');
    }


    public function deconnexion()
    {
        if (!isset($_SESSION["logged"]) || $_SESSION["logged"] !== true)
        // On vérifie si l'utilisateur n'est pas connecté
        // Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
        {
            // On redirige l'utilisateur vers la page de connexion
            header("Location: /connexion");
            exit; // On utilise exit pour arrêter l'exécution du script après la redirection
        }

        unset($_SESSION); // Vidage de la session
        session_destroy(); // Destruction de la session
        setcookie("PHPSESSID", "", time() - 3600); // Suppression du cookie de session

        header("Location: /");
        // On redirige l'utilisateur vers la page d'accueil après la déconnexion
        exit;
    }

    public function motDePasseOublie()
    {
        $tabTitle = "Mot de passe oublié - Nails Endless Beauty";
        $additionalCss = ["/assets/css/12-auth-reset.css", "/assets/css/07-forms-base.css"];
        render('auth.forgot-password', compact('tabTitle', 'additionalCss'));
    }


    // === Vérification de l'adresse e-mail (MOT DE PASSE OUBLIE) ===
    public function verificationConfirmationMail()
    {
        /* CE FICHIER PERMET DE VERIFIER LE CHAMP DE L'ADRESSE MAIL DE LA PAGE "MDP-oublie.php"
    ON VERIFIE EGALEMENT SI L'ADRESSE MAIL SAISIE EXISTE BIEN DANS NOTRE TABLE DE DONNEES
    NOUS PREPARONS L'ENVOIE DU MAIL QUI PERMETTRA DE MOFIDIER LE MDP AVEC LE LIEN URL CONTENANT LE TOKEN
    ON ENVOIE DANS LE MAIL LA PAGE HTML : "réinitialiser-motdepasse.html"
    */

        // On inclut le fichier Forme.php qui contient mes fonctions php, ainsi que PHP_Mailer pour me permettre d'envoyer un mail
        require_once APP_ROOT . '/../includes/FormSecurity.php';
        require_once APP_ROOT . '/../includes/PHP_Mailer.php';

        $error = [];

        // Je vérifie si l'adresse mail est vide
        if (empty($_POST["email"])) {
            // Si elle est vide alors j'envoie un message d'erreur
            $error["mail"] = "Veuillez entrer une adresse e-mail";
        } else {
            $mail = trim($_POST["email"]);
            // retire les espaces au début et à la fin du string
            $mail = stripslashes($mail);
            // retire les \ présent dans le string.
            $mail = htmlspecialchars($mail);
            // Remplace tous les caractères spéciaux de html (<,>...) par leurs code HTML (&gt;...) afin d'empêcher toute injection de code.
            // Vérification de la validité de l'adresse e-mail
            if (!filter_var($mail, FILTER_VALIDATE_EMAIL))
            // On verifie si le string $mail est une adresse e-mail valide, si ça ressemble à une adresse e-mail
            // Cela nous renvoie false si l'adresse e-mail n'est pas valide
            // filter_var est une fonction PHP qui permet de filtrer une variable.
            // FILTER_VALIDATE_EMAIL est un filtre qui permet de valider une adresse e-mail.
            {
                $error["mail"] = "Votre adresse e-mail n'est pas valide";
                // On ajoute un message d'erreur dans le tableau $error
            } else {
                // Vérification si l'adresse e-mail existe déjà
                $user = User::emailExists($mail);
                // Si un utilisateur a été trouvé avec cet e-mail
                if ($user) {
                    // Création d'un token pour mon envoie de mail
                    // On génère un token (chaîne aléatoire sécurisée) pour la réinitialisation du mot de passe
                    $token = bin2hex(random_bytes(50));

                    $expiry_date = date('Y-m-d H:i:s', strtotime('+1 hours'));
                    // A partir de maintenant le token expire dans une heure

                    User::insertPasswordResetToken($user["id"], $token, $expiry_date);

                    /* 
                    * À ce stade, on a stocké un token dans une table à part (password_reset)
                    * Cela permet de sécuriser le système de réinitialisation.
                    * Quand la personne cliquera sur le lien dans le mail, on vérifiera si ce token existe encore et n'est pas expiré.
                    */

                    $miseEnFormMail = file_get_contents(APP_ROOT . "/../view/Mails/reset-password.php");
                    // On récupère le contenu HTML du mail à envoyer (template prédéfini) depuis un fichier externe.

                    $miseEnFormMail = str_replace('$token$', $token, $miseEnFormMail);
                    // "str_replace" est une fonction PHP qui remplace du texte dans une chaine de caractères
                    // * str_replace('QUOI_REMPLACER', 'PAR_QUOI', 'DANS_QUEL_TEXTE')

                    // Envoi du mail de réinitialisation avec le sujet et le contenu personnalisé
                    EnvoyerMail($mail, "Réinitialisation mot de passe", $miseEnFormMail);
                    $_SESSION["Message-confirmation-envoi-mail"] =  "Un e-mail de réinitialisation a été envoyé à votre adresse.";
                } else {
                    $_SESSION["Message-confirmation-envoi-mail"] =  "Cette adresse e-mail n'existe pas dans notre base.";
                }
            }
        } // ? fin vérification mail

        header("Location: /accueil");
    }

    public function reinitialiserMotDePasse()
    {
        // Vérification du token pour réinitialisation de mot de passe
        // ! Ce code vérifie si le lien cliqué dans l'email est valide pour réinitialiser un mot de passe.

        // 1. Je démarre la session (comme toujours)
        // ? ça me permet de garder des informations d'une page à l'autre
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // 3. Je récupère le token depuis l'URL
        // ? Quand quelqu'un clique sur le lien dans l'email l'URL ressemble à ça: "monsite.com/reset.php?token=abc123xyz789"

        $token = $_GET['token'] ?? '';
        // ? Le  $_GET['token'] permet de récupérer la partie après le "token="
        // ? -->  ?? "" : Cela signifie que si le token n'existe pas on met une chaine vide

        $message = "";  // ? Permet de stocker le message à afficher à l'utilisateur
        $success = false; // ? Pour savoir si tout s'est bien passé (true/false)

        // 4. Je vérifie que le token n'est pas vide, c'est à dire qu'il existe
        if (!empty($token)) {

            // 5. Je cherche ce token dans ma base de données PASSWORD_RESET 
            $confirmation = User::findPasswordResetToken($token);
            // used = FALSE : le token n'a pas encore été utilisé
            // expiry_date > NOW() : le token ne doit pas être expiré
            // reset_token = ? : Le token doit correspondre exactement
            // ? En gros ça veut dire "Trouve moi un token qui correspond à "Tkoa5984a2", qui n'a pas encore été utilisé et qui
            // ? n'est pas expiré

            // 6. Si j'ai trouvé le token ET qu'il est valide
            if ($confirmation) {

                // Token valide ! On peut afficher le formulaire de nouveau mot de passe
                $success = true; // ? Tout va bien
                $message = "Token valide. Vous pouvez maintenant changer votre mot de passe.";

                // Je stocke l'ID du token et l'ID utilisateur pour les utiliser dans le formulaire
                $_SESSION['reset_token_id'] = $confirmation['id']; // ? ['id'] de la table de BDD
                $_SESSION['reset_user_id'] = $confirmation['user_id']; // ? ['user_id'] de la table de BDD

                // ? On va sauvegarder 2 informations dans la session:
                //? - reset_token_id : Qui est l'id du token
                //? - reset_user_id : Qui est l'id de l'utilisateur
            } else {
                // Token invalide ou expiré
                $success = false;
                $message = "Ce lien de réinitialisation est invalide ou a expiré.";
            }
        } else {
            $success = false;
            $message = "Aucun token de réinitialisation fourni.";
        }

        // 7. J'inclus la page qui va afficher soit le formulaire, soit le message d'erreur
        $tabTitle = "Réinitialiser le mot de passe - Nails Endless Beauty";
        $additionalCss = ["/assets/css/12-auth-reset.css", "/assets/css/07-forms-base.css"];
        render('auth.reset-password', compact('tabTitle', 'success', 'message', 'additionalCss'));
    }
    public function traitementReinitialisationMotDePasse()
    {
        // Vérification des champs du formulaire "Réinitialisation du mot de passe"

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        require_once APP_ROOT . '/../includes/FormSecurity.php';

        $error = [];

        // Vérifier que l'utilisateur vient bien de la page de vérification du token
        if (!isset($_SESSION['reset_token_id']) || !isset($_SESSION['reset_user_id'])) {
            header("Location: /");
            exit;
        }

        // Vérifier que le formulaire a été soumis
        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // var_dump($_POST);
            // die("coucou");

            // Vérification du nouveau mot de passe
            if (empty($_POST["New-mdp"])) {
                $error["nouveau_mdp"] = "Veuillez entrer un nouveau mot de passe";
            } else {
                $nouveau_mdp = trim($_POST["New-mdp"]);
                if (strlen($nouveau_mdp) < 8 || strlen($nouveau_mdp) > 40) {
                    $error["nouveau_mdp"] = "Votre mot de passe n'a pas une taille adaptée";
                } elseif (
                    !preg_match('/[A-Z]/', $nouveau_mdp) ||
                    !preg_match('/[a-z]/', $nouveau_mdp) ||
                    !preg_match('/[0-9]/', $nouveau_mdp) ||
                    !preg_match('/[!@#$%^&*(),.?":{}|<>+]/', $nouveau_mdp)
                ) {
                    $error["nouveau_mdp"] = "Votre mot de passe doit contenir au moins une majuscule, une minuscule, un chiffre et un caractère spécial.";
                }
            }

            // Vérification de la confirmation du nouveau mot de passe (retaper)

            if (empty($_POST["Confirm-mdp"])) {
                $error["confirmer_nouveau_mdp"] = "Veuillez confirmer votre nouveau mot de passe";
            } else {
                $confirmer_nouveau_mdp = trim($_POST["Confirm-mdp"]);

                // Vérifier que les deux mots de passe sont identiques
                if (isset($nouveau_mdp) && $nouveau_mdp !== $confirmer_nouveau_mdp) {
                    $error["confirmer_mdp"] = "Les mots de passe ne correspondent pas";
                }
            }

            // S'il n'y a aucune erreur alors on peut changer le mot de passe
            // Si l'utilisateur a bien rempli le formulaire (pas d'erreurs)
            if (empty($error)) {
                // Il faut crypter le mot de passe
                $nouveau_mdp_hash = password_hash($nouveau_mdp, PASSWORD_DEFAULT);
                // Nous devons mettre le mot de passe à jour (crud)
                // Changer le mot de passe dans la base de données
                User::updateUserPassword($_SESSION['reset_user_id'], $nouveau_mdp_hash);
                // ! Je reprends l'id d'une autre page "Confirmer-votre-nouveau-mdp.php" avec $_SESSION 
                // ! Ce qu'il y avait dans la page "Confirmer-votre-nouveau-mdp.php" :

                // [ Je stocke l'ID du token et l'ID utilisateur pour les utiliser dans le formulaire
                // $_SESSION['reset_token_id'] = $confirmation['id'];
                //$_SESSION['reset_user_id'] = $confirmation['user_id']; ]

                // Avec $_SESSION['reset_user_id'] : Je vais récupérer l'information 
                //? reset_user_id = L'id de l'utilisateur
                //? reset_token_id = L'id du token

                // Marquons le token comme utilisé
                User::markPasswordResetTokenAsUsed($_SESSION['reset_token_id']);

                // Pour finir il ne faut pas oublier de nettoyer la session
                unset($_SESSION['reset_token_id']);
                unset($_SESSION['reset_user_id']);

                // Message de succès
                $_SESSION["Message-confirmation-envoi-mail"] = "Votre mot de passe a été changé avec succès ! Vous pouvez maintenant vous connecter.";
                // ! A REVOIR !!! DOIS JE UTILISER TOUJOURS LA MEME VARIABLE "Message-confirmation-envoi-mail"

                // Redirection vers la page de connexion
                header("Location: /connexion");
                exit;
            }
        }

        header("Location: /");

        /* 

    📊 DANS LA TABLE password_reset
    Voici un exemple de ce qu'il y a dans votre table :
    iduser_idreset_tokenexpiry_dateused512abc123xyz2025-06-18FALSE625def456uvw2025-06-18FALSE78ghi789rst2025-06-17TRUE

    🎯 SCÉNARIO CONCRET
    Imaginons que Marie (utilisateur ID 25) a oublié son mot de passe.
    1. Marie clique sur son lien email
    URL : monsite.com/reset.php?token=def456uvw
    2. Le code récupère le token
    php$token = $_GET['token']; // = "def456uvw"
    3. Le code cherche dans la table
    php$sql = $pdo->prepare("SELECT * FROM password_reset WHERE reset_token = ?");
    $sql->execute(["def456uvw"]);
    $confirmation = $sql->fetch();
    4. Résultat récupéré
    php$confirmation = [
        'id' => 6,              // ← L'ID de cette ligne dans password_reset
        'user_id' => 25,        // ← L'ID de Marie dans la table users
        'reset_token' => 'def456uvw',
        'expiry_date' => '2025-06-18',
        'used' => false
    ];
    5. Stockage dans la session
    php$_SESSION['reset_token_id'] = $confirmation['id'];       // = 6
    $_SESSION['reset_user_id'] = $confirmation['user_id'];   // = 25

    🔍 QUE SIGNIFIENT CES VARIABLES ?
    reset_token_id = 6

    Fait référence à : La ligne numéro 6 dans la table password_reset
    Pourquoi on en a besoin : Pour marquer cette ligne comme "utilisée" plus tard

    reset_user_id = 25

    Fait référence à : L'utilisateur numéro 25 dans la table users (= Marie)
    Pourquoi on en a besoin : Pour changer le mot de passe de Marie (pas de quelqu'un d'autre !)
    */
    }
}

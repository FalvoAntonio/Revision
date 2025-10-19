<?php 

// Utilisation de PHPMailer 
/* 
* PHPMailer est utilisé pour envoyer des emails en PHP de manière plus fiable, sécurisée et facile que la fonction native mail().
* Pourquoi l'utilser ?
* - Support SMTP : Permet d'envoyer des emails via un serveur SMTP (comme Gmail), ce qui améliore la livraison et l’authentification.
* - Sécurité (TLS/SSL) : Chiffre les emails pour éviter qu'ils soient interceptés. 
* - Pièces jointes : Permet d'envoyer des fichiers en pièces jointes facilement, sans se compliquer le <code class="
* - HTML et texte brut : Permet d'envoyer des emails en format HTML et texte brut, ce qui est important
* pour la compatibilité avec différents clients de messagerie.
* - Meilleure gestion des erreurs : Fournit des messages d'erreur clairs (ex: mauvais mot de passe SMTP),
* contrairement à mail() qui échoue souvent sans explication.
* - Compatibilité : Fonctionne bien avec les fournisseurs d'email modernes (Gmail,Outlook, etc.) qui réduit les chances d'atterir en spam.

*  EN RESUME : PHPMailer est plus complet, plus sûr, et beaucoup plus pratique pour envoyer des emails professionnels en PHP
*/

use PHPMailer\PHPMailer\PHPMailer; // C'est la classe principale pour créer et envoyers des emails.
use PHPMailer\PHPMailer\Exception; // C'est la classe pour la configuration du serveur SMTP et le debug.
use PHPMailer\PHPMailer\SMTP; // C'est la classe pour capturer les erreurs spécifiques à PHPMailer.

// On inclut l'autoloader de Composer pour charger automatiquement les classes de PHPMailer.
// Sans ces lignes je devrais écrire le nom de la classe PHPMailer en entier à chaque fois que je l'utilise :
// ? Sans use (long et pénible)
// $mail = new \PHPMailer\PHPMailer\PHPMailer(true);
// ? Avec use (propre et lisible)
// $mail = new PHPMailer(true);

require __DIR__ . '/../vendor/autoload.php'; // Chemin vers l'autoloader de Composer
// ! C'est absolument indispensable : sans cette ligne Php ne sait pas ou trouver les classes PHPMailer.

function EnvoyerMail(string $destinataire, string $sujet, string $message): bool
// Je déclare ma fonction pour envoyer un e-mail avec PHPMailer, elle me retourne un string qui indique si l'envoi a réussi ou échoué.
{
    try {
        // Création d'une instance de PHPMailer
        $mail = new PHPMailer(true); // Ici nous allons crée un nouveau PHPMailer, le paramètre true permet d'activer
        // les exceptions pour capturer les erreurs.
        
        
        // Configuration SMTP basique:


        $mail->isSMTP(); // * Ceci va activé  le mode SMTP : On va dire à PHPMailer d'utiliser un serveur SMTP.
        // ! Pour avoir les informations il faudra se connecter sur mailtrap
        $mail->Host = 'smtp.gmail.com'; // * C'est l'adresse du serveur qui va envoyer mes emails : exemples smtp.gmail.com, sandbox.smtp.mailtra.io
        $mail->SMTPAuth = true; // * Active l'authentification : Le serveur SMTP demande un nom d'utilisateur et mot de passe
        $mail->Port = 587; // * Chaque serveur SMTP utilise un port spécifique
        $mail->Username = 'falvo.tonio@gmail.com'; // * Mon nom d'utilisateur 
        $mail->Password = 'dyxr xkbq wfal ektl'; // * Mon mot de passe pour le serveur SMTP
       
        
        // Configuration de l'email
        $mail->setFrom('Endlessbeauty.lc@gmail.com', 'Mon Site'); // * L'adresse mail et le nom affiché de l'expéditeur
        $mail->addAddress($destinataire); // * On utilise le paramètre de la fonction
        $mail->isHTML(true); // * Permet d'utiliser des balises HTML dans le message
        $mail->CharSet = 'UTF-8'; // * Permet d'utiliser les accents et caractères spéciaux français
        $mail->Subject = $sujet; // * Sujet du message
        $mail->Body = $message; // * Le corps du message
        
        $mail->send(); // * C'est pour envoyer l'envoi
        return true; // Si tout s'est bien passé, il retourne true.
        
    } catch (Exception $error) {
        error_log("Erreur email : " . $error->getMessage());
        return false; // Il retourne un message d'erreur.
    }
}

function renderMail(string $templatePath, array $variables = []): string
{
    $miseEnFormMail = file_get_contents(APP_ROOT . $templatePath);

    foreach ($variables as $key => $value) 
    {
        $miseEnFormMail = str_replace("$$key$", htmlspecialchars($value), $miseEnFormMail);

    }
    return $miseEnFormMail;
}
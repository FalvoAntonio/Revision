<?php

// Le processus complet en action :

// Utilisateur coche la case reCAPTCHA → Google génère un token
// Formulaire envoyé → Token inclus dans $_POST['g-recaptcha-response']
// Votre serveur appelle verifyRecaptcha() → Requête vers Google
// Google répond → Confirmation si l'utilisateur est humain
// Votre code traite la réponse → Autorise ou refuse l'inscription

/**
 * Undocumented function
 *
 * @param string $recaptchaResponse réponse envoyée par le captcha (dans le formulaire) $_POST
 * @param string $secretKey La clée privée fournis par google
 * @param string|null $userIp ip de l'utilisateur
 * @return array réponse de l'API ou les erreurs
 */

//  FONCTION POUR VERIFIER UN reCAPTCHA GOOGLE COTE SERVEUR 

function verifyRecaptcha($recaptchaResponse, $secretKey, $userIp = null) {
    // URL de l'API reCAPTCHA
    $url = 'https://www.google.com/recaptcha/api/siteverify';
    // Cette ligne définit l'adresse web où Google attend nos demandes de vérification
    // C'est comme une adresse postale, mais pour les ordinateurs
    // "siteverify" = le service spécialisé de Google pour vérifier les captchas
    
    // Données à envoyer
    // ! Ce qu'on attend :
    // Paramètre POST  Description
    // 'secret'	: Obligatoire. Clé partagée entre votre site et reCAPTCHA.
    // 'response'	: Obligatoire. Jeton de réponse de l'utilisateur fourni par l'intégration de reCAPTCHA côté client sur votre site.
    // 'remoteip'	: Facultatif. Adresse IP de l'utilisateur

    $data = array(
        'secret' => $secretKey, // Notre clé privée 
        'response' => $recaptchaResponse // Notre Token utilisateur
    );
    // Ici on prépare un "paquet" de données à envoyer à Google
    // 'secret' = notre mot de passe secret pour prouver qu'on est autorisés
    // 'response' = le code que l'utilisateur a obtenu en cochant la case
    // C'est comme remplir un formulaire avant de l'envoyer par courrier
    
    // Ajouter l'IP de l'utilisateur si fournie (optionnel)
    if ($userIp !== null) {
        $data['remoteip'] = $userIp;
    }
    // Si on connaît l'adresse IP de l'utilisateur, on l'ajoute au paquet
    // !== null signifie "si cette variable contient quelque chose"
    // L'IP aide Google à détecter si quelqu'un essaie de tricher depuis le même ordinateur

    // Configuration cURL (POUR LA COMMUNICATION)
    // curl_init() démarre un "facteur numérique" qui va porter notre message à Google
    // $ch = c'est notre "facteur" qu'on va configurer avant l'envoi
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url); // URL de destination : https://www.google.com/recaptcha/api/siteverify
    curl_setopt($ch, CURLOPT_POST, true); // Fait référence à la méthode POST
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data)); // Données
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Récupérer la réponse
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true); // Vérfiier le certificat SSL
    curl_setopt($ch, CURLOPT_TIMEOUT, 30); // Timeout 30 secondes
    
    // Exécuter la requête
    $response = curl_exec($ch);
    // curl_exec($ch) = le facteur part maintenant livrer le message !
    // $response = ce que Google nous a répondu (stocké dans cette variable)
    // Envoie la requête et récupère la réponse 
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    // On demande au facteur : "comment ça s'est passé ?"
    // CURLINFO_HTTP_CODE = le code de statut de la livraison
    // 200 = "tout va bien", 404 = "adresse introuvable", 500 = "erreur serveur", etc.

    // Vérifier les erreurs cURL
    if (curl_error($ch)) {
        // curl_error($ch) vérifie si le facteur a eu un problème
        // Exemples : pas d'internet, serveur fermé, timeout, etc.
        curl_close($ch);
        // On "licencie" le facteur (libère la mémoire de l'ordinateur)
        return array('error' => 'Erreur cURL: ' . curl_error($ch));
    }
    // On retourne un message d'erreur à notre programme principal
    // return = "stop la fonction ici et renvoie ce résultat"
    // array('error' => '...') = on crée un tableau avec le message d'erreur
    
    
    curl_close($ch);
    
    // Vérifier le code de réponse HTTP
    if ($httpCode !== 200) {
        // Si le code n'est pas 200 (succès), il y a eu un problème
        // !== signifie "différent de" (plus strict que !=)
        return array('error' => 'Erreur HTTP: ' . $httpCode);
        // On retourne une erreur avec le code exact reçu
        // Exemples : "Erreur HTTP: 404" ou "Erreur HTTP: 500"
    }
    
    // Décoder la réponse JSON
    $result = json_decode($response, true);
    // Google nous a répondu en format JSON (comme un langage standardisé)
    // json_decode() traduit ce langage JSON en tableau PHP qu'on peut utiliser
    // true = transformer en tableau (sinon ce serait un objet PHP)
    // Exemple: '{"success":true}' devient ['success' => true]
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        // json_last_error() vérifie si la traduction JSON s'est bien passée
        // JSON_ERROR_NONE = constante PHP qui signifie "aucune erreur"
        // Si différent, c'est que le JSON était cassé/invalide
        

        return array('error' => 'Erreur JSON: ' . json_last_error_msg());
        // json_last_error_msg() donne le détail de l'erreur JSON
        // Exemple : "Syntax error" si Google a envoyé du JSON mal formé
    }
    
    return $result;
    // Tout s'est bien passé ! On retourne la réponse de Google
    // $result contient maintenant un tableau avec success, error-codes, etc.
    // C'est ce tableau qui sera utilisé dans votre formulaire principal
}

// Étape 1 : Affichage du formulaire

// Le navigateur charge le script Google
// Google génère automatiquement le widget reCAPTCHA
// L'utilisateur voit la case "Je ne suis pas un robot"

// Étape 2 : Interaction utilisateur

// L'utilisateur coche la case
// Google peut demander des défis supplémentaires (images, sons)
// Une fois validé, Google génère un token de réponse

// Étape 3 : Soumission du formulaire

// Le token est inclus automatiquement dans $_POST['g-recaptcha-response']
// Votre serveur récupère ce token
// Votre serveur contacte Google pour vérifier le token

// Étape 4 : Validation

// Google confirme ou infirme la validité
// Si valide : le formulaire continue son traitement
// Si invalide : une erreur est affichée à l'utilisateur
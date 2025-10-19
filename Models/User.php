<?php

namespace App\Models;

use App\Includes\Database;

class User
{
    // Récupérer tous les utilisateurs
    public static function getAll(): array
    {
        $pdo = Database::pdo();
        $stmt = $pdo->query('SELECT * FROM users ORDER BY id ASC'); // ajuster le nom de table/colonnes si besoin
        return $stmt->fetchAll();
    }

// === POUR LE TRAITEMENT DU FORMULAIRE (INSCRIPTION) ===
// === POUR LA RÉINITIALISATION MOT DE PASSE (emailExists) ===

    // Vérifier si l'email existe
    public static function emailExists($email) 
{
    $pdo = Database::pdo();
    $sql = $pdo->prepare("SELECT * FROM users WHERE email=?"); // Je cherche dans la table users un email qui correspond à celui fourni
    $sql->execute([$email]);
    return $sql->fetch(); 
}
// Créer un nouvel l'utilisateur
public static function insertUser($prenom, $nom, $mail, $numerotel, $prefix, $motdepasse) 
{
    $pdo = Database::pdo();
    $stmt = $pdo->prepare("INSERT INTO users (firstname, lastname, email, phone, phone_prefix, password) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$prenom, $nom, $mail, $numerotel, $prefix, $motdepasse]);
    return $pdo->lastInsertId(); // Retourne l'ID de l'utilisateur créé car sinon on ne peut pas récupérer l'ID de l'utilisateur pour la suite (envoi email de confirmation)
}
// Stocker le token de confirmation email
public static function insertEmailToken($user_id, $token, $expiry_date) 
{
    $pdo = Database::pdo();
    $stmt_token = $pdo->prepare("INSERT INTO email_confirmations (user_id, confirmation_token, expiry_date) VALUES (?, ?, ?)");
    $stmt_token->execute([$user_id, $token, $expiry_date]);
}
public static function ChercherLeToken($token) {
    $pdo = Database::pdo();
     $sql = $pdo->prepare("
        SELECT * 
        FROM email_confirmations 
        WHERE confirmation_token = ? 
        AND used = FALSE 
        AND expiry_date > NOW()
    ");
    // used = FALSE : le token n'a pas encore été utilisé
    // expiry_date > NOW() : le token n'a pas expiré
    
    $sql->execute([$token]);
    $confirmation = $sql->fetch();
    return $confirmation;
}
public static function TokenValide($confirmation)
{
        $pdo = Database::pdo();
        // 7. Je marque le token comme utilisé
        // Ici, je mets à jour la table email_confirmations pour indiquer que le token a été utilisé
        $update_token = $pdo->prepare("UPDATE email_confirmations SET used = TRUE WHERE id = ?"); // used = TRUE : le token a été utilisé
        $update_token->execute([$confirmation['id']]);
        
        // 8. Ici, vous pourriez ajouter une colonne "email_verified" dans users
        $update_user = $pdo->prepare("UPDATE users SET email_verified = TRUE WHERE id = ?");
        $update_user->execute([$confirmation['user_id']]);
        
}


// === POUR LE TRAITEMENT DE LA CONNEXION ===

/**
 * Prend en paramètre une instance de pdo connecté à la BDD et un email, puis cherche dans la bdd si il y a un utilisateur avec cet email
 * @param string $email email de l'utilisateur à chercher
 * @return array|false Utilisateur trouvé
 */
public static function UsersEmail(string $email): array|false // Trouve l'utilisateur par son email
{
    $pdo = Database::pdo(); 
    $smtp = $pdo->prepare("SELECT * FROM users WHERE email = ? ");
    $smtp->execute([$email]);
    $utilisateur = $smtp->fetch();
    return $utilisateur;
}
public static function getUserById($user_id) // Trouve l'utilisateur par son ID
{
    $pdo = Database::pdo(); 
    $smtp = $pdo->prepare("SELECT * FROM users WHERE id = ? ");
    $smtp->execute([$user_id]);
    return $smtp->fetch();
}

public static function TentativesConnexionReset(string $email) // Remet le compteur de tentative à zéro
{
    $pdo = Database::pdo(); 
    // On remet le compteur de tentative à zéro
    $smtp = $pdo->prepare("UPDATE users SET login_attempts = 0, last_login_attempts = NULL WHERE email = ?");
    $smtp->execute([$email]);
}

public static function AugmenterNbrTentatives(string $email, int $tentative) // Incrémente le nombre de tentative échouée (anti-bruteforce)
// Commentaire : on incrémente le nombre de tentative échouée et on met à jour la date de la dernière tentative
// ça sert à limiter les tentatives de connexion (bruteforce)
// Si il y a 5 tentatives échouées, on bloque la connexion pendant 15 minutes
{
    $pdo = Database::pdo(); 
    $smtp = $pdo->prepare("UPDATE users SET login_attempts = ?, last_login_attempts = CURRENT_TIMESTAMP() WHERE email = ?");
    $smtp->execute([$tentative, $email]);
}

// === POUR LA GESTION DES UTILISATEURS ===
public static function deleteUser($userId) // Supprimer un utilisateur par son ID
{
    $db = Database::pdo();
    $stmt = $db->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$userId]);
}

// === POUR LA RÉINITIALISATION MOT DE PASSE ===

public static function insertPasswordResetToken($user_id, $token, $expiry_date) { // Pour insérer un token de réinitialisation de mot de passe
    $pdo = Database::pdo();
    // Préparation d'une requête pour insérer le token dans la table "password_reset"
    $stmt_token = $pdo->prepare("INSERT INTO password_reset (user_id, reset_token, expiry_date) VALUES (?, ?, ?)");
    // Exécution de la requête avec l'ID de l'utilisateur, le token et la date d'expiration
    $stmt_token->execute([$user_id, $token, $expiry_date]);
    // Je lie le token à l'utilisateur que je viens de créé
}


public static function findPasswordResetToken($token) { // Pour trouver un token de réinitialisation de mot de passe
    $pdo = Database::pdo();
    // 5. Je cherche ce token dans ma base de données PASSWORD_RESET 
    $sql = $pdo->prepare("
        SELECT * 
        FROM password_reset 
        WHERE reset_token = ? 
        AND used = FALSE 
        AND expiry_date > NOW()
    ");
    // used = FALSE : le token n'a pas encore été utilisé
    // expiry_date > NOW() : le token ne doit pas être expiré
    // reset_token = ? : Le token doit correspondre exactement
    // ? En gros ça veut dire "Trouve moi un token qui correspond à "Tkoa5984a2", qui n'a pas encore été utilisé et qui
    // ? n'est pas expiré
    
    $sql->execute([$token]);
    $confirmation = $sql->fetch();
    return $confirmation;
}


public static function updateUserPassword($user_id, $nouveau_mdp_hash) { // Pour mettre à jour le mot de passe d'un utilisateur
    $pdo = Database::pdo();
    // Nous devons mettre le mot de passe à jour (crud)
    // Changer le mot de passe dans la base de données
    $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
    $stmt->execute([$nouveau_mdp_hash, $user_id]);
}


public static function markPasswordResetTokenAsUsed($token_id) { // Pour marquer un token de réinitialisation de mot de passe comme utilisé
    $pdo = Database::pdo();
    // Marquons le token comme utilisé
    $stmt_token = $pdo->prepare("UPDATE password_reset SET used = TRUE WHERE id = ?");
    $stmt_token->execute([$token_id]);
}
    


}

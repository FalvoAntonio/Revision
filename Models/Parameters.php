<?php

namespace App\Models;
use App\Includes\Database;

class Parameters
{
    // === POUR LA MODIFICATION DE L'EMAIL DANS LES PARAMÈTRES DU COMPTE ===
public static function updateUserEmail($user_id, $nouvel_email) {
    $pdo = Database::pdo();
    $stmt = $pdo->prepare("UPDATE users SET email = ? WHERE id = ?");
    return $stmt->execute([$nouvel_email, $user_id]);
}

// === POUR LA MODIFICATION DU MOT DE PASSE DANS LES PARAMÈTRES DU COMPTE ===
 public static function updateUserPassword($user_id, $nouveau_mdp_hash) {
        $pdo = Database::pdo();
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        return $stmt->execute([$nouveau_mdp_hash, $user_id]);
    }
}

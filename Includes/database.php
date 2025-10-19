<?php

namespace App\Includes;

use PDO;
// PDO : Interface PHP pour se connecter à MySQL
// final : permet que cette classe ne peut pas être héritée
final class Database
{
    private static ?PDO $pdo = null; // Stock l'unique instance 

    public static function pdo(): PDO
    {
        if (!self::$pdo) { // Si l'instance n'existe pas encore
            // On la crée une seule fois
            $config = require APP_ROOT . '/../config/config.php';
            self::$pdo = new PDO($config['db']['dsn'], $config['db']['user'], $config['db']['pass'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, 
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ]);
        }
        return self::$pdo; // On retounre toujours la même instance

        // J'insère dans la propriété $pdo une nouvelle instance de pdo en lui donnant en paramètres le DSN, l'utilisateur et le mot de passe
        // Et les options de pdo, comme le mode d'erreur et le mode de récupération par défaut
    }
}

// Explications :
// - new PDO() = Crée une nouvelle connexion à la base de données
// - $cfg['db']['dsn'] = Récupère le DSN depuis la config


// database.php : utilise les variables
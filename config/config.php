<?php
// config.php : Centralise toute la configuration 
// C'est le fichier de configuration centre qui dit à mon application:
// -Comment se connecter à la base de données
// -En quel mode elle foctionne (développement ou production)
// -Ou trouver les fichiers

// Rôle: 
// -Variables d'environnement: Récupère les paramètres depuis Docker (DSN,utilisateur,mot de passe)
// -Mode app: dev ou prod
// -Paramètres BDD : Connexion à MySQL
// -DSN = Data Source Name, c'est l'adresse de ma BDD: mysql:host=db;dbname=endless_db

// On Retourne un tableau :

return [
    // Config pour l'application
    'app' => [
        'name' => 'Endless V3',
        'mode' => getenv('APP_ENV') ?: 'prod',
        // Sécurité
        // On récupère la variable d'environnement APP_ENV, "?:" = Opérateur ternaire : " si vide, alors"
        // "prod" = valeur par défaut si APP_ENV n'existe pas
        "debug" => filter_var(getenv('DEBUG'), FILTER_VALIDATE_BOOLEAN) ?: false,
        // On récupère la variable "DEBUG" avec et on converti en true/false
        // Si true: Affiche tous les messages d'erreur détaillés, si false: Masque les erreurs sensibles
        'base_url' => '/',

    ],
    // Techniquement on récupère sur docker "docker-compose"
    // On récupère les informations du fichier ".env" avec getenv('')
    // php récupère les variables

    // Config pour la base de données
    'db' => [
        'dsn'  => getenv('DSN'), // = mysql:host=db;dbname=endless_db
        'user' => getenv('DB_USER'), // = endless_user
        'pass' => getenv('DB_PASSWORD') // = endless_password
    ]
];



// ? Fichier .env:

// APP_ENV=DEV
// DEBUG=true
// DB_HOST=localhost
// DB_NAME=endless_db
// DB_USER=endless_user
// DB_PASSWORD=endless_password
// MYSQL_ROOT_PASSWORD=root_password
// DSN="TEST-mysql:host=${DB_HOST};dbname=${DB_NAME};charset=utf8mb4"

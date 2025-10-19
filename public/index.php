<?php
define('APP_ROOT', __DIR__);
// const APP_ROOT = __DIR__;

// index.php ne fait que 3 choses :

// 1. Autoload (charge les classes)
// 2. Config (paramètres)
// 3. Routes (le routeur)


// Charge l'autoload de Composer
// Il permet de charger automatiquement les classes PHP, plutôt que de devoir les require manuellement
// Il se sert des namespaces et de la structure des dossiers pour retrouver les classes

require_once APP_ROOT . '/../vendor/autoload.php';


// Charge la config en premier
$config = require APP_ROOT . '/../config/config.php';



require_once APP_ROOT . '/../includes/routes.php';

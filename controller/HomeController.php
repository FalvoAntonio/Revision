<?php


use App\Includes\DebugBar;

session_start();

// Récupère le middleware (singleton)
class HomeController
{
    private DebugBar $debug;

    public function __construct()
    {
        $this->debug = DebugBar::getInstance();
    }

    // === PAGE ACCUEIL ===
    public function accueil()
    {
        require_once APP_ROOT . '/../includes/MessageFlash.php'; // Pour les messages flash

     // Récupérer les formations depuis la BDD
    // $formations = Training::getAllTrainingsBySlug($slug);
        $additionalCss = ["/assets/css/02-home.css"];
        $tabTitle = "Accueil - Nails Endless Beauty";
        render('home.accueil', compact('tabTitle', 'additionalCss'));
    }
    // === PAGE ABOUT ===
    public function about()
    {
        $additionalCss = ["/assets/css/20-about-us.css"];
        $tabTitle = "Qui Sommes nous - Nails Endless Beauty";
        render('home.about', compact('tabTitle', 'additionalCss'));
    }


    public function monEspace()
    {
        // Vérifier que l'utilisateur est connecté 
        if (!isset($_SESSION["logged"]) || !isset($_SESSION["user_id"])) {
            header("Location: /connexion");
            exit;
        }
        $tabTitle = "Mon Espace - Nails Endless Beauty";
        $additionalCss = ["/assets/css/16-user-dashboard.css"];
        render('home.monespace', compact('tabTitle', 'additionalCss'));
    }

    public function parametres()
    {
        require_once APP_ROOT . '/../Includes/FormSecurity.php'; // Pour les tokens CSRF
        $tabTitle = "Paramètres - Nails Endless Beauty";
        $additionalCss = ["/assets/css/17-user-setting.css"];
        render('home.parametres', compact('tabTitle', 'additionalCss'));
    }

    public function contact()
    {
        $tabTitle = "Contact - Nails Endless Beauty";
        $additionalCss = ["/assets/css/25-contact.css"];
        render('home.contact', compact('tabTitle', 'additionalCss'));
    }
    public function monProfil()
    {
        $tabTitle = "Mon Profil - Nails Endless Beauty";
        $additionalCss = ["/assets/css/26-user-profile.css"];
        render('home.myprofile', compact('tabTitle', 'additionalCss'));
    }
}


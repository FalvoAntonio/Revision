<?php
$debug = \App\Includes\DebugBar::getInstance();
$isLogged = isset($_SESSION["logged"]);
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="author" content="Endless Beauty">
    <meta name="description" content="Centre de formation en ligne spécialisé dans les soins des ongles et des pieds. Découvrez nos formations professionnelles pour devenir un expert en manucure, pédicure, nail art et plus encore. Apprenez à votre rythme avec nos cours en ligne interactifs et développez vos compétences dans l'industrie de la beauté. Rejoignez-nous dès aujourd'hui et donnez vie à votre passion pour les soins des ongles !">
    <meta name="keywords" content="formation ongles, formation pédicure, formation manucure, formation nail art, cours en ligne ongles, soins des ongles, formation professionnelle beauté, apprentissage à distance ongles, techniques de manucure, techniques de pédicure, design d'ongles, certification ongles, formation styliste ongulaire, formation prothésiste ongulaire, formation vernis semi-permanent">
    <meta name="og:title" content="Endless Beauty - Centre de formation en ligne pour les soins des ongles et des pieds">
    <meta name="og:description" content="Découvrez nos formations professionnelles en ligne pour développer vos compétences en soins des ongles et des pieds. Apprenez à votre rythme avec nos experts.">
    <!-- A changer le jour de la mise en ligne du site -->
    <meta name="og:image" content="https://localhost/assets/images/Logo Endless.png"> 
    <?= $debug->renderHead() ?>
    <!-- CSS principal -->
    <link rel="stylesheet" href="/public/styles.css"> 
    <!-- CSS supplémentaires spécifiques à la page -->
    <?php if (isset($additionalCss)): ?>
        <?php foreach ($additionalCss as $cssFile): ?>
            <link rel="stylesheet" href="<?= $cssFile ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <!-- Google reCAPTCHA -->
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <!-- Titre de la page -->
    <title><?= $tabTitle ?></title>
</head>

<body>
    <!-- HEADER -->
    <header>
        <nav class="Menu">
            <div class="Menu-Titre">
                <ul>
                    <li><a href="/about">Qui sommes-nous ?</a></li>
                    <li><a href="/liste-formations">Formations</a></li>
                    <li><a href="/contact">Contact</a></li>
                    <li><a href="/liste-services">Prendre rendez-vous</a></li>
                </ul>
                <a href="/accueil" class="menu-titre-link">
                    <img class="headerlogo" src="/assets/images/Logo Endless.png" alt="Titre header">
                    <p>Centre de formation</p>
                </a>
            </div>

            <div class="icons-menu">
                <div class="logomenuburger">
                    <span class="span1"></span>
                    <span class="span2"></span>
                    <span class="span3"></span>
                </div>
                
                <img class="icon icon-recherche" src="/assets/images/Recherche.svg" alt="Ma Recherche" />
                
                <?php if($isLogged): ?>
                    <a href="/mon-espace">
                <?php else: ?>
                    <a href="/connexion">
                <?php endif; ?>
                    <img class="icon icon-login <?= $isLogged ? 'connected' : ''; ?>" src="/assets/images/Login.png" alt="Identifiant" />
                </a>
                
                <img class="icon icon-panier" src="/assets/images/Panier.png" alt="Mon Panier" />
            </div>
        </nav>
    </header>

    <!-- MENU UTILISATEUR -->
    <nav>
        <div class="container-menu-compte" id="menu-utilisateur">
            <div class="menu-compte">
                <div class="menu-content">
                    <a href="/mon-espace">Mon espace</a>
                    <a href="/mon-profil">Profil</a>
                    <a href="/mes-rendez-vous">Mes rendez-vous</a>
                    <a href="">Achats</a>
                    <a href="">Documents</a>
                    <a href="/parametres">Paramètres</a>
                    <a href="/deconnexion">Déconnexion</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- CONTENU PRINCIPAL INJECTER ICI -->
    <?= $content ?>

    <!-- FOOTER -->
    <footer>
        <div class="footer-content">
            <!-- DESCRIPTION -->
            <div class="footer-brand">
                <div class="footer-name">Nails Endless Beauty</div>
                <p class="footer-description">
                    Découvrez nos formations professionnelles en ligne pour développer vos compétences en soins des ongles et des pieds. Apprenez à votre rythme avec nos experts.
                </p>
            </div>

            <!-- CONTACT -->
            <div class="footer-contact">
                <h3>Contact</h3>
                <div class="contact-item">Endlessbeauty.lc@gmail.com</div>
                <div class="contact-item">06 71 54 13 54</div>
            </div>

            <!-- RESEAUX SOCIAUX -->
            <div class="footer-social">
                <h3>Suivez-nous</h3>
                <div class="social-links">
                    <a href="https://www.tiktok.com/@endless.beauty8?_t=ZN-8wNbi4AV1cs&_r=1" target="_blank" rel="noopener" class="social-link">
                        <img src="/assets/images/icons8-tiktok.svg" alt="TikTok Logo">
                    </a>
                    <a href="https://www.instagram.com/endlessbeauty_nailss/" target="_blank" rel="noopener" class="social-link">
                        <img src="/assets/images/Icone-Instagram.svg" alt="Instagram Logo">
                    </a>
                </div>
            </div>
        </div>

        <!-- COPYRIGHT EN BAS -->
        <div class="footer-bottom">
            <p class="copyright">&copy; 2025 Nails Endless Beauty - Tous droits réservés</p>
        </div>
    </footer>
    
<!-- MODALES -->
    <!-- Modale Recherche -->
    <div class="overlay-modal overlay-recherche">
        <div class="Modal-Recherche">
            <button class="button-close">X</button>
            <h1>Ma recherche</h1>
            <div class="recherche-container">
                <form id="rechercheForm" action="" method="get">
                    <div class="search-wrapper">
                        <input type="text" id="rechercheInput" name="recherche" placeholder="Rechercher une formation" required />
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modale Panier -->
    <div class="overlay-modal overlay-panier">
        <div class="Modal-container">
           <button class="button-close">X</button>
           <h1>Mon panier</h1>
           <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Alias vel impedit nisi magnam placeat? Quia optio beatae ducimus. Unde voluptates eum enim quo corrupti omnis corporis nihil aliquid et reiciendis!</p>
           <button class="btn-form">Paiement</button>
        </div>
    </div>

    <!-- SCRIPTS JAVASCRIPT -->
   <script type="module" src="/assets/js/script.js"></script>
   
</body>
</html>
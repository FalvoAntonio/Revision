<!-- IMAGE SOUS HEADER -->
<div class="container-image-header">     
    <img src="/assets/Images/image-sous-header.jpg" alt="Salon de beauté professionnel">   
</div>

<!-- CONTENU PRINCIPAL -->
<main class="main-content accueil">
    <!-- Messages flash (si la fonction existe) -->
    <?php if (function_exists('afficheMessageFlash')): ?>
        <?php afficheMessageFlash("Message-confirmation-envoi-mail"); ?>
        <?php afficheMessageFlash("suppression-compte"); ?>
    <?php endif; ?>

    <!-- SECTION INTRODUCTION -->
    <section class="introduction">
        <h2>Bienvenue dans notre univers de formations beauté</h2>
        <p>
            Découvrez nos formations professionnelles en ligne pour développer vos
            compétences en soins des ongles et des pieds. Nos cours vidéo vous
            permettent d'apprendre à votre rythme et de maîtriser les dernières
            techniques du secteur.
        </p>
    </section>

    <!-- SECTION PRÉSENTATION SALON -->
    <section class="salon-presentation">
        <!-- Images du salon à gauche -->
        <div class="image-container">
            <img src="/assets/Images/Photos QuiSommesNous/OPX05269.jpg" alt="Salon Endless Beauty" class="salon-image">
            <img src="/assets/Images/Photos Accueil/IMG_3655.jpeg" alt="Salon Endless Beauty" class="salon-image">
        </div>

        <!-- Texte à droite -->
        <div class="text-container">
            <h2>Bienvenue chez Endless Beauty</h2>
            <p>Découvrez notre <span class="highlight">salon professionnel</span> dédié aux soins des ongles et à la beauté des pieds. Un espace moderne et chaleureux où se mélangent expertise technique et détente absolue.</p>
            
            <p>Notre équipe de <span class="highlight">professionnelles certifiées</span> vous accompagne dans l'apprentissage des techniques les plus avancées du secteur, de la manucure russe aux extensions Gel X.</p>
            
            <p>Grâce à nos <span class="highlight">formations en ligne</span>, vous pouvez désormais apprendre à votre rythme les secrets d'un travail de qualité professionnelle, directement depuis chez vous.</p>
            
            <p>Rejoignez-nous dans cette aventure beauté et développez vos compétences avec les meilleures techniques du marché !</p>
            
            <a href="/liste-formations" class="discover-button btn">Découvrir nos formations</a>
        </div>
    </section>

    <!-- TITRE FORMATIONS -->
    <div class="formations-title">
        <h2>Nos Formations</h2>
        <p>Explorez un univers complet dédié à la beauté des mains et des pieds. Nos formations en ligne couvrent un éventail de techniques modernes : manucure russe, extensions Gel X, soins des pieds et traitements spécifiques. Conçus pour les débutants comme pour les professionnels en perfectionnement, nos modules vous offrent un apprentissage précis, accessible à tout moment, pour maîtriser les gestes qui font la différence.</p>
    </div>

    <!-- CONTAINER FORMATIONS -->
    <div class="formations-container flex-container">
        <!-- Formation 1: Manucure Russe -->
        <div class="formation1 formation">
            <div class="formation-image">
                <img src="./assets/Images/Photos Accueil/IMG_3652.jpeg" alt="Manucure Russe" />
            </div>
            <h1>Manucure Russe</h1>
            <p class="contenu"><img src="./assets/Images/Logo-Contenu.png" alt="Logo-Contenu">Contenu: Gainage + Couleur sous cuticule</p>
            <p class="duree"><img src="./assets/Images/Logo-Durée.png" alt="Logo-Durée">Durée: 1H30 à 2H de vidéos</p>
            <p class="niveau"><img src="./assets/Images/Logo-Niveau.png" alt="Logo-Niveau">Niveau: Débutant à intermédiaire</p>
            <p class="prix">150€</p>
            <a href="/formation/manucure_russe" class="button-formation btn">Découvrir cette formation</a>
        </div>

        <!-- Formation 2: Extension Gel X -->
        <div class="formation2 formation">
            <div class="formation-image">
                <img src="./assets/Images/Photos Accueil/IMG_3653.jpeg" alt="Extension Gel X" />
            </div>
            <h1>Extension Gel X</h1>
            <p class="contenu"><img src="./assets/Images/Logo-Contenu.png" alt="Logo-Contenu">Contenu: Techniques d'extension Gel X</p>
            <p class="duree"><img src="./assets/Images/Logo-Durée.png" alt="Logo-Durée">Durée: 2H à 2H30 de vidéos</p>
            <p class="niveau"><img src="./assets/Images/Logo-Niveau.png" alt="Logo-Niveau">Niveau: Débutant à intermédiaire</p>
            <p class="prix">200€</p>
            <a href="/formation/extension_gel_x" class="button-formation btn">Découvrir cette formation</a>
        </div>

        <!-- Formation 3: Beauté des pieds -->
        <div class="formation3 formation">
            <div class="formation-image">
                <img src="./assets/Images/Photos Accueil/IMG_3654.jpeg" alt="Beauté des pieds" />
            </div>
            <h1>Beauté des pieds</h1>
            <p class="contenu"><img src="./assets/Images/Logo-Contenu.png" alt="Logo-Contenu">Contenu: Pédicure Russe + Gainage</p>
            <p class="duree"><img src="./assets/Images/Logo-Durée.png" alt="Logo-Durée">Durée:: 1H30 à 2H de vidéos</p>
            <p class="niveau"><img src="./assets/Images/Logo-Niveau.png" alt="Logo-Niveau">Niveau: Débutant à intermédiaire</p>
            <p class="prix">180€</p>
            <a href="/formation/beaute_des_pieds" class="button-formation btn">Découvrir cette formation</a>
        </div>

        <!-- Formation 4: Anti-callosité -->
        <div class="formation4 formation">
            <div class="formation-image">
                <img src="./assets/Images/Photos Accueil/IMG_3655.jpeg" alt="Soin Anti-callosité" />
            </div>
            <h1>Soin Anti-callosité des pieds</h1>
            <p class="contenu"><img src="./assets/Images/Logo-Contenu.png" alt="Logo-Contenu">Contenu: Traitement & soin des callosités</p>
            <p class="duree"><img src="./assets/Images/Logo-Durée.png" alt="Logo-Durée">Durée: 1H à 1H30</p>
            <p class="niveau"><img src="./assets/Images/Logo-Niveau.png" alt="Logo-Niveau">Niveau: Débutant à intermédiaire</p>
            <p class="prix">120€</p>
            <a href="/formation/soin_anti-callosite_des_pieds" class="button-formation btn">Découvrir cette formation</a>
        </div>

        <!-- Formation 5: Pack Complet -->
        <div class="formation5 formation"> 
            <div class="formation-image"> 
                <img src="./assets/Images/Photos Accueil/IMG_3656.jpeg" alt="Pack Complet" />
                <img class="logo-promo" src="./assets/Images/Logo-Promo.png" alt="Logo-Promo">
            </div>
            <h1>PACK COMPLET</h1>
            <p class="contenu"><img src="./assets/Images/Logo-Contenu.png" alt="Logo-Contenu">Contenu: Tous les modules</p>
            <p class="duree"><img src="./assets/Images/Logo-Durée.png" alt="Logo-Durée">Durée: Environ 8H de vidéos</p>
            <p><img src="./assets/Images/Logo-Diamant.png" alt="Logo-Diamant">Formation complète professionnelle</p>
            <p class="prix">
                <img src="./assets/Images/Logo-Prix.png" alt="Logo-Prix">
                <span class="prix-barre">500€</span>
                <span class="prix-promo">450€</span>
            </p>
                 <a href="/formation/pack_complet" class="button-formation btn">
                <img src="./assets/Images/Logo-Hot.png" alt="Logo Hot">OFFRE SPECIALE<img src="./assets/Images/Logo-Hot.png" alt="Logo Hot"></a>
        </div>
    </div>
</main>

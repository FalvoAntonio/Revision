<!-- SECTION TITRE DE LA PAGE -->
<section class="title-section">
    <h1>Mes Informations Personnelles</h1>
    <p class="subtitle">Consultez vos données personnelles et votre historique</p>
</section>

<!-- IMAGE D'EN-TÊTE -->
<div class="container-image-header">     
   <img src="/assets/images/Page Mon Espace/parametres-header.jpg" alt="informations personnelles">
</div>

<!-- CONTENU PRINCIPAL -->
<div class="container">
    
    <!-- INFORMATIONS PERSONNELLES -->
    <div class="info-section">
        <h2>Informations de base</h2>
        
        <div class="personal-info">
            <div class="info-item">
                <span class="info-label">Nom :</span>
                <span class="info-value"><?= htmlspecialchars($_SESSION["user_lastname"]) ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Prénom :</span>
                <span class="info-value"><?= htmlspecialchars($_SESSION["user_firstname"]) ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Téléphone :</span>
                <span class="info-value">
                    <!-- À récupérer depuis la base de données -->
                    06 XX XX XX XX
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Adresse mail :</span>
                <span class="info-value"><?= htmlspecialchars($_SESSION["user_email"]) ?></span>
                <!-- htmlspecialchars() permet d'éviter les failles XSS en échappant les caractères spéciaux -->
            </div>
        </div>
        
        <div class="action-buttons">
            <a href="/parametres" class="btn">Modifier mes informations</a>
        </div>
    </div>
    
    <!-- SUIVI GLOBAL -->
    <div class="info-section">
        <h2>Suivi global</h2>
        
        <div class="stats-overview">
            <div class="stat-box">
                <div class="stat-title">Nombre de rendez-vous effectués</div>
                <div class="stat-number">
                    <!-- À calculer depuis la base de données -->
                    5 rendez-vous
                </div>
            </div>
            
            <div class="stat-box">
                <div class="stat-title">Date du dernier rendez-vous</div>
                <div class="stat-date">
                    <!-- À récupérer depuis la base de données -->
                    15 novembre 2024
                </div>
            </div>
        </div>
        
        <!-- SERVICES LES PLUS PRISÉS -->
        <div class="favorite-services">
            <h3>Vos services préférés</h3>
            
            <div class="service-stats">
                <div class="service-item">
                    <span class="service-name">Manucure Russe</span>
                    <span class="service-count">3 fois</span>
                </div>
                
                <div class="service-item">
                    <span class="service-name">Gainage nude</span>
                    <span class="service-count">2 fois</span>
                </div>
                
                <div class="service-item">
                    <span class="service-name">Beauté des pieds</span>
                    <span class="service-count">1 fois</span>
                </div>
            </div>
        </div>
    </div>
</div>
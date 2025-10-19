<section class="title-section">
    <h1>Mes Rendez-vous</h1>
    <p class="subtitle">Consultez et gérez vos rendez-vous à venir</p>
</section>

<div class="container-image-header">     
   <img src="/assets/images/Page Mon Espace/rdv-header.jpg" alt="photo rendez-vous">
</div>
<h2 class="h2-styled">Soyez prêt pour votre rendez-vous</h2>
<div class="container appointments-container">
    <h2>Mes prochains rendez-vous</h2>
    
    <?php if (empty($rendezVous)): ?>
        <!-- Si l'utilisateur n'a aucun rendez-vous -->
        <div class="no-appointments">
            <p>Vous n'avez aucun rendez-vous programmé.</p>
            <a href="/liste-services" class="btn">Prendre un rendez-vous</a>
        </div>
    <?php else: ?>
        <!-- Si l'utilisateur a des rendez-vous -->
        <div class="appointments-list">
            <?php foreach ($rendezVous as $rdv): ?>
                <div class="appointment-card">
                    <div class="appointment-date">
                        <p><strong>Date :</strong><?= date('d/m/Y', strtotime($rdv['date_rdv'])) ?></p>
                        <p><strong>Durée :</strong><span><?= $rdv['heure_rdv'] ?></span></p>
                    </div>
                    
                    <div class="appointment-details">
                        <h3><?= htmlspecialchars($rdv['service_nom']) ?></h3>
                        <p><strong>Durée :</strong> <?= $rdv['service_duree'] ?> minutes</p>
                        <p><strong>Prix :</strong> <?= $rdv['service_prix'] ?>€</p>
                        <p><strong>Prothésiste :</strong> <?= htmlspecialchars($rdv['prothesiste']) ?></p>
                        
                        <?php if (!empty($rdv['notes'])): ?>
                            <p><strong>Notes :</strong> <?= htmlspecialchars($rdv['notes']) ?></p>
                        <?php endif; ?>
                    </div>
                    
                    <div class="appointment-status">
                        <span class="status-badge status-<?= $rdv['statut'] ?>">
                            <?= ucfirst($rdv['statut']) ?>
                        </span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
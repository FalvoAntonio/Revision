<div class="container">
        <div class="confirmation-card">
            <div class="success-icon">
                ✅
            </div>
            
            <h1>Rendez-vous confirmé !</h1>
            <p class="subtitle">Votre réservation a été enregistrée avec succès</p>
            
            <div class="rdv-details">
                <h2>📋 Récapitulatif de votre RDV</h2>
                
                <div class="detail-row">
                    <span class="label">📅 Date :</span>
                    <span class="value"><?= $date_rdv ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">🕐 Heure :</span>
                    <span class="value"><?= $heure_rdv ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">💅 Prestation :</span>
                    <span class="value"><?= htmlspecialchars($service['nom']) ?></span>
                </div>
                
                <div class="detail-row">
                    <span class="label">⏱️ Durée :</span>
                    <span class="value"><?= $service['duree'] ?> minutes</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">💰 Prix :</span>
                    <span class="value"><?= number_format($service['prix'], 2) ?>€</span>
                </div>
                
                <div class="detail-row">
                    <span class="label">👩‍💼 Prothésiste :</span>
                    <span class="value"><?= htmlspecialchars($prothesiste) ?></span>
                </div>
                
                <?php if (!empty($notes['notes'])): ?>
                <div class="detail-row">
                    <span class="label">📝 Notes :</span>
                    <span class="value"><?= nl2br(htmlspecialchars($notes)) ?></span>
                </div>
                <?php endif; ?>
            </div>
            
            <div class="salon-info">
                <h3>📍 Adresse du salon</h3>
                <p>37 Rue de la Cousinerie<br>59650 Villeneuve-d'Ascq</p>
            </div>
            
            <div class="email-status">
                <?php if ($email_sent): ?>
                    <div class="email-success">
                        ✉️ Emails de confirmation envoyés avec succès !<br>
                        <small>Vous et le salon avez reçu un email de confirmation</small>
                    </div>
                <?php else: ?>
                    <div class="email-error">
                        ⚠️ RDV enregistré mais problème d'envoi d'email<br>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="important-info">
                <h3>ℹ️ Informations importantes</h3>
                <ul>
                    <li>Merci d'arriver 5 minutes avant votre RDV</li>
                    <li>En cas d'empêchement, prévenez-nous au moins 24h à l'avance</li>
                    <li>N'hésitez pas à nous contacter pour toute question</li>
                </ul>
            </div>
            
            <div class="action-buttons">
                <a href="./liste-services" class="btn btn-secondary">
                    📅 Prendre un autre RDV
                </a>
                <a href="/accueil" class="btn btn-primary">
                    🏠 Retour à l'accueil
                </a>
            </div>
        </div>
    </div>
    
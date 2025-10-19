
// Cette ligne va attendre que la page soit complétement chargee avant d'exécuter le code 
// * DOMContentLoaded = "Attendre que toute la page HTML soit construite avant d'exécuter mon code"
// Sans ça mon code pourrait essayer de récupérer un élément qui n'existe pas encore 
document.addEventListener('DOMContentLoaded', function() {

    const dateInput = document.getElementById('date_rdv'); // Le champ pour choisir la date 
    const heureSelect = document.getElementById('heure_rdv'); // La liste déroulante pour l'heure 
    const infoCreneaux = document.getElementById('info-creneaux'); // Zone pour afficher les informations
    // On va récupérer les "id" du code html
    
    // ! LORSQU'ON CHANGE DE DATE 
    // Événement quand la date change
    dateInput?.addEventListener('change', function() {
        // 'change' = "Quand la valeur de cet élément change"
        // function() = "Voici ce qu'il faut faire quand ça arrive"
        // * L'utilisateur a changé la date

        // this = "l'élément qui a déclenché l'événement" (ici, le champ de date)
        const selectedDate = this.value;
        // * Nouvelle date sélectionnée
        
        // Réinitialiser la sélection d'heure
        heureSelect.value = '';
        // * Sélection d'heure remise à zéro
        
        // ! VERIFICATION DE LA DATE 
        // Vérifications côté client

        if (selectedDate) { // Si une date a été sélectionnée
            const today = new Date(); // Date d'aujourd'hui
            const selected = new Date(selectedDate); // Date sélectionnée par l'utilisateur 
            
            // Vérifier si c'est dans le passé
            if (selected < today.setHours(0,0,0,0)) {
                // Si la date sélectionné par l'utilisateur est plus petite est la date du jour
                // Tu mets l'alerte
                alert('❌ Vous ne pouvez pas réserver dans le passé !');
                this.value = ''; // On vide le champ date
                heureSelect.innerHTML = '<option value="">Sélectionnez d\'abord une date</option>';
                infoCreneaux.textContent = '';
                return; // On arrête ici pas besoin d'aller plus loin 
            }
        }
        
        // Mettre à jour les créneaux
        // Si tout va bien et pas d'erreur on met les créneaux disponibles 
        updateCreneauxDisponibles(selectedDate);
    });
    
    
    // Fonction pour mettre à jour les créneaux disponibles


    // Pourquoi utiliser "async" ? : Cette fonction peut faire des choses qui prennent du temps
    // Par exemple : aller chercher des données sur le serveur
    // On attend des réponses avant de continuer
    // Avec "await" on doit attendre que cette action soit terminée avant de continuer
    async function updateCreneauxDisponibles(selectedDate) {
    // date = paramètre "2024-07-15" (format AAAA-MM-JJ)
    // Si pas de date on arrête tout de suite 
        if (!selectedDate) {
            // Et on affiche le message par défaut
            heureSelect.innerHTML = '<option value="">Sélectionnez d\'abord une date</option>';
            //Définir la valeur de innerHTML vous permet de remplacer aisément le contenu existant d'un élément par un nouveau contenu.
            // SI ON A PAS DE DATE ON AFFICHE LE MESSAGE PAR DEFAUT
            infoCreneaux.textContent = '';
            // return = Stop sors de cette fonction maintenant
            return;
        }
        
        // Afficher un message de chargement

        // disabled = true signifie "griser l'élément, l'utilisateur ne peut plus cliquer dessus"
        heureSelect.disabled = true;
        // On désactive la liste des heures pendant le chargement
        // On met un message dans la liste déroulante pour indiquer que ça charge
        heureSelect.innerHTML = '<option value="">⏳ Chargement des créneaux...</option>';
        infoCreneaux.textContent = '';

        // * POURQUOI Utiliser TRY/CATCH ?
        // try/catch = "Essaie de faire ça, mais si ça plante, voilà ce qu'il faut faire"
        // C'est comme avoir un filet de sécurité en cas d'erreur
        try {
            // Appeler notre script PHP en mode AJAX

            // fetch = "Va chercher des données sur le serveur"
            // C'est comme envoyer un message à quelqu'un et attendre sa réponse
            const response = await fetch(`/api/timeslots?ajax=creneaux&date=${selectedDate}`);
            // On envoie une demande au fichier PHP avec la date en paramètre
            // await = "Attendre que le serveur réponde avant de continuer"
            // ! TRANSFORMATION DE LA REPONSE
            // Le serveur nous renvoie du JSON
            const data = await response.json();
            // Transforme la réponse en objet JavaScript qu'on peut utiliser 
            // await car cette transformation peut prendre du temps

            // * Exemple de ce qu'on reçoit :
            // {
            //   success: true,
            //   creneaux_libres: ["09:00", "09:30", "10:00"],
            //   nb_creneaux: 3
            // }
            console.log(data);
            
            // ! TRAITEMENT DES DONNEES RECUES
            // si le serveur a réussi à récupérer les donnéees
            if (data.success) {
                // On vide complétement la liste des heures pour repartir à zéro 
                heureSelect.innerHTML = ''; // Liste des heures vidée
                
            // ! DANS LE CAS OU : AUNCUN CRENEAU DISPONIBLE
                // Si le tableau est vide, s'il n'y ap as de créneau libre ce jour 
                // C'est le cas si le salon est fermé comme le dimanche ou si tous les rendez-vous de la journée ont déja été pris
                if (data.creneaux_libres.length === 0) {
                    // On vérifie quel jour de la semaine c'est :
                    // getDay() renvoie : 0=Dimanche, 1=Lundi, 2=Mardi ... 6=Samedi
                    // Aucun créneau disponible
                    const dayOfWeek = new Date(selectedDate).getDay();
                    if (dayOfWeek === 0) { // si c'est dimanche
                        heureSelect.innerHTML = '<option value="">Le salon est fermé le dimanche</option>';
                        infoCreneaux.innerHTML = '<span class="info-ferme">🚫 Le salon est fermé le dimanche</span>';
                    } else { // Sinon si tous les créneaux sont occupés
                        heureSelect.innerHTML = '<option value="">Aucun créneau disponible ce jour</option>';
                        infoCreneaux.innerHTML = '<span class="info-complet">😔 Tous les créneaux sont occupés ce jour</span>';
                    }
                } else {
                    // ! DANS LE CAS : OU IL Y A DES CRENEAUX DISPONIBLES
                    // Ajouter l'option par défaut
                    const defaultOption = document.createElement('option'); // Crée un nouvel élément HTML
                    defaultOption.value = ''; // La valeur envoyée au serveur  (vide = rien de sélectionné)
                    defaultOption.textContent = 'Choisir un créneau'; // Le texte affiché à l'utilisateur
                    heureSelect.appendChild(defaultOption); // Ajouter cet élément à la liste
                    
                    // ! BOUCLE SUR CHAQUE CRENEAU
                    // Ajouter les créneaux disponibles
                    // forEach : Pour chaque élément de ce tableau, fais cette action
                    data.creneaux_libres.forEach(creneau => {
                        // Avec data on récupère les données dans le echo_json de la page "gestion des creneaux"
                        // On crée une nouvelle option pour ce créneau
                        const option = document.createElement('option');
                        option.value = creneau; // Ex: 14h30
                        option.textContent = creneau; // Ex: 14h30 affiché à l'utilisateur
                        heureSelect.appendChild(option); // On l'ajoute à la lsite des heures
                    });
                    // !  GESTION DU PLURIEL ET AFFICHAGE
                    // Afficher le nombre de créneaux disponibles
                    const nbCreneaux = data.nb_creneaux;
                    // Ternaire : condition ? valeur_si_vrai : valeur_si_faux
                    const pluriel = nbCreneaux > 1 ? 's' : ''; // Si nbCreneaux est supérieur à 1 alors on met un s sinon on met rien
                    const pluriel2 = nbCreneaux > 1 ? 'x' : ''; // Si nbCreneaux est supérieur à 1 alors on met un x sinon on met rien
                    infoCreneaux.innerHTML = `<span class="info-disponible">✅ ${nbCreneaux} créneau${pluriel2} disponible${pluriel}</span>`;
                }
            } else {
                // ! ERREUR DU SERVEUR
                heureSelect.innerHTML = '<option value="">Erreur lors du chargement</option>';
                infoCreneaux.innerHTML = '<span class="info-erreur">❌ Erreur lors du chargement des créneaux</span>';
            }
        } catch (error) {
                // ! ERREUR DE CONNEXUIN
                // Le catch "attrape" toutes les erreurs qui peuvent survenir dans le try
                // Par exemple : pas de connexion internet, serveur en panne, etc.
            console.error('Erreur:', error);
            heureSelect.innerHTML = '<option value="">Erreur lors du chargement</option>';
            infoCreneaux.innerHTML = '<span class="info-erreur">❌ Erreur de connexion</span>';
            // ! NETTOYAGE FINAL
            // finally = "Fais ça dans TOUS les cas, même s'il y a eu une erreur"
            // C'est comme éteindre la lumière en sortant d'une pièce, peu importe ce qui s'est passé dedans
        } finally {
            heureSelect.disabled = false;
        }
    }
    // ! VALIDATION AVANT ENVOI DU FORMULAIRE
    // Validation avant envoi du formulaire
        // 'submit' = "Quand le formulaire est envoyé"
        // querySelector = "Tout le formulaire"
    document.querySelector('.reservation-form')?.addEventListener('submit', function(e) {

        const date = dateInput.value;
        const heure = heureSelect.value;
        
        // Si la date ou l'heure manque 
        if (!date || !heure) {
            // Alors on empêche l'envoi du formulaire
            e.preventDefault();
            // Et on met un message d'alerte
            alert('❌ Veuillez sélectionner une date et une heure');
            return;
        }

        // ! CONFIRMATION RDV
        // Confirmation finale
        const dateFormatee = new Date(date).toLocaleDateString('fr-FR'); // Date formatée pour affichage 
        const confirmation = confirm( // confirm = "Afficher une popup avec OK/Annuler"
            `Confirmer votre rendez-vous le ${dateFormatee} à ${heure} ?`
        );
        
        // Si l'utilisateur clique sur "Annuler"
        if (!confirmation) {
            e.preventDefault(); // On empêche l'envoi du formulaire
        }
    });
});
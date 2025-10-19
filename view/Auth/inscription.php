
    <div class="container">

        <h1>Créez votre compte</h1>
        <form id="signup-form" action="/verification-de-formulaire-inscription" method="POST">
            <div class="form-groupe">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email" placeholder="votreemail@exemple.com" required>
                <?php MessagesErrorsFormulaire("mail") ?>
            </div>

            <div class="form-groupe">
                <label for="password">Mot de passe</label>
                <input type="password" id="password" name="password" placeholder="Choisissez un mot de passe sécurisé"
                    required>
                <?php MessagesErrorsFormulaire("motdepasse") ?>
                <!-- J'introduis le message d'erreur -->
                <!-- Si j'ai une erreur j'affiche le message d'érreur sinon je mets rien -->

            </div>

            <div class="form-groupe">
                <label for="firstname">Prénom</label>
                <input type="text" id="firstname" name="firstname" placeholder="Votre prénom" required>
                <?php MessagesErrorsFormulaire("prenom") ?>
            </div>

            <div class="form-groupe">
                <label for="lastname">Nom</label>
                <input type="text" id="lastname" name="lastname" placeholder="Votre nom" required>
                <?php MessagesErrorsFormulaire("nom") ?>
            </div>

            <div class="form-groupe">
                <label for="phone">Numéro de téléphone</label>
                <div class="input-tel">
                    <select id="prefix" name="prefix" class="tel-prefix" required>
                    </select>
                    <input type="tel" id="phone" name="phone" placeholder="6 12 34 56 78" class="tel-input" required>
                    <?php MessagesErrorsFormulaire("prefix") ?>
                    <?php MessagesErrorsFormulaire("numerotel") ?>

                </div>
            </div>
            <!-- Je reprends également la div de google et j'insère moi même la clef client -->
            <!-- data-sitekey : Clé publique fournie par Google (visible côté client) -->
            <div class="g-recaptcha" data-sitekey="6LcfPX0rAAAAALoy2aJmHzColxMSuKbYlyKVF1hr"></div>
            <button type="submit" class="btn-form" name="signup-form">Créer un compte</button>
        </form>
    </div>
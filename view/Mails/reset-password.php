 <!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Réinitialisation mot de passe</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      background-color: #f4f4f4;
      margin: 0;
      padding: 0;
    }

    .container-mail {
      max-width: 600px;
      margin: 30px auto;
      background-color: #ffffff;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
      text-align: center;
    }

    .logo {
      width: 120px;
      margin-bottom: 20px;
    }

    h1 {
      color: #333333;
    }

    p {
      color: #555555;
      line-height: 1.5;
    }

    .button {
      display: inline-block;
      margin-top: 20px;
      padding: 12px 24px;
      background-color: #007bff;
      color: #ffffff !important;
      text-decoration: none;
      border-radius: 5px;
      font-weight: bold;
    }

    .footer {
      font-size: 12px;
      color: #999999;
      margin-top: 30px;
    }
  </style>
</head>
<body>
  <div class="container-mail">
   <img src="http://localhost:8200/assets/Images/Logo-Endless-Mail.png" alt="Logo de la société" class="logo" />

    <h1>Réinitialisation de votre mot de passe</h1>
    <p>Bonjour,</p>
    <p>Vous avez demandé à réinitialiser votre mot de passe. Vous pouvez le faire en cliquant sur le bouton ci-dessous :</p>

    <a href="http://localhost:8200/reinitialiser-mot-de-passe?token=$token$" class="button">
      Réinitialiser mon mot de passe
    </a>

    <p>Ce lien est valable pendant une durée limitée pour des raisons de sécurité.</p>
    <p>Si vous n’êtes pas à l’origine de cette demande, vous pouvez ignorer ce message en toute sécurité.</p>

    <p class="footer">
      Merci et excellente journée,<br>
      L’équipe Endless Beauty Nails
    </p>
  </div>
</body>
</html>
</body>

</html>

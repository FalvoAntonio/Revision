    <!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Confirmation d'inscription</title>
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

    <h1>Bienvenue chez Endless Beauty Nails !</h1>
    <p> Merci pour votre inscription ! Nous sommes ravis de vous accueillir dans notre univers dédié à la beauté des ongles et au bien-être</p>
    <p>
      Que vous soyez ici pour prendre soin de vous, découvrir nos prestations en salon, ou bien suivre nos formations en ligne pour développer vos compétences en stylisme ongulaire, vous êtes au bon endroit.
    </p>
    <p>
      Veuillez confirmer votre adresse e-mail en cliquant sur le bouton ci-dessous :
    </p>
    <a href="http://localhost:8200/confirmer-votre-mail?token=$token$" class="button">
      Confirmer mon adresse
    </a>

    <p class="footer">
      Si vous n’avez pas effectué cette inscription, vous pouvez ignorer ce message.
    </p>
  </div>
</body>
</html>
</body>

</html>
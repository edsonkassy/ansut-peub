<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation de candidature partenaire PEUB</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f8fafc;
            color: #1e293b;
            margin: 0;
            padding: 20px;
        }
        .container {
            background: #fff;
            max-width: 600px;
            margin: 0 auto;
            padding: 32px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 32px;
            padding-bottom: 24px;
            border-bottom: 3px solid #265BFF;
        }
        .logo {
            max-width: 120px;
            margin-bottom: 16px;
        }
        .title {
            color: #265BFF;
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .subtitle {
            color: #64748b;
            font-size: 15px;
            margin-bottom: 0;
        }
        .greeting {
            font-size: 17px;
            margin-bottom: 16px;
            color: #1e293b;
            font-weight: 600;
        }
        .message {
            margin-bottom: 16px;
            color: #334155;
            font-size: 15px;
            line-height: 1.6;
        }
        .recap {
            background: #f1f5f9;
            border: 2px solid #265BFF;
            padding: 20px;
            margin: 24px 0;
            font-size: 15px;
        }
        .recap strong {
            color: #265BFF;
        }
        .steps-section {
            background: #eff6ff;
            border-left: 4px solid #265BFF;
            padding: 20px;
            margin: 24px 0;
        }
        .steps-section strong {
            color: #265BFF;
            font-weight: 600;
        }
        .info {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            margin: 24px 0;
            font-size: 15px;
        }
        .info strong {
            color: #265BFF;
        }
        .contact-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            margin: 24px 0;
        }
        .contact-section strong {
            color: #265BFF;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 13px;
        }
        .footer strong {
            color: #265BFF;
        }
        @media (max-width: 600px) {
            .container { 
                padding: 20px;
                margin: 10px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('images/logo_ansut.png') }}" alt="ANSUT Logo" class="logo">
            <div class="title">📋 Candidature Partenaire</div>
            <div class="subtitle">Confirmation de réception</div>
        </div>

        <div class="greeting">
            Cher(e) {{ $partenaire->personne_contact_nom }},
        </div>

        <div class="message">
            Nous sommes ravis de vous accueillir en tant que partenaire sur la plateforme ANSUT PEUB. Votre compte a été créé avec succès.
        </div>

        <div class="recap">
            <strong>📋 Récapitulatif de votre candidature :</strong><br><br>
            • <b>Organisation :</b> {{ $partenaire->nom_organisation }}<br>
            • <b>Type :</b> {{ ucfirst(str_replace('_', ' ', $partenaire->type_organisation)) }}<br>
            • <b>Contact :</b> {{ $partenaire->personne_contact_email }}<br>
            • <b>Date d'inscription :</b> {{ now()->format('d/m/Y à H:i') }}
        </div>

        <div class="steps-section">
            <strong>📋 Prochaines étapes :</strong><br><br>
            1. <strong>Validation de votre candidature</strong> - Examen de votre profil<br>
            2. <strong>Activation de votre compte</strong> - Accès à la plateforme partenaire<br>
            3. <strong>Configuration de votre profil</strong> - Complétez vos informations<br>
            4. <strong>Publication d'opportunités</strong> - Commencez à créer vos offres
        </div>

        <div class="info">
            <strong>🔐 Accès à la plateforme :</strong><br><br>
            Une fois votre candidature validée, vous recevrez un email contenant vos identifiants de connexion à la plateforme partenaire. Vous pourrez alors accéder à votre espace dédié et commencer à publier vos opportunités.
        </div>

        <div class="contact-section">
            <strong>📞 Besoin d'aide ?</strong><br><br>
            Notre équipe partenariat est là pour vous accompagner :<br>
            <strong>Email :</strong> support@peub.ci<br>
            <strong>Téléphone :</strong> +225 27 22 49 00 00
        </div>

        <div class="footer">
            <p><strong>ANSUT</strong> - Programme d'Excellence Universelle pour les Bacheliers</p>
            <p>Cet email a été envoyé automatiquement. Merci de ne pas y répondre.</p>
        </div>
    </div>
</body>
</html>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue sur PEUB</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.5;
            color: #333;
            background-color: #fff;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 400px;
            margin: 0 auto;
            background: white;
        }
        .content {
            padding: 20px 0;
            text-align: center;
        }
        .title {
            font-size: 18px;
            font-weight: bold;
            margin: 0 0 10px 0;
            color: #0E7490;
        }
        .subtitle {
            font-size: 14px;
            color: #666;
            margin: 0 0 20px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 4px;
            color: #000;
            font-family: monospace;
            background: #f0f9ff;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 2px solid #0E7490;
        }
        .expiry {
            font-size: 12px;
            color: #999;
            margin: 20px 0;
        }
        .footer {
            padding-top: 20px;
            text-align: center;
            font-size: 12px;
            color: #999;
            border-top: 1px solid #eee;
        }
        .welcome-message {
            background: linear-gradient(135deg, #0E7490 0%, #0c5f7a 100%);
            color: white;
            padding: 30px 20px;
            border-radius: 12px;
            margin-bottom: 20px;
        }
        .welcome-emoji {
            font-size: 48px;
            margin-bottom: 10px;
        }
        .next-steps {
            text-align: left;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .next-steps h3 {
            color: #0E7490;
            margin: 0 0 10px 0;
            font-size: 14px;
        }
        .next-steps ul {
            margin: 0;
            padding-left: 20px;
            font-size: 13px;
            color: #555;
        }
        .next-steps li {
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Welcome Message -->
        <div class="welcome-message">
            <div class="welcome-emoji">🎉</div>
            <h1 style="margin: 0 0 10px 0; font-size: 24px;">Bienvenue sur PEUB !</h1>
            <p style="margin: 0; font-size: 14px; opacity: 0.9;">
                Nous sommes ravis de vous compter parmi nous
            </p>
        </div>

        <div class="content">
            <p class="subtitle">
                Bonjour @if($user->bachelier && $user->bachelier->prenoms){{ $user->bachelier->prenoms }}@endif,<br>
                Voici votre code de vérification pour finaliser votre inscription :
            </p>

            <div class="otp-code">{{ $otp }}</div>

            <p class="expiry">
                ⏰ Ce code expire dans <strong>10 minutes</strong>
            </p>

            <!-- Next Steps -->
            <div class="next-steps">
                <h3>📋 Prochaines étapes :</h3>
                <ul>
                    <li>Entrez ce code sur la page de vérification</li>
                    <li>Complétez votre profil avec vos informations</li>
                    <li>Téléchargez vos documents (pièce d'identité, collante BAC)</li>
                    <li>Rédigez votre lettre de motivation</li>
                    <li>Votre profil sera analysé par notre IA pour calculer votre score PEUB</li>
                </ul>
            </div>

            <p style="font-size: 13px; color: #666; margin-top: 20px;">
                <strong>💡 Conseil :</strong> Préparez vos documents à l'avance pour un processus plus rapide !
            </p>
        </div>

        <div class="footer">
            <p style="margin: 0 0 10px 0;">
                Si vous n'avez pas demandé ce code, ignorez cet email.
            </p>
            <p style="margin: 0; color: #0E7490; font-weight: bold;">
                ANSUT - PEUB
            </p>
            <p style="margin: 5px 0 0 0; font-size: 11px;">
                Projet d'Excellence Universelle pour les Bacheliers
            </p>
        </div>
    </div>
</body>
</html>


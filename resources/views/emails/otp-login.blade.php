<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Code de connexion</title>
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
            margin: 20px 0;
        }
        .notice {
            font-size: 12px;
            color: #666;
            margin: 15px 0;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            color: #999;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="content">
            <h1 class="title">Votre code de connexion</h1>
            <p class="subtitle">Utilisez ce code pour vous connecter à votre compte PEUB</p>
            
            <div class="otp-code">{{ $otp }}</div>
            
            <div class="notice">
                Ce code expire dans 10 minutes
            </div>
            
            <div class="notice">
                Pour votre sécurité, ne partagez jamais ce code avec qui que ce soit.
            </div>
        </div>
        
        <div class="footer">
            PEUB - Programme d'Excellence Universelle pour les Bacheliers
        </div>
    </div>
</body>
</html> 
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenue dans l'administration PEUB</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #1e293b;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8fafc;
        }
        .email-container {
            background: white;
            border: 1px solid #e2e8f0;
            padding: 32px;
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
            height: auto;
            margin-bottom: 16px;
        }
        .title {
            color: #265BFF;
            font-size: 24px;
            font-weight: 700;
            margin: 0;
        }
        .subtitle {
            color: #64748b;
            font-size: 15px;
            margin-top: 8px;
        }
        .content {
            margin-bottom: 32px;
        }
        .content p {
            color: #334155;
            font-size: 15px;
            margin-bottom: 16px;
        }
        .otp-section {
            background: #f1f5f9;
            border: 2px solid #265BFF;
            padding: 24px;
            text-align: center;
            margin: 24px 0;
        }
        .otp-code {
            font-size: 32px;
            font-weight: 700;
            color: #265BFF;
            letter-spacing: 3px;
            margin: 16px 0;
            font-family: 'Courier New', monospace;
        }
        .otp-info {
            color: #64748b;
            font-size: 14px;
        }
        .roles-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            margin: 24px 0;
        }
        .roles-section h3 {
            color: #1e293b;
            font-size: 16px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .role-badge {
            display: inline-block;
            background: #265BFF;
            color: white;
            padding: 6px 12px;
            font-size: 12px;
            font-weight: 600;
            margin: 3px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        .instructions {
            background: #eff6ff;
            border-left: 4px solid #265BFF;
            padding: 20px;
            margin: 24px 0;
        }
        .instructions h3 {
            color: #265BFF;
            font-size: 16px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .instructions ol {
            margin: 0;
            padding-left: 20px;
        }
        .instructions li {
            margin-bottom: 8px;
            color: #334155;
        }
        .footer {
            text-align: center;
            margin-top: 40px;
            padding-top: 24px;
            border-top: 1px solid #e2e8f0;
            color: #64748b;
            font-size: 13px;
        }
        .button {
            display: inline-block;
            background: #265BFF;
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            font-weight: 600;
            margin: 16px 0;
            text-align: center;
        }
        .button:hover {
            background: #1e40af;
        }
        .warning {
            background: #fef3c7;
            border: 1px solid #f59e0b;
            color: #92400e;
            padding: 16px;
            margin: 20px 0;
        }
        .warning strong {
            color: #78350f;
        }
        .info-list {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 20px;
            margin: 24px 0;
        }
        .info-list h3 {
            color: #1e293b;
            font-size: 16px;
            font-weight: 600;
            margin-top: 0;
            margin-bottom: 12px;
        }
        .info-list ul {
            margin: 0;
            padding-left: 20px;
        }
        .info-list li {
            margin-bottom: 8px;
            color: #334155;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <img src="{{ asset('images/logo_ansut.png') }}" alt="ANSUT" class="logo">
            <h1 class="title">Bienvenue dans l'Administration PEUB</h1>
            <p class="subtitle">Votre accès administrateur a été créé avec succès</p>
        </div>

        <!-- Content -->
        <div class="content">
            <p>Bonjour,</p>
            
            <p>Vous avez été ajouté en tant qu'administrateur sur la plateforme PEUB (Programme d'Excellence Universelle pour les Bacheliers).</p>
            
            @if($createdBy)
                <p>Votre compte a été créé par <strong>{{ $createdBy->email }}</strong>.</p>
            @endif

            <!-- OTP Section -->
            <div class="otp-section">
                <h3 style="margin-top: 0; color: #265BFF; font-size: 18px; font-weight: 600;">Code de Première Connexion</h3>
                <div class="otp-code">{{ $otp }}</div>
                <div class="otp-info">
                    <p><strong>Ce code expire dans 10 minutes</strong></p>
                    <p>Utilisez ce code pour vous connecter et configurer votre accès.</p>
                </div>
            </div>

            <!-- Roles Section -->
            <div class="roles-section">
                <h3>Vos Rôles et Permissions</h3>
                <p>Vous avez été assigné aux rôles suivants :</p>
                <div style="margin: 15px 0;">
                    @foreach($user->adminRoles as $role)
                        <span class="role-badge">{{ $role->display_name }}</span>
                    @endforeach
                </div>
                <p style="font-size: 14px; color: #64748b;">
                    Ces rôles déterminent vos permissions sur la plateforme. Vous pouvez consulter le détail de vos permissions après votre première connexion.
                </p>
            </div>

            <!-- Instructions -->
            <div class="instructions">
                <h3>Instructions de Première Connexion</h3>
                <ol>
                    <li>Rendez-vous sur la <a href="{{ route('auth.login') }}" style="color: #265BFF;">page de connexion</a></li>
                    <li>Saisissez votre adresse email : <strong>{{ $user->email }}</strong></li>
                    <li>Entrez le code OTP ci-dessus quand il vous sera demandé</li>
                    <li>Vous serez automatiquement connecté à votre espace d'administration</li>
                </ol>
            </div>

            <!-- Login Button -->
            <div style="text-align: center; margin: 32px 0;">
                <a href="{{ route('auth.login') }}" class="button">Se Connecter Maintenant</a>
            </div>

            <!-- Security Warning -->
            <div class="warning">
                <strong>⚠️ Sécurité :</strong> Ce code OTP est personnel et confidentiel. Ne le partagez avec personne. Si vous n'avez pas demandé cet accès, contactez immédiatement l'administrateur système.
            </div>

            <!-- Additional Info -->
            <div class="info-list">
                <h3>Informations Utiles</h3>
                <ul>
                    <li><strong>Votre email :</strong> {{ $user->email }}</li>
                    <li><strong>Statut du compte :</strong> {{ $user->status === 'active' ? 'Actif' : 'En attente' }}</li>
                    <li><strong>Nombre de rôles :</strong> {{ $user->adminRoles->count() }}</li>
                    <li><strong>Accès créé le :</strong> {{ $user->created_at->format('d/m/Y à H:i') }}</li>
                </ul>
            </div>

            <p>Si vous rencontrez des difficultés lors de votre première connexion, n'hésitez pas à contacter l'équipe technique.</p>
            
            <p><strong>Bienvenue dans l'équipe d'administration PEUB !</strong></p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>ANSUT</strong> - Programme d'Excellence Universelle pour les Bacheliers</p>
            <p>Cet email a été envoyé automatiquement, merci de ne pas y répondre.</p>
            <p>Pour toute question : <a href="mailto:support@ansut.ci" style="color: #265BFF;">support@ansut.ci</a></p>
        </div>
    </div>
</body>
</html> 
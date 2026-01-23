<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attribution de dotation PEUB</title>
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
            font-size: 18px;
            margin-bottom: 16px;
            color: #265BFF;
            font-weight: 700;
        }
        .message {
            margin-bottom: 16px;
            color: #334155;
            font-size: 15px;
            line-height: 1.6;
        }
        .dotation-card {
            background: #265BFF;
            color: white;
            padding: 24px;
            margin: 24px 0;
            text-align: center;
            border: 2px solid #1e40af;
        }
        .dotation-title {
            font-size: 20px;
            font-weight: 700;
            margin-bottom: 8px;
        }
        .dotation-description {
            opacity: 0.9;
            font-size: 15px;
        }
        .details-section {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 24px;
            margin: 24px 0;
        }
        .details-title {
            color: #265BFF;
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 16px;
        }
        .detail-row {
            display: flex;
            justify-content: space-between;
            margin: 12px 0;
            padding: 8px 0;
            border-bottom: 1px solid #e2e8f0;
            font-size: 15px;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: 600;
            color: #475569;
        }
        .detail-value {
            color: #1e293b;
            text-align: right;
            font-weight: 500;
        }
        .cta-button {
            display: inline-block;
            background: #265BFF;
            color: white;
            padding: 14px 28px;
            text-decoration: none;
            font-weight: 600;
            text-align: center;
            margin: 24px auto;
            display: block;
            width: fit-content;
        }
        .cta-button:hover {
            background: #1e40af;
        }
        .info-box {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 20px;
            margin: 24px 0;
        }
        .info-box h4 {
            color: #92400e;
            margin-top: 0;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 600;
        }
        .info-box p {
            margin-bottom: 0;
            font-size: 15px;
            color: #451a03;
        }
        .characteristics-box {
            background: #ecfdf5;
            border: 2px solid #10b981;
            padding: 20px;
            margin: 24px 0;
        }
        .characteristics-box h4 {
            color: #065f46;
            margin-top: 0;
            margin-bottom: 8px;
            font-size: 16px;
            font-weight: 600;
        }
        .characteristics-box p {
            margin-bottom: 0;
            font-size: 15px;
            color: #064e3b;
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
        .footer p {
            margin: 8px 0;
        }
        .footer .small {
            font-size: 12px;
            color: #94a3b8;
            margin-top: 15px;
        }
        @media (max-width: 600px) {
            .container { 
                padding: 20px;
                margin: 10px;
            }
            .detail-row {
                flex-direction: column;
                gap: 4px;
            }
            .detail-value {
                text-align: left;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1 class="title">🎓 ANSUT PEUB</h1>
            <p class="subtitle">Programme d'Excellence Universitaire en Bourse</p>
        </div>

        <div class="greeting">
            🎉 Félicitations {{ $bachelier->prenoms }} !
        </div>

        <div class="message">
            Nous avons le plaisir de vous informer qu'une dotation a été activée sur votre compte PEUB.
        </div>

        <div class="dotation-card">
            <div class="dotation-title">
                📦 {{ $inventaire->nom }}
            </div>
            <div class="dotation-description">
                {{ $inventaire->description }}
            </div>
        </div>

        <div class="details-section">
            <h3 class="details-title">📋 Détails de votre dotation</h3>
            
            <div class="detail-row">
                <span class="detail-label">🏷️ Type de dotation</span>
                <span class="detail-value">
                    @switch($inventaire->type_dotation)
                        @case('ordinateur_portable')
                            💻 Ordinateur portable
                            @break
                        @case('connexion_internet')
                            🌐 Connexion internet
                            @break
                        @case('abonnement_ia')
                            🤖 Abonnement IA
                            @break
                        @default
                            📦 {{ ucfirst(str_replace('_', ' ', $inventaire->type_dotation)) }}
                    @endswitch
                </span>
            </div>

            <div class="detail-row">
                <span class="detail-label">🏭 Fournisseur</span>
                <span class="detail-value">{{ $fournisseur->nom }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">🏷️ Marque</span>
                <span class="detail-value">{{ $inventaire->marque }} {{ $inventaire->modele }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">🔢 Identifiant unique</span>
                <span class="detail-value">{{ $dotation->identifiant_unique ?? 'Sera fourni ultérieurement' }}</span>
            </div>

            <div class="detail-row">
                <span class="detail-label">📅 Date d'attribution</span>
                <span class="detail-value">{{ $dotation->date_attribution->format('d/m/Y à H:i') }}</span>
            </div>

            @if($dotation->date_debut)
            <div class="detail-row">
                <span class="detail-label">🚀 Date de début</span>
                <span class="detail-value">{{ $dotation->date_debut->format('d/m/Y') }}</span>
            </div>
            @endif

            @if($dotation->date_fin)
            <div class="detail-row">
                <span class="detail-label">⏰ Date de fin</span>
                <span class="detail-value">{{ $dotation->date_fin->format('d/m/Y') }}</span>
            </div>
            @endif

            @if($inventaire->valeur_unitaire)
            <div class="detail-row">
                <span class="detail-label">💰 Valeur</span>
                <span class="detail-value">{{ number_format($inventaire->valeur_unitaire, 0, ',', ' ') }} FCFA</span>
            </div>
            @endif
        </div>

        @if($inventaire->caracteristiques)
        <div class="characteristics-box">
            <h4>⚙️ Caractéristiques techniques</h4>
            <p>{{ $inventaire->caracteristiques }}</p>
        </div>
        @endif

        @if($dotation->donnees_specifiques && isset($dotation->donnees_specifiques['conditions_utilisation']))
        <div class="info-box">
            <h4>📋 Conditions d'utilisation</h4>
            <p>{{ $dotation->donnees_specifiques['conditions_utilisation'] }}</p>
        </div>
        @endif

        <a href="{{ route('bachelier.dotations') }}" class="cta-button">
            Voir mes dotations
        </a>

        <div class="info-box">
            <h4>📞 Besoin d'aide ?</h4>
            <p>
                Si vous avez des questions concernant votre dotation, n'hésitez pas à nous contacter à 
                <strong>support@ansut.ci</strong> <br> 0716001291 ou via votre espace personnel.
            </p>
        </div>

        <div class="footer">
            <p><strong>ANSUT</strong> - Programme d'Excellence Universitaire en Bourse</p>
            <p>République de Côte d'Ivoire</p>
            
            <p class="small">
                Cet email a été envoyé automatiquement. Merci de ne pas répondre directement à cet email.
            </p>
        </div>
    </div>
</body>
</html>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouvelle candidature - PEUB</title>
</head>
<body style="margin: 0; padding: 0; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Arial, sans-serif; background-color: #ffffff;">
    <table width="100%" cellpadding="0" cellspacing="0" style="max-width: 500px; margin: 40px auto; padding: 0 20px;">
        <!-- Header simple -->
        <tr>
            <td style="text-align: center; padding-bottom: 40px;">
                <div style="width: 48px; height: 48px; background: linear-gradient(135deg, #0E7490, #0c5f7a); border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 20px;">
                    <span style="color: white; font-size: 24px;">🔔</span>
                </div>
                <h1 style="margin: 0; font-size: 24px; font-weight: 600; color: #111827;">
                    Nouvelle candidature
                </h1>
            </td>
        </tr>
        
        <!-- Contenu -->
        <tr>
            <td style="color: #374151; font-size: 16px; line-height: 1.6;">
                <p style="margin: 0 0 24px 0;">
                    Un nouveau candidat nécessite votre validation.
                </p>
                
                <!-- Infos candidat -->
                <div style="background: #F9FAFB; border-radius: 8px; padding: 20px; margin-bottom: 24px;">
                    <p style="margin: 0 0 8px 0; color: #6B7280; font-size: 14px;">
                        <strong style="color: #111827;">{{ $bachelier->prenoms }} {{ $bachelier->nom }}</strong>
                    </p>
                    <p style="margin: 0 0 4px 0; color: #6B7280; font-size: 14px;">
                        {{ $bachelier->serie_bac }} · {{ $bachelier->note_bac }}/400 · {{ ucfirst(str_replace('_', ' ', $bachelier->mention)) }}
                    </p>
                    <p style="margin: 0; color: #9CA3AF; font-size: 13px;">
                        {{ $bachelier->email_eleve }}
                    </p>
                </div>
                
                <!-- Bouton simple -->
                <div style="text-align: center; margin: 32px 0;">
                    <a href="{{ route('admin.bacheliers.show', $bachelier->id) }}" 
                       style="display: inline-block; background: linear-gradient(to right, #0E7490, #0c5f7a); color: white; text-decoration: none; padding: 12px 32px; border-radius: 8px; font-weight: 500; font-size: 15px;">
                        Examiner
                    </a>
                </div>
            </td>
        </tr>
        
        <!-- Footer minimal -->
        <tr>
            <td style="padding-top: 40px; text-align: center; color: #9CA3AF; font-size: 12px; border-top: 1px solid #E5E7EB;">
                <p style="margin: 16px 0 0 0;">
                    © {{ date('Y') }} ANSUT - PEUB Admin
                </p>
            </td>
        </tr>
    </table>
</body>
</html>

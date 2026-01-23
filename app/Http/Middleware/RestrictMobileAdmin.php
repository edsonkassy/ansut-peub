<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RestrictMobileAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Vérifier si l'utilisateur est sur mobile
        if ($this->isMobileDevice($request)) {
            // Rediriger vers la page de restriction mobile avec gestion d'erreur
            try {
                return response()->view('admin.mobile-restricted');
            } catch (\Exception $e) {
                // Si la vue ne peut pas être chargée, retourner une réponse simple
                return $this->simpleMobileRestrictResponse();
            }
        }

        return $next($request);
    }

    /**
     * Retourner une réponse simple de restriction mobile
     */
    private function simpleMobileRestrictResponse(): Response
    {
        $html = '<!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Accès restreint - Administration PEUB</title>
            <style>
                body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f5f5f5; }
                .container { max-width: 400px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
                .btn { background: #dc2626; color: white; padding: 12px 24px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; display: inline-block; margin-top: 20px; }
                .btn:hover { background: #b91c1c; }
            </style>
        </head>
        <body>
            <div class="container">
                <h1>⚠️ Accès non autorisé</h1>
                <p>L\'espace d\'administration PEUB n\'est pas accessible depuis un appareil mobile.</p>
                <p>Veuillez utiliser un ordinateur ou une tablette.</p>
                <form method="POST" action="' . route('logout') . '">
                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                    <button type="submit" class="btn">OK, me déconnecter</button>
                </form>
            </div>
        </body>
        </html>';
        
        return response($html, 200)->header('Content-Type', 'text/html');
    }

    /**
     * Déterminer si l'appareil est mobile
     */
    private function isMobileDevice(Request $request): bool
    {
        $userAgent = $request->header('User-Agent', '');
        
        // Liste des patterns pour détecter les appareils mobiles
        $mobilePatterns = [
            '/Mobile|Android|iPhone|iPad|iPod|Windows Phone|Opera Mini|BlackBerry|webOS|IEMobile/i',
            '/Mobile|Android|iPhone|iPod|BlackBerry|webOS|Windows Phone/i'
        ];
        
        // Vérifier si l'User-Agent correspond aux patterns mobiles
        foreach ($mobilePatterns as $pattern) {
            if (preg_match($pattern, $userAgent)) {
                return true;
            }
        }
        
        // Vérification supplémentaire basée sur la largeur d'écran (si disponible)
        $screenWidth = $request->header('Screen-Width');
        if ($screenWidth && (int)$screenWidth < 1024) {
            return true;
        }
        
        // Vérifier les headers mobiles courants
        $mobileHeaders = [
            'HTTP_X_WAP_PROFILE',
            'HTTP_X_WAP_CLIENTID',
            'HTTP_WAP_CONNECTION',
            'HTTP_PROFILE',
            'HTTP_X_OPERAMINI_PHONE_UA',
            'HTTP_X_NOKIA_GATEWAY_ID',
            'HTTP_X_ORANGE_ID',
            'HTTP_X_VODAFONE_3GPDPCONTEXT',
            'HTTP_X_HUAWEI_USERID'
        ];
        
        foreach ($mobileHeaders as $header) {
            if ($request->hasHeader($header)) {
                return true;
            }
        }
        
        return false;
    }
}

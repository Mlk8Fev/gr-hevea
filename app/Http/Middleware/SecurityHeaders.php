<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
        
        // 1. X-Frame-Options : Empêche l'affichage du site dans une iframe (protection Clickjacking)
        // SAMEORIGIN = autorise uniquement les iframes du même domaine
        $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
        
        // 2. X-Content-Type-Options : Empêche le MIME sniffing
        // Le navigateur doit respecter le Content-Type déclaré
        $response->headers->set('X-Content-Type-Options', 'nosniff');
        
        // 3. X-XSS-Protection : Active la protection XSS du navigateur
        // 1; mode=block = bloque la page si XSS détecté
        $response->headers->set('X-XSS-Protection', '1; mode=block');
        
        // 4. Referrer-Policy : Contrôle les informations envoyées dans l'en-tête Referer
        // strict-origin-when-cross-origin = envoie l'origine uniquement en HTTPS
        $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
        
        // 5. Permissions-Policy : Désactive les fonctionnalités non utilisées
        // Empêche l'accès à la géolocalisation, micro, caméra
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');
        
        // 6. HSTS (Strict-Transport-Security) : Force HTTPS
        // SEULEMENT en production avec HTTPS configuré
        if (config('app.env') === 'production' && $request->secure()) {
            // max-age=31536000 = 1 an
            // includeSubDomains = applique aussi aux sous-domaines
            $response->headers->set(
                'Strict-Transport-Security', 
                'max-age=31536000; includeSubDomains'
            );
        }
        
        // 7. Content-Security-Policy (CSP) : Politique de sécurité du contenu
        // Définit les sources autorisées pour les scripts, styles, images, etc.
        $csp = implode('; ', [
            "default-src 'self'",  // Par défaut, uniquement même origine
            "script-src 'self' 'unsafe-inline' 'unsafe-eval' https://cdn.jsdelivr.net https://code.iconify.design",  // Scripts
            "style-src 'self' 'unsafe-inline' https://fonts.googleapis.com",  // CSS
            "font-src 'self' https://fonts.gstatic.com",  // Fonts
            "img-src 'self' data: https:",  // Images (self + data: pour base64)
            "connect-src 'self'",  // AJAX/Fetch
            "frame-ancestors 'self'",  // Qui peut mettre le site en iframe
            "base-uri 'self'",  // Balise <base>
            "form-action 'self'",  // Destination des formulaires
        ]);
        $response->headers->set('Content-Security-Policy', $csp);
        
        return $response;
    }
}

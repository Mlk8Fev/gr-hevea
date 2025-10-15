<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class SecureFileController extends Controller
{
    /**
     * Servir un fichier de manière sécurisée
     */
    public function serve(Request $request, $path)
    {
        // 1. Vérifier que l'utilisateur est authentifié
        if (!Auth::check()) {
            abort(403, 'Accès non autorisé');
        }
        
        // 2. Construire le chemin complet
        $fullPath = storage_path('app/' . $path);
        
        // 3. Vérifier que le fichier existe
        if (!file_exists($fullPath)) {
            abort(404, 'Fichier non trouvé');
        }
        
        // 4. Vérifier les permissions (exemple: admin peut tout voir)
        $user = Auth::user();
        
        // Si ce n'est pas un admin, vérifier que le fichier lui appartient
        if (!in_array($user->role, ['admin', 'superadmin'])) {
            // Extraire l'identifiant du chemin
            // Exemple: producteurs/PROD001/fichier.pdf
            if (str_contains($path, 'producteurs/')) {
                $parts = explode('/', $path);
                $codeFphci = $parts[1] ?? null;
                
                // Vérifier que l'utilisateur peut accéder à ce producteur
                // (à adapter selon votre logique métier)
                $producteur = \App\Models\Producteur::where('code_fphci', $codeFphci)->first();
                
                if (!$producteur) {
                    abort(403, 'Accès non autorisé');
                }
                
                // Exemple: vérifier que l'utilisateur est AGC de la coopérative du producteur
                // À adapter selon vos règles métier
            }
        }
        
        // 5. Servir le fichier
        return response()->file($fullPath);
    }
    
    /**
     * Télécharger un fichier
     */
    public function download(Request $request, $path)
    {
        // Même logique que serve() mais avec download()
        if (!Auth::check()) {
            abort(403, 'Accès non autorisé');
        }
        
        $fullPath = storage_path('app/' . $path);
        
        if (!file_exists($fullPath)) {
            abort(404, 'Fichier non trouvé');
        }
        
        // Vérifier les permissions...
        // (même code que ci-dessus)
        
        return response()->download($fullPath);
    }
}


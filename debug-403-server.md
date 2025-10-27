# Debug 403 sur le serveur Plesk

## 1. Activer le mode debug temporairement

Dans `.env` sur le serveur :
```bash
APP_DEBUG=true
```

Puis :
```bash
php artisan config:clear
```

## 2. Logs Laravel en temps réel

```bash
tail -f storage/logs/laravel-$(date +%Y-%m-%d).log
```

Puis tentez de vous connecter en AGC et voyez l'erreur complète.

## 3. Logs Apache/Plesk

Dans Plesk → Logs → Access & Error logs

Cherchez :
```
403 /admin/cooperatives
```

## 4. Debug dans le middleware

Ajoutez temporairement dans `app/Http/Middleware/CheckRole.php` :

```php
public function handle(Request $request, Closure $next, string $role = null): Response
{
    if (!auth()->check()) {
        return redirect('/login');
    }

    $user = auth()->user();
    
    // DEBUG
    \Log::info('CheckRole - User: ' . $user->email . ' Role: ' . $user->role . ' Allowed: ' . $role);
    
    if ($role) {
        $allowedRoles = explode(',', $role);
        $allowedRoles = array_map('trim', $allowedRoles);
        
        \Log::info('CheckRole - Checking: ' . print_r($allowedRoles, true));
        
        if (!in_array($user->role, $allowedRoles) && !$user->isSiege()) {
            \Log::error('CheckRole - BLOCKED - User role: ' . $user->role . ' Allowed: ' . $role);
            abort(403, 'Accès non autorisé');
        }
    }
    
    \Log::info('CheckRole - ALLOWED');
    return $next($request);
}
```

Puis regardez les logs :
```bash
tail -f storage/logs/laravel-*.log | grep CheckRole
```


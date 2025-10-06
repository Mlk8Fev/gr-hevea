<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'setup' ? 'Activation 2FA' : 'Code de connexion' }} - FPH-CI</title>
</head>
<body style="font-family: Arial, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 0;">
        
        <!-- Header -->
        <div style="background-color: #447748; padding: 30px; text-align: center;">
            <h1 style="color: #ffffff; margin: 20px 0 0 0; font-size: 24px;">
                {{ $type === 'setup' ? '🔐 Activation 2FA' : '🔐 Code de connexion' }}
            </h1>
        </div>

        <!-- Content -->
        <div style="padding: 40px 30px;">
            <p style="font-size: 16px; color: #333333; margin-bottom: 20px;">
                Bonjour,
            </p>

            @if($type === 'setup')
                <p style="font-size: 16px; color: #333333; margin-bottom: 20px;">
                    Vous devez activer l'authentification à deux facteurs (2FA) pour accéder au système FPH-CI.
                </p>
                <p style="font-size: 16px; color: #333333; margin-bottom: 30px;">
                    Votre code de confirmation est :
                </p>
            @else
                <p style="font-size: 16px; color: #333333; margin-bottom: 20px;">
                    Vous avez tenté de vous connecter au système FPH-CI.
                </p>
                <p style="font-size: 16px; color: #333333; margin-bottom: 30px;">
                    Votre code de connexion est :
                </p>
            @endif

            <!-- Code Box -->
            <div style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%); 
                        padding: 30px; 
                        text-align: center; 
                        margin: 30px 0; 
                        border-radius: 12px;
                        border: 2px solid #447748;">
                <span style="font-size: 36px; 
                            font-weight: bold; 
                            color: #447748; 
                            letter-spacing: 8px;
                            font-family: Arial, sans-serif;">
                    {{ $code }}
                </span>
            </div>

            <p style="font-size: 14px; color: #666666; margin-bottom: 20px;">
                <strong>⚠️ Ce code est valide pendant 5 minutes seulement.</strong>
            </p>

            @if($type === 'setup')
                <p style="font-size: 14px; color: #666666; margin-bottom: 20px;">
                    Une fois activé, vous devrez entrer un code similaire à chaque connexion pour sécuriser votre compte.
                </p>
            @else
                <p style="font-size: 14px; color: #666666; margin-bottom: 20px;">
                    Si vous n'avez pas tenté de vous connecter, ignorez cet email et changez votre mot de passe.
                </p>
            @endif

            <!-- Security Notice -->
            <div style="background-color: #fff3cd; 
                        border: 1px solid #ffeaa7; 
                        border-radius: 8px; 
                        padding: 20px; 
                        margin: 30px 0;">
                <p style="font-size: 14px; color: #856404; margin: 0;">
                    <strong>🛡️ Sécurité :</strong> Ne partagez jamais ce code avec qui que ce soit. 
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f8f9fa; padding: 30px; text-align: center; border-top: 1px solid #e9ecef;">
            <p style="font-size: 12px; color: #666666; margin: 0;">
                FPH-CI - Système de Gestion et de Traçabilité de la Graine d'Hevea<br>
                Cet email est envoyé automatiquement, merci de ne pas y répondre.
            </p>
        </div>
    </div>
</body>
</html>

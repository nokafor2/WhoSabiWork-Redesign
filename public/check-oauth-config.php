<?php
/**
 * Google OAuth Configuration Checker
 * 
 * Upload this file to your public folder and visit:
 * https://your-domain.com/check-oauth-config.php
 * 
 * ⚠️ DELETE THIS FILE AFTER USE FOR SECURITY!
 */

// Load Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

?>
<!DOCTYPE html>
<html>
<head>
    <title>OAuth Configuration Check</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 50px auto;
            padding: 20px;
            background: #f5f5f5;
        }
        .card {
            background: white;
            padding: 20px;
            margin: 20px 0;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .success { color: #28a745; font-weight: bold; }
        .error { color: #dc3545; font-weight: bold; }
        .warning { color: #ffc107; font-weight: bold; }
        .code {
            background: #f4f4f4;
            padding: 10px;
            border-radius: 4px;
            font-family: monospace;
            margin: 10px 0;
        }
        h1 { color: #333; }
        h2 { color: #555; border-bottom: 2px solid #007bff; padding-bottom: 5px; }
        .status-icon { font-size: 24px; margin-right: 10px; }
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        table td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }
        table td:first-child {
            font-weight: bold;
            width: 250px;
        }
        .delete-warning {
            background: #fff3cd;
            border: 2px solid #ffc107;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>🔍 Google OAuth Configuration Checker</h1>
    
    <div class="delete-warning">
        <strong>⚠️ SECURITY WARNING:</strong> Delete this file after checking! This exposes configuration details.
    </div>

    <?php
    // Check Google OAuth Configuration
    $googleClientId = config('services.google.client_id');
    $googleClientSecret = config('services.google.client_secret');
    $googleRedirect = config('services.google.redirect');
    $appUrl = config('app.url');
    $appEnv = config('app.env');
    
    // Overall Status
    $allGood = $googleClientId && $googleClientSecret && $googleRedirect;
    ?>

    <div class="card">
        <h2>Overall Status</h2>
        <?php if ($allGood): ?>
            <p class="success">
                <span class="status-icon">✅</span>
                Google OAuth appears to be configured!
            </p>
        <?php else: ?>
            <p class="error">
                <span class="status-icon">❌</span>
                Google OAuth is NOT properly configured!
            </p>
        <?php endif; ?>
    </div>

    <div class="card">
        <h2>Environment Details</h2>
        <table>
            <tr>
                <td>Environment</td>
                <td><strong><?php echo $appEnv; ?></strong></td>
            </tr>
            <tr>
                <td>App URL</td>
                <td><?php echo $appUrl ?: '<span class="error">❌ NOT SET</span>'; ?></td>
            </tr>
            <tr>
                <td>HTTPS Enabled</td>
                <td>
                    <?php if (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off'): ?>
                        <span class="success">✅ Yes</span>
                    <?php else: ?>
                        <span class="error">❌ No (Required for production OAuth)</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>Current URL</td>
                <td><?php echo (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST']; ?></td>
            </tr>
        </table>
    </div>

    <div class="card">
        <h2>Google OAuth Configuration</h2>
        <table>
            <tr>
                <td>GOOGLE_CLIENT_ID</td>
                <td>
                    <?php if ($googleClientId): ?>
                        <span class="success">✅ SET</span>
                        <div class="code"><?php echo substr($googleClientId, 0, 20) . '...' . substr($googleClientId, -10); ?></div>
                    <?php else: ?>
                        <span class="error">❌ NOT SET</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>GOOGLE_CLIENT_SECRET</td>
                <td>
                    <?php if ($googleClientSecret): ?>
                        <span class="success">✅ SET</span>
                        <div class="code"><?php echo substr($googleClientSecret, 0, 15) . '...' . substr($googleClientSecret, -5); ?></div>
                    <?php else: ?>
                        <span class="error">❌ NOT SET</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>GOOGLE_REDIRECT_URL</td>
                <td>
                    <?php if ($googleRedirect): ?>
                        <span class="success">✅ SET</span>
                        <div class="code"><?php echo $googleRedirect; ?></div>
                    <?php else: ?>
                        <span class="error">❌ NOT SET</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="card">
        <h2>Routes Check</h2>
        <?php
        $routes = app('router')->getRoutes();
        $oauthRouteFound = false;
        $callbackRouteFound = false;
        
        foreach ($routes as $route) {
            if (strpos($route->uri(), 'auth/{provider}') !== false && $route->methods()[0] === 'GET') {
                $oauthRouteFound = true;
            }
            if (strpos($route->uri(), 'auth/{provider}/callback') !== false) {
                $callbackRouteFound = true;
            }
        }
        ?>
        <table>
            <tr>
                <td>OAuth Redirect Route</td>
                <td>
                    <?php if ($oauthRouteFound): ?>
                        <span class="success">✅ Found: GET /auth/{provider}</span>
                    <?php else: ?>
                        <span class="error">❌ Not Found</span>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td>OAuth Callback Route</td>
                <td>
                    <?php if ($callbackRouteFound): ?>
                        <span class="success">✅ Found: GET /auth/{provider}/callback</span>
                    <?php else: ?>
                        <span class="error">❌ Not Found</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="card">
        <h2>Controller Check</h2>
        <?php
        $controllerExists = file_exists(app_path('Http/Controllers/SocialAuthController.php'));
        ?>
        <table>
            <tr>
                <td>SocialAuthController</td>
                <td>
                    <?php if ($controllerExists): ?>
                        <span class="success">✅ Exists</span>
                    <?php else: ?>
                        <span class="error">❌ Not Found</span>
                    <?php endif; ?>
                </td>
            </tr>
        </table>
    </div>

    <div class="card">
        <h2>Required Google Console Settings</h2>
        <p>Your Google Cloud Console OAuth redirect URI should be set to:</p>
        <div class="code">
            <?php 
            $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'];
            echo $currentUrl . '/auth/google/callback';
            ?>
        </div>
        <p><small>⚠️ Must use HTTPS in production!</small></p>
    </div>

    <?php if (!$allGood): ?>
    <div class="card">
        <h2>🔧 How to Fix</h2>
        <ol>
            <li>
                <strong>Add to your .env file:</strong>
                <div class="code">
GOOGLE_CLIENT_ID=your-google-client-id-here<br>
GOOGLE_CLIENT_SECRET=your-google-client-secret-here<br>
GOOGLE_REDIRECT_URL="${APP_URL}/auth/google/callback"<br>
APP_URL=<?php echo $currentUrl; ?>
                </div>
            </li>
            <li>
                <strong>Clear Laravel config cache:</strong>
                <div class="code">
php artisan config:clear<br>
php artisan cache:clear<br>
php artisan route:clear
                </div>
            </li>
            <li>
                <strong>Refresh this page to verify</strong>
            </li>
        </ol>
    </div>
    <?php endif; ?>

    <div class="card">
        <h2>📚 Next Steps</h2>
        <?php if ($allGood): ?>
            <p class="success">✅ Configuration looks good! Test the OAuth flow:</p>
            <ol>
                <li>Visit your login page</li>
                <li>Click "Sign in with Google"</li>
                <li>Authorize the app</li>
                <li>You should be redirected back and logged in</li>
            </ol>
        <?php else: ?>
            <p class="error">❌ Configuration incomplete. Follow the fix steps above.</p>
        <?php endif; ?>
        
        <p style="margin-top: 20px;">
            <strong>📖 Full Documentation:</strong> See <code>PRODUCTION_OAUTH_SETUP.md</code>
        </p>
    </div>

    <div class="delete-warning">
        <strong>🚨 IMPORTANT:</strong> Delete this file (<code>check-oauth-config.php</code>) after checking!<br>
        Run: <code>rm public/check-oauth-config.php</code>
    </div>

    <div style="text-align: center; margin-top: 30px; color: #999;">
        Generated: <?php echo date('Y-m-d H:i:s'); ?>
    </div>
</body>
</html>


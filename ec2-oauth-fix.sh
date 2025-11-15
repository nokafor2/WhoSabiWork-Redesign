#!/bin/bash

# 🔧 AWS EC2 OAuth Quick Fix Script
# This script restarts services and clears all caches properly

echo "════════════════════════════════════════════════════"
echo "🔧 AWS EC2 OAuth Configuration Fix"
echo "════════════════════════════════════════════════════"
echo ""

# Change to your Laravel project directory
# UPDATE THIS PATH TO YOUR ACTUAL PROJECT PATH
PROJECT_PATH="/var/www/html"

cd "$PROJECT_PATH" || {
    echo "❌ Error: Could not find project at $PROJECT_PATH"
    echo "Please edit this script and set the correct PROJECT_PATH"
    exit 1
}

echo "📂 Working in: $(pwd)"
echo ""

# Step 1: Remove cached files manually
echo "🗑️  Step 1: Removing cached configuration files..."
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes.php
rm -f bootstrap/cache/services.php
echo "✅ Cached files removed"
echo ""

# Step 2: Clear Laravel caches
echo "🧹 Step 2: Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo "✅ Laravel caches cleared"
echo ""

# Step 3: Check .env file
echo "🔍 Step 3: Checking .env file..."
if [ ! -f .env ]; then
    echo "❌ Error: .env file not found!"
    exit 1
fi

echo "📄 .env file found"
echo "🔑 Google OAuth settings:"
grep "GOOGLE_CLIENT_ID\|GOOGLE_CLIENT_SECRET\|GOOGLE_REDIRECT\|APP_URL" .env | sed 's/=.*/=***HIDDEN***/'
echo ""

# Step 4: Set correct permissions
echo "🔐 Step 4: Setting file permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || sudo chown -R nginx:nginx storage bootstrap/cache 2>/dev/null || sudo chown -R apache:apache storage bootstrap/cache 2>/dev/null
sudo chmod -R 775 storage bootstrap/cache
echo "✅ Permissions set"
echo ""

# Step 5: Restart PHP-FPM
echo "🔄 Step 5: Restarting PHP-FPM..."
PHP_FPM_SERVICE=$(systemctl list-units --type=service --all | grep -o 'php[0-9.]*-fpm' | head -1)
if [ -z "$PHP_FPM_SERVICE" ]; then
    PHP_FPM_SERVICE="php-fpm"
fi
echo "   Restarting $PHP_FPM_SERVICE..."
sudo systemctl restart "$PHP_FPM_SERVICE"
sleep 2
sudo systemctl status "$PHP_FPM_SERVICE" --no-pager | head -3
echo "✅ PHP-FPM restarted"
echo ""

# Step 6: Restart Web Server
echo "🔄 Step 6: Restarting web server..."
if systemctl is-active --quiet nginx; then
    echo "   Restarting Nginx..."
    sudo systemctl restart nginx
    sleep 2
    sudo systemctl status nginx --no-pager | head -3
    WEB_SERVER="Nginx"
elif systemctl is-active --quiet httpd; then
    echo "   Restarting Apache (httpd)..."
    sudo systemctl restart httpd
    sleep 2
    sudo systemctl status httpd --no-pager | head -3
    WEB_SERVER="Apache (httpd)"
elif systemctl is-active --quiet apache2; then
    echo "   Restarting Apache (apache2)..."
    sudo systemctl restart apache2
    sleep 2
    sudo systemctl status apache2 --no-pager | head -3
    WEB_SERVER="Apache (apache2)"
else
    echo "⚠️  Warning: Could not detect web server (Nginx/Apache)"
    WEB_SERVER="Unknown"
fi
echo "✅ Web server restarted"
echo ""

# Step 7: Recache for production (optional but recommended)
echo "⚡ Step 7: Optimizing for production..."
php artisan config:cache
php artisan route:cache
echo "✅ Configuration cached"
echo ""

# Step 8: Test configuration
echo "🧪 Step 8: Testing OAuth configuration..."
echo "════════════════════════════════════════════════════"

php artisan tinker --execute="
    \$clientId = config('services.google.client_id');
    \$clientSecret = config('services.google.client_secret');
    \$redirect = config('services.google.redirect');
    \$appUrl = config('app.url');
    
    echo '\n';
    echo '📊 Configuration Test Results:\n';
    echo '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n';
    
    if (\$clientId) {
        echo '✅ GOOGLE_CLIENT_ID: SET\n';
        echo '   ' . substr(\$clientId, 0, 20) . '...' . substr(\$clientId, -10) . '\n';
    } else {
        echo '❌ GOOGLE_CLIENT_ID: NOT SET\n';
    }
    
    echo '\n';
    
    if (\$clientSecret) {
        echo '✅ GOOGLE_CLIENT_SECRET: SET\n';
        echo '   ' . substr(\$clientSecret, 0, 15) . '...' . substr(\$clientSecret, -5) . '\n';
    } else {
        echo '❌ GOOGLE_CLIENT_SECRET: NOT SET\n';
    }
    
    echo '\n';
    
    if (\$redirect) {
        echo '✅ GOOGLE_REDIRECT_URL: SET\n';
        echo '   ' . \$redirect . '\n';
    } else {
        echo '❌ GOOGLE_REDIRECT_URL: NOT SET\n';
    }
    
    echo '\n';
    
    if (\$appUrl) {
        echo '✅ APP_URL: SET\n';
        echo '   ' . \$appUrl . '\n';
    } else {
        echo '⚠️  APP_URL: NOT SET\n';
    }
    
    echo '\n';
    echo '━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━\n';
    
    if (\$clientId && \$clientSecret && \$redirect) {
        echo '\n✅ All OAuth settings are configured!\n';
    } else {
        echo '\n❌ Some OAuth settings are missing!\n';
        echo '\nTo fix:\n';
        echo '1. Edit .env file: nano .env\n';
        echo '2. Add missing variables\n';
        echo '3. Run this script again\n';
    }
    
    echo '\n';
"

echo ""
echo "════════════════════════════════════════════════════"
echo "✅ Fix script completed!"
echo "════════════════════════════════════════════════════"
echo ""
echo "📝 Summary:"
echo "   • Project: $PROJECT_PATH"
echo "   • PHP-FPM: $PHP_FPM_SERVICE"
echo "   • Web Server: $WEB_SERVER"
echo ""
echo "🧪 Next Steps:"
echo "   1. Visit your login page"
echo "   2. Click 'Sign in with Google'"
echo "   3. Test the OAuth flow"
echo ""
echo "🔗 Diagnostic URL:"
echo "   https://your-domain.com/check-oauth-config.php"
echo "   (Upload check-oauth-config.php to public/ folder first)"
echo ""
echo "🆘 Still having issues?"
echo "   • Check Laravel logs: tail -f storage/logs/laravel.log"
echo "   • Check web server logs: sudo tail -f /var/log/nginx/error.log"
echo "   • Review: AWS_EC2_OAUTH_FIX.md"
echo ""


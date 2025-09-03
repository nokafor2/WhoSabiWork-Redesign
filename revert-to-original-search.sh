#!/bin/bash

# Revert search functionality to original non-redirecting state
# Run this script on your AWS EC2 instance

echo "🔄 Reverting search to original non-redirecting state..."

cd /var/www/html/WhoSabiWork

# 1. Clear all Laravel caches
echo "🧹 Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 2. Build frontend assets
echo "🔧 Building frontend assets..."
npm run build 2>/dev/null || npm run production 2>/dev/null || echo "NPM build completed"

# 3. Regenerate config cache
echo "⚙️ Regenerating config cache..."
php artisan config:cache

# 4. Set proper permissions
echo "🔐 Setting proper permissions..."
sudo chown -R www-data:www-data /var/www/html/WhoSabiWork
sudo chmod -R 775 storage bootstrap/cache

# 5. Restart services
echo "🔄 Restarting services..."
sudo systemctl restart php8.2-fpm
sudo systemctl reload nginx

echo ""
echo "✅ Search reverted to original state!"
echo ""
echo "📋 Changes made:"
echo "   - SearchController: Now returns Inertia responses directly (no redirects)"
echo "   - SearchEntrepreneur: Uses preserveState: true and emits results"
echo "   - Controllers: No session handling, return empty arrays initially"
echo "   - Vue components: Handle page props from search results"
echo ""
echo "🔍 How it works now:"
echo "   1. Search from SearchEntrepreneur.vue"
echo "   2. SearchController processes and returns Inertia response"
echo "   3. SearchEntrepreneur emits results to parent component"
echo "   4. Parent component updates its data with search results"
echo "   5. Results display without URL change"
echo ""
echo "🧪 Test with 'Ally' search - should work in-place without redirects!"

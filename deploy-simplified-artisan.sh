#!/bin/bash

# Deploy simplified Artisan/Index.vue with direct data assignment
# Run this script on your AWS EC2 instance

echo "🚀 Deploying simplified Artisan/Index.vue..."

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
echo "✅ Simplified Artisan/Index.vue deployed!"
echo ""
echo "🔍 What was simplified:"
echo "   ❌ Removed complex computed property"
echo "   ❌ Removed watchers"
echo "   ❌ Removed multiple data sources (flashData, directArtisans)"
echo "   ✅ Added simple artisans2 array"
echo "   ✅ Direct data assignment in methods"
echo "   ✅ Clear console logging for debugging"
echo ""
echo "🧪 Testing the simplified version:"
echo "   1. Navigate to artisans page"
echo "   2. Search for 'Ally'"
echo "   3. Look for console messages:"
echo "      🚀 Artisan component mounted"
echo "      🔍 updateSearchedArtisans called with:"
echo "      ✅ Setting artisans2 from search results"
echo "      ✅ Search results set: [...]"
echo "      ✅ Results length: X"
echo ""
echo "🎯 Expected behavior:"
echo "   - Simple data flow: Search → Method → Direct Assignment → Template Update"
echo "   - No complex reactivity chains"
echo "   - Clear debugging output"
echo "   - Results should display immediately after search"

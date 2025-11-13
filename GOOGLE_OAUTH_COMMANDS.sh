#!/bin/bash
# Google OAuth Implementation - Command Reference
# Run these commands in order after setting up Google credentials

echo "=================================================="
echo "Google OAuth Setup Commands"
echo "=================================================="
echo ""

# Step 1: Install Laravel Socialite
echo "Step 1: Installing Laravel Socialite..."
composer require laravel/socialite

# Step 2: Clear configuration cache
echo ""
echo "Step 2: Clearing configuration cache..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear

# Step 3: Migrate database (DEVELOPMENT ONLY - WIPES DATA!)
echo ""
echo "Step 3: Migrating database..."
echo "⚠️  WARNING: This will WIPE your database!"
read -p "Continue with migration? (y/N): " confirm
if [[ $confirm == [yY] ]]; then
    php artisan migrate:fresh --seed
    echo "✅ Database migrated and seeded"
else
    echo "⏭️  Skipping migration. Run manually when ready:"
    echo "   php artisan migrate:fresh --seed"
fi

# Step 4: Clear caches again
echo ""
echo "Step 4: Clearing caches again..."
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear

# Step 5: Verify routes
echo ""
echo "Step 5: Verifying OAuth routes..."
php artisan route:list | grep "oauth"

echo ""
echo "=================================================="
echo "✅ Setup Complete!"
echo "=================================================="
echo ""
echo "Next steps:"
echo "1. Make sure you've added Google credentials to .env:"
echo "   GOOGLE_CLIENT_ID=your_client_id"
echo "   GOOGLE_CLIENT_SECRET=your_client_secret"
echo "   GOOGLE_REDIRECT_URL=\"\${APP_URL}/auth/google/callback\""
echo ""
echo "2. Start your development server:"
echo "   php artisan serve"
echo ""
echo "3. Visit http://localhost/userlogin and click Google button"
echo ""
echo "=================================================="


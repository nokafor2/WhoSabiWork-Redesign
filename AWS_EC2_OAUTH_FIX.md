# 🔧 AWS EC2 OAuth Troubleshooting Guide

## Problem: OAuth not working after updating .env and clearing cache

---

## ✅ **Solution Steps for AWS EC2**

### **Step 1: Restart Web Server & PHP-FPM**

SSH into your EC2 instance and run:

#### **For Apache + PHP-FPM:**
```bash
sudo systemctl restart php-fpm
sudo systemctl restart httpd
# or
sudo systemctl restart apache2
```

#### **For Nginx + PHP-FPM:**
```bash
sudo systemctl restart php-fpm
sudo systemctl restart nginx
```

#### **Check if services restarted successfully:**
```bash
sudo systemctl status php-fpm
sudo systemctl status nginx  # or httpd/apache2
```

---

### **Step 2: Clear PHP OpCache**

PHP OpCache can cache old configurations:

```bash
# Restart PHP-FPM to clear OpCache
sudo systemctl restart php8.1-fpm  # or your PHP version (php7.4-fpm, php8.0-fpm, etc.)

# Or restart all PHP versions
sudo systemctl restart php*-fpm
```

---

### **Step 3: Verify .env File Location**

Make sure you're editing the **correct** .env file:

```bash
# Navigate to your Laravel project
cd /var/www/html  # or your project path

# Check .env file exists
ls -la .env

# View the Google OAuth settings
cat .env | grep GOOGLE

# Should show:
# GOOGLE_CLIENT_ID=your-id-here
# GOOGLE_CLIENT_SECRET=your-secret-here
# GOOGLE_REDIRECT_URL=...
```

---

### **Step 4: Check File Permissions**

Ensure Laravel can read the .env file:

```bash
# Set correct ownership (replace www-data with your web server user)
sudo chown www-data:www-data .env

# Set correct permissions
sudo chmod 644 .env

# Check permissions
ls -la .env
# Should show: -rw-r--r-- www-data www-data
```

---

### **Step 5: Manually Remove Config Cache**

Sometimes `config:clear` doesn't fully clear:

```bash
# Remove cached config files manually
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes.php
rm -f bootstrap/cache/services.php

# Clear all Laravel caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan config:cache
php artisan route:cache
```

---

### **Step 6: Check Environment Loading**

Verify Laravel is loading the .env file:

```bash
# Run Laravel Tinker
php artisan tinker

# Then run these commands in Tinker:
>>> config('services.google.client_id');
>>> config('services.google.client_secret');
>>> config('services.google.redirect');
>>> config('app.url');

# Press Ctrl+D to exit Tinker
```

**Expected output:**
- Should show your actual credentials, **not** `null`

---

### **Step 7: Check Google Console Settings**

Your Google Cloud Console must have the **exact** redirect URI:

1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Navigate to: **APIs & Services** > **Credentials**
3. Click on your OAuth 2.0 Client ID
4. Check **Authorized redirect URIs**:
   ```
   https://your-ec2-domain.com/auth/google/callback
   ```

**Important:**
- ✅ Must use **HTTPS** (not HTTP)
- ✅ No trailing slash
- ✅ Must match exactly (including www or non-www)

---

### **Step 8: Test with Diagnostic Script**

Upload the diagnostic script to check live config:

```bash
# Upload check-oauth-config.php to your EC2 instance
scp public/check-oauth-config.php ec2-user@your-ec2-ip:/var/www/html/public/

# Or create it directly on server
nano /var/www/html/public/check-oauth-config.php
# (paste the content from check-oauth-config.php)
```

Visit: `https://your-domain.com/check-oauth-config.php`

**Delete the file after checking:**
```bash
rm public/check-oauth-config.php
```

---

### **Step 9: Check HTTPS Configuration**

OAuth requires HTTPS in production. Verify SSL:

```bash
# Check if SSL certificate is installed
sudo certbot certificates

# If using Apache
sudo apache2ctl -S | grep 443

# If using Nginx
sudo nginx -T | grep ssl
```

**If HTTPS is not configured:**
```bash
# Install Certbot (Let's Encrypt)
sudo yum install certbot python3-certbot-nginx  # Amazon Linux 2
# or
sudo apt install certbot python3-certbot-nginx  # Ubuntu

# Generate SSL certificate
sudo certbot --nginx -d your-domain.com
# or for Apache
sudo certbot --apache -d your-domain.com
```

---

### **Step 10: Check Laravel Logs**

View error logs for clues:

```bash
# View Laravel logs
tail -f storage/logs/laravel.log

# View web server error logs
# For Apache
sudo tail -f /var/log/httpd/error_log
# For Nginx
sudo tail -f /var/log/nginx/error.log
```

---

## 🔍 **Quick Diagnostic Commands**

Run all these on your EC2 instance:

```bash
# 1. Check current directory
pwd

# 2. List .env file
ls -la .env

# 3. Check Google config in .env
grep GOOGLE .env

# 4. Test config loading
php artisan tinker --execute="echo config('services.google.client_id');"

# 5. List routes
php artisan route:list | grep oauth

# 6. Check web server status
sudo systemctl status nginx  # or apache2/httpd

# 7. Check PHP-FPM status
sudo systemctl status php-fpm
```

---

## 🚨 **Common EC2-Specific Issues**

### Issue: Config returns `null` even after updating .env

**Cause:** Config is cached or PHP-FPM not restarted

**Solution:**
```bash
# Remove cache files
rm -f bootstrap/cache/config.php

# Restart PHP-FPM
sudo systemctl restart php-fpm

# Clear and recache
php artisan config:clear
php artisan config:cache
```

---

### Issue: "redirect_uri_mismatch" error

**Cause:** Google Console redirect URI doesn't match actual domain

**Solution:**
1. Check your **actual** production URL: `https://ec2-xx-xx-xx-xx.compute-1.amazonaws.com`
2. Or use your custom domain: `https://your-domain.com`
3. Add **exact** URL to Google Console
4. Update `.env` file: `APP_URL=https://your-actual-domain.com`

---

### Issue: HTTP instead of HTTPS

**Cause:** OAuth requires HTTPS in production

**Solution:**
```bash
# Force HTTPS redirect in Laravel
# Edit app/Http/Middleware/TrustProxies.php

# Add to AppServiceProvider boot() method:
if (config('app.env') === 'production') {
    URL::forceScheme('https');
}
```

---

### Issue: Environment variables not loading

**Cause:** Server might be using actual environment variables instead of .env

**Check:**
```bash
# Check if environment variables are set
printenv | grep GOOGLE

# If they exist, they override .env file
# You need to set them or unset them
```

---

### Issue: Wrong PHP version

**Cause:** Multiple PHP versions installed, using wrong one

**Solution:**
```bash
# Check PHP version
php -v

# Check which PHP-FPM is running
sudo systemctl status php*-fpm

# Restart the correct version
sudo systemctl restart php8.1-fpm  # or your version
```

---

## ✅ **Complete Fix Script**

Create a file `fix-oauth.sh` on your EC2:

```bash
#!/bin/bash

echo "🔧 Fixing OAuth configuration on EC2..."

# Navigate to Laravel project
cd /var/www/html  # Change to your project path

# Remove cached files
echo "Removing cached files..."
rm -f bootstrap/cache/config.php
rm -f bootstrap/cache/routes.php
rm -f bootstrap/cache/services.php

# Clear Laravel caches
echo "Clearing Laravel caches..."
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Set correct permissions
echo "Setting file permissions..."
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# Restart services
echo "Restarting services..."
sudo systemctl restart php-fpm
sudo systemctl restart nginx  # or apache2/httpd

# Recache for production
echo "Caching configuration..."
php artisan config:cache
php artisan route:cache

# Test configuration
echo ""
echo "Testing configuration..."
php artisan tinker --execute="
    echo 'GOOGLE_CLIENT_ID: ' . (config('services.google.client_id') ? 'SET ✅' : 'NOT SET ❌') . PHP_EOL;
    echo 'GOOGLE_CLIENT_SECRET: ' . (config('services.google.client_secret') ? 'SET ✅' : 'NOT SET ❌') . PHP_EOL;
    echo 'GOOGLE_REDIRECT: ' . config('services.google.redirect') . PHP_EOL;
"

echo ""
echo "✅ Done! Test OAuth at: https://your-domain.com/userlogin"
```

Run it:
```bash
chmod +x fix-oauth.sh
./fix-oauth.sh
```

---

## 🧪 **Test the Fix**

1. Visit: `https://your-domain.com/userlogin`
2. Click **"Sign in with Google"**
3. Should redirect to Google OAuth
4. After authorization, should redirect back and log you in

---

## 🆘 **Still Not Working?**

### **Last Resort Checks:**

1. **Check if using Load Balancer:**
   ```bash
   # If behind ALB/ELB, check X-Forwarded-Proto
   # Edit app/Http/Middleware/TrustProxies.php
   protected $proxies = '*';
   ```

2. **Check SELinux (Amazon Linux):**
   ```bash
   # Disable SELinux temporarily for testing
   sudo setenforce 0
   
   # If it works, properly configure SELinux
   sudo chcon -R -t httpd_sys_rw_content_t storage/
   ```

3. **Check Firewall:**
   ```bash
   # Ensure port 443 is open
   sudo firewall-cmd --list-all
   ```

4. **Check EC2 Security Group:**
   - AWS Console > EC2 > Security Groups
   - Ensure **HTTPS (443)** is allowed inbound

---

## 📝 **Quick Checklist**

- [ ] Updated `.env` with production Google OAuth credentials
- [ ] `.env` file has correct path: `/var/www/html/.env`
- [ ] `.env` file permissions: `644` owned by web server user
- [ ] Cleared all Laravel caches
- [ ] Removed bootstrap/cache files manually
- [ ] Restarted PHP-FPM
- [ ] Restarted web server (Nginx/Apache)
- [ ] HTTPS is configured and working
- [ ] Google Console has correct redirect URI
- [ ] Tested with Tinker - config returns actual values (not null)
- [ ] Checked Laravel logs for errors
- [ ] Web server logs show no errors

---

## 💡 **Pro Tips**

1. **Always use separate credentials** for development and production
2. **Use `.env.example`** to document required variables
3. **Set up monitoring** for OAuth failures
4. **Use AWS Systems Manager Parameter Store** for sensitive credentials
5. **Enable CloudWatch logs** for better debugging

---

**Need more help?** Share the output of:
```bash
php artisan tinker --execute="var_dump(config('services.google'));"
```

And I can help debug further!


# 🚀 Production 419 Error Fix Guide for AWS EC2

## Problem
419 CSRF token mismatch errors occurring on:
- Reply to comments
- Reply to photograph comments
- Editing photograph captions

Works fine locally but fails in production.

---

## 🔍 Root Causes
1. Session cookies not being set properly due to domain mismatch
2. HTTPS/HTTP cookie security settings
3. Storage directory permissions
4. Cache not cleared after deployment
5. APP_URL not matching actual domain

---

## ✅ Complete Fix Steps

### 1️⃣ **Update Production `.env` File**

SSH into your EC2 instance and edit `.env`:

```bash
ssh -i your-key.pem ubuntu@your-ec2-ip
cd /var/www/your-project  # Adjust path
nano .env
```

Add/update these lines:

```env
# Application Settings
APP_NAME="WhoSabiWork"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Session Configuration (CRITICAL FOR 419 FIX)
SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_DOMAIN=.yourdomain.com
SESSION_SECURE_COOKIE=true
SESSION_SAME_SITE=lax

# If NOT using HTTPS, set to false
# SESSION_SECURE_COOKIE=false

# Sanctum (if used)
SANCTUM_STATEFUL_DOMAINS=yourdomain.com,www.yourdomain.com
```

**Replace `yourdomain.com` with your actual domain!**

---

### 2️⃣ **Fix Storage Permissions**

```bash
# Set proper ownership
sudo chown -R www-data:www-data storage bootstrap/cache

# Set proper permissions
sudo chmod -R 775 storage bootstrap/cache

# Ensure session directory exists and is writable
sudo mkdir -p storage/framework/sessions
sudo chmod -R 775 storage/framework/sessions
```

---

### 3️⃣ **Clear All Caches**

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
php artisan session:clear

# Rebuild config cache
php artisan config:cache
php artisan route:cache
```

---

### 4️⃣ **Test CSRF Configuration**

Visit these URLs in your browser after deployment:

```
https://yourdomain.com/test-csrf
```

You should see JSON output like:
```json
{
  "csrf_token": "abc123...",
  "session_id": "xyz789...",
  "session_driver": "file",
  "session_domain": ".yourdomain.com",
  "session_secure": true,
  "session_same_site": "lax",
  "session_writable": true
}
```

**Check:**
- ✅ `session_writable` should be `true`
- ✅ `session_domain` should match your domain
- ✅ `csrf_token` should be a long string

---

### 5️⃣ **Build Frontend Assets**

```bash
# Install dependencies
npm install

# Build for production
npm run build

# Or if using Vite
npm run build
```

---

### 6️⃣ **Restart Services**

```bash
# Restart PHP-FPM
sudo systemctl restart php8.2-fpm  # Adjust PHP version

# Restart Nginx
sudo systemctl restart nginx

# Or Apache
sudo systemctl restart apache2
```

---

### 7️⃣ **Test Each Functionality**

After deployment, test:
1. ✅ Login/Logout
2. ✅ Add reply to comment
3. ✅ Add reply to photograph comment
4. ✅ Edit photograph caption
5. ✅ Check browser console for errors

---

## 🐛 Still Having Issues?

### Check Browser Console
Open Developer Tools (F12) and check:
```javascript
// In Console tab, run:
document.cookie
```

Look for your session cookie. It should exist and have:
- Name: `whosabiwork_session` or similar
- Domain: `.yourdomain.com`
- Secure: `true` (if HTTPS)
- HttpOnly: `true`
- SameSite: `Lax`

### Check Laravel Logs
```bash
tail -f storage/logs/laravel.log
```

Look for:
- Session errors
- CSRF token errors
- Permission denied errors

### Verify Session Files
```bash
ls -la storage/framework/sessions
```

You should see session files being created when users visit the site.

---

## 🔒 Security Notes

1. **Always use HTTPS in production** → Set `SESSION_SECURE_COOKIE=true`
2. **Never expose `.env` file** → Ensure it's in `.gitignore`
3. **Remove test routes** → Delete `routes/test_csrf.php` after testing
4. **Keep dependencies updated** → Run `composer update` and `npm update` regularly

---

## 📝 Common Mistakes

❌ **Wrong:** `SESSION_DOMAIN=yourdomain.com` (no leading dot for subdomains)
✅ **Right:** `SESSION_DOMAIN=.yourdomain.com` (with leading dot)

❌ **Wrong:** `APP_URL=http://localhost`
✅ **Right:** `APP_URL=https://yourdomain.com`

❌ **Wrong:** `SESSION_SECURE_COOKIE=true` with HTTP
✅ **Right:** `SESSION_SECURE_COOKIE=false` for HTTP (or use HTTPS)

❌ **Wrong:** Forgetting to clear cache after `.env` changes
✅ **Right:** Always run `php artisan config:clear && php artisan config:cache`

---

## ✅ Final Checklist

Before considering the fix complete:

- [ ] `.env` updated with correct domain
- [ ] Storage permissions set correctly (775)
- [ ] All caches cleared
- [ ] Frontend assets rebuilt
- [ ] Services restarted
- [ ] Test route shows `session_writable: true`
- [ ] Can login successfully
- [ ] Can add replies to comments
- [ ] Can edit photograph captions
- [ ] Browser console shows no CSRF errors
- [ ] Test routes removed from production

---

## 🆘 Emergency Rollback

If something breaks:

```bash
# Restore previous .env
cp .env.backup .env

# Clear everything
php artisan config:clear
php artisan cache:clear

# Restart services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx
```

---

## 📞 Need More Help?

If issues persist after following all steps:
1. Check `storage/logs/laravel.log` for specific errors
2. Verify your Nginx/Apache configuration
3. Ensure SSL certificate is valid
4. Check firewall rules on EC2
5. Verify security groups allow HTTPS (port 443)

---

**Last Updated:** $(date)
**Laravel Version:** Check with `php artisan --version`
**PHP Version:** Check with `php -v`


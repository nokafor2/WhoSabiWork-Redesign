# ⚡ Quick Fix Reference - 419 Error in Production

## 🎯 Most Common Fix (Do This First!)

### On Your EC2 Server:

```bash
# 1. Update .env file
nano .env

# Add these lines (replace yourdomain.com):
APP_URL=https://yourdomain.com
SESSION_DOMAIN=.yourdomain.com
SESSION_SECURE_COOKIE=true

# 2. Fix permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache

# 3. Clear cache
php artisan config:clear
php artisan cache:clear
php artisan config:cache

# 4. Restart services
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

# 5. Test
# Visit: https://yourdomain.com/test-csrf
# Should show: "session_writable": true
```

---

## 🔍 Quick Diagnostics

### Test if CSRF is working:
```bash
curl https://yourdomain.com/test-csrf
```

Expected output should include:
```json
{
  "session_writable": true,
  "csrf_token": "some-long-token-here"
}
```

### Check session directory:
```bash
ls -la storage/framework/sessions/
```

Should show files being created.

---

## 🚨 If Still Not Working

### Check these in order:

1. **Is APP_URL correct?**
   ```bash
   grep APP_URL .env
   # Should match your actual domain
   ```

2. **Are permissions correct?**
   ```bash
   ls -la storage/framework/sessions/
   # Should show www-data ownership
   ```

3. **Is config cached?**
   ```bash
   php artisan config:clear
   php artisan config:cache
   ```

4. **Are services running?**
   ```bash
   sudo systemctl status php8.2-fpm
   sudo systemctl status nginx
   ```

---

## 💡 HTTP vs HTTPS

**Using HTTPS (SSL)?**
```env
SESSION_SECURE_COOKIE=true
```

**Using HTTP only?**
```env
SESSION_SECURE_COOKIE=false
```

---

## 🎯 Critical .env Settings

```env
APP_URL=https://yourdomain.com           # Must match actual domain
SESSION_DOMAIN=.yourdomain.com           # Note the leading dot
SESSION_SECURE_COOKIE=true               # true for HTTPS, false for HTTP
SESSION_DRIVER=file                      # file is most reliable
SESSION_LIFETIME=120                     # 2 hours
```

---

## ✅ Success Checklist

- [ ] `.env` updated
- [ ] Cache cleared
- [ ] Permissions fixed
- [ ] Services restarted
- [ ] Test route returns `session_writable: true`
- [ ] Can submit forms without 419 error

---

## 📞 Still Stuck?

Check the full guide: `PRODUCTION_419_FIX_GUIDE.md`


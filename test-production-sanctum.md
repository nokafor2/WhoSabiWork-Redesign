# 🧪 Laravel Sanctum Production Testing Guide

## Prerequisites
1. Your GitLab CI/CD pipeline has deployed the updated code
2. `.env.production` has been updated with Sanctum settings
3. Database migrations have been run

## 🔧 Manual Steps Before Testing

### Step 1: Update .env.production on AWS
SSH into your AWS server and update the `.env.production` file:

```bash
ssh ec2-user@ec2-13-40-186-156.eu-west-2.compute.amazonaws.com
cd /var/www/html/WhoSabiWork-Redesign
nano .env.production
```

Copy the entire content from `aws-production-env.txt` to replace your `.env.production`, or update these key settings:
```env
SANCTUM_STATEFUL_DOMAINS=ec2-13-40-186-156.eu-west-2.compute.amazonaws.com,13.40.186.156,localhost
SESSION_DRIVER=database
SESSION_DOMAIN=.ec2-13-40-186-156.eu-west-2.compute.amazonaws.com
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_SECURE_COOKIE=false
SESSION_SAME_SITE=lax
SANCTUM_GUARD=web
CORS_ALLOWED_ORIGINS=http://ec2-13-40-186-156.eu-west-2.compute.amazonaws.com,http://13.40.186.156
CORS_SUPPORTS_CREDENTIALS=true
APP_URL=http://ec2-13-40-186-156.eu-west-2.compute.amazonaws.com
CACHE_DRIVER=redis
```

### Step 2: Run Migrations
```bash
cd /var/www/html/WhoSabiWork-Redesign
php artisan migrate --force
php artisan config:cache
sudo systemctl restart php-fpm nginx
```

## 🧪 Production Testing Steps

### Test 1: API Endpoints
```bash
# Test public API endpoint
curl -X GET "http://ec2-13-40-186-156.eu-west-2.compute.amazonaws.com/api/states" \
  -H "Accept: application/json" \
  -H "X-Requested-With: XMLHttpRequest"

# Alternative using IP
curl -X GET "http://13.40.186.156/api/states" \
  -H "Accept: application/json" \
  -H "X-Requested-With: XMLHttpRequest"

# Expected: JSON response with states data
```

### Test 2: CSRF Cookie
```bash
# Get CSRF cookie
curl -X GET "http://ec2-13-40-186-156.eu-west-2.compute.amazonaws.com/sanctum/csrf-cookie" \
  -H "Accept: application/json" \
  -c cookies.txt

# Expected: 200 OK status, XSRF-TOKEN cookie set
```

### Test 3: Login API
```bash
# Login with credentials (replace with real user)
curl -X POST "http://ec2-13-40-186-156.eu-west-2.compute.amazonaws.com/api/login" \
  -H "Accept: application/json" \
  -H "Content-Type: application/json" \
  -H "X-Requested-With: XMLHttpRequest" \
  -b cookies.txt \
  -d '{
    "email": "test@example.com",
    "password": "password123"
  }'

# Expected: JSON response with user data and token
```

### Test 4: Protected API Endpoint
```bash
# Use token from login response
curl -X GET "http://ec2-13-40-186-156.eu-west-2.compute.amazonaws.com/api/user" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer YOUR_TOKEN_HERE" \
  -H "X-Requested-With: XMLHttpRequest"

# Expected: User data JSON response
```

### Test 5: Frontend Integration
Open browser and test:
1. Navigate to `http://ec2-13-40-186-156.eu-west-2.compute.amazonaws.com`
2. Open browser developer tools → Network tab
3. Try logging in through the frontend
4. Check for:
   - CSRF cookie being set
   - Successful login response
   - Subsequent API calls include proper headers

## 🐛 Troubleshooting

### Issue: CORS Errors
**Symptoms:** Browser console shows CORS errors
**Solution:**
```bash
# Check CORS configuration
php artisan config:show cors
# Verify your domain is in CORS_ALLOWED_ORIGINS
```

### Issue: Session Not Persisting
**Symptoms:** User gets logged out immediately
**Solution:**
```bash
# Check session table exists
php artisan migrate:status | grep sessions
# Verify session configuration
php artisan config:show session
```

### Issue: Token Not Working
**Symptoms:** 401 Unauthorized on protected routes
**Solution:**
```bash
# Check Sanctum tables
php artisan migrate:status | grep personal_access_tokens
# Verify token in database
mysql -h your-rds-endpoint -u admin -p
USE whosabiwork_DB;
SELECT * FROM personal_access_tokens ORDER BY created_at DESC LIMIT 5;
```

### Issue: SSL/HTTPS Problems
**Symptoms:** Mixed content warnings, insecure requests
**Solution:**
1. Ensure `APP_URL=https://whosabiwork.com`
2. Set `SESSION_SECURE_COOKIE=true`
3. Configure web server for SSL

## ✅ Success Indicators
- [ ] CSRF cookie endpoint returns 200
- [ ] Login API returns user data and token
- [ ] Protected routes work with Bearer token
- [ ] Sessions persist across requests
- [ ] No CORS errors in browser console
- [ ] Frontend can authenticate and stay logged in

## 📊 Production Monitoring
Monitor these logs after deployment:
```bash
# Laravel logs
tail -f /var/www/html/WhoSabiWork-Redesign/storage/logs/laravel.log

# Nginx access logs
sudo tail -f /var/log/nginx/access.log

# PHP-FPM logs
sudo tail -f /var/log/php-fpm/www-error.log
```

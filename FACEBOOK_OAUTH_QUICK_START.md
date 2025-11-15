# 🚀 Facebook OAuth - Quick Start Guide

**5-Minute Setup** | Get Facebook Login working fast!

---

## 📋 **Quick Overview**

```
1. Create Facebook App (5 min)
2. Get App ID & Secret (1 min)
3. Add to .env file (1 min)
4. Clear cache & test (2 min)
```

**Total Time:** ~10 minutes

---

## 🎯 **Step 1: Create Facebook App**

### **Go to Facebook Developers**
👉 **URL:** https://developers.facebook.com/

### **Create App**
1. Click **"Create App"** (green button)
2. Choose **"Consumer"** type
3. Enter app details:
   ```
   App Name: WhoSabiWork
   Email: your-email@example.com
   ```
4. Click **"Create App"**
5. Complete CAPTCHA

⏱️ **Time:** 2 minutes

---

## 🔑 **Step 2: Get Credentials**

### **Navigate to Settings**
1. Left sidebar → **Settings** → **Basic**

### **Copy Your Credentials**
```
App ID: [Copy this]
App Secret: Click "Show" → [Copy this]
```

### **Add App Domains**
Scroll down to find **"App Domains"**, add:
```
localhost
your-production-domain.com
```

Click **"Save Changes"**

⏱️ **Time:** 2 minutes

---

## 🔵 **Step 3: Add Facebook Login**

### **Add Product**
1. Left sidebar → **Add Products**
2. Find **"Facebook Login"**
3. Click **"Set Up"**
4. Choose **"Web"**

### **Configure OAuth Redirect URIs**
1. Left sidebar → **Facebook Login** → **Settings**
2. In **"Valid OAuth Redirect URIs"**, add:
   ```
   http://localhost/auth/facebook/callback
   https://your-production-domain.com/auth/facebook/callback
   ```
3. Click **"Save Changes"**

⏱️ **Time:** 3 minutes

---

## 💻 **Step 4: Add to Your Laravel App**

### **Update .env File**

Open `.env` and add:

```env
# Facebook OAuth Configuration
FACEBOOK_CLIENT_ID=your-app-id-from-step-2
FACEBOOK_CLIENT_SECRET=your-app-secret-from-step-2
FACEBOOK_REDIRECT_URL="${APP_URL}/auth/facebook/callback"
```

### **Clear Laravel Cache**

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

⏱️ **Time:** 2 minutes

---

## 🧪 **Step 5: Test It!**

### **Test Locally**

1. Visit: `http://localhost/userlogin`
2. Click **"Facebook"** button
3. Should redirect to Facebook
4. Login with your Facebook account
5. Authorize the app
6. Should redirect back and be logged in ✅

⏱️ **Time:** 1 minute

---

## 🚨 **Common Issues**

### **Error: "URL Blocked"**
```
Solution: Check OAuth Redirect URI in Facebook console
Make sure it matches EXACTLY (no trailing slash)
```

### **Error: "App Not Set Up"**
```
Solution: App is in Development Mode
Either add yourself as test user OR switch to Live Mode
```

### **Error: "Missing client_id"**
```
Solution: 
1. Check .env has FACEBOOK_CLIENT_ID
2. Run: php artisan config:clear
3. Restart server
```

---

## 📊 **Visual Flowchart**

```
User clicks "Facebook" button
          ↓
Redirects to Facebook Login
          ↓
User logs in to Facebook
          ↓
Facebook asks: "Allow WhoSabiWork to access your info?"
          ↓
User clicks "Continue"
          ↓
Facebook redirects to: your-app.com/auth/facebook/callback
          ↓
Laravel SocialAuthController handles callback
          ↓
Checks if user exists (by Facebook ID or email)
          ↓
    ┌─────┴─────┐
    ↓           ↓
Exists      New User
    ↓           ↓
Login       Create Account + Login
    ↓           ↓
    └─────┬─────┘
          ↓
Redirect to homepage (/)
          ↓
User is logged in ✅
```

---

## 🎯 **What You Get**

After Facebook login, Laravel receives:

```php
[
    'id' => 'facebook-user-id',
    'name' => 'John Doe',
    'email' => 'john@example.com',
    'avatar' => 'https://facebook-cdn.../picture',
    'token' => 'access-token',
]
```

Your app automatically:
- ✅ Creates user account (if new)
- ✅ Links Facebook to existing email (if exists)
- ✅ Logs user in
- ✅ Redirects to homepage

---

## 🔐 **For Production (AWS EC2)**

### **Same Facebook App or New One?**

**Option A: Use Same App** (Easier)
```
Just add production redirect URI to same app:
https://your-domain.com/auth/facebook/callback
```

**Option B: Separate App** (Recommended)
```
Create new Facebook App for production
Keeps dev and prod completely separate
```

### **Add to Production .env**

```bash
# SSH into EC2
ssh ec2-user@your-ec2-ip

# Edit .env
cd /var/www/html/WhoSabiWork-Redesign
nano .env

# Add credentials
FACEBOOK_CLIENT_ID=your-production-app-id
FACEBOOK_CLIENT_SECRET=your-production-app-secret
FACEBOOK_REDIRECT_URL="${APP_URL}/auth/facebook/callback"

# Save and exit
# Ctrl+X → Y → Enter

# Clear cache
php artisan config:clear
php artisan cache:clear

# Restart services
sudo systemctl restart php-fpm
sudo systemctl restart nginx
```

### **Make App Public (Live Mode)**

Your app starts in **Development Mode** (only you can test).

To allow anyone to login:
1. Facebook Console → Top right corner
2. Toggle from **"Development"** to **"Live"**
3. May need to request permissions first:
   - **App Review** → **Permissions and Features**
   - Request: `public_profile` and `email`

---

## 📱 **Mobile Testing (Optional)**

Facebook OAuth works on mobile browsers too!

Just make sure redirect URI includes mobile domain:
```
https://m.your-domain.com/auth/facebook/callback
```

---

## ✅ **Quick Checklist**

- [ ] Created Facebook App
- [ ] Got App ID and App Secret
- [ ] Added App Domains (localhost + production)
- [ ] Added Facebook Login product
- [ ] Configured OAuth Redirect URIs
- [ ] Added credentials to `.env`
- [ ] Cleared Laravel caches
- [ ] Tested locally - works!
- [ ] Added to production `.env` (if ready)
- [ ] Switched to Live Mode (for public)

---

## 🆘 **Need Help?**

**Full detailed guide:** See `FACEBOOK_OAUTH_SETUP.md`

**Facebook Developers:** https://developers.facebook.com/

**Laravel Socialite Docs:** https://laravel.com/docs/10.x/socialite

---

## 🎉 **That's It!**

You now have Facebook Login working! 

Users can login with:
- ✅ Email/Username/Phone + Password (existing)
- ✅ Google account (working)
- ✅ Facebook account (just added!)

---

**Questions?** Check the full guide in `FACEBOOK_OAUTH_SETUP.md`

**Last Updated:** 2025-11-15


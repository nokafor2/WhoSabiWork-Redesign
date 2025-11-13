# ⚡ Quick Start Guide - Google OAuth

**Time Required:** 15-20 minutes  
**Difficulty:** Easy

---

## 🚀 5-Step Setup

### Step 1: Install Laravel Socialite (2 minutes)

```bash
composer require laravel/socialite
```

Wait for installation to complete.

---

### Step 2: Get Google OAuth Credentials (10 minutes)

1. **Go to:** https://console.cloud.google.com/

2. **Create Project:**
   - Click "Select Project" → "New Project"
   - Name: "WhoSabiWork OAuth"
   - Click "Create"

3. **Enable API:**
   - Go to "APIs & Services" → "Library"
   - Search "Google+ API"
   - Click "Enable"

4. **Configure Consent Screen:**
   - Go to "APIs & Services" → "OAuth consent screen"
   - User Type: "External" → Click "Create"
   - App name: "WhoSabiWork"
   - User support email: your-email@example.com
   - Developer contact: your-email@example.com
   - Click "Save and Continue" (skip optional fields)
   - Scopes: Just click "Save and Continue"
   - Test users: Click "Save and Continue"
   - Click "Back to Dashboard"

5. **Create Credentials:**
   - Go to "APIs & Services" → "Credentials"
   - Click "Create Credentials" → "OAuth client ID"
   - Application type: "Web application"
   - Name: "WhoSabiWork Web"
   - Authorized redirect URIs:
     - Add: `http://localhost/auth/google/callback`
     - (Add your production URL later)
   - Click "Create"
   - **COPY** the Client ID and Client Secret (you'll need them next)

---

### Step 3: Configure .env File (2 minutes)

Open your `.env` file and add:

```env
GOOGLE_CLIENT_ID=paste_your_client_id_here
GOOGLE_CLIENT_SECRET=paste_your_client_secret_here
GOOGLE_REDIRECT_URL="${APP_URL}/auth/google/callback"
```

**Example:**
```env
GOOGLE_CLIENT_ID=123456789-abcdefghijk.apps.googleusercontent.com
GOOGLE_CLIENT_SECRET=GOCSPX-1234567890abcdefgh
GOOGLE_REDIRECT_URL="${APP_URL}/auth/google/callback"
```

---

### Step 4: Migrate Database (1 minute)

**Development (will wipe database):**
```bash
php artisan migrate:fresh --seed
```

**Production (safer):**
```bash
php artisan migrate
```

---

### Step 5: Clear Caches & Test (2 minutes)

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

**Start server:**
```bash
php artisan serve
```

**Test it:**
1. Visit: http://localhost/userlogin
2. Click the "Google" button
3. Sign in with your Google account
4. You should be redirected back and logged in! ✅

---

## ✅ Success Checklist

After completing setup, verify:

- [ ] Laravel Socialite installed
- [ ] Google Cloud Project created
- [ ] OAuth credentials obtained
- [ ] Credentials added to `.env`
- [ ] Database migrated successfully
- [ ] Caches cleared
- [ ] Server running
- [ ] Google login button works
- [ ] Can sign in with Google
- [ ] Redirected back after OAuth
- [ ] Automatically logged in

---

## 🐛 Quick Troubleshooting

### Error: "redirect_uri_mismatch"
**Fix:** In Google Console, make sure redirect URI is exactly:
```
http://localhost/auth/google/callback
```
(No trailing slash!)

### Error: "Class Socialite not found"
**Fix:** Run:
```bash
composer require laravel/socialite
php artisan config:clear
```

### Error: "Client authentication failed"
**Fix:** Check if credentials in `.env` are correct (no extra spaces)

### Google button doesn't work
**Fix:** Clear browser cache and try again

### User created but not logged in
**Fix:** Check `config/session.php` - session driver should be 'file' or 'database'

---

## 📱 What Happens When User Clicks "Google"?

```
1. User clicks "Google" button
   ↓
2. Redirected to Google's login page
   ↓
3. User signs in with Google account
   ↓
4. Google asks "Allow WhoSabiWork to access your email and profile?"
   ↓
5. User clicks "Allow"
   ↓
6. Google redirects back to your app
   ↓
7. App creates/finds user account
   ↓
8. User automatically logged in
   ↓
9. Redirected to homepage with success message
```

**Time:** Usually 5-10 seconds total

---

## 🎉 You're Done!

Your users can now login with Google! 🚀

### What Works Now:
✅ Login with Google account  
✅ Auto-create account from Google  
✅ Link Google to existing accounts  
✅ Regular password login still works  
✅ Seamless authentication  

### Next Steps (Optional):
- Add Facebook OAuth (similar process)
- Pull user avatar from Google
- Add "Login with Google" to registration page
- Set up production OAuth credentials

---

## 📚 Need More Help?

**Full Documentation:**
- `GOOGLE_OAUTH_ENV_SETUP.md` - Detailed setup guide
- `GOOGLE_OAUTH_IMPLEMENTATION_COMPLETE.md` - Complete implementation details
- `GOOGLE_OAUTH_RESTORE_POINT.md` - Rollback instructions

**Laravel Socialite Docs:**
https://laravel.com/docs/10.x/socialite

**Google OAuth Docs:**
https://developers.google.com/identity/protocols/oauth2

---

**Happy coding! 🎉**


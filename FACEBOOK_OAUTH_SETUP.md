# 🔵 Facebook OAuth Setup Guide

Complete step-by-step guide to setting up Facebook Login for your Laravel application.

---

## 📋 **Prerequisites**

- ✅ Personal Facebook account
- ✅ Email address (verified)
- ✅ Phone number (for verification)
- ✅ Your application domains (localhost + production)

---

## 🚀 **Step-by-Step Setup**

### **Step 1: Access Facebook Developers**

1. Go to [Facebook Developers](https://developers.facebook.com/)
2. Click **"Get Started"** in the top right (or **"My Apps"** if you already have an account)
3. Log in with your Facebook account
4. If first time, you'll need to verify your account:
   - Verify email address
   - May need to verify phone number
   - Accept Facebook Developer Terms

---

### **Step 2: Create a New App**

1. Click **"Create App"** button (green button in top right)

2. **Choose an app type:**
   - Select **"Consumer"** (for user authentication)
   - Click **"Next"**

   > **Note:** If you see different options like "Business", "Gaming", etc., choose **"Consumer"** or **"None"** (if available) for general web app login.

3. **Add app details:**
   ```
   App Name: WhoSabiWork
   App Contact Email: your-email@example.com
   ```
   - Click **"Create App"**

4. **Security Check:**
   - Complete the CAPTCHA security check
   - Click **"Submit"**

5. **Success!** Your app is created. You'll see the App Dashboard.

---

### **Step 3: Get Your App ID and App Secret**

1. On the left sidebar, click **"Settings"** > **"Basic"**

2. You'll see your credentials:
   ```
   App ID: 1234567890123456
   App Secret: [Click "Show" to reveal]
   ```

3. **Copy your App ID:**
   - This is visible by default
   - Copy it to a secure location

4. **Get your App Secret:**
   - Click **"Show"** button next to App Secret
   - Enter your Facebook password to confirm
   - Copy the App Secret to a secure location
   - ⚠️ **IMPORTANT:** Never share or commit this to Git!

5. **Add App Domains (Important!):**
   - Scroll down to find **"App Domains"**
   - Add your domains (one per line):
     ```
     localhost
     your-production-domain.com
     ```
   - Click **"Save Changes"** at the bottom

---

### **Step 4: Add Facebook Login Product**

1. On the left sidebar, find **"Add Products"** (or click **"Add Product"** button)

2. Find **"Facebook Login"** in the product list

3. Click **"Set Up"** button under Facebook Login

4. Choose your platform:
   - Select **"Web"** (or **"Website"**)
   - You may see a quickstart guide - you can skip this

---

### **Step 5: Configure Facebook Login Settings**

1. On the left sidebar, expand **"Facebook Login"**

2. Click on **"Settings"** (under Facebook Login)

3. **Configure OAuth Settings:**

   **Valid OAuth Redirect URIs:** (Most Important!)
   ```
   http://localhost/auth/facebook/callback
   https://your-production-domain.com/auth/facebook/callback
   ```
   
   > **CRITICAL:** 
   > - Must be EXACT URLs (no trailing slash)
   > - Use `http://` for localhost (development)
   > - Use `https://` for production (required!)
   > - If using port (like `:8000`), include it: `http://localhost:8000/auth/facebook/callback`

   **Client OAuth Login:** ✅ Enabled (should be on by default)
   
   **Web OAuth Login:** ✅ Enabled (should be on by default)
   
   **Force Web OAuth Reauthentication:** ❌ Disabled (optional)
   
   **Use Strict Mode for Redirect URIs:** ✅ Enabled (recommended)
   
   **Enforce HTTPS:** ✅ Enabled for production (disabled for localhost)

4. **Login from Devices:** (Optional)
   - Can leave blank for now

5. Click **"Save Changes"** at the bottom

---

### **Step 6: Configure App Mode (IMPORTANT!)**

By default, your app is in **Development Mode** and only works for you and test users.

#### **For Development/Testing:**

1. Top right corner shows app mode status
2. Should say **"Development Mode"**
3. **Add Test Users:**
   - Left sidebar: **Roles** > **Test Users**
   - Click **"Add"** to create test users
   - These test accounts can login even in Development Mode

#### **For Production (Public Use):**

1. **Complete App Review (Required for public use):**
   - Left sidebar: **App Review** > **Permissions and Features**
   - Request **"public_profile"** and **"email"** permissions
   - These are usually approved automatically

2. **Make App Public:**
   - Top right corner: Click the **"Development Mode"** toggle
   - Switch to **"Live Mode"**
   - Confirm the switch
   
   > **Note:** Some apps may require Business Verification first. If so:
   > - Settings > Basic > Complete Business Verification
   > - Upload business documents
   > - Wait for approval (can take a few days)

3. **App Goes Live:**
   - Anyone with a Facebook account can now login
   - Make sure you've completed all required fields in Settings > Basic

---

### **Step 7: Optional - Configure Data Deletion Callback**

Facebook requires a data deletion callback URL:

1. Go to **Settings** > **Basic**
2. Scroll to **"Data Deletion Instructions URL"**
3. Add your privacy policy or data deletion page:
   ```
   https://your-domain.com/privacy-policy
   https://your-domain.com/data-deletion
   ```
4. Or select **"Automatically delete user data"**
5. Click **"Save Changes"**

---

## 📝 **Summary of What You Need**

After completing the steps above, you should have:

```env
# From Step 3 (Settings > Basic)
FACEBOOK_CLIENT_ID=1234567890123456
FACEBOOK_CLIENT_SECRET=abcdef1234567890abcdef1234567890

# Your callback URL (from Step 5)
FACEBOOK_REDIRECT_URL=http://localhost/auth/facebook/callback
```

---

## 🔧 **Step 8: Add Credentials to Your Laravel App**

### **Local Development (.env file):**

1. Open your project's `.env` file
2. Add these lines:
   ```env
   # Facebook OAuth Configuration
   FACEBOOK_CLIENT_ID=your-app-id-here
   FACEBOOK_CLIENT_SECRET=your-app-secret-here
   FACEBOOK_REDIRECT_URL="${APP_URL}/auth/facebook/callback"
   ```

3. Replace `your-app-id-here` and `your-app-secret-here` with actual values

4. **Clear Laravel cache:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   ```

5. **Restart your development server**

---

### **Production (AWS EC2):**

1. SSH into your EC2 instance:
   ```bash
   ssh ec2-user@your-ec2-ip
   ```

2. Navigate to your Laravel project:
   ```bash
   cd /var/www/html/WhoSabiWork-Redesign
   ```

3. Edit the `.env` file:
   ```bash
   nano .env
   ```

4. Add or update these lines:
   ```env
   APP_URL=https://your-production-domain.com

   FACEBOOK_CLIENT_ID=your-production-app-id
   FACEBOOK_CLIENT_SECRET=your-production-app-secret
   FACEBOOK_REDIRECT_URL="${APP_URL}/auth/facebook/callback"
   ```
   
   > **Note:** Use the SAME Facebook App or create a separate one for production

5. Save and exit (Ctrl+X, then Y, then Enter)

6. **Clear caches:**
   ```bash
   php artisan config:clear
   php artisan cache:clear
   php artisan route:clear
   ```

7. **Restart services:**
   ```bash
   sudo systemctl restart php-fpm
   sudo systemctl restart nginx
   ```

---

## 🧪 **Step 9: Test Your Setup**

### **Test Locally:**

1. Visit: `http://localhost/userlogin`
2. Click the **"Facebook"** button
3. Should redirect to Facebook login
4. Log in with Facebook
5. Authorize the app
6. Should redirect back to your app and be logged in

### **Test on Production:**

1. Visit: `https://your-domain.com/userlogin`
2. Click the **"Facebook"** button
3. Log in with Facebook
4. Authorize the app
5. Should redirect back and be logged in

---

## 🔍 **Verify Configuration**

### **Check if credentials are loaded:**

```bash
php artisan tinker
```

Then run:
```php
config('services.facebook.client_id');
config('services.facebook.client_secret');
config('services.facebook.redirect');
```

Should output your credentials (not `null`).

---

## 🚨 **Common Issues & Solutions**

### **Issue 1: "URL Blocked: This redirect failed..."**

**Error Message:**
```
URL Blocked: This redirect failed because the redirect URI is not 
whitelisted in the app's Client OAuth Settings.
```

**Solution:**
1. Go to Facebook Developer Console
2. **Facebook Login** > **Settings**
3. Add the EXACT redirect URI to **"Valid OAuth Redirect URIs"**
4. Make sure there's no trailing slash
5. Make sure protocol matches (http vs https)

---

### **Issue 2: "App Not Set Up: This app is still in development mode"**

**Solution:**
1. Add yourself as a test user: **Roles** > **Test Users**
2. Or switch app to **Live Mode** (requires app review for permissions)

---

### **Issue 3: "Can't Load URL: The domain of this URL isn't included in the app's domains"**

**Solution:**
1. Go to **Settings** > **Basic**
2. Add your domain to **"App Domains"**:
   ```
   localhost
   your-production-domain.com
   ```
3. Save changes

---

### **Issue 4: "Missing required parameter: client_id"**

**Solution:**
1. Check `.env` file has `FACEBOOK_CLIENT_ID`
2. Run `php artisan config:clear`
3. Restart server
4. Verify with `php artisan tinker` that config loads

---

### **Issue 5: Email Not Returned by Facebook**

**Cause:** User hasn't shared email, or permission not requested

**Solution:**
1. In Facebook Developer Console: **App Review** > **Permissions and Features**
2. Request **"email"** permission (usually auto-approved)
3. In your code, request the email scope (Socialite handles this automatically)

---

## 🔐 **Security Best Practices**

1. **Never commit credentials to Git:**
   - `.env` is in `.gitignore` by default
   - Never add real credentials to `.env.example`

2. **Use different apps for dev/prod (optional but recommended):**
   - Create separate Facebook App for production
   - Easier to manage permissions and settings

3. **Keep App Secret secure:**
   - Never expose in frontend code
   - Never log in error messages
   - Rotate if compromised

4. **Enable HTTPS in production:**
   - Facebook requires HTTPS for production OAuth
   - Use Let's Encrypt (free SSL certificate)

5. **Regularly review app permissions:**
   - Only request permissions you need
   - `public_profile` and `email` are usually sufficient

6. **Set up Data Deletion callback:**
   - Required by Facebook policy
   - Provide way for users to delete their data

---

## 📊 **Facebook OAuth vs Google OAuth**

| Aspect | Facebook | Google |
|--------|----------|--------|
| **Setup Complexity** | Moderate | Moderate |
| **App Modes** | Development/Live | Single mode |
| **Default Permissions** | public_profile | email, profile |
| **Email Access** | Requires permission | Auto-included |
| **Production Approval** | May need app review | Usually instant |
| **User Data** | Name, email, picture | Name, email, avatar |
| **HTTPS Requirement** | Yes (production) | Yes (production) |

---

## 🎯 **What Facebook Returns**

When a user logs in with Facebook, you receive:

```php
[
    'id' => '1234567890',           // Facebook User ID
    'name' => 'John Doe',            // Full name
    'email' => 'john@example.com',   // Email (if permission granted)
    'avatar' => 'https://...',       // Profile picture URL
    'token' => 'long-access-token',  // Access token
]
```

---

## 🔄 **Testing with Test Users**

### **Create Test Users:**

1. **Roles** > **Test Users**
2. Click **"Add"** button
3. Enter number of users to create
4. Facebook generates fake accounts for testing

### **Login with Test User:**

1. Get test user credentials from dashboard
2. Use them to test Facebook login
3. Works even in Development Mode

---

## 📚 **Additional Resources**

- [Facebook Login Documentation](https://developers.facebook.com/docs/facebook-login)
- [Facebook Login Best Practices](https://developers.facebook.com/docs/facebook-login/best-practices)
- [Laravel Socialite Facebook Provider](https://laravel.com/docs/10.x/socialite#facebook)
- [Facebook App Review Process](https://developers.facebook.com/docs/app-review)

---

## ✅ **Configuration Checklist**

Before going live, verify:

- [ ] Facebook App created
- [ ] App ID and App Secret obtained
- [ ] App Domains added (localhost + production)
- [ ] Facebook Login product added
- [ ] Valid OAuth Redirect URIs configured
- [ ] `public_profile` and `email` permissions requested
- [ ] Credentials added to local `.env`
- [ ] Credentials added to production `.env`
- [ ] Laravel caches cleared
- [ ] Services restarted
- [ ] Tested locally with Facebook account
- [ ] Tested on production (if Live Mode)
- [ ] Data deletion callback configured
- [ ] App switched to Live Mode (for public use)

---

## 🎉 **You're Done!**

Your app now supports Facebook Login! Users can:
- ✅ Sign up with Facebook (creates new account)
- ✅ Login with Facebook (if account exists)
- ✅ Link Facebook to existing email account

---

**Last Updated:** 2025-11-15

**Status:** Ready for Implementation


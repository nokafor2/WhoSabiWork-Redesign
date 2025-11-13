# 🔑 Google OAuth Environment Setup

## Required Environment Variables

Add these variables to your `.env` file:

```env
# Google OAuth Configuration
GOOGLE_CLIENT_ID=your_google_client_id_here
GOOGLE_CLIENT_SECRET=your_google_client_secret_here
GOOGLE_REDIRECT_URL="${APP_URL}/auth/google/callback"

# Facebook OAuth Configuration (for future use)
FACEBOOK_CLIENT_ID=your_facebook_client_id_here
FACEBOOK_CLIENT_SECRET=your_facebook_client_secret_here
FACEBOOK_REDIRECT_URL="${APP_URL}/auth/facebook/callback"
```

## 📝 How to Get Google OAuth Credentials

### Step 1: Create Google Cloud Project
1. Go to [Google Cloud Console](https://console.cloud.google.com/)
2. Create a new project or select an existing one
3. Name it (e.g., "WhoSabiWork OAuth")

### Step 2: Enable Google+ API
1. In the Google Cloud Console, navigate to "APIs & Services" > "Library"
2. Search for "Google+ API"
3. Click on it and click "Enable"

### Step 3: Create OAuth Credentials
1. Go to "APIs & Services" > "Credentials"
2. Click "Create Credentials" > "OAuth client ID"
3. If prompted, configure the OAuth consent screen:
   - User Type: External (for public use)
   - App name: WhoSabiWork
   - User support email: your-email@example.com
   - Developer contact: your-email@example.com
   - Add authorized domains: your-domain.com
   - Scopes: email, profile, openid
   - Save and Continue

4. Create OAuth Client ID:
   - Application type: Web application
   - Name: WhoSabiWork Web Client
   - Authorized JavaScript origins:
     - http://localhost (for local development)
     - https://your-domain.com (for production)
   - Authorized redirect URIs:
     - http://localhost/auth/google/callback (for local development)
     - https://your-domain.com/auth/google/callback (for production)
   - Click "Create"

5. Copy the Client ID and Client Secret
6. Add them to your `.env` file

### Step 4: Configure .env File

Replace the placeholders with your actual credentials:

```env
APP_URL=http://localhost  # or your production URL

GOOGLE_CLIENT_ID=your_google_client_id
GOOGLE_CLIENT_SECRET=your_google_client_secret
GOOGLE_REDIRECT_URL="${APP_URL}/auth/google/callback"
```

### Step 5: Install Laravel Socialite

Run this command in your terminal:

```bash
composer require laravel/socialite
```

### Step 6: Clear Configuration Cache

After adding the credentials:

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
```

### Step 7: Migrate Database

Apply the database changes:

```bash
php artisan migrate:fresh --seed
```

**⚠️ WARNING:** This will wipe your database! Only run this in development.

For production, create a separate migration:
```bash
php artisan make:migration add_oauth_fields_to_users_table
```

## 🧪 Testing

### Local Development Testing

1. Make sure your APP_URL in `.env` is correct:
   ```env
   APP_URL=http://localhost
   ```

2. Start your development server:
   ```bash
   php artisan serve
   ```

3. Visit the login page: `http://localhost/userlogin`

4. Click the "Google" button

5. You should be redirected to Google's OAuth consent screen

6. After authorizing, you'll be redirected back to your app and automatically logged in

### Test Scenarios

✅ **New User via Google:**
- Click Google button
- Sign in with Google account
- Should create new user and log you in
- Check database for new user with `provider = 'google'`

✅ **Existing User via Google:**
- Already have an account with the same email
- Click Google button
- Sign in with Google account
- Should link OAuth to existing account
- Check database: `provider` and `provider_id` updated

✅ **Return User via Google:**
- Already logged in with Google before
- Click Google button
- Should log you in immediately

✅ **Regular Login Still Works:**
- Existing password login should still function normally
- Users can choose between Google or password login

## 🔒 Security Notes

1. **Never commit `.env` file** - It's in `.gitignore` by default
2. **Keep Client Secret secure** - Never expose it in frontend code
3. **Use HTTPS in production** - Google requires HTTPS for OAuth in production
4. **Limit OAuth scopes** - Only request email and profile permissions
5. **Validate redirect URIs** - Ensure they match exactly in Google Console

## 🚨 Common Issues & Solutions

### Issue: "redirect_uri_mismatch" error

**Solution:** Ensure the redirect URI in your Google Console matches exactly:
```
http://localhost/auth/google/callback
```
Note: No trailing slash, exact domain match

### Issue: "Client authentication failed"

**Solution:** 
- Check if GOOGLE_CLIENT_ID and GOOGLE_CLIENT_SECRET are correct in `.env`
- Run `php artisan config:clear`

### Issue: "The provided authorization grant is invalid"

**Solution:**
- Clear your browser cookies for the app
- Try again with a fresh browser session

### Issue: User created but not logged in

**Solution:**
- Check if `Auth::login($user, true);` is called in SocialAuthController
- Check session configuration in `config/session.php`

### Issue: "Socialite not found"

**Solution:**
```bash
composer require laravel/socialite
php artisan config:clear
```

## 📚 Additional Resources

- [Laravel Socialite Documentation](https://laravel.com/docs/10.x/socialite)
- [Google OAuth 2.0 Documentation](https://developers.google.com/identity/protocols/oauth2)
- [Google Cloud Console](https://console.cloud.google.com/)

---

**Implementation Date:** <?php echo date('Y-m-d'); ?>



Self update
- Ask cursor AI to go through ur databases or schema and ask it to merge all other schema's for the user's table for creation of new tables into one 
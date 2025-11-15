# 🔧 GitLab CI/CD OAuth Credentials Fix

## 🚨 Problem: OAuth Credentials Keep Disappearing After GitLab Push

### **What Was Happening:**

When you pushed code to GitLab, the CI/CD pipeline was:
1. ❌ Copying `.env.production` from Git repo to the server
2. ❌ The Git repo's `.env.production` didn't have OAuth credentials (for security)
3. ❌ Pipeline overwrote server's `.env.production` with the empty one from Git
4. ❌ Then copied that empty `.env.production` to `.env`
5. ❌ **Result:** Your Google OAuth credentials disappeared!

---

## ✅ Solution: Use GitLab CI/CD Variables

The **proper** way to handle sensitive credentials in CI/CD is to use GitLab's secret management, not store them in files.

---

## 📋 Step-by-Step Setup Guide

### **Step 1: Add OAuth Credentials to GitLab CI/CD Variables**

1. Go to your GitLab project: `https://gitlab.com/nokafor2-group/whosabiwork`
2. Navigate to: **Settings** > **CI/CD** > **Variables** (expand)
3. Click **"Add Variable"**

Add these 4 variables:

#### Variable 1: GOOGLE_CLIENT_ID
```
Key: GOOGLE_CLIENT_ID
Value: your-production-google-client-id-here
Type: Variable
Environment scope: All (default)
Protect variable: ✅ Checked
Mask variable: ✅ Checked
```

#### Variable 2: GOOGLE_CLIENT_SECRET
```
Key: GOOGLE_CLIENT_SECRET
Value: your-production-google-client-secret-here
Type: Variable
Environment scope: All (default)
Protect variable: ✅ Checked
Mask variable: ✅ Checked
```

#### Variable 3: GOOGLE_REDIRECT_URL
```
Key: GOOGLE_REDIRECT_URL
Value: https://your-production-domain.com/auth/google/callback
Type: Variable
Environment scope: All (default)
Protect variable: ✅ Checked
Mask variable: ❌ Unchecked (URLs can't be masked)
```

#### Variable 4: APP_URL (if not already set)
```
Key: APP_URL
Value: https://your-production-domain.com
Type: Variable
Environment scope: All (default)
Protect variable: ✅ Checked
Mask variable: ❌ Unchecked
```

---

### **Step 2: Verify Variables Are Set**

After adding all 4 variables, you should see them listed:

![GitLab Variables](https://docs.gitlab.com/ee/ci/variables/img/ci_variables_v13_6.png)

The values will be **masked** (hidden) for security.

---

### **Step 3: What the Updated Pipeline Does**

The `.gitlab-ci.yml` file has been updated to:

1. ✅ **Exclude `.env` files** from rsync (line 159)
   ```yaml
   - rsync -avz --exclude node_modules --exclude .env --exclude .env.backup --delete-after ./ $SSH_HOST:/var/www/html/WhoSabiWork-Redesign/
   ```

2. ✅ **Copy base `.env.production`** to `.env` (line 162)
   ```yaml
   - ssh $SSH_HOST "cp /var/www/html/WhoSabiWork-Redesign/.env.production /var/www/html/WhoSabiWork-Redesign/.env"
   ```

3. ✅ **Inject OAuth credentials** from GitLab CI/CD Variables (lines 165-169)
   ```yaml
   - ssh $SSH_HOST "cd /var/www/html/WhoSabiWork-Redesign && echo 'GOOGLE_CLIENT_ID=${GOOGLE_CLIENT_ID}' >> .env"
   - ssh $SSH_HOST "cd /var/www/html/WhoSabiWork-Redesign && echo 'GOOGLE_CLIENT_SECRET=${GOOGLE_CLIENT_SECRET}' >> .env"
   - ssh $SSH_HOST "cd /var/www/html/WhoSabiWork-Redesign && echo 'GOOGLE_REDIRECT_URL=${GOOGLE_REDIRECT_URL}' >> .env"
   ```

4. ✅ **Verify injection** was successful (line 172)
   ```yaml
   - ssh $SSH_HOST "cd /var/www/html/WhoSabiWork-Redesign && grep -q 'GOOGLE_CLIENT_ID' .env && echo '✅ Google OAuth credentials injected successfully'"
   ```

5. ✅ **Clear caches and restart services** (lines 175-180)
   ```yaml
   - php artisan config:clear
   - php artisan cache:clear
   - sudo systemctl restart php-fpm
   - sudo systemctl restart nginx
   ```

---

## 🧪 Testing the Fix

### **Step 1: Commit and Push**

```bash
git add .gitlab-ci.yml
git commit -m "Fix: Inject OAuth credentials from GitLab CI/CD variables"
git push gitlab main
```

### **Step 2: Watch the Pipeline**

1. Go to GitLab: **CI/CD** > **Pipelines**
2. Watch the `ssh_deploy` stage
3. Look for the verification message:
   ```
   ✅ Verifying OAuth configuration...
   ✅ Google OAuth credentials injected successfully
   ```

### **Step 3: Verify on Server**

SSH into your EC2 instance and check:

```bash
# Check if credentials are in .env
cd /var/www/html/WhoSabiWork-Redesign
grep GOOGLE_CLIENT_ID .env

# Should output (with your actual ID):
# GOOGLE_CLIENT_ID=your-client-id-here
```

### **Step 4: Test OAuth Flow**

1. Visit your production site: `https://your-domain.com/userlogin`
2. Click **"Sign in with Google"**
3. Should redirect to Google OAuth
4. After authorization, should redirect back and log you in

---

## 🔐 Security Benefits

### **Why This Approach is Better:**

1. ✅ **Credentials never in Git** - Can't be leaked in commit history
2. ✅ **Separate dev/prod credentials** - Use different values per environment
3. ✅ **Masked in logs** - GitLab masks protected variables in pipeline logs
4. ✅ **Access control** - Only maintainers can view/edit CI/CD variables
5. ✅ **Easy rotation** - Change credentials without code changes

### **What NOT to Do:**

❌ **Never commit `.env` files** to Git
❌ **Never put credentials** in `.env.production` in Git
❌ **Never hardcode secrets** in `.gitlab-ci.yml`
❌ **Never store production secrets** locally

---

## 📊 How It Works Now

### **Before (Broken):**

```
Git Repo (.env.production - no OAuth)
    ↓ rsync
Server (.env.production - overwrites, loses OAuth)
    ↓ copy
Server (.env - no OAuth) ❌
```

### **After (Fixed):**

```
Git Repo (.env.production - base config)
    ↓ rsync (excludes .env)
Server (.env.production - base config)
    ↓ copy
Server (.env - base config)
    ↓ inject from GitLab CI/CD Variables
Server (.env - with OAuth) ✅
```

---

## 🆘 Troubleshooting

### Issue: Pipeline shows "OAuth injection failed!"

**Cause:** GitLab CI/CD variables not set correctly

**Solution:**
1. Check **Settings** > **CI/CD** > **Variables**
2. Ensure all 3 OAuth variables are set
3. Ensure they're **not expired**
4. Ensure they're available for **All environments**

---

### Issue: Variables show as empty in .env

**Cause:** Variables not properly escaped or masked

**Solution:**
1. In GitLab CI/CD Variables, uncheck **"Mask variable"** temporarily
2. Run pipeline again to see actual value in logs (for debugging only)
3. Re-enable masking after debugging

---

### Issue: OAuth still not working after deployment

**Cause:** Config cache not cleared or services not restarted

**Solution:**
```bash
# SSH into EC2
cd /var/www/html/WhoSabiWork-Redesign

# Clear caches
php artisan config:clear
php artisan cache:clear

# Restart services
sudo systemctl restart php-fpm
sudo systemctl restart nginx

# Verify config loaded
php artisan tinker --execute="echo config('services.google.client_id');"
```

---

## 🚀 Advanced: Multiple Environments

You can use different credentials for staging and production:

### **Step 1: Add Environment-Specific Variables**

In GitLab CI/CD Variables, set **Environment scope**:

- `GOOGLE_CLIENT_ID` (scope: production)
- `GOOGLE_CLIENT_ID` (scope: staging)
- etc.

### **Step 2: Update .gitlab-ci.yml**

```yaml
deploy_production:
  stage: deploy
  environment:
    name: production
  only:
    - main
  # Uses production variables

deploy_staging:
  stage: deploy
  environment:
    name: staging
  only:
    - develop
  # Uses staging variables
```

---

## 📝 Quick Reference

### **GitLab CI/CD Variables Location:**
```
GitLab Project → Settings → CI/CD → Variables
```

### **Required Variables:**
```
GOOGLE_CLIENT_ID
GOOGLE_CLIENT_SECRET
GOOGLE_REDIRECT_URL
APP_URL (if not already set)
```

### **How to Add More Secrets:**

When you need to add other secrets (e.g., Facebook OAuth, AWS keys):

1. Add to GitLab CI/CD Variables
2. Inject in `.gitlab-ci.yml`:
   ```yaml
   - ssh $SSH_HOST "echo 'YOUR_SECRET=${YOUR_SECRET}' >> .env"
   ```
3. Never commit the actual values to Git!

---

## ✅ Checklist

After setting this up:

- [ ] Added all 4 variables to GitLab CI/CD
- [ ] Protected and masked sensitive variables
- [ ] Updated `.gitlab-ci.yml` (already done)
- [ ] Committed and pushed changes
- [ ] Pipeline ran successfully
- [ ] Saw "✅ Google OAuth credentials injected successfully"
- [ ] Verified credentials in server's `.env` file
- [ ] Tested OAuth login flow
- [ ] OAuth works after deployment ✅

---

## 🎉 Benefits

### **Now When You Push:**

1. ✅ OAuth credentials **persist** after deployment
2. ✅ No manual server edits required
3. ✅ Credentials stay **secure** (not in Git)
4. ✅ **Consistent** deployments every time
5. ✅ Easy to **update** credentials (just change in GitLab UI)

---

## 📚 Additional Resources

- [GitLab CI/CD Variables Documentation](https://docs.gitlab.com/ee/ci/variables/)
- [Protecting Sensitive Information](https://docs.gitlab.com/ee/ci/variables/#protected-cicd-variables)
- [Laravel Environment Configuration](https://laravel.com/docs/10.x/configuration#environment-configuration)

---

**Last Updated:** 2025-11-15

**Status:** ✅ Implemented and Ready to Deploy


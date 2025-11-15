# 🔧 OAuth Migration Fix - Foreign Key & Consolidation

## 🚨 **Issues Fixed:**

1. ✅ **Foreign Key Constraint Error** - Fixed type mismatch
2. ✅ **Separate Migration** - Consolidated into users table

---

## 📝 **What Was Changed:**

### **1. Users Table Migration (`2014_10_12_000000_create_users_table.php`)**

**Added:**
```php
$table->string('alternate_email')->nullable()->comment('Secondary email for OAuth providers with different emails');
$table->timestamp('alternate_email_verified_at')->nullable();
$table->index('alternate_email');
```

### **2. Social Accounts Migration (`2025_11_16_000002_create_social_accounts_table.php`)**

**Fixed:**
```php
// BEFORE (WRONG - causes foreign key error):
$table->foreignId('user_id')->constrained()->onDelete('cascade');

// AFTER (CORRECT - matches users.id type):
$table->unsignedInteger('user_id');
$table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
```

**Reason:** The `users` table uses `increments('id')` which creates `UNSIGNED INTEGER`, not `BIGINT UNSIGNED`.

### **3. Deleted Separate Migration**

**Removed:** `2025_11_16_000001_add_alternate_email_to_users_table.php`  
**Reason:** Fields now included in main users table migration.

---

## 🔄 **How to Apply the Fix:**

### **Option 1: Fresh Migration (If Database is Disposable)**

If you're okay with losing current data:

```bash
# Drop all tables and migrate fresh
php artisan migrate:fresh --seed
```

This will:
- Drop all tables
- Run all migrations from scratch
- Seed the database

---

### **Option 2: Rollback and Re-migrate (If Database Has Data)**

If you want to keep existing data:

#### **Step 2.1: Check Migration Status**

```bash
php artisan migrate:status
```

Look for the status of `create_social_accounts_table`.

#### **Step 2.2: Drop Failed Table Manually (If Exists)**

```bash
php artisan tinker
```

Then run:
```php
Schema::dropIfExists('social_accounts');
exit
```

Or using MySQL directly:
```sql
USE whosabiwork;
DROP TABLE IF EXISTS social_accounts;
```

#### **Step 2.3: Remove Migration Record**

```bash
php artisan tinker
```

Then run:
```php
DB::table('migrations')->where('migration', 'like', '%create_social_accounts_table%')->delete();
DB::table('migrations')->where('migration', 'like', '%add_alternate_email_to_users%')->delete();
exit
```

#### **Step 2.4: Add Alternate Email to Existing Users Table**

If your users table is already migrated, add the alternate email fields:

```bash
php artisan tinker
```

Then run:
```php
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

Schema::table('users', function (Blueprint $table) {
    // Check if columns don't exist first
    if (!Schema::hasColumn('users', 'alternate_email')) {
        $table->string('alternate_email')->nullable()->after('email_verified_at')->comment('Secondary email for OAuth providers');
        $table->timestamp('alternate_email_verified_at')->nullable()->after('alternate_email');
        $table->index('alternate_email');
    }
});

echo "Alternate email fields added to users table!\n";
exit
```

#### **Step 2.5: Run Social Accounts Migration**

```bash
php artisan migrate
```

---

## 🧪 **Verify the Fix:**

### **Check Tables Were Created:**

```bash
php artisan tinker
```

Then run:
```php
// Check users table has alternate_email
Schema::hasColumn('users', 'alternate_email'); // Should return true
Schema::hasColumn('users', 'alternate_email_verified_at'); // Should return true

// Check social_accounts table exists
Schema::hasTable('social_accounts'); // Should return true

// Check columns in social_accounts
Schema::getColumnListing('social_accounts');
// Should show: id, user_id, provider, provider_id, provider_token, provider_email, etc.

exit
```

---

## 📊 **Expected Database Structure:**

### **users table:**
```
+----------------------------+
| Field                      |
+----------------------------+
| id (UNSIGNED INT)          |
| first_name                 |
| last_name                  |
| username                   |
| gender                     |
| email                      |
| email_verified_at          |
| alternate_email            | ← NEW
| alternate_email_verified_at| ← NEW
| password                   |
| phone_number               |
| phone_verified_at          |
| account_type               |
| account_status             |
| is_admin                   |
| provider                   |
| provider_id                |
| provider_token             |
| remember_token             |
| created_at                 |
| updated_at                 |
| deleted_at                 |
+----------------------------+
Indexes:
- alternate_email
- (provider, provider_id)
- account_status
- account_type
```

### **social_accounts table:**
```
+---------------------------+
| Field                     |
+---------------------------+
| id (BIGINT UNSIGNED)      |
| user_id (UNSIGNED INT)    | ← Matches users.id type
| provider                  |
| provider_id               |
| provider_token            |
| provider_refresh_token    |
| provider_email            |
| provider_email_verified   |
| avatar                    |
| created_at                |
| updated_at                |
+---------------------------+
Foreign Key: user_id → users.id
Unique: (provider, provider_id)
Indexes:
- (user_id, provider)
- provider_email
```

---

## 🚨 **Common Issues:**

### **Issue: "Table 'social_accounts' already exists"**

**Solution:**
```bash
# Drop the table
php artisan tinker
Schema::dropIfExists('social_accounts');
exit

# Remove from migrations table
php artisan tinker
DB::table('migrations')->where('migration', 'like', '%create_social_accounts%')->delete();
exit

# Run migration again
php artisan migrate
```

---

### **Issue: "Column 'alternate_email' already exists"**

**Solution:**
Either:
- Your users table already has this field (good!)
- Or you need to drop the duplicate

Check:
```bash
php artisan tinker
Schema::hasColumn('users', 'alternate_email');
```

If it exists, you're good!

---

### **Issue: "SQLSTATE[23000]: Integrity constraint violation"**

**Solution:**
This means there's conflicting data. Either:
- Drop and recreate tables (if testing)
- Or carefully migrate data

---

## ✅ **Success Indicators:**

After successful migration, you should see:

```bash
php artisan migrate:status

# Should show:
✓ 2014_10_12_000000_create_users_table (with alternate_email)
✓ 2025_11_16_000002_create_social_accounts_table
```

---

## 🧪 **Test the Setup:**

```bash
php artisan tinker
```

Test creating a social account:
```php
$user = User::first(); // Or create a test user

$socialAccount = new App\Models\SocialAccount([
    'user_id' => $user->id,
    'provider' => 'google',
    'provider_id' => 'test123',
    'provider_email' => 'test@gmail.com',
    'provider_email_verified' => true,
]);

$socialAccount->save();

echo "Social account created successfully!\n";

// Verify relationship
$user->socialAccounts()->count(); // Should return 1

// Clean up
$socialAccount->delete();
```

---

## 📚 **What to Do Next:**

1. ✅ **Apply the fix** using Option 1 or Option 2 above
2. ✅ **Verify tables** are created correctly
3. ✅ **Test OAuth login** with Google and Facebook
4. ✅ **Test linking** multiple providers
5. ✅ **Deploy to production** when ready

---

## 🎯 **Summary:**

### **Root Cause:**
- Foreign key type mismatch: `increments()` vs `foreignId()`
- Users table uses `UNSIGNED INTEGER`
- Social accounts was using `BIGINT UNSIGNED`

### **Solution:**
- Changed to `unsignedInteger()` in social_accounts migration
- Consolidated alternate_email into users table migration
- Deleted separate migration

### **Result:**
- ✅ Foreign key constraint works
- ✅ All fields in one users table migration
- ✅ Clean migration structure

---

**Last Updated:** 2025-11-16

**Status:** ✅ Fixed and Ready to Migrate


# WhoSabiWork - Quick Reference Guide

**Last Updated:** November 15, 2025

---

## 🚀 Quick Start Commands

### Development Server
```bash
# Start Laravel backend
php artisan serve

# Start Vite frontend (in another terminal)
npm run dev
```

### Database
```bash
# Fresh database with seeding
php artisan migrate:fresh --seed

# Run migrations only
php artisan migrate

# Rollback migrations
php artisan migrate:rollback
```

### Cache Management
```bash
# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize for production
php artisan optimize
```

### Build Assets
```bash
# Development build (watch mode)
npm run dev

# Production build
npm run build
```

---

## 📁 Important File Locations

### Backend (Laravel)

#### Controllers
```
app/Http/Controllers/
├── HomeController.php                # Photo feed
├── UserController.php                # User profiles
├── ArtisanController.php            # Artisan listings
├── MobileMarketController.php       # Mobile market
├── SearchController.php             # Search functionality
├── SocialAuthController.php         # OAuth (Google/Facebook)
├── AuthController.php               # Web/API login
├── PhotographLikeController.php     # Like functionality
├── PhotographDislikeController.php  # Dislike functionality
├── PhotographCommentController.php  # Comments
└── PhotographReplyController.php    # Replies
```

#### Models
```
app/Models/
├── User.php                         # User model
├── Photograph.php                   # Photos
├── PhotographComment.php            # Comments
├── PhotographReply.php              # Replies
├── PhotographLike.php               # Likes
├── PhotographDislike.php            # Dislikes
├── SocialAccount.php                # Linked social accounts
└── BusinessCategory.php             # Business types
```

#### Traits
```
app/Http/Traits/
└── GlobalFunctions.php              # Reusable functions
    ├── photoFeedData()              # Get photo feed
    ├── getSpecifiedUserDetails()    # Get user details
    ├── getTechOrSpareUserDetails()  # Get tech/spare details
    └── getUserDetails()             # Get user by ID
```

#### Middleware
```
app/Http/Middleware/
└── HandleInertiaRequests.php        # Share data with Inertia
```

---

### Frontend (Vue.js)

#### Pages
```
resources/js/Pages/
├── Index/                           # Homepage
│   ├── Index.vue
│   └── Components/
│       ├── HeroSection.vue
│       ├── AboutSection.vue
│       ├── PackagesSection.vue
│       └── CTASection.vue
├── PhotoFeed/
│   └── Index.vue                    # Photo feed page
├── User/
│   ├── UserProfile.vue              # User profile
│   └── Components/
│       ├── ImageCard.vue            # Photo card
│       ├── PhotoCommentAndReplyCard.vue
│       ├── PhotoReplyCard.vue
│       ├── LikeAndDislikeCard.vue
│       ├── CommentForm.vue
│       └── ConnectedAccountsSection.vue
├── Artisan/
│   └── Index.vue                    # Artisan listings
├── MobileMarket/
│   └── Index.vue                    # Mobile market
├── SignIn/
│   └── Index.vue                    # Login page
└── SignUp/
    └── Create.vue                   # Registration
```

#### Components
```
resources/js/components/
├── UI/
│   ├── AdCard.vue                   # Advertisement card
│   ├── FeedbackModal.vue            # Reusable modal
│   ├── BusinessDetails.vue          # Business info card
│   └── SelectEntrepreneur.vue       # Filter dropdown
└── Layout/
    ├── MainLayout.vue               # Main layout wrapper
    ├── Navigation.vue               # Header navigation
    └── FooterLayout.vue             # Footer
```

#### Vuex Store
```
resources/js/Store/
└── index.js                         # Vuex store
    ├── State
    ├── Mutations
    ├── Actions
    └── Getters
```

---

### Database

#### Migrations
```
database/migrations/
├── 2014_10_12_000000_create_users_table.php
├── create_photographs_table.php
├── create_photographs_comments_table.php
├── create_photographs_replies_table.php
├── create_photograph_likes_table.php
├── create_photograph_dislikes_table.php
└── create_social_accounts_table.php
```

#### Seeders
```
database/seeders/
├── DatabaseSeeder.php               # Main seeder
├── UsersSeeder.php                  # Users
├── PhotographsSeeder.php            # Photos
├── PhotographCommentsSeeder.php     # Comments
├── PhotographRepliesSeeder.php      # Replies
└── ArtisansSeeder.php               # Artisans
```

---

### Configuration

#### Environment
```
.env                                 # Local environment
.env.production                      # Production environment
```

#### Config Files
```
config/
├── services.php                     # OAuth credentials
├── session.php                      # Session configuration
├── auth.php                         # Authentication
└── inertia.php                      # Inertia.js config
```

---

## 🔐 OAuth Setup

### Google OAuth
```env
GOOGLE_CLIENT_ID=your_client_id_here
GOOGLE_CLIENT_SECRET=your_client_secret_here
GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
```

### Facebook OAuth
```env
FACEBOOK_CLIENT_ID=your_app_id_here
FACEBOOK_CLIENT_SECRET=your_app_secret_here
FACEBOOK_REDIRECT_URL=http://localhost:8000/auth/facebook/callback
```

**Full Guide:** `MULTI_PROVIDER_OAUTH_IMPLEMENTATION.md`

---

## 🎨 Key Vue Components

### AdCard.vue (Photo Feed)
```vue
<!-- Props -->
:photoData="photo"

<!-- Data -->
- photoLikes, photoDislikes, commentCount
- showCommentsModal
- likeAndDislikeData
- commentData

<!-- Events -->
@updateLikeAndDislike="handleLikeAndDislikeUpdate"
@updateComment="handleCommentUpdate"
@reply-added="handleReplyAdded"
```

### LikeAndDislikeCard.vue
```vue
<!-- Props -->
:likeAndDislikeData="{
  userId, photographId, likes, dislikes,
  userLiked, userDisliked
}"

<!-- Emits -->
@updateLikeAndDislike="{ likes, dislikes, userLiked, userDisliked }"
```

### CommentForm.vue
```vue
<!-- Props -->
:commentData="{
  photographId, photographUserId, commentCount
}"

<!-- Emits -->
@updateComment="{ commentData, commentCount }"
```

---

## 🗄️ Database Tables

### Core Tables

#### users
```sql
- id, full_name, email, password
- phone, username, dob, sex
- account_type, account_status
- provider, provider_id, provider_token
- alternate_email, alternate_email_verified_at
- created_at, updated_at
```

#### photographs
```sql
- id, user_id, path, photo_type, caption
- created_at, updated_at
```

#### photographs_comments
```sql
- id, photograph_id, user_id, comment
- created_at, updated_at
```

#### photographs_replies
```sql
- id, photograph_comment_id, photograph_id
- user_id, user_id_comment, reply
- created_at, updated_at
```

#### photograph_likes
```sql
- id, photograph_id, photograph_user_id
- user_id, like
- created_at, updated_at
```

#### photograph_dislikes
```sql
- id, photograph_id, photograph_user_id
- user_id, dislike
- created_at, updated_at
```

#### social_accounts
```sql
- id, user_id, provider, provider_id
- provider_token, provider_refresh_token
- provider_email, provider_email_verified
- avatar, created_at, updated_at
```

---

## 🛠️ Common Tasks

### Add a New Feature
1. Create backend controller/route
2. Create/update Vue component
3. Add Vuex action/mutation (if needed)
4. Test functionality
5. Document in `Z-CursorAI-ReadMe/`

### Fix a Bug
1. Identify the issue (frontend/backend)
2. Check browser console/Laravel logs
3. Fix the code
4. Test the fix
5. Commit with descriptive message

### Deploy to Production
```bash
# Automated via GitLab CI/CD
git push origin main

# Manual deployment
./Z-CursorAI-ReadMe/deploy.sh
```

---

## 🐛 Debugging

### Frontend (Vue.js)
```javascript
// Console logging
console.log('Variable:', this.variableName);

// Vuex state
console.log('Store:', this.$store.state);

// Inertia page props
console.log('Props:', this.$page.props);
```

### Backend (Laravel)
```php
// Log to storage/logs/laravel.log
\Log::info('Debug message', ['data' => $variable]);

// Dump and die
dd($variable);

// Dump without dying
dump($variable);
```

### Database Queries
```bash
# Enable query logging in .env
DB_LOG_QUERIES=true

# Or in code
\DB::enableQueryLog();
// ... queries ...
dd(\DB::getQueryLog());
```

---

## 🔗 Important Routes

### Web Routes
```php
// Authentication
GET  /login                          # Login page
POST /login                          # Login submit
POST /logout                         # Logout

// OAuth
GET  /auth/{provider}                # OAuth redirect
GET  /auth/{provider}/callback       # OAuth callback
DELETE /auth/{provider}/unlink       # Unlink account

// Pages
GET  /                               # Homepage
GET  /photo-feed                     # Photo feed
GET  /users/{id}                     # User profile
GET  /artisans                       # Artisan listings
GET  /mobile-market                  # Mobile market
GET  /search                         # Search

// Photo Actions
POST /photograph-like                # Like photo
POST /photograph-dislike             # Dislike photo
POST /photograph-comment             # Comment on photo
POST /photograph-reply               # Reply to comment
```

---

## 📊 Performance Tips

### Backend Caching
```php
use Illuminate\Support\Facades\Cache;

$data = Cache::remember('key', 600, function () {
    return ExpensiveQuery::all();
});
```

### Database Indexing
```php
// In migration
$table->index('user_id');
$table->index(['photograph_id', 'user_id']);
```

### Eager Loading
```php
// Avoid N+1 queries
$photos = Photograph::with(['user', 'comments.replies'])->get();
```

### Vue Performance
```vue
<!-- Use v-show for frequent toggles -->
<div v-show="isVisible">Content</div>

<!-- Use v-if for conditional rendering -->
<div v-if="shouldRender">Content</div>

<!-- Key for list rendering -->
<div v-for="item in items" :key="item.id">
```

---

## 📝 Git Commands

### Common Workflow
```bash
# Check status
git status

# Stage changes
git add .

# Commit
git commit -m "feat: Add feature description"

# Push to main
git push origin main

# Pull latest
git pull origin main
```

### Branch Management
```bash
# Create branch
git checkout -b feature/new-feature

# Switch branch
git checkout main

# Merge branch
git merge feature/new-feature

# Delete branch
git branch -d feature/new-feature
```

---

## 🔍 Searching the Codebase

### Find Text in Files
```bash
# Windows (PowerShell)
Get-ChildItem -Recurse -Filter "*.php" | Select-String "searchText"

# Or use IDE search (Ctrl+Shift+F in most IDEs)
```

### Find Files
```bash
# Find by name
Get-ChildItem -Recurse -Filter "*filename*"

# Find by extension
Get-ChildItem -Recurse -Filter "*.vue"
```

---

## 📚 Documentation

**All detailed documentation is in `Z-CursorAI-ReadMe/`**

### Key Documents
- `INDEX.md` - Documentation index
- `MULTI_PROVIDER_OAUTH_IMPLEMENTATION.md` - OAuth guide
- `CAPTION_TRUNCATION_IMPLEMENTATION.md` - UI features
- `AWS_EC2_OAUTH_FIX.md` - Production fixes

---

## ⚡ Keyboard Shortcuts (VS Code)

```
Ctrl+P          # Quick file open
Ctrl+Shift+F    # Search in files
Ctrl+`          # Toggle terminal
Ctrl+B          # Toggle sidebar
Ctrl+Shift+P    # Command palette
F12             # Go to definition
Alt+Left        # Go back
Alt+Right       # Go forward
```

---

## 💡 Pro Tips

1. 🔍 **Use Artisan Tinker** for quick database queries
2. 🎯 **Check Laravel Logs** when debugging backend issues
3. 🧪 **Test in Incognito** to avoid cache issues
4. 📝 **Document as you code** in `Z-CursorAI-ReadMe/`
5. 🚀 **Use Vuex DevTools** for state debugging
6. ⚡ **Cache expensive queries** for better performance
7. 🔐 **Never commit `.env`** files
8. 📊 **Use eager loading** to prevent N+1 queries

---

## 🆘 Getting Help

### Error Messages
1. Check browser console (F12)
2. Check Laravel logs (`storage/logs/laravel.log`)
3. Search error on Google/Stack Overflow
4. Check documentation in `Z-CursorAI-ReadMe/`

### Performance Issues
1. Enable query logging
2. Check for N+1 queries
3. Add database indexes
4. Implement caching
5. Use Vuex for state management

---

**Last Updated:** November 15, 2025  
**Maintained by:** WhoSabiWork Development Team

---

*Quick, organized, efficient! 🚀*


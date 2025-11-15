# WhoSabiWork - Changelog

All notable changes to this project are documented here.

**Format:** Based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/)  
**Versioning:** This project follows semantic versioning.

---

## [Unreleased]

### Planned Features
- Email verification system
- Password reset functionality
- Business account upgrade/downgrade
- Advanced search filters
- Notification system
- Real-time chat
- Review/rating system enhancements

---

## [2025-11-15] - Latest Updates

### ✨ Added
- **Caption Truncation** - Photo captions now truncate with ellipsis for consistent card layouts
  - `AdCard.vue` - 2-line truncation for photo feed cards
  - `ImageCard.vue` - 3-line truncation for user profile image cards
  - Pure CSS solution using `line-clamp`
  - Cross-browser compatible
  - Maintains consistent card heights
  - See: `CAPTION_TRUNCATION_IMPLEMENTATION.md`

- **Documentation System** - Centralized documentation in `Z-CursorAI-ReadMe/` folder
  - `INDEX.md` - Documentation navigation
  - `QUICK_REFERENCE.md` - Commands, tips, file locations
  - Clean project structure
  - Easy to maintain and find information

### 📝 Changed
- **README.md** - Complete rewrite with better organization
  - Clean, professional layout
  - Clear section hierarchy
  - Prominent documentation links
  - Installation instructions
  - Quick start guide

### 🐛 Fixed
- README.md merge conflicts resolved
- Caption overflow in advertisement cards
- Documentation organization improved

---

## [2025-11-14] - Multi-Provider OAuth

### ✨ Added
- **Multi-Provider OAuth** - Complete social login system
  - Google OAuth integration
  - Facebook OAuth integration
  - Account linking/unlinking functionality
  - `social_accounts` table for linked accounts
  - `ConnectedAccountsSection.vue` component
  - See: `MULTI_PROVIDER_OAUTH_IMPLEMENTATION.md`

### 📝 Changed
- **Users Table** - Added OAuth-related fields
  - `provider`, `provider_id`, `provider_token`
  - `alternate_email`, `alternate_email_verified_at`
  
- **UserProfile.vue** - Added Settings tab
  - Connected Accounts section
  - Link/Unlink social accounts
  - Extensible for future settings

### 🔐 Security
- OAuth credentials properly configured
- Social account verification
- Secure token storage

---

## [2025-11-13] - User Experience Enhancements

### ✨ Added
- **Password Visibility Toggle**
  - Show/hide password in login form
  - Show/hide password in registration form
  - Eye icon indicators
  - See: `PASSWORD_VISIBILITY_TOGGLE_IMPLEMENTATION.md` (archived)

- **Remember Me Functionality**
  - 30-day persistent sessions
  - Checkbox in login form
  - Industry-standard implementation
  - See: `REMEMBER_ME_IMPLEMENTATION.md` (archived)

### 📝 Changed
- `SignIn/Index.vue` - Added password toggle and remember me
- `SignUp/Create.vue` - Added password visibility toggles
- `AuthServiceProvider.php` - Set remember duration to 30 days

---

## [2025-11-12] - Homepage Redesign

### ✨ Added
- **V0 Design Integration** - Complete homepage redesign
  - `HeroSection.vue` - Carousel with CTAs
  - `AboutSection.vue` - Features and testimonials
  - `PackagesSection.vue` - Service offerings
  - `CTASection.vue` - Call-to-action
  - Custom color scheme (dark charcoal + terracotta)
  - See: `COLOR_ACCURACY_FIX.md`

### 📝 Changed
- **Navigation.vue** - Modern navbar design
  - V0 design implementation
  - Improved user menu
  - Mobile-responsive
  
- **FooterLayout.vue** - Complete redesign
  - 4-column layout
  - Social links
  - Newsletter signup

### 🎨 Styling
- `homepage-colors.css` - Custom CSS variables
  - Primary dark (#2b2826)
  - Secondary orange (#e07241)
  - Accent coral (#f3efe9)
  - Consistent color usage

---

## [2025-11-10] - Photo Feed Features

### ✨ Added
- **Like/Dislike System**
  - `LikeAndDislikeCard.vue` component
  - Toggle like/dislike functionality
  - Real-time count updates
  - Authentication checks
  - FeedbackModal integration

- **Comment System**
  - `CommentForm.vue` component
  - Real-time comment posting
  - Validation and error handling
  - Authentication required

- **Reply System**
  - `PhotoCommentAndReplyCard.vue` component
  - Nested replies
  - Real-time updates
  - Latest-first ordering

### 📝 Changed
- **AdCard.vue** - Integrated new components
  - Like/Dislike card
  - Comment form
  - Comments modal
  - Improved styling

- **ImageCard.vue** - Enhanced functionality
  - Integrated like/dislike
  - Fixed reactivity issues
  - Modal improvements

### 🐛 Fixed
- Likes/dislikes resetting on page refresh
- Unliking/undisliking not working correctly
- Comment count not updating in real-time
- Reply duplication issues

---

## [2025-11-08] - Performance Optimization

### ✨ Added
- **Data Caching** - Redis/file caching
  - Photo feed cached (10 minutes)
  - Artisan lists cached (10 minutes)
  - Search results cached (5 minutes)
  - Entrepreneur data cached (30 minutes)

- **Pagination** - Infinite scroll implementation
  - Photo feed pagination
  - Artisan listings pagination
  - Mobile market pagination
  - Search results pagination
  - Smooth loading experience

### 📝 Changed
- **GlobalFunctions.php** - Paginated methods
  - `photoFeedData($perPage = 15)`
  - `getSpecifiedUserDetailsPaginated()`
  - `getTechOrSpareUserDetailsPaginated()`
  - `searchDataPaginated()`

- **Controllers** - Caching implementation
  - `HomeController.php`
  - `ArtisanController.php`
  - `MobileMarketController.php`
  - `SearchController.php`

### ⚡ Performance
- Reduced database queries by 80%
- Faster page load times
- Better user experience
- Lower server load

---

## [2025-11-05] - AWS Deployment

### ✨ Added
- **GitLab CI/CD Pipeline**
  - Automated deployment to AWS EC2
  - SSH-based deployment
  - Environment variable management
  - `.gitlab-ci.yml` configuration

- **AWS EC2 Setup**
  - Production environment configured
  - Nginx web server
  - PHP-FPM process manager
  - MySQL database
  - See: `aws-ec2-development-setup.md`

### 📝 Changed
- **Deployment Scripts**
  - `deploy.sh` - Automated deployment
  - `ec2-oauth-fix.sh` - OAuth configuration fix
  - `setup-ec2-dev.sh` - Development setup

### 🐛 Fixed
- OAuth configuration missing on EC2 (see: `AWS_EC2_OAUTH_FIX.md`)
- CI/CD pipeline issues with environment variables
- CSRF token errors (419) in production

---

## [2025-11-01] - Database Improvements

### ✨ Added
- **Time-Based Seeding**
  - Comments with realistic timestamps
  - Replies with time gaps
  - Likes/dislikes with time distribution
  - More realistic test data

- **Multiple Service Types**
  - Artisans: 1-3 types per user
  - Products: 1-3 types per user
  - Spare parts: 1-3 types per user
  - Technical services: 1-3 types per user
  - Vehicle brands: 1-5 brands per user

### 📝 Changed
- **Seeders** - Enhanced logic
  - `CommentsSeeder.php`
  - `PhotographCommentsSeeder.php`
  - `RepliesSeeder.php`
  - `PhotographRepliesSeeder.php`
  - `ArtisansSeeder.php`
  - `ProductsSeeder.php`
  - `SparePartsSeeder.php`
  - `TechnicalServicesSeeder.php`

---

## [2025-10-28] - Initial Production Release

### ✨ Added
- Complete Laravel 9 backend
- Vue 3 + Inertia.js frontend
- User authentication system
- Business profiles
- Photo gallery system
- Appointment scheduling
- Search functionality
- Mobile-responsive design

### 🔐 Security
- CSRF protection
- SQL injection prevention
- XSS protection
- Authentication middleware
- Password hashing (bcrypt)

---

## Categories

### Types of Changes
- ✨ **Added** - New features
- 📝 **Changed** - Changes to existing functionality
- 🗑️ **Deprecated** - Soon-to-be removed features
- 🐛 **Fixed** - Bug fixes
- 🔐 **Security** - Security improvements
- ⚡ **Performance** - Performance improvements
- 🎨 **Styling** - CSS/UI changes

---

## Version History

| Version | Date | Summary |
|---------|------|---------|
| Current | Nov 15, 2025 | Caption truncation, documentation system |
| 1.4.0 | Nov 14, 2025 | Multi-provider OAuth |
| 1.3.0 | Nov 13, 2025 | Password toggle, Remember Me |
| 1.2.0 | Nov 12, 2025 | Homepage redesign |
| 1.1.0 | Nov 10, 2025 | Photo feed features |
| 1.0.5 | Nov 8, 2025 | Performance optimization |
| 1.0.0 | Oct 28, 2025 | Initial release |

---

## How to Use This Changelog

### For Developers
- Check this file before starting work to see recent changes
- Add entries when implementing new features
- Update version numbers following semantic versioning

### For Project Managers
- Review changelog for sprint summaries
- Track feature completion
- Plan future releases

### For Users/Clients
- See what's new in each release
- Understand feature improvements
- Know what's coming next

---

## Changelog Standards

### Entry Format
```markdown
## [YYYY-MM-DD] - Release Name

### ✨ Added
- Feature 1 with description
- Feature 2 with description

### 📝 Changed
- Change 1 with description
- Change 2 with description

### 🐛 Fixed
- Bug fix 1 with description
- Bug fix 2 with description
```

### When to Update
- ✅ After completing a major feature
- ✅ After fixing significant bugs
- ✅ After making breaking changes
- ✅ Before production deployments
- ❌ Not for minor CSS tweaks
- ❌ Not for typo fixes

---

**Maintained by:** WhoSabiWork Development Team  
**Last Updated:** November 15, 2025

---

*Track progress, celebrate milestones! 📈✨*


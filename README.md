# WhoSabiWork

A Laravel + Vue.js platform connecting entrepreneurs, artisans, and customers for service discovery and appointments.

---

## 📋 About

WhoSabiWork is a comprehensive web application that helps users find and connect with local service providers, artisans, and businesses. Built with Laravel backend and Vue.js frontend, the platform features:

- **User Authentication** - Password, Google OAuth, Facebook OAuth
- **Service Discovery** - Find artisans, mobile marketers, technicians, and spare part sellers
- **Photo Feed** - Social media-style feed with likes, comments, and replies
- **Appointments** - Schedule and manage appointments with service providers
- **Real-time Interaction** - Comments, replies, likes/dislikes with instant updates
- **Business Profiles** - Detailed profiles with galleries, ratings, and availability

---

## 🚀 Tech Stack

### Backend
- **Laravel 9.x** - PHP Framework
- **MySQL** - Database
- **Laravel Sanctum** - API Authentication
- **Laravel Socialite** - OAuth Integration

### Frontend
- **Vue 3** - JavaScript Framework
- **Inertia.js** - SPA without API
- **Vuex** - State Management
- **Bootstrap 5** - CSS Framework
- **FontAwesome** - Icons

### DevOps
- **GitLab CI/CD** - Continuous Integration/Deployment
- **AWS EC2** - Production Hosting
- **Nginx/Apache** - Web Server
- **PHP-FPM** - Process Manager

---

## 📚 Documentation

**⚠️ ALL TECHNICAL DOCUMENTATION IS IN THE `Z-CursorAI-ReadMe/` FOLDER**

```
📁 Z-CursorAI-ReadMe/
├── 📄 INDEX.md                          # Documentation index
├── 📄 QUICK_REFERENCE.md                # Quick reference guide
├── 📄 CHANGELOG.md                      # Project history
├── 📄 MULTI_PROVIDER_OAUTH_IMPLEMENTATION.md
├── 📄 CAPTION_TRUNCATION_IMPLEMENTATION.md
├── 📄 AWS_EC2_OAUTH_FIX.md
├── 📄 COLOR_ACCURACY_FIX.md
├── 📄 aws-ec2-development-setup.md
└── 📜 Shell Scripts (deploy.sh, setup scripts, etc.)
```

### Quick Links:
- **[📖 Documentation Index](Z-CursorAI-ReadMe/INDEX.md)** - Start here
- **[⚡ Quick Reference](Z-CursorAI-ReadMe/QUICK_REFERENCE.md)** - Commands & tips
- **[📜 Changelog](Z-CursorAI-ReadMe/CHANGELOG.md)** - What's new
- **[🔐 OAuth Guide](Z-CursorAI-ReadMe/MULTI_PROVIDER_OAUTH_IMPLEMENTATION.md)** - Google/Facebook setup

---

## ⚙️ Installation

### Prerequisites
- PHP >= 8.0
- Composer
- Node.js >= 16.x
- MySQL >= 5.7

### Setup Steps

1. **Clone the repository**
```bash
git clone https://gitlab.com/nokafor2-group/whosabiwork.git
cd whosabiwork
```

2. **Install PHP dependencies**
```bash
composer install
```

3. **Install JavaScript dependencies**
```bash
npm install
```

4. **Configure environment**
```bash
cp .env.example .env
php artisan key:generate
```

5. **Configure database in `.env`**
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=whosabiwork
DB_USERNAME=root
DB_PASSWORD=
```

6. **Run migrations and seeders**
```bash
php artisan migrate:fresh --seed
```

7. **Build frontend assets**
```bash
npm run dev
# or for production
npm run build
```

8. **Start development server**
```bash
php artisan serve
```

Visit: `http://localhost:8000`

---

## 🔐 OAuth Configuration

### Google OAuth
```bash
GOOGLE_CLIENT_ID=your_client_id
GOOGLE_CLIENT_SECRET=your_client_secret
GOOGLE_REDIRECT_URL=http://localhost:8000/auth/google/callback
```

### Facebook OAuth
```bash
FACEBOOK_CLIENT_ID=your_app_id
FACEBOOK_CLIENT_SECRET=your_app_secret
FACEBOOK_REDIRECT_URL=http://localhost:8000/auth/facebook/callback
```

See `Z-CursorAI-ReadMe/` folder for detailed OAuth setup guides.

---

## 📁 Project Structure

```
whosabiwork/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   ├── Middleware/
│   │   └── Traits/
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── js/
│   │   ├── Pages/         # Vue pages
│   │   ├── components/    # Vue components
│   │   └── Store/         # Vuex store
│   ├── css/
│   └── views/
├── routes/
│   ├── web.php
│   └── api.php
├── public/
└── Z-CursorAI-ReadMe/     # Technical documentation
```

---

## 🌟 Key Features

### Authentication
- ✅ Email/Password Login
- ✅ Google OAuth
- ✅ Facebook OAuth
- ✅ Remember Me (30 days)
- ✅ Multi-Provider Account Linking

### User Profiles
- ✅ Avatar & Cover Photos
- ✅ Business Details
- ✅ Gallery Management
- ✅ Ratings & Reviews
- ✅ Availability Scheduling

### Social Features
- ✅ Photo Feed with Pagination
- ✅ Like/Dislike System
- ✅ Comments & Replies
- ✅ Real-time Updates
- ✅ Infinite Scroll

### Search & Discovery
- ✅ Advanced Search
- ✅ Category Filtering
- ✅ Location-based Search
- ✅ Cached Results
- ✅ Pagination

### Appointments
- ✅ Schedule Management
- ✅ Time Slot Selection
- ✅ Status Tracking
- ✅ Accept/Decline/Cancel

---

## 🧪 Development Commands

### Backend
```bash
php artisan serve              # Start dev server
php artisan migrate           # Run migrations
php artisan migrate:fresh --seed  # Fresh DB with seed data
php artisan tinker            # Interactive console
php artisan cache:clear       # Clear cache
```

### Frontend
```bash
npm run dev                   # Development build (watch mode)
npm run build                 # Production build
npm run lint                  # Run linter
```

---

## 🚢 Deployment

Deployment is automated via GitLab CI/CD pipeline.

### Production Deployment
```bash
git push origin main
# Pipeline automatically deploys to AWS EC2
```

See `Z-CursorAI-ReadMe/` for detailed deployment guides.

---

## 🤝 Contributing

1. Create a feature branch
2. Make your changes
3. Test thoroughly
4. Create a merge request
5. Wait for review

---

## 📝 License

This project is proprietary software. All rights reserved.

---

## 👥 Authors

**WhoSabiWork Development Team**

---

## 📧 Support

For issues and questions, please contact the development team.

---

## 🗂️ Documentation Index

All detailed technical documentation is in `Z-CursorAI-ReadMe/`:

- Multi-Provider OAuth Implementation
- Database Schema & Migrations
- Frontend Component Documentation
- API Endpoints
- Deployment Guides
- Troubleshooting Guides
- Shell Scripts & Automation

---

**Built with Laravel ❤️ Vue.js**

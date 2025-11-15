#!/bin/bash

# 🚀 AWS EC2 Development Environment Setup Script
# Run this on your EC2 instance: bash setup-ec2-dev.sh

echo "🚀 Setting up development environment on AWS EC2..."

# Update system
echo "📦 Updating system packages..."
sudo yum update -y

# Install development essentials
echo "🔧 Installing development tools..."
sudo yum groupinstall -y "Development Tools"
sudo yum install -y git vim nano htop tree curl wget

# Install Node.js and npm
echo "📦 Installing Node.js..."
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install -y nodejs

# Install GitHub CLI
echo "🐙 Installing GitHub CLI..."
sudo yum install -y gh

# Install Python 3 and pip (for additional tools)
echo "🐍 Installing Python tools..."
sudo yum install -y python3 python3-pip

# Install useful CLI tools
echo "🛠️ Installing helpful CLI tools..."
npm install -g tldr how2

# Set up Laravel development tools
echo "🎵 Setting up Laravel tools..."
cd /var/www/html/WhoSabiWork-Redesign

# Install IDE helper for better development
composer require --dev barryvdh/laravel-ide-helper --quiet
php artisan ide-helper:generate 2>/dev/null || echo "IDE helper generation skipped"

# Create useful aliases
echo "⚡ Setting up aliases..."
cat >> ~/.bashrc << 'EOF'

# Laravel Development Aliases
alias art='php artisan'
alias artisan='php artisan'
alias tinker='php artisan tinker'
alias migrate='php artisan migrate'
alias seed='php artisan db:seed'
alias fresh='php artisan migrate:fresh --seed'
alias serve='php artisan serve --host=0.0.0.0 --port=8000'

# Git aliases
alias gs='git status'
alias ga='git add'
alias gc='git commit'
alias gp='git push'
alias gl='git log --oneline'

# Navigation aliases
alias ll='ls -la'
alias la='ls -A'
alias l='ls -CF'
alias ..='cd ..'
alias ...='cd ../..'

# Laravel project shortcut
alias cdp='cd /var/www/html/WhoSabiWork-Redesign'

# Composer aliases
alias ci='composer install'
alias cu='composer update'
alias cr='composer require'
alias cda='composer dump-autoload'

EOF

# Source the new aliases
source ~/.bashrc

echo "✅ Development environment setup complete!"
echo ""
echo "🎯 Next steps:"
echo "1. Install GitHub Copilot CLI: gh extension install github/gh-copilot"
echo "2. Authenticate with GitHub: gh auth login"
echo "3. Test with: gh copilot suggest 'Laravel artisan commands'"
echo ""
echo "🚀 Useful commands now available:"
echo "  art migrate    - Run migrations"
echo "  art tinker     - Open Laravel REPL"
echo "  fresh          - Fresh migration with seeding"
echo "  cdp            - Go to project directory"
echo "  serve          - Start Laravel development server"
echo ""
echo "📁 Your project is at: /var/www/html/WhoSabiWork-Redesign"

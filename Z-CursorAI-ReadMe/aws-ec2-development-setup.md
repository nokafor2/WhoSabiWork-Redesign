# 🚀 AWS EC2 Development Environment Setup

## Option 1: VS Code Remote Development (Recommended)

### Install VS Code Server on EC2
```bash
# SSH to your EC2 instance
ssh ec2-user@ec2-13-40-186-156.eu-west-2.compute.amazonaws.com

# Install Node.js (required for VS Code Server)
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install -y nodejs

# Download and install VS Code Server
wget -O- https://aka.ms/install-vscode-server/setup.sh | sh
```

### Configure Remote SSH in Local VS Code
1. Install "Remote - SSH" extension in your local VS Code
2. Add your EC2 instance to SSH config:
```
Host aws-whosabiwork
    HostName ec2-13-40-186-156.eu-west-2.compute.amazonaws.com
    User ec2-user
    IdentityFile ~/.ssh/your-key.pem
```

### Benefits:
- ✅ Full VS Code experience on remote server
- ✅ AI extensions work (GitHub Copilot, etc.)
- ✅ Direct file editing on production
- ✅ Integrated terminal
- ✅ Laravel extension support

---

## Option 2: GitHub Copilot CLI

### Install GitHub Copilot CLI
```bash
# Install npm (if not already installed)
sudo yum install -y npm

# Install GitHub Copilot CLI
npm install -g @githubnext/github-copilot-cli

# Authenticate
gh auth login
gh extension install github/gh-copilot

# Usage examples:
gh copilot suggest "create a Laravel migration for users table"
gh copilot explain "php artisan migrate:fresh --seed"
```

---

## Option 3: Vim/Neovim with AI Plugins

### Install Neovim with AI capabilities
```bash
# Install Neovim
sudo yum install -y neovim

# Install vim-plug
curl -fLo ~/.local/share/nvim/site/autoload/plug.vim --create-dirs \
    https://raw.githubusercontent.com/junegunn/vim-plug/master/plug.vim

# Configure with AI plugins (create ~/.config/nvim/init.vim)
```

Example Neovim config with AI:
```vim
call plug#begin()
Plug 'github/copilot.vim'
Plug 'dense-analysis/ale'
Plug 'tpope/vim-fugitive'
call plug#end()

" Enable Copilot
let g:copilot_enabled = 1
```

---

## Option 4: Cloud Development Environment

### AWS Cloud9 (AWS Native)
```bash
# Create Cloud9 environment
aws cloud9 create-environment-ec2 \
    --name "WhoSabiWork-Dev" \
    --description "Laravel Sanctum Development" \
    --instance-type t3.small \
    --subnet-id your-subnet-id \
    --automatic-stop-time-minutes 30
```

### Benefits:
- ✅ Built-in AWS integration
- ✅ Pre-configured development environment
- ✅ Collaborative coding
- ✅ Automatic scaling

---

## Option 5: Terminal-Based AI Tools

### Install useful CLI tools
```bash
# Install Git and development essentials
sudo yum groupinstall -y "Development Tools"
sudo yum install -y git curl wget

# Install Laravel-specific tools
composer global require laravel/installer
composer global require laravel/valet

# Install helpful CLI tools
pip3 install --user thefuck  # Command correction
npm install -g tldr          # Better man pages
npm install -g how2          # Stack Overflow from CLI
```

### Usage examples:
```bash
# Get Laravel help
tldr php artisan

# Fix typos automatically  
fuck  # (after a typo)

# Stack Overflow search
how2 "laravel sanctum authentication"
```

---

## 🎯 Recommended Setup for Your Laravel Project

### 1. Install Development Essentials
```bash
ssh ec2-user@ec2-13-40-186-156.eu-west-2.compute.amazonaws.com

# Update system
sudo yum update -y

# Install development tools
sudo yum groupinstall -y "Development Tools"
sudo yum install -y git vim nano htop tree

# Install Node.js and npm
curl -fsSL https://rpm.nodesource.com/setup_18.x | sudo bash -
sudo yum install -y nodejs

# Install Composer globally (if not already done)
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

### 2. Set up Laravel Development Tools
```bash
# Install Laravel global tools
composer global require laravel/installer
composer global require laravel/tinker

# Add Composer global bin to PATH
echo 'export PATH="$HOME/.composer/vendor/bin:$PATH"' >> ~/.bashrc
source ~/.bashrc

# Install useful Laravel packages
cd /var/www/html/WhoSabiWork-Redesign
composer require --dev barryvdh/laravel-ide-helper
php artisan ide-helper:generate
```

### 3. Configure Git for Development
```bash
# Set up Git (replace with your details)
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"

# Set up SSH for GitHub (if needed)
ssh-keygen -t ed25519 -C "your.email@example.com"
```

### 4. Install GitHub CLI and Copilot
```bash
# Install GitHub CLI
sudo yum install -y gh

# Authenticate and install Copilot
gh auth login
gh extension install github/gh-copilot

# Test Copilot
gh copilot suggest "Laravel artisan command to create controller"
```

---

## 🚀 Quick Laravel Development Commands

Once set up, you can use these AI-assisted commands:

```bash
# Get suggestions for Laravel commands
gh copilot suggest "create a new Laravel controller with CRUD methods"

# Explain existing commands
gh copilot explain "php artisan migrate:fresh --seed --force"

# Get help with debugging
gh copilot suggest "debug Laravel 500 error in production"

# Sanctum-specific help
gh copilot suggest "configure Laravel Sanctum for API authentication"
```

---

## 📊 Comparison of Options

| Option | AI Support | Learning Curve | Laravel Integration | Cost |
|--------|------------|----------------|-------------------|------|
| **VS Code Remote** | ⭐⭐⭐⭐⭐ | Low | Excellent | Free |
| **GitHub Copilot CLI** | ⭐⭐⭐⭐ | Low | Good | $10/month |
| **Neovim + AI** | ⭐⭐⭐ | High | Good | Free/Paid |
| **AWS Cloud9** | ⭐⭐ | Medium | Good | AWS pricing |
| **Terminal Tools** | ⭐⭐ | Low | Basic | Free |

## 🎯 My Recommendation

**Start with VS Code Remote + GitHub Copilot CLI** for the best balance of:
- AI assistance
- Laravel development tools
- Familiar interface
- Direct server access

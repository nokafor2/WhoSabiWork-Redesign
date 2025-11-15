#!/bin/bash

# AWS Certificate Manager + Application Load Balancer Setup
# This script helps configure your EC2 instance to work behind an ALB with ACM SSL

echo "🔐 Setting up EC2 instance for ACM + Application Load Balancer..."

# 1. Update Nginx configuration for ALB
echo "📝 Creating ALB-optimized Nginx configuration..."

sudo tee /etc/nginx/sites-available/ayuaprojects-alb.com > /dev/null <<'EOF'
# Nginx configuration for ayuaprojects.com behind AWS Application Load Balancer
# ALB handles SSL termination, so Nginx only needs to handle HTTP

server {
    listen 80;
    server_name ayuaprojects.com www.ayuaprojects.com localhost;
    
    # Document root
    root /var/www/html/WhoSabiWork/public;
    index index.php index.html index.htm;
    
    # Trust ALB forwarded headers
    real_ip_header X-Forwarded-For;
    set_real_ip_from 10.0.0.0/8;     # VPC CIDR - adjust as needed
    set_real_ip_from 172.16.0.0/12;  # VPC CIDR - adjust as needed
    set_real_ip_from 192.168.0.0/16; # VPC CIDR - adjust as needed
    real_ip_recursive on;
    
    # Security Headers (ALB will handle HSTS)
    add_header X-Frame-Options "SAMEORIGIN" always;
    add_header X-XSS-Protection "1; mode=block" always;
    add_header X-Content-Type-Options "nosniff" always;
    add_header Referrer-Policy "no-referrer-when-downgrade" always;
    add_header Content-Security-Policy "default-src 'self' http: https: data: blob: 'unsafe-inline'" always;
    
    # Gzip Compression
    gzip on;
    gzip_vary on;
    gzip_min_length 1024;
    gzip_proxied any;
    gzip_types text/plain text/css text/xml text/javascript application/x-javascript application/xml+rss application/javascript;
    
    # Client max body size (for file uploads)
    client_max_body_size 100M;
    
    # Health check endpoint for ALB
    location /health {
        access_log off;
        return 200 "healthy\n";
        add_header Content-Type text/plain;
    }
    
    # Handle Laravel routes
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    # Handle PHP files
    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        
        # Pass ALB headers to PHP
        fastcgi_param HTTP_X_FORWARDED_FOR $http_x_forwarded_for;
        fastcgi_param HTTP_X_FORWARDED_PROTO $http_x_forwarded_proto;
        fastcgi_param HTTP_X_FORWARDED_PORT $http_x_forwarded_port;
        fastcgi_param HTTP_HOST $http_host;
        fastcgi_param SERVER_NAME $http_host;
        
        # Force HTTPS scheme for Laravel URL generation
        fastcgi_param HTTPS on;
        fastcgi_param SERVER_PORT 443;
        
        # Increase timeouts
        fastcgi_read_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_connect_timeout 300;
    }
    
    # Static assets caching
    location ~* \.(jpg|jpeg|png|gif|ico|css|js|woff|woff2|ttf|svg)$ {
        expires 1y;
        add_header Cache-Control "public, immutable";
        try_files $uri =404;
    }
    
    # Deny access to sensitive files
    location ~ /\.(?!well-known).* {
        deny all;
    }
    
    location ~ /\.ht {
        deny all;
    }
    
    # Deny access to Laravel specific files
    location ~ /(\.env|composer\.(json|lock)|package\.json|\.git) {
        deny all;
    }
    
    # Handle Laravel storage files
    location /storage {
        alias /var/www/html/WhoSabiWork/storage/app/public;
    }
    
    # Error pages
    error_page 404 /index.php;
    
    # Logging
    access_log /var/log/nginx/ayuaprojects.com.access.log;
    error_log /var/log/nginx/ayuaprojects.com.error.log;
}
EOF

# 2. Enable the new configuration
echo "🔧 Enabling ALB-optimized Nginx configuration..."
sudo rm -f /etc/nginx/sites-enabled/default
sudo rm -f /etc/nginx/sites-enabled/ayuaprojects.com
sudo ln -sf /etc/nginx/sites-available/ayuaprojects-alb.com /etc/nginx/sites-enabled/

# 3. Test Nginx configuration
echo "🔍 Testing Nginx configuration..."
sudo nginx -t

if [ $? -eq 0 ]; then
    echo "✅ Nginx configuration is valid"
    sudo systemctl reload nginx
else
    echo "❌ Nginx configuration has errors!"
    exit 1
fi

# 4. Update Laravel configuration
echo "📝 Updating Laravel configuration for ALB..."
cd /var/www/html/WhoSabiWork

# Clear caches
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Regenerate config cache
php artisan config:cache

# 5. Set proper permissions
echo "🔐 Setting proper permissions..."
sudo chown -R www-data:www-data /var/www/html/WhoSabiWork
sudo chmod -R 755 /var/www/html/WhoSabiWork
sudo chmod -R 775 /var/www/html/WhoSabiWork/storage
sudo chmod -R 775 /var/www/html/WhoSabiWork/bootstrap/cache

# 6. Restart services
echo "🔄 Restarting services..."
sudo systemctl restart php8.2-fpm
sudo systemctl restart nginx

# 7. Check service status
echo "📊 Checking service status..."
sudo systemctl status nginx --no-pager -l
sudo systemctl status php8.2-fpm --no-pager -l

echo ""
echo "✅ EC2 configuration for ALB complete!"
echo ""
echo "📋 Next steps to complete ACM + ALB setup:"
echo "1. Create ACM certificate in AWS Console"
echo "2. Create Application Load Balancer"
echo "3. Configure target group pointing to this EC2 instance"
echo "4. Update DNS to point to ALB"
echo ""
echo "🔗 After ALB setup, your site will be: https://ayuaprojects.com"

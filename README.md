# LARAVEL PROJECT SETUP GUIDE

1. Clone the Repository

---

git clone <repository-url> project-folder
cd project-folder

2. Install Composer (if not installed)

---

curl -sS https://getcomposer.org/installer | php -- --2

3. Install Dependencies

---

php composer.phar install --optimize-autoloader

# OR for production

php composer.phar install --no-dev --optimize-autoloader

4. Environment Setup

---

cp .env.example .env

# Update .env with database and app configuration

5. Public Folder Setup

---

cp -r public/* /home/u316993456/domains/islamicstudyportal.org/public_html/v2/

# Update index.php in public_html/v2/ to:

require **DIR**.'/../vendor/autoload.php';
$app = require_once **DIR**.'/../bootstrap/app.php';

6. Add .htaccess File

---

Place in public_html/v2/.htaccess:

<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews -Indexes
    </IfModule>

    RewriteEngine On

    # Redirect all requests to index.php
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteRule ^ index.php [L]

</IfModule>

7. Set Permissions

---

chmod -R 755 .
chmod -R 755 storage bootstrap/cache

8. Install Laravel Packages

---

# Laravel Breeze

composer require laravel/breeze --dev

# Docs: https://github.com/laravel/breeze

# Spatie Permission

composer require spatie/laravel-permission

# Docs: https://spatie.be/docs/laravel-permission/v6/basic-usage

# Yajra DataTables

composer require yajra/laravel-datatables-oracle

# Docs: https://yajrabox.com/docs/laravel-datatables/master

9. Useful Commands

---

# Create new file

touch filename.php

# Create new folder

mkdir foldername

# View file in nano editor

nano filename.php

# Delete file or folder

rm -rf filename_or_folder

10. Final Steps

---

php artisan key:generate
php artisan migrate --seed
php artisan config:cache
php artisan route:cache
php artisan view:cache

=======================================
✅ Laravel project is now ready to use!
=======================================

# Simple .htaccess for EduSmart
Options -Indexes

# Enable URL rewriting
RewriteEngine On
RewriteBase /EduSmart/

# Allow direct access to files and folders
RewriteCond %{REQUEST_FILENAME} -f [OR]
RewriteCond %{REQUEST_FILENAME} -d
RewriteRule ^ - [L]

# Redirect everything else to index.php
RewriteRule ^ index.php [QSA,L]
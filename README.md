
# Installation

git clone https://github.com/kibretb/Glide-PHP-Practical-Test.git

cd Glide-PHP-Practical-Test

composer install --ignore-platform-reqs

php artisan migrate


# API endpoints
 Run php artisan route:list to see the defined API endpoints
  /api/vendor/multiple-mac-lookup 
  /api/vendor/single-mac-lookup

  postman endpoints
    http://127.0.0.1:8000/api/vendor/single-mac-lookup
    expected input mac_address
    
    http://127.0.0.1:8000/api/vendor/multiple-mac-lookup
    expected input, an array of mac addresses (mac_addresses[])

# Kernel Command
  php artisan app:import-ieee-oui-data
  This command first stores(updates) the latest version of the OUI IEEE file inside the storage public folder and persists it to the database.

# Database 
These are the default database credentials

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root

They can be updated on the .env file
DB_PASSWORD=


  



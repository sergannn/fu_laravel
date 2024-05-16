#php artisan breeze:install blade --no-interaction
echo 'hello its sergey from file'
echo 'clearing'
php artisan config:clear
echo "Caching config..."
#php artisan config:cache
php artisan route:clear
echo "Caching routes..."
#php artisan route:cache
echo "Running migrations..."
php artisan migrate --force


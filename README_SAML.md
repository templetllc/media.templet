php7.4 composer require 24slides/laravel-saml2

Added config/saml.php 

Added some vars in .env, in my case:

SAML2_DEBUG=true
APP_URL="http://localhost:8000/"
SAML2_LOGIN_URL="http://localhost:8000/home"
SAML2_LOGOUT_URL="http://localhost:8000/"


php7.4 artisan vendor:publish --provider="Slides\Saml2\ServiceProvider"

php7.4 artisan migrate

php7.4 artisan package:discover

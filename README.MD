# Routerunner


[![Build Status](https://travis-ci.org/timtegeler/routerunner.svg?branch=master)](https://travis-ci.org/timtegeler/routerunner)

```php
<?php
use TimTegeler\Routerunner\Routerunner;
Router::setCallableNameSpace("Controller");
Router::route("GET", "/", "Index->get");
Router::route("GET", "/user/[numeric]", "User->get");
Router::route("POST", "/login", "Index->post");
Router::execute($_SERVER[‘REQUEST_METHOD’], $_SERVER['REQUEST_URI’]);
```

### Configfile
```
GET    /                Index->get
GET    /user/[numeric]  User->get
POST   /login           Index->post
```

```php
<?php
use TimTegeler\Routerunner\Router;
Router::setCallableNameSpace("Controller");
Router::parse("path/to/config");
Router::execute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
```

## Composer
```
{
    "require": {
        "timtegeler/routerunner": "dev-master"
    }
}
```

## Apache .htaccess
```
Options +FollowSymLinks
RewriteEngine On
RewriteRule ^(.*)$ index.php [NC,L]
```
# Routerunner

[![Build Status](https://travis-ci.org/timtegeler/routerunner.svg?branch=master)](https://travis-ci.org/timtegeler/routerunner)

**Routerunner** is a router for PHP 5.3+ based upon pseudo regular expressions

* Simple pseudo regular expression for routing 
* Defining routes in a separete [routesfile](#routesfile) without overhead from php
* Caching routesfile for reduce the parsing time 

```php
<?php
use TimTegeler\Routerunner\Routerunner;

// Setting root namespace for controller 
Router::setCallableNameSpace("Controller");

// Adding routes
Router::route("GET", "/", "Index->get");
Router::route("GET", "/user/[numeric]", "User->get");
Router::route("POST", "/login", "Index->post");

// Trigger exceution 
Router::execute($_SERVER[‘REQUEST_METHOD’], $_SERVER['REQUEST_URI’]);
```

### Routesfile

With the routesfile you can define your routes in a more clean fashion.

```
GET    /                Index->get
GET    /user/[numeric]  User->get
POST   /login           Index->post
```

You need to let Routerunner parse your routefile, but furthermore you can define routes in php.

```php
// Parsing the configfile
Router::parse("path/to/routesfile");
Router::route("GET", "/faq", "Index->faq");
```

### Caching

If you want to reduce the parsing time of your routesfile you can activate caching with setting the path to
a read- and writeable (from your webserver) file. Be careful, every content of the file will be deleted.

```php
// Setting the cachefile
Router::setCacheFile("path/to/cache");
```

## Composer

You can use [composer](https://getcomposer.org/) to download Routerunner. At the moment Routerunner ist not availabel 
over [packagist](https://packagist.org/), but the first stable release will be published there. 

```
{
    "repositories": [
        {
        "url": "https://github.com/timtegeler/routerunner.git",
        "type": "git"
        }
    ],
    "require": {
        "timtegeler/routerunner": "dev-master"
    }
}
```

## Apache .htaccess

If you are using Apache as your Webserver you need to rewrite all request to i.e. the index.php where 
the Router is excecuted.

```
RewriteEngine On
RewriteRule ^(.*)$ index.php [NC,L]
```
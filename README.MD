# Routerunner - meep meep!

[![Build Status](https://travis-ci.org/timtegeler/routerunner.svg?branch=master)](https://travis-ci.org/timtegeler/routerunner)
[![Coverage Status](https://coveralls.io/repos/timtegeler/routerunner/badge.svg?branch=master&service=github)](https://coveralls.io/github/timtegeler/routerunner?branch=master)

**Routerunner** is a router for PHP 5.3+ based on pseudo regular expressions

* Simple [pseudo regular expression](#pseudo-regular-expression) for routing 
* Defining routes in a separate [routesfile](#routesfile) without overhead from php
* [Cachig](#caching) routesfile to reduce the parsing time 

```php
<?php
use TimTegeler\Routerunner\Routerunner;

$routerunner = new Routerunner();
// Setting root namespace for controller
$routerunner->setCallableNameSpace("Controller");

// Adding routes
$routerunner->route("GET", "/", "Index->get");
$routerunner->route("GET", "/user/[numeric]", "User->get");
$routerunner->route("POST", "/login", "Index->post");

// Trigger execution 
$routerunner->execute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
```

### Pseudo regular expression

Routerunner uses a pseudo regular expressions to describe the [HTTP-method](https://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol) and [URI](https://en.wikipedia.org/wiki/Uniform_Resource_Identifier). 

#### HTTP-method
```
*       // Match GET and POST  
GET     // Match GET
POST    // Match POST
```

#### URI
```
[numeric]  // Match all numeric values like 1, 1.1, 2, 143, ... 
[string]   // Match all strings like alice, bob, eve, ...

// examples
/user/[numeric] // Match /user/1, /user/2, /user/3, ...
/user/[string]  // Match /user/alice, /user/bob, /user/eve, ...
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
$routerunner->parse("path/to/routesfile");
$routerunner->route("GET", "/faq", "Index->faq");
```

### Caching

If you want to reduce the parsing time of your routesfile you can activate caching with setting the path to
a read- and writeable (from your webserver) file. Be careful, every content of the file will be deleted.

```php
<?php
// Setting the cachefile
$routerunner->setCacheFile("path/to/cache");
```

## Composer

You can use [composer](https://getcomposer.org/) to download Routerunner. At the moment Routerunner ist not availabel 
over [packagist](https://packagist.org/), but the first stable release will be published there. 

```json
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
the router is excecuted. For accessing assets like CSS-, JS-, and imagefiles the rewrite excepts all existing files and directories.  

```
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [NC,L]
```

In some cases you need to activate the module mod_rewrite.

```sh
a2enmod rewrite
```
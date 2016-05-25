# Routerunner - meep meep!

[![Build Status](https://travis-ci.org/timtegeler/routerunner.svg?branch=master)](https://travis-ci.org/timtegeler/routerunner)
[![Coverage Status](https://coveralls.io/repos/timtegeler/routerunner/badge.svg?branch=master&service=github)](https://coveralls.io/github/timtegeler/routerunner?branch=master)
[![GitHub license](https://img.shields.io/github/license/timtegeler/routerunner.svg)]()

**Routerunner** is a router for PHP 5.4+ based on pseudo regular expressions

* Simple [pseudo regular expression](#pseudo-regular-expression) for routing 
* Defining routes in a separate [routesfile](#routesfile) without overhead from php
* [Cachig](#caching) routesfile to reduce the parsing time 

```php
<?php
use TimTegeler\Routerunner\Routerunner;

// Setting root namespace for controller
$routerunner = new Routerunner("Controller");

// Adding routes
$routerunner->route("GET", "/", "Index->get");
$routerunner->route("GET", "/user/(numeric)", "User->get");
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
(numeric)  // Match all numeric values like 1, 1.1, 2, 143, ...
(string)   // Match all strings like alice, bob, eve, ...

// examples
/user/(numeric) // Match /user/1, /user/2, /user/3, ...
/user/(string)  // Match /user/alice, /user/bob, /user/eve, ...
```


### Routesfile

With the routesfile you can define your routes in a more clean fashion.

```
GET    /                Index->get
GET    /user/(numeric)  User->get
POST   /login           Index->post
```

You need to let Routerunner parse your routefile, but furthermore you can define routes in php.

```php
<?php
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

### Middleware

Middlewares are designed to implement contraints upon your regular routing.

A middleware expects a controller- and a method-name as constructor parameters. This controller respectively methods acts as the target for a possibly rerouting. Every middleware  must implement the process-method; it takes the controller matched by the current route as a parameter and should return a boolean value. Routerunner evaluates the return value. If the return value is *false* then routerunner interrupts and tries to starts the method of the bounded controller. Otherwise routerunner tries to processes the next registered middlewares or starts the actually controller.'

For example middlewares enable you to control the access to restricted controllers like private pages or something similar. Such a middleware could detect if a user is not logged in and reroute to the login-page.
```php
<?php

use TimTegeler\Routerunner\Middleware\Middleware;

/**
 * Class checkQuota
 */
class checkQuota extends Middleware
{
    /**
     * @var int
     */
    private static $quota = 3;
    /**
     * @param $controller
     * @return bool
     */
    public function process($controller)
    {
        if (isset($_SESSION['quota'])) {
            return $_SESSION['quota'] < self::$quota;
        }
        return true;
    }
}
```

Every middleware must registered with routerunner. Routerunner will process middlewares in the order they are registered.

```php
<?php

use \TimTegeler\Routerunner\Callback;

$checkQuota = new checkQuota('User', 'exceededQuota');

$routerunner->registerMiddleware($checkQuota);
```

### Postprocessor

Postprocessing enables you to process the return value of your controller. One thinkable scenario could be a REST-api which uses JSON to transfer data. A Postprocessor could care about encoding the JSON-string.

Admittedly postprocessing a return value only by triggering a single function isn't quite brave. A more conclusive argument for postprocessing would be a simple template-engine which renders HTML code.

```php
<?php

use TimTegeler\Routerunner\PostProcessor\PostProcessorInterface;

/**
 * Class Encoder
 */
class Encoder implements PostProcessorInterface
{

    /**
     * @param $return
     * @return string
     */
    public function process($return)
    {
        return json_encode($return);
    }
}
```

You can enable a Postprocessor by register it with routerunner.

```php
<?php

$routerunner->setPostProcessor(new Encoder());
```

## Composer

You can use [composer](https://getcomposer.org/) to download and install Routerunner. Visit routerunner on [packagist](https://packagist.org/packages/timtegeler/routerunner).

### composer.json
```json
{
    "repositories": [
        {
        "url": "https://github.com/timtegeler/routerunner.git",
        "type": "git"
        }
    ],
    "require": {
        "timtegeler/routerunner": "^0.0.3"
    }
}
```

or

### comandline

```sh
composer require timtegeler/routerunner
```

## Apache .htaccess

If you are using Apache as your Webserver you need to rewrite all request to i.e. the index.php where 
routerunner is excecuted. For accessing assets like CSS-, JS-, and imagefiles the rewrite excepts all existing files and directories.

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

## Demo

In this demonstration you can examine the basic usage with all possible features in use.

[timtegeler/routerunner-example](https://github.com/timtegeler/routerunner-example)
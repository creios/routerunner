# Routerunner - meep meep!

[![Build Status](https://travis-ci.org/timtegeler/routerunner.svg?branch=master)](https://travis-ci.org/timtegeler/routerunner)
[![Coverage Status](https://coveralls.io/repos/timtegeler/routerunner/badge.svg?branch=master&service=github)](https://coveralls.io/github/timtegeler/routerunner?branch=master)
[![GitHub license](https://img.shields.io/github/license/timtegeler/routerunner.svg)]()

**Routerunner** is a config-driven router for HTTP requests

* Simple [pseudo regular expression](#pseudo-regular-expression) for routing 
* Defining routes in a separate [routesfile](#routesfile) without overhead from php
* REST support based on CRUDL actions
* [Cachig](#caching) routesfile to reduce the parsing time 
* Implements the PSR-15 middleware interface

```php
<?php

use TimTegeler\Routerunner\Routerunner;

// Setting path to config file
$routerunner = new Routerunner("/path/to/config.yml");
// Trigger execution
$routerunner->execute($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
```

### Config file

With the yml-based config file you can define your routes and configure routerunner in a clean fashion.

```yml
routes:
  - [GET, /, Index->get]
  - [GET, /subpath, Index->get]
  - [GET, /api, Index->api]
  - [GET, /(string), Index->get]
  - [POST, /(string)/(numeric), Index->post]

rest: 
  - [CRUDL, /user, User]
  - [L, /group, Group]
  
fallback: Index->get

baseNamespace: Controller\Namespace
```

### Pseudo regular expression

Routerunner uses a pseudo regular expressions to describe the [HTTP-method](https://en.wikipedia.org/wiki/Hypertext_Transfer_Protocol) and [URI](https://en.wikipedia.org/wiki/Uniform_Resource_Identifier). 

#### HTTP-method
```
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

### Caching

If you want to reduce the parsing time of your routesfile you can activate caching with setting the path to
a read- and writeable (from your webserver) file. Be careful, every content of the file will be deleted.

```php
<?php

// Enabling caching
$routerunner->setCaching(true);
```

### PSR-15 middleware

Routerunner implements the PSR-15 middleware interface. That means that you can use Routerunner directly with a PSR-15 Dispatcher.

```php
<?php

$dispatcher = new Dispatcher([
    new Routerunner("/path/to/config.yml")
]);

$response = $dispatcher->dispatch(new ServerRequest());
```

### Routerunner Middleware

Middlewares are designed to implement constraints upon your regular routing.

A middleware expects a controller- and a method-name as constructor parameters. This controller respectively methods acts as the target for a possibly rerouting. Every middleware  must implement the process-method; it takes the controller matched by the current route as a parameter and should return a boolean value. Routerunner evaluates the return value. If the return value is *false* then routerunner interrupts and tries to starts the method of the bounded controller. Otherwise routerunner tries to processes the next registered middlewares or starts the actually controller.'

For example middlewares enable you to control the access to restricted controllers like private pages or something similar. Such a middleware could detect if a user is not logged in and reroute to the login-page.

```php
<?php

use TimTegeler\Routerunner\Middleware\Middleware;

/**
 * Class CheckQuota
 */
class CheckQuota extends Middleware
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
$checkQuota = new CheckQuota('User', 'exceededQuota');

$routerunner->registerMiddleware($checkQuota);
```

### Routerunner Postprocessor

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
    "require": {
        "timtegeler/routerunner": "^1.1.0"
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

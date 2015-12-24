# Routerunner


[![Build Status](https://travis-ci.org/timtegeler/routerunner.svg?branch=master)](https://travis-ci.org/timtegeler/routerunner)

```php
use TimTegeler\Routerunner\Routerunner;
Router::setCallableNameSpace("Controller");
Router::route("GET", "/", "Index->get");
Router::route("GET", "/user/[numeric]", "User->get");
Router::route("POST", "/login", "Index->post");
Router::execute($_SERVER[‘REQUEST_METHOD’], $_SERVER['REQUEST_URI’]);
```

```
GET    /                Index->get
GET    /user/[numeric]  User->get
POST   /login           Index->post
```

```php
use TimTegeler\Routerunner\Router;
Router::setCallableNameSpace("Controller");
Router::route("GET", "/", "Index->get");
Router::execute($_SERVER[‘REQUEST_METHOD’], $_SERVER['REQUEST_URI’]);
```
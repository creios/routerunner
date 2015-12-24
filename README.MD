# Routerunner

```php
use TimTegeler\Routerunner\Routerunner;
Routerunner::setCallableNameSpace("Controller");
Routerunner::route("GET", "/", "Index->get");
Routerunner::route("GET", "/user/[numeric]", "User->get");
Routerunner::route("POST", "/login", "Index->post");
Routerunner::execute($_SERVER[‘REQUEST_METHOD’], $_SERVER['REQUEST_URI’]);
```

```
GET    /                Index->get
GET    /user/[numeric]  User->get
POST   /login           Index->post
```

```php
use TimTegeler\Router\Router;
Routerunner::setCallableNameSpace("Controller");
Routerunner::route("GET", "/", "Index->get");
Routerunner::execute($_SERVER[‘REQUEST_METHOD’], $_SERVER['REQUEST_URI’]);
```
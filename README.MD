# Routerunner

```php
use TimTegeler\Router\Router;

Router::setCallableNameSpace("Controller");

Router::route("GET", "/", "Index->get");

Router::route("GET", "/user/[numeric]", "User->get");

Router::route("POST", "/login", "Index->post");

Router::execute($_SERVER[‘REQUEST_METHOD’], $_SERVER['REQUEST_URI’]);
```

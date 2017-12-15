# Code Inc. library to render exception in HTML and CLI

## Usage

Also take a look to [this example file](https://github.com/codeinchq/lib-exceptiondisplay/blob/master/example.php).

```php
use CodeInc\ExceptionDisplay\ExceptionRederingEngine;

// Creating fake exception
$exception1 = new Exception("A Previous exception");
$exception2 = new Exception("A big error", 0, $exception1);

// Rendering
(new ExceptionRederingEngine($exception2, true))->render();
```


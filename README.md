# Code Inc. library to render errors in HTML and CLI

## Usage

Also take a look to [this example file](https://github.com/codeinchq/lib-exceptiondisplay/blob/master/examples/example.php).

```php
use CodeInc\ExceptionDisplay\ExceptionRederingEngine;

// Creating fake exceptions
$exception = new Exception("A big error", 0, 
  new Exception("A Previous exception"));

// Rendering
(new ExceptionRederingEngine($exception, true))->render();
```

## Screenshots

### Terminal
<img src="https://github.com/codeinchq/lib-exceptiondisplay/blob/master/examples/terminal.png?raw=true" alt="">

### Browser
<img src="https://github.com/codeinchq/lib-exceptiondisplay/blob/master/examples/browser.png?raw=true" alt="">

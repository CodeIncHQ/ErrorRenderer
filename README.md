# Code Inc. library to render errors in HTML and CLI

## Usage

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
<img src="https://github.com/codeinchq/lib-errordisplay/blob/master/screenshots/terminal.png?raw=true" alt="">

### Browser
<img src="https://github.com/codeinchq/lib-errordisplay/blob/master/screenshots/browser.png?raw=true" alt="">

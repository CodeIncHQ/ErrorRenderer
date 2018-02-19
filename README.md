# Code Inc. library to render errors in HTML and CLI

## Usage

```php
<?php
use CodeInc\ErrorDisplay\ErrorRenderingEngine;

// Creating fake exceptions
$exception1 = new \Exception("A source exception");
		$exception2 = new \Exception("A child exception", 0, $exception1);
		return new \Exception("A last exception", 1010, $exception2);

// Rendering (using 
echo new ErrorRenderingEngine($exception1);
```

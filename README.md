# Code Inc. library to render errors in HTML and CLI

The PHP 7 library provides to classes dedicated to render exceptions and errors (everything which implements [`Throwable`](http://php.net/manual/fr/class.throwable.php)).

## Usage

```php
<?php
use CodeInc\ErrorDisplay\HtmlErrorRenderer;
use CodeInc\ErrorDisplay\ConsoleErrorRenderer;

// Creating a fake exception
$fakeException = new \Exception("A last exception", 1010, 
    new \Exception("A child exception", 0,
        new \Exception("A source exception")));

// Rendering for a web browser
echo new HtmlErrorRenderer($fakeException);

// Rendering for CLI
echo new ConsoleErrorRenderer($fakeException);

// Rendering using option (all option enabled)
echo new ConsoleErrorRenderer($fakeException, ConsoleErrorRenderer::OPT_ALL);

// Rendering with all options but no colors
echo new ConsoleErrorRenderer($fakeException, ConsoleErrorRenderer::OPT_ALL ^ ConsoleErrorRenderer::OPT_COLORS);
```

## Installation
This library is available through [Packagist](https://packagist.org/packages/codeinc/lib-errordisplay) and can be installed using [Composer](https://getcomposer.org/): 

```bash
composer require codeinc/lib-errordisplay
```

# License

The library is published under the MIT license (see [`LICENSE`](LICENSE) file). 
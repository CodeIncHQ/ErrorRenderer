# Code Inc. library to render errors in HTML and CLI

The PHP 7 library provides to classes dedicated to render exceptions and errors (everything which implements [`Throwable`](http://php.net/manual/fr/class.throwable.php)).

## Usage

Rendering an exception:
```php
<?php
use CodeInc\ErrorDisplay\HtmlErrorRenderer;
use CodeInc\ErrorDisplay\TermErrorRenderer;

// Creating a fake exception
$fakeException = new \Exception("A last exception", 1010, 
    new \Exception("A child exception", 0,
        new \Exception("A source exception")));

// Rendering for a web browser
echo new HtmlErrorRenderer($fakeException);

// Rendering for CLI
echo new TermErrorRenderer($fakeException);

// Rendering using option (all option enabled)
echo new TermErrorRenderer($fakeException, TermErrorRenderer::OPT_ALL);

// Rendering with all options but no colors
echo new TermErrorRenderer($fakeException, TermErrorRenderer::OPT_ALL ^ TermErrorRenderer::OPT_COLORS);
```


# License

The library is published under the MIT license (see [`LICENSE` file](https://github.com/codeinchq/lib-errordisplay/blob/master/LICENSE)). 
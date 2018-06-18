<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - 2018 - Code Inc. SAS - All Rights Reserved.    |
// | Visit https://www.codeinc.fr for more information about licensing.  |
// +---------------------------------------------------------------------+
// | NOTICE:  All information contained herein is, and remains the       |
// | property of Code Inc. SAS. The intellectual and technical concepts  |
// | contained herein are proprietary to Code Inc. SAS are protected by  |
// | trade secret or copyright law. Dissemination of this information or |
// | reproduction of this material  is strictly forbidden unless prior   |
// | written permission is obtained from Code Inc. SAS.                  |
// +---------------------------------------------------------------------+
//
// Author:   Joan Fabrégat <joan@codeinc.fr>
// Date:     06/12/2017
// Time:     18:54
// Project:  ErrorRenderer
//
namespace CodeInc\ErrorRenderer;
use ReflectionClass;
use Throwable;


/**
 * Class HtmlErrorRenderer
 *
 * @package CodeInc\ErrorDisplay
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class HtmlErrorRenderer extends AbstractErrorRenderer
{
    // Options
    public const OPT_RENDER_FUNC_ARGS = 1024;
    public const OPT_RENDER_CSS = 2048;
    public const OPT_ALL = parent::OPT_ALL | self::OPT_RENDER_FUNC_ARGS | self::OPT_RENDER_CSS;
    public const OPT_DEFAULT = parent::OPT_DEFAULT | self::OPT_RENDER_CSS;

    /**
     * BrowserRenderingEngine constructor.
     *
     * @param Throwable $throwable
     * @param int|null $options
     */
    public function __construct(Throwable $throwable, int $options = null)
    {
        parent::__construct($throwable, $options !== null ? $options : self::OPT_DEFAULT);
    }

    /**
     * Returns the HTML code.
     *
     * @return string
     * @throws \ReflectionException
     */
    public function get():string
    {
        ob_start();
        ?>
        <!-- --------------------------------- EXCEPTION --------------------------------- -->
        <div class="exception-report" data-type="<?=htmlspecialchars(get_class($this->throwable))?>">
            <?
            $this->renderTitle("Error", 4);
            // Renders the main exception
            $this->renderException($this->throwable);

            // Renders the previous exceptions
            if ($this->options & self::OPT_RENDER_BACKTRACE && $previous = $this->throwable->getPrevious()) {
                echo '<div class="exception-previous">';
                $this->renderTitle("Previous exceptions", 2);
                do {
                    $this->renderException($previous);
                }
                while ($previous = $previous->getPrevious());
                echo '</div>';
            }
            $this->renderTitle("End error", 4);

            // Renders the CSS
            $this->renderStyles();
            ?>
        </div>
        <!-- --------------------------------- END EXCEPTION --------------------------------- -->
        <?
        return ob_get_clean();
    }

    /**
     * Renders a title.
     *
     * @param string $title
     * @param int|null $sideSize
     */
    private function renderTitle(string $title, int $sideSize = null):void
    {
        $titleSide = $sideSize ? str_pad("", $sideSize, "-") : null;
        echo '<div class="exception-title">'
            .($titleSide ? "$titleSide " : "")
            .htmlspecialchars($title)
            .($titleSide ? " $titleSide" : "")
            .'</div>';
    }

    /**
     * Renders and exception.
     *
     * @param \Throwable $exception
     * @throws \ReflectionException
     */
    private function renderException(\Throwable $exception):void
    {
        $reflectionClass = new ReflectionClass($exception);
        ?>
        <div class="exception" data-type="<?=htmlspecialchars($reflectionClass->getName())?>">
            <span class="exception-class" title="<?=htmlspecialchars($reflectionClass->getName())?>">
                [<?=htmlspecialchars($reflectionClass->getShortName())?>]
            </span>
            <span class="exception-message">
				<?=htmlspecialchars($exception->getMessage())?>
			</span><br>
            <span class="exception-location">
				<?=htmlspecialchars($exception->getFile()).':'.$exception->getLine()?>
			</span>
            <? $this->renderExceptionTrace($exception) ?>
        </div>
        <?
    }

    /**
     * Renders the Exception backtrace
     *
     * @param \Throwable $exception
     */
    private function renderExceptionTrace(\Throwable $exception):void
    {
        if ($this->options & self::OPT_RENDER_BACKTRACE) {
            ?>
            <div class="exception-trace closed" onclick="this.classList.toggle('closed');">
                <strong>Backtrace</strong>
                <ol>
                    <? foreach ($exception->getTrace() as $item) {
                        echo "<li>";
                        if (isset($item["function"]) && $item["function"]) {
                            echo "<span class='exception-trace-function'>\n";
                            if (isset($item["class"], $item["type"]) && $item["class"] && $item["type"]) {
                                echo htmlspecialchars($item["class"].$item["type"]);
                            }
                            echo htmlspecialchars($item["function"]);
                            echo "(";
                            if ($this->options & self::OPT_RENDER_FUNC_ARGS && isset($item["args"])
                                && is_array($item["args"]) && !empty($item["args"])) {

                                $i = 0;
                                echo "<br>";
                                foreach ($item["args"] as $arg) {
                                    if (!is_string($arg)) {
                                        if (is_object($arg) && method_exists($arg, '__toString')) {
                                            $arg = "\"".htmlspecialchars($arg->__toString())."\"";
                                        }
                                        else {
                                            $arg = "<i>".gettype($arg)."</i>";
                                        }
                                    }
                                    else {
                                        $arg = "\"".htmlspecialchars($arg)."\"";
                                    }
                                    echo "<span class='exception-trace-arg'>$arg";
                                    if (++$i < count($item["args"])) echo ", ";
                                    echo "</span><br>";
                                }
                            }
                            echo ")</span><br>";
                        }
                        if (isset($item['file'])) {
                            echo "<span class='exception-location'>"
                                .htmlspecialchars($item['file']);
                            if (isset($item['line'])) echo ":".$item['line'];
                            echo "</span>\n";
                        }
                        echo "</li>\n";
                    } ?>
                </ol>
            </div>
            <?
        }
    }

    /**
     * Renders the CSS styles.
     */
    private function renderStyles():void
    {
        if ($this->options & self::OPT_RENDER_CSS) {
            foreach ([__DIR__.'/../assets/HtmlErrorRenderer/styles.min.css',
                         __DIR__.'/../assets/HtmlErrorRenderer/styles.css'] as $file) {
                if (file_exists($file)) {
                    echo '<style>';
                    readfile($file);
                    echo '</style>';
                    break;
                }
            }
        }
    }
}
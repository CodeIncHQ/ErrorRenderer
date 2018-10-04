<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE                                               |
// +---------------------------------------------------------------------+
// | Copyright (c) 2017 - Code Inc. SAS - All Rights Reserved.           |
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
// Date:     15/12/2017
// Time:     11:44
// Project:  ErrorRenderer
//
namespace CodeInc\ErrorRenderer;
use Colors\Color;
use Throwable;


/**
 * Class ConsoleErrorRenderer
 *
 * @package CodeInc\ErrorDisplay
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ConsoleErrorRenderer extends AbstractErrorRenderer
{
    // Options
    public const OPT_COLORS = 1024;
    public const OPT_ALL = parent::OPT_ALL | self::OPT_COLORS;
    public const OPT_DEFAULT = self::OPT_ALL;

    // Styles
    private const STYLE_ITALIC = 1;
    private const STYLE_BOLD = 2;
    private const STYLE_CENTER = 4;
    private const STYLE_TITLE = 8;
    private const STYLE_SUBTITLE = 16;

    /**
     * @var int
     */
    private $termCols;

    /**
     * @var Color
     */
    private $termColor;

    /**
     * TerminalRenderingEngine constructor.
     *
     * @param Throwable $throwable
     * @param int|null $options
     */
    public function __construct(Throwable $throwable, int $options = null)
    {
        parent::__construct($throwable, $options !== null ? $options : self::OPT_DEFAULT);
        if (class_exists(Color::class)) {
            $this->termColor = new Color();
        }
    }

    /**
     * Returns a blank line.
     *
     * @param int $count Number of lines to be returned
     * @return string
     */
    private function getBlankLine(int $count = null):string
    {
        $out = "";
        for ($i = 0; $i < ($count ?: 1); $i++) {
            $out .= $this->getLine('');
        }
        return $out;
    }

    /**
     * Returns the number of columns in the terminal windows.
     *
     * @return int
     */
    private function countTermCols():int
    {
        if (!$this->termCols) {
            if (!($this->termCols = (int)exec('tput cols'))) {
                $this->termCols = 80;
            }
        }
        return $this->termCols;
    }

    /**
     * Renders the exception.
     */
    public function get():string
    {
        // renders the error
        $out = "\n"
            .$this->getBlankLine()
            .$this->getLine("Error", self::STYLE_TITLE)
            .$this->getBlankLine()
            .$this->getErrorBlock($this->throwable);

        if ($this->options & self::OPT_RENDER_PREVIOUS_EXCEPTIONS
            && $previous = $this->throwable->getPrevious()) {

            $out .= $this->getBlankLine(3)
                .$this->getLine("Previous errors", self::STYLE_SUBTITLE);
            $isFirst = true;
            do {
                if (!$isFirst) {
                    $out .= $this->getBlankLine(2);
                }
                $out .= $this->getErrorBlock($previous, 2);
                $isFirst = false;
            }
            while ($previous = $previous->getPrevious());
        }

        $out .= $this->getBlankLine()
            .$this->getLine("End error",self::STYLE_TITLE)
            .$this->getBlankLine()
            ."\n";
        return $out;
    }

    /**
     * Returns an error block.
     *
     * @param \Throwable $exception
     * @param int|null $linePadding
     * @return string
     */
    private function getErrorBlock(\Throwable $exception, ?int $linePadding = null):string
    {
        // Renders the message
        $out = $this->getLine(get_class($exception), self::STYLE_BOLD | self::STYLE_ITALIC, $linePadding)
            .$this->getLine($exception->getMessage(), null, $linePadding);

        // Renders the position
        if ($this->options & self::OPT_RENDER_LOCATION) {
            $out .= $this->getLine($exception->getFile().':'.$exception->getLine(),
                    self::STYLE_ITALIC, $linePadding)
                .$this->getBlankLine();
        }

        // Renders the backtrace
        if ($this->options & self::OPT_RENDER_BACKTRACE) {
            $out .= $this->getLine("Backtrace",
                    self::STYLE_BOLD | self::STYLE_ITALIC,$linePadding + 2)
                .$this->getLine($exception->getTraceAsString(),
                    self::STYLE_ITALIC, $linePadding + 2);
        }

        return $out;
    }

    /**
     * Adds padding and line breaks to the content to make it fit inside the terminal windows with a
     * proper word cesure.
     *
     * @param string $content
     * @param int|null $styles Rendering style (see STYLE_XXX class constants)
     * @param int|null $paddingLength
     * @return string
     */
    private function getLine(string $content, int $styles = null, int $paddingLength = null):string
    {
        $termCols = $this->countTermCols();

        // title lines
        if ($styles & self::STYLE_TITLE || $styles & self::STYLE_SUBTITLE) {
            $styles |= self::STYLE_BOLD | self::STYLE_CENTER;
            $titleSide = str_pad("", $styles & self::STYLE_TITLE ? 4 : 2, "-");
            $content = ($titleSide ? "$titleSide " : "").strtoupper($content).($titleSide ? " $titleSide" : "");
        }

        // centering
        if ($styles & self::STYLE_CENTER && strlen($content) < $termCols) {
            $leftPadding = ceil(($termCols - strlen($content)) / 2) + strlen($content);
            $content = str_pad(str_pad($content, $leftPadding, " ", STR_PAD_LEFT),
                $termCols, " ");
        }

        // adding padding
        $paddedContent = "";
        $paddingLength = $paddingLength === null ? 2 : $paddingLength + 2;
        $padding = str_pad("", $paddingLength, " ");
        $availableCols = $termCols - $paddingLength * 2;
        // processing the content by line
        foreach (explode("\n", $content) as $contentLine) {
            $paddedLine = "";

            // processing the content word by word
            foreach (explode(" ", $contentLine) as $word) {
                if (strlen("$paddedLine $word") >= $availableCols) {
                    $paddedContent .= (!empty($paddedContent) ? "\n" : "")
                        .$padding
                        .str_pad($paddedLine, $availableCols, " ")
                        .$padding;
                    $paddedLine = "";
                }
                $paddedLine .= "$word ";
            }

            if (!empty($paddedLine)) {
                $paddedContent .= (!empty($paddedContent) ? "\n" : "")
                    .$padding
                    .str_pad($paddedLine, $availableCols, " ")
                    .$padding;
            }
        }

        // applying colors
        if ($this->options & self::OPT_COLORS && $this->termColor instanceof Color) {
            $paddedContent = $this->termColor->bg('red', $this->termColor->apply('white', $paddedContent));
            if ($styles & self::STYLE_BOLD) {
                $paddedContent = $this->termColor->apply('bold', $paddedContent);
            }
            if ($styles & self::STYLE_ITALIC) {
                $paddedContent = $this->termColor->apply('italic', $paddedContent);
            }
        }
        return "$paddedContent\n";
    }
}
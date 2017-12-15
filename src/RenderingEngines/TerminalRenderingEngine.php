<?php
//
// +---------------------------------------------------------------------+
// | CODE INC. SOURCE CODE - CONFIDENTIAL                                |
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
// Project:  lib-exceptiondisplay
//
namespace CodeInc\ExceptionDisplay\RenderingEngines;
use Colors\Color;


/**
 * Class TerminalRenderingEngine
 *
 * @package CodeInc\ExceptionDisplay\RenderingEngines
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class TerminalRenderingEngine extends AbstractRenderingEngine {
	const DEFAULT_LINE_LENGTH = 80;
	const DEFAULT_CONTENT_SIDE_PADDING = 2;

	/**
	 * @var int
	 */
	private $termCols;

	/**
	 * @var Color
	 */
	private $termColor;

	/**
	 * Returns the Color class for a given string to color terminal messages.
	 *
	 * @param string $text
	 * @return Color
	 */
	private function termColor(string $text):Color {
		if (!$this->termColor instanceof Color) {
			$this->termColor = new Color();
		}
		return $this->termColor->__invoke($text);
	}

	/**
	 * Returns the number of columns in the terminal windows.
	 *
	 * @return int
	 */
	private function getTermCols():int {
		if (!$this->termCols) {
			if (!($this->termCols = (int)exec('tput cols'))) {
				$this->termCols = self::DEFAULT_LINE_LENGTH;
			}
		}
		return $this->termCols;
	}

	/**
	 * Renders the exception.
	 */
	public function render() {
		echo "\n";
		$this->renderTitle("Error", 4);
		$this->renderException($this->getException());

		if ($this->isVerboseModeEnabled() && ($previous = $this->getException()->getPrevious()) !== false) {
			$this->renderBlankLine();
			$this->renderTitle("Previous exceptions", 2);
			$isFirst = true;
			do {
				if (!$isFirst) $this->renderBlankLine(2);
				$this->renderException($previous, 5, 5);
				$isFirst = false;
			}
			while ($previous = $previous->getPrevious());
		}


		$this->renderTitle("End error", 4);
		echo "\n";
	}

	/**
	 * @param \Exception $exception
	 * @param int|null $leftPadding
	 * @param int|null $rightPadding
	 */
	private function renderException(\Exception $exception, int $leftPadding = null, int $rightPadding = null) {
		// Renders the message
		echo $this->termColor($this->getPaddedContent(get_class($exception), $leftPadding, $rightPadding))
				->white->bold->bg_red."\n";
		echo $this->termColor($this->getPaddedContent($exception->getMessage(), $leftPadding, $rightPadding))
				->white->bg_red."\n";

		// Renders the position
		$this->renderBlankLine();
		echo $this->termColor($this->getPaddedContent($exception->getFile().':'.$exception->getLine(),
				$leftPadding, $rightPadding))->white->italic->bg_red."\n";

		// Renders the trace
		if ($this->isVerboseModeEnabled()) {
			echo $this->termColor($this->getPaddedContent($exception->getTraceAsString(),
					$leftPadding, $rightPadding))->white->italic->bg_red."\n";
		}
	}

	/**
	 * Renders a title.
	 *
	 * @param string $title
	 * @param int $sideSize
	 */
	private function renderTitle(string $title, int $sideSize = null) {
		$this->renderBlankLine();
		$titleSide = $sideSize ? str_pad("", $sideSize, "-") : null;
		echo $this->termColor($this->getCenteredContent(
				($titleSide ? "$titleSide " : "")
				.strtoupper($title)
				.($titleSide ? " $titleSide" : "")
			))->white->bold->bg_red."\n";
		$this->renderBlankLine();
	}

	/**
	 * Renders a blank line
	 *
	 * @param int $count
	 */
	private function renderBlankLine(int $count = null) {
		if ($count && $count > 1) {
			for ($i = 0; $i < $count; $i++) {
				$this->renderBlankLine();
			}
		}
		else {
			echo $this->termColor($this->getPaddedContent(''))->white->bold->bg_red."\n";
		}
	}

	/**
	 * Adds padding and line breaks to the content to make it fit inside the terminal windows with a
	 * proper word cesure.
	 *
	 * @param string $content
	 * @param int|null $leftPadding
	 * @param int|null $rightPadding
	 * @return string
	 */
	private function getPaddedContent(string $content, int $leftPadding = null, int $rightPadding = null):string {
		if ($leftPadding === null) $leftPadding = self::DEFAULT_CONTENT_SIDE_PADDING;
		if ($rightPadding === null) $rightPadding = self::DEFAULT_CONTENT_SIDE_PADDING;
		$maxLength = $this->getTermCols() - $leftPadding - $rightPadding;

		$paddedContent = "";
		foreach (explode("\n", $content) as $contentLine) { // processing the content by line
			$paddedLine = "";
			foreach (explode(" ", $contentLine) as $word) { // processing the content word by word
				if (strlen("$paddedLine $word") >= $maxLength) {
					$paddedContent .= (!empty($paddedContent) ? "\n" : "")
						.str_pad("", $leftPadding, " ")
						.str_pad($paddedLine, $maxLength, " ")
						.str_pad("", $rightPadding, " ");
					$paddedLine = "";
				}
				$paddedLine .= "$word ";
			}
			if (!empty($paddedLine)) {
				$paddedContent .= (!empty($paddedContent) ? "\n" : "")
					.str_pad("", $leftPadding, " ")
					.str_pad($paddedLine, $maxLength, " ")
					.str_pad("", $rightPadding, " ");
			}
		}
		return $paddedContent;
	}

	/**
	 * Returns a centered content.
	 *
	 * @param string $content
	 * @return string
	 */
	private function getCenteredContent(string $content):string {
		if (strlen($content) < $this->getTermCols()) {
			$leftPadding = ceil(($this->getTermCols() - strlen($content)) / 2) + strlen($content);
			return str_pad(str_pad($content, $leftPadding," ", STR_PAD_LEFT), $this->getTermCols(), " ");
		}
		return $this->getPaddedContent($content);
	}
}
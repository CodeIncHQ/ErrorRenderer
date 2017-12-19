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
// Date:     06/12/2017
// Time:     18:54
// Project:  lib-gui
//
namespace CodeInc\ErrorDisplay\RenderingEngines;


/**
 * Class ErrorBrowserRenderingEngine
 *
 * @package CodeInc\ErrorDisplay\RenderingEngines
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ErrorBrowserRenderingEngine extends AbstractRenderingEngine {
	/**
	 * Renders the HTML code.
	 */
	public function render() {
		?>
		<!-- --------------------------------- EXCEPTION --------------------------------- -->
		<div class="exception-report" data-type="<?=htmlspecialchars(get_class($this->getException()))?>">
			<?
			$this->renderTitle("Error", 4);
			// Renders the main exception
			$this->renderException($this->getException());

			// Renders the previous exceptions
			if ($previous = $this->getException()->getPrevious()) {
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
	}

	/**
	 * Renders a title.
	 *
	 * @param string $title
	 * @param int|null $sideSize
	 */
	private function renderTitle(string $title, int $sideSize = null) {
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
	 * @param \Exception $exception
	 */
	private function renderException(\Exception $exception) {
		?>
		<div class="exception" data-type="<?=htmlspecialchars(get_class($exception))?>">
			<div class="exception-class"><?=htmlspecialchars(get_class($exception))?></div>
			<div class="exception-message"><?=htmlspecialchars($exception->getMessage())?></div>
			<div class="exception-location"><?=htmlspecialchars($exception->getFile()).':'.$exception->getLine()?></div>
			<div class="exception-trace"><?=nl2br(htmlspecialchars($exception->getTraceAsString()))?></div>
		</div>
		<?
	}

	/**
	 * Renders the CSS styles.
	 */
	private function renderStyles() {
		?>
		<style scoped>
			div.exception-report {
				font-family: "Courier New", monospace;
				background: #97040C;
				border-bottom-width: 5px;
				margin: 15px;
				padding: 15px;
				color: #fff;
				font-size: 14px;
				list-style: decimal;
				text-align: left;
				max-width: 1000px;
				min-width: 400px;
			}
			div.exception-report div.exception-title {
				padding: 3px;
				font-weight: bold;
				text-transform: uppercase;
				text-align: center;
				margin: 15px 0;
			}
			div.exception-report div.exception-title:first-of-type {
				margin-top: 0;
			}
			div.exception-report div.exception-title:last-of-type {
				margin-bottom: 0;
			}
			div.exception-report div.exception {
				margin: 10px;
			}
			div.exception-report div.exception-previous {
				margin: 40px 30px 0;
			}
			div.exception-report div.exception-previous div.exception {
				margin-bottom: 30px;
			}
			div.exception-report div.exception-class {
				font-weight: bold;
			}
			div.exception-report div.exception-location, div.exception-reportdiv.exception-trace {
				font-size: .8em;
				font-style: italic;
				margin-top: 5px;
			}
			div.exception-report div.exception-location {
				margin-top: 20px;
			}
		</style>
		<?
	}
}
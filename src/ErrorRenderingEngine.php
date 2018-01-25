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
// Time:     13:03
// Project:  lib-errordisplay
//
namespace CodeInc\ErrorDisplay;
use CodeInc\ErrorDisplay\RenderingEngines\AbstractRenderingEngine;
use CodeInc\ErrorDisplay\RenderingEngines\ErrorBrowserRenderingEngine;
use CodeInc\ErrorDisplay\RenderingEngines\ErrorTerminalRenderingEngine;
use CodeInc\ErrorDisplay\RenderingEngines\RenderingEngineInterface;
use Throwable;


/**
 * Class ErrorRenderingEngine
 *
 * @package CodeInc\ErrorDisplay
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ErrorRenderingEngine extends AbstractRenderingEngine {
	/**
	 * @var RenderingEngineInterface
	 */
	private $renderingEngine;

	/**
	 * ExceptionRederingEngine constructor.
	 *
	 * @param Throwable $exception
	 * @param bool|null $verboseMode
	 */
	public function __construct(Throwable $exception, bool $verboseMode = null) {
		parent::__construct($exception, $verboseMode);

		// Command line interface
		if (php_sapi_name() == "cli") {
			$this->renderingEngine = new ErrorTerminalRenderingEngine($exception, $verboseMode);
		}

		// Web browser
		else {
			$this->renderingEngine = new ErrorBrowserRenderingEngine($exception, $verboseMode);
		}
	}

	/**
	 * Renders the exception.
	 */
	public function render() {
		$this->renderingEngine->render();
	}
}
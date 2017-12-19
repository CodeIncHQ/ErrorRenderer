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
// Time:     13:03
// Project:  lib-exceptiondisplay
//
namespace CodeInc\ExceptionDisplay;
use CodeInc\ExceptionDisplay\RenderingEngines\AbstractRenderingEngine;
use CodeInc\ExceptionDisplay\RenderingEngines\BrowserRenderingEngine;
use CodeInc\ExceptionDisplay\RenderingEngines\TerminalRenderingEngine;
use CodeInc\ExceptionDisplay\RenderingEngines\RenderingEngineInterface;
use Throwable;


/**
 * Class RenderException
 *
 * @package CodeInc\ExceptionDisplay
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ExceptionRederingEngine extends AbstractRenderingEngine {
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
			$this->renderingEngine = new TerminalRenderingEngine($exception, $verboseMode);
		}

		// Web browser
		else {
			$this->renderingEngine = new BrowserRenderingEngine($exception, $verboseMode);
		}
	}

	/**
	 * Renders the exception.
	 */
	public function render() {
		$this->renderingEngine->render();
	}
}
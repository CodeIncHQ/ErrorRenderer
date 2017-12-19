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
// Time:     13:07
// Project:  lib-errordisplay
//
namespace CodeInc\ErrorDisplay\RenderingEngines;
use Throwable;


/**
 * Class AbstractRenderingEngine
 *
 * @package CodeInc\ErrorDisplay\RenderingEngines
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractRenderingEngine implements RenderingEngineInterface {
	/**
	 * @var Throwable
	 */
	private $exception;

	/**
	 * @var bool
	 */
	private $verboseMode;

	/**
	 * CLIExceptionDisplay constructor.
	 *
	 * @param Throwable $exception
	 * @param bool $verboseMode
	 */
	public function __construct(Throwable $exception, bool $verboseMode = null) {
		$this->setException($exception);
		$this->setVerboseMode($verboseMode ?? true);
	}

	/**
	 * Sets the parent exception.
	 *
	 * @param Throwable $exception
	 */
	protected function setException(Throwable $exception) {
		$this->exception = $exception;
	}

	/**
	 * Returns the exception to be rendered.
	 *
	 * @return Throwable
	 */
	public function getException() {
		return $this->exception;
	}

	/**
	 * Verifies if the verbose mode is enabled.
	 *
	 * @return bool
	 */
	public function isVerboseModeEnabled():bool {
		return $this->verboseMode;
	}

	/**
	 * Sets the verbose mode.
	 *
	 * @param bool $verboseMode
	 */
	protected function setVerboseMode(bool $verboseMode) {
		$this->verboseMode = $verboseMode;
	}

	/**
	 * Returns the view's HTML source code
	 *
	 * @return string
	 */
	public function get():string {
		ob_start();
		$this->render();
		return ob_get_clean();
	}

	/**
	 * Alias of get()
	 *
	 * @see AbstractRenderingEngine::get()
	 * @return string
	 */
	public function __toString():string {
		try {
			return $this->get();
		}
		catch (\Throwable $exception) {
			return "Error: ".$exception->getMessage();
		}
	}
}
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
// Project:  ErrorRenderer
//
namespace CodeInc\ErrorRenderer;
use Throwable;


/**
 * Class AbstractErrorRenderer
 *
 * @package CodeInc\ErrorDisplay
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
abstract class AbstractErrorRenderer implements ErrorRendererInterface {
	// Options
	public const OPT_RENDER_LOCATION = 1;
	public const OPT_RENDER_BACKTRACE = 2;
	public const OPT_RENDER_PREVIOUS_EXCEPTIONS = 4;
	public const OPT_ALL = self::OPT_RENDER_LOCATION | self::OPT_RENDER_BACKTRACE | self::OPT_RENDER_PREVIOUS_EXCEPTIONS;
	public const OPT_DEFAULT = self::OPT_ALL;

	/**
	 * @var Throwable
	 */
	protected $throwable;

	/**
	 * @var int
	 */
	protected $options = [];

	/**
	 * AbstractRenderingEngine constructor.
	 *
	 * @param Throwable $throwable
	 * @param int $options
	 */
	public function __construct(Throwable $throwable, int $options = null) {
		$this->throwable = $throwable;
		$this->options = $options !== null ? $options : self::OPT_DEFAULT;
	}

	/**
	 * Alias of get()
	 *
	 * @see AbstractErrorRenderer::get()
	 * @return string
	 */
	public function __toString():string {
		try {
			return $this->get();
		}
		catch (\Throwable $exception) {
			return "Rendering error: ".$exception->getMessage();
		}
	}
}
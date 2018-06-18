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
// Time:     13:12
// Project:  ErrorRenderer
//
namespace CodeInc\ErrorRenderer;
use Throwable;


/**
 * Class RenderingEngineException
 *
 * @package CodeInc\ErrorDisplay\RenderingEngines
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class ErrorRendererException extends \Exception
{
	/**
	 * @var ErrorRendererInterface
	 */
	private $renderer;

	/**
	 * ErrorRendererException constructor.
	 *
	 * @param string $message
	 * @param ErrorRendererInterface $renderer
	 * @param null|Throwable $previous
	 */
	public function __construct(string $message, ErrorRendererInterface $renderer, ?Throwable $previous = null)
    {
		$this->renderer = $renderer;
		parent::__construct($message, 0, $previous);
	}

	/**
	 * @return ErrorRendererInterface
	 */
	public function getRenderer():ErrorRendererInterface
    {
		return $this->renderer;
	}
}
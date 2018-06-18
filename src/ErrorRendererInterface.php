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
// Time:     11:48
// Project:  ErrorRenderer
//
namespace CodeInc\ErrorRenderer;
use Throwable;


/**
 * Interface ErrorRendererInterface
 *
 * @package CodeInc\ErrorDisplay
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface ErrorRendererInterface
{
	/**
	 * ExceptionRendererInterface constructor.
	 *
	 * @param Throwable $throwable
	 * @param int|null $options
	 */
	public function __construct(\Throwable $throwable, ?int $options = null);

	/**
	 * Returns the code to be displayed.
	 *
	 * @return string
	 */
	public function get():string;

	/**
	 * Alias of get(). Returns the code to be displayed.
	 *
	 * @see ErrorRendererInterface::get()
	 * @return string
	 */
	public function __toString():string;
}

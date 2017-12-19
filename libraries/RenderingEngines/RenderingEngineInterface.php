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
// Time:     11:48
// Project:  lib-errordisplay
//
namespace CodeInc\ErrorDisplay\RenderingEngines;
use Throwable;


/**
 * Interface RenderingEngineInterface
 *
 * @package CodeInc\ErrorDisplay\RenderingEngines
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
interface RenderingEngineInterface {
	/**
	 * Renders the exception.
	 */
	public function render();

	/**
	 * Returns the exception to be rendered.
	 *
	 * @return Throwable
	 */
	public function getException();

	/**
	 * Verifies if the verbose mode is enabled.
	 *
	 * @return bool
	 */
	public function isVerboseModeEnabled():bool;


	/**
	 * Returns the view's HTML source code
	 *
	 * @return string
	 */
	public function get():string;

	/**
	 * Alias of get()
	 *
	 * @see ErrorBrowserRenderingEngine::get()
	 * @return string
	 */
	public function __toString():string;
}

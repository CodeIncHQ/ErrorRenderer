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
// Date:     19/02/2018
// Time:     14:32
// Project:  lib-errordisplay
//
namespace Tests\CodeInc\ErrorDisplay;
use CodeInc\ErrorRenderer\ConsoleErrorRenderer;
use PHPUnit\Framework\TestCase;


/**
 * Class TerminalRenderingEngineTest
 *
 * @author Joan Fabrégat <joan@codeinc.fr>
 */
class TerminalRenderingEngineTest extends TestCase {
	private function getException():\Exception {
		$exception1 = new \Exception("A source exception");
		$exception2 = new \Exception("A child exception", 0, $exception1);
		return new \Exception("A last exception", 1010, $exception2);
	}

	public function testTerminalExceptionRendering():void {
		try {
			echo new ConsoleErrorRenderer($this->getException(), ConsoleErrorRenderer::OPT_ALL);
			$this->assertTrue(true);
		}
		catch (\Throwable $exception) {
			$this->throwException($exception);
			$this->assertTrue(false);
		}
	}
}
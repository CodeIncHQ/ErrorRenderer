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
// Time:     11:47
// Project:  lib-exceptiondisplay
//
require_once __DIR__.'/vendor/autoload.php';
use CodeInc\ExceptionDisplay\ExceptionRederingEngine;

// Creating fake exception
$exception1 = new Exception("A Previous exception");
$exception2 = new Exception("A big error", 0, $exception1);

// Rendering
(new ExceptionRederingEngine($exception2, true))->render();
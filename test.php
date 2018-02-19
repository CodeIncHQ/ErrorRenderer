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
// Time:     14:46
// Project:  lib-errordisplay
//



const OPT2 = 1;
const OPT4 = 2;
const OPT8 = 4;
const OPT16 = 8;
const OPT32 = 16;
const OPTALL = 31;



function testFlags(int $flags) {
	echo "flags: $flags<br><br>";
	foreach ([OPT2, OPT4, OPT8, OPT16, OPT32, OPTALL] as $flag) {
		echo "$flag: ".(($flag & $flags) ? "ok" : "non")."<br>";
	}
}


testFlags(OPT8 | OPT4);
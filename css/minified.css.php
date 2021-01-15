<?php

header('Content-type: text/css');

ob_start('compress'); /* css files for compression */

include('fonts.css');
include('bootstrap.css');
include('style.css');
include('bootstrap-responsive.css'); 

ob_end_flush();

function compress($buffer) {
	$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
	$buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer); 
	/* remove tabs, spaces, newlines, etc. */
	return $buffer;
}

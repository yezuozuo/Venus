<?php
/**
 * Created by PhpStorm.
 * User: zoco
 * Date: 16/8/16
 * Time: 17:06
 */

if(function_exists('errorInfoWeb')) {
    function errorInfoWeb($msg, $content) {
        $info = <<<HTMLS
<html>
<head>
<title>$msg</title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<style type="text/css">
*{
	font-family:		Consolas, Courier New, Courier, monospace;
	font-size:			14px;
}
body {
	background-color:	#fff;
	margin:				40px;
	color:				#000;
}

#content  {
border:				#999 1px solid;
background-color:	#fff;
padding:			20px 20px 12px 20px;
line-height:160%;
}

h1 {
font-weight:		normal;
font-size:			14px;
color:				#990000;
margin: 			0 0 4px 0;
}
</style>
</head>
<body>
	<div id="content">
		<h1>$msg</h1>
		<p>$content</p><pre>
HTMLS;
        $trace = debug_backtrace();
        $info .= str_repeat('-', 100) . "\n";

        /**
         * 回溯错误
         */
        foreach ($trace as $key => $value) {
            if (empty($value['line'])) {
                $value['line'] = 0;
            }
            if (empty($value['class'])) {
                $value['class'] = '';
            }
            if (empty($value['type'])) {
                $value['type'] = '';
            }
            if (empty($value['file'])) {
                $value['file'] = 'unknown';
            }
            $info .= "#$key line:{$value['line']} call:{$value['class']}{$value['type']}{$value['function']}\t file:{$value['file']}\n";
        }
        $info .= str_repeat('-', 100) . "\n";
        $info .= '</pre></div></body></html>';
        echo $info && exit();
    }
}

if(function_exists('errorInfoCli')) {
    function errorInfoCli($msg,$content) {
        echo $msg.$content && exit();
    }
}

if (PHP_OS == 'WINNT') {
    define('NL', "\r\n");
} else {
    define('NL', "\n");
}
define('BL', "<br/>");

if(php_sapi_name() == 'cli') {
    define('SP',NL.'*******************'.NL);
    if(function_exists('errorInfo')) {
        function errorInfo($msg,$content) {
            errorInfoCli($msg,$content);
        }
    }
} else {
    define('SP',BL.'<hr>'.BL);
    if(function_exists('errorInfo')) {
        function errorInfo($msg,$content) {
            errorInfoWeb($msg,$content);
        }
    }
}
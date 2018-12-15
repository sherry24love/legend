<?php
/**
 * 2017年5月5日
 * author email 349017188@qq.com
 * author qq 349017188
 * edit by sherry
 */



/**
 * 将部分字符串用星号替代
 */
if( !function_exists('s_hide_star') ) {
	function s_hide_star( $string , $len , $pos = 'middel' ) {
		substr_replace($string, '****', $start);
	}
}
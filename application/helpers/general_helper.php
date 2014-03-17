<?php

if (!defined('BASEPATH'))
	exit('No direct script access allowed');

/**
 * Generates include javascript
 *
 * @access	public
 * @param	string
 * @param	string
 * @return	string
 */
if (!function_exists('include_js')) {

	function include_js($src = '', $type='text/javascript', $language = '') {
		$CI = & get_instance();
		$scripts = '';
		if (is_array($src)) {
			for ($i = 0; $i < count($src); $i++) {
				$scripts .= '<script src="' . $CI->config->site_url($src[$i]) . '" type="'.$type.'"></script>';
			}
		} else {
			$scripts = '<script src="' . $CI->config->site_url($src) . '" type="'.$type.'"></script>';
		}
		return $scripts;
	}
}

/**
 * Generates show message modified
 *
 * @access	public
 * @param		string
 * @param		string
 * @return	string
 */

if ( ! function_exists('show_message'))
{
	function show_message($msg, $type){
		
		$messge = "";
		$class = "";
		if ( $type === "error" ) {
			$class = "error";
		} elseif ( $type === "success" ) {
			$class = "success";
		} elseif ($type === 'notice'){
			$class = 'notice';
		} else {
			$class = 'warning';
		}
		$messge .= '<dl id="system-message"><dd class="'.$class.' message"><ul><li>';
		$messge .= $msg;
		$messge .='</li></ul></dd></dl>';
		return $messge;
	}
}

/* End of file general_helper.php */
/* Location: ./application/helpers/general_helper.php */

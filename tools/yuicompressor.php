#!/usr/bin/php
<?php
require_once('../application/helpers/assets_helper.php');

$css_files = array();
$js_files = array();

foreach(get_css_files() as $css_file)
{
	if ($css_file['media'] == 'all')
	{
		$css_files[] = '../'.$css_file['path'];		
	}
}

foreach(get_js_files() as $js_file)
{
	$js_files[] = '../'.$js_file['path'];
}

exec ('cat '.implode($css_files, ' ').' | java -jar yuicompressor.jar --type css -o ../css/all.css');
exec ('cat '.implode($js_files, ' ').' | java -jar yuicompressor.jar --type js -o ../js/all.js');
?>
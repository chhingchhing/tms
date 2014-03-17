#!/usr/bin/php
<?php
$languages = array('english' => 'en', 'spanish' => 'es', 'indonesia' => 'id', 'french' => 'fr');

fwrite(STDOUT, "Please enter file: ");
$file = trim(fgets(STDIN));

fwrite(STDOUT, "Please enter key: ");
$key = trim(fgets(STDIN));

fwrite(STDOUT, "Please enter english value: ");
$value = trim(fgets(STDIN));

foreach($languages as $folder=>$code)
{
    $path = dirname(__FILE__).'/../application/language/'.$folder.'/'.$file;
    $transaltedValue = translateTo($value, $code);
	$pair = "\$lang['$key'] = '$transaltedValue';";
	file_put_contents($path, str_replace('?>', "$pair\n?>", file_get_contents($path)));
}

exit(0);

function translateTo($value, $language_key)
{
	if ($language_key == 'en')
	{
		return $value;
	}
	
	return 'NOT_TRANSLATED';
}
?>
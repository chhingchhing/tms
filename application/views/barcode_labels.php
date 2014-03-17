<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title><?php echo lang('items_generate_barcodes'); ?></title>
</head>
<body style="margin: 0;">
<table width='50%' align='center' cellpadding='20'>
<tr>
<?php 
for($k=0;$k<count($items);$k++)
{
	$item = $items[$k];
	$barcode = $item['id'];
	$text = $item['name'];
	
	$style = ($k == count($items) -1) ? 'text-align:center;font-size: 10pt;padding: 1px;' : 'text-align:center;font-size: 10pt;page-break-after: always;padding: 1px;';
	echo "<div style='$style'>".$this->config->item('company')."<br /><img src='".site_url('barcode').'?barcode='.rawurlencode($barcode).'&text='.rawurlencode($text)."&scale=$scale' /></div>";
}
?>
</tr>
</table>
</body>
</html>

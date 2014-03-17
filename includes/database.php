<?php

$connect = mysql_connect('localhost','root','') or die ('Error hosting!');
mysql_select_db('mps_db', $connect) or die ('SQL Error!');

session_start();

?>
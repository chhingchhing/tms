<div id="navigation">
	<ul>
		<?php
		if (isset($_SESSION['username'])) {
			$update = mysql_query("UPDATE tbl_page SET page_status = 1 WHERE page_name='Profile'") or die('Mysql Error with Profile page');
		} else {
			$update = mysql_query("UPDATE tbl_page SET page_status = 0 WHERE page_name='Profile'") or die('Mysql Error with Profile page');
		}
		$menu = mysql_query("SELECT * FROM tbl_page WHERE page_status = 1") or die('Mysql Error');
		$count = count($menu);
		while ($resultMenu = mysql_fetch_array($menu)) {
			if (isset($_GET['id'])) {
				$current = $_GET['id'];
				if ($resultMenu['page_name'] == $current) {
					echo '<li><a href="index.php?id=' . $resultMenu['page_name'] . '" class="active"><span>' . $resultMenu['page_name'] . '</span></a></li>';
				} else if ($resultMenu['page_name'] == (count($resultMenu) - 1)) {
					echo '<li class="last"><a href="index.php?id=' . $resultMenu['page_name'] . '"><span>' . $resultMenu['page_name'] . '</span></a></li>';
				} else {
					echo '<li><a href="index.php?id=' . $resultMenu['page_name'] . '"><span>' . $resultMenu['page_name'] . '</span></a></li>';
				}
				// no action produce by onclick
			} else {
				if ($resultMenu['page_name'] == 'Home') {
//					if (count($resultMenu)==0) {
					echo '<li><a href="index.php?id=' . $resultMenu['page_name'] . '" class="active"><span>' . $resultMenu['page_name'] . '</span></a></li>';
				} else if ($resultMenu['page_name'] == (count($resultMenu) - 1)) {
					echo '<li class="last"><a href="index.php?id=' . $resultMenu['page_name'] . '"><span>' . $resultMenu['page_name'] . '</span></a></li>';
				} else {
					echo '<li><a href="index.php?id=' . $resultMenu['page_name'] . '"><span>' . $resultMenu['page_name'] . '</span></a></li>';
				}
			}
		}
		?>
	</ul>
</div>
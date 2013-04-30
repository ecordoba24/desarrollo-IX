<?php
$debug = true;

/* MYSQL DATABASE CONNECTION DETAILS */

$db_host = "localhost";
$db_user = "root";
$db_pass = "1234";
$db_name = "epa_130430";

$link = @mysql_connect($db_host, $db_user, $db_pass);

if (!$link)
{
	/*
	include("components/maintenance.php");
	if($debug){
		if( mysql_errno() ){
			echo '<br/><b><font color=\'red\'>Mysql error:'.mysql_errno().':</font></b> '.mysql_error()."\n<br/>When executing:<br/>\n$sql\n<br/>";
		} else {
			echo '<br/><b><font color=\'green\'>Query success:</font></b> '.$sql;
		}
		echo "<br/>";
	}
	*/
	echo "Error mysql_connect()";
	exit();
}

$db_selected = @mysql_select_db($db_name);

if (!$db_selected)
{
	/*
	include("components/maintenance.php");

	if($debug){
		if( mysql_errno() ){
			echo '<br/><b><font color=\'red\'>Mysql error:'.mysql_errno().':</font></b> '.mysql_error()."\n<br/>When executing:<br/>\n$sql\n<br/>";
		} else {
			echo '<br/><b><font color=\'green\'>Query success:</font></b> '.$sql;
		}
		echo "<br/>";
	}
	*/
	echo "Error mysql_select_db()";
	exit();
}

mysql_query("SET NAMES 'utf8'");
	// PDO

try {
	$dbh = new PDO('mysql:host='.$db_host.';dbname='.$db_name, $db_user, $db_pass);
} catch (PDOException $e) {
	print "Error: " . $e->getMessage() . "<br />";
	die();
}

?>
<?php

// Page

if (is_int(intval($_GET['page'])) && $_GET['page'] > 0)
	$page = $_GET['page'];
elseif ($page == "all" || $_GET['page'] == "all")
	$page = "";
else
	$page = 1;

	// QUERY COLUMNS

$sql_columns .= "users.id AS user_id ";
//$sql_columns .= "users.user_language, ";

// QUERY TABLES

$sql_tables .= "FROM users ";
//$sql_tables .= "FROM users, profiles, admin_classes_profile_groups, profile_groups ";

// QUERY CONDITIONS

//$sql_conditions .= "WHERE users.id = profiles.user_id ";
//$sql_conditions .= "AND admin_classes_profile_groups.admin_class_id = '".$session_var['admin_class_id']."' ";

$sql = "SELECT ";
$sql .= "users.id ";
$sql .= $sql_tables;
$sql .= $sql_conditions;

$res = mysql_query($sql);
if($debug){
	if( mysql_errno() ){
		echo '<br/><b><font color=\'red\'>Mysql error:'.mysql_errno().':</font></b> '.mysql_error()."\n<br/>When executing:<br/>\n$sql\n<br/>";
	} else {
		echo '<br/><b><font color=\'green\'>Query success:</font></b> '.$sql;
	}
	echo "<br/>";
}

$total_rows = mysql_num_rows($res);
//echo $total_rows."<br/>";

$page_rows = isset($_COOKIE["pagrow"])?$_COOKIE["pagrow"]:10;
//echo $page_rows."<br/>";

$total_pages = ceil($total_rows / $page_rows);
//echo $total_pages."<br/>";

if ($total_pages == 0)
	$total_pages = 1;

//exit();

	// Gets content from database

$sql = "SELECT ";
$sql .= $sql_columns;
$sql .= $sql_tables;
$sql .= $sql_conditions;

/*
$sql .= "ORDER BY ".$sort." ".strtoupper($order)." ";
if ($sort2 != "" && $order2 != "")
	$sql .= ", ".$sort2." ".strtoupper($order2)." ";
*/
	if ($page != "")
		$sql .= "LIMIT ".(($page-1)*$page_rows).", ".$page_rows." ";

	$res = mysql_query($sql);
	if($debug){
		if( mysql_errno() ){
			echo '<br/><b><font color=\'red\'>Mysql error:'.mysql_errno().':</font></b> '.mysql_error()."\n<br/>When executing:<br/>\n$sql\n<br/>";
		} else {
			echo '<br/><b><font color=\'green\'>Query success:</font></b> '.$sql;
		}
		echo "<br/>";
	}
/*
	$count = 0;
	while($row = mysql_fetch_assoc($res))
	{
		// Loads content into arrays

		$profile[$count][$key] = $value;
		$count++;

	}
*/
	var_dump($profile);
	?>
	<table cellspacing="0" >
		<tbody id="inter">
			<?php while($row = mysql_fetch_assoc($res)) { ?>
			<tr>
				<td align="left"><?php echo $row['user_id']; ?></td>
			</tr>
			<?php } ?>
		</tbody>
		<tfoot>
			<tr>
				<td colspan="8">
					<div class="left">
					</div>
					<div class="right">
						<form id="form_goto" name="form_goto" method="get" action="" onsubmit="if (!formValidate(<?php echo $total_pages; ?>)) return false;"> <?php if ($total_pages > 1) { ?>
							<?php echo _GO_TO; ?>: &nbsp;
							<input name="page" type="text" value="<?php echo $page; ?>" id="page" size="2" />
							<a href="#" onclick="document.form_goto.submit()" class="button">Go</a>
							&nbsp;|&nbsp;
							<?php } ?>
							<?php
							$page_rows = isset($_COOKIE["pagrow"])?$_COOKIE["pagrow"]:10;
							echo _SHOW_ROWS ?>:
							<select name="pag" onchange="location.href='components/html/pagerowset.php?pr='+this.value">
								<option value="10" <?php if ($page_rows==10) echo " selected"; ?>>10</option>
								<option value="25" <?php if ($page_rows==25) echo " selected"; ?>>25</option>
								<option value="50" <?php if ($page_rows==50) echo " selected"; ?>>50</option>
								<option value="75" <?php if ($page_rows==75) echo " selected"; ?>>75</option>
								<option value="100" <?php if ($page_rows==100) echo " selected"; ?>>100</option>
							</select>  &nbsp;
							<?php echo $page." / ".$total_pages; ?>
							<?php if ($page > 1) { ?>
							<a href="index.php?section=reports&report=<?php echo $_GET['report']; ?>&item=search_user_search_account&report=<?php echo $_GET['report']; ?>&include_inactives=<?php echo $searchvar['include_inactives']; ?>&find=<?php echo $searchvar['find']; ?>&where=<?php echo $searchvar['where']; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&page=<?php echo $page-1; ?>" class="button"><img src="images/arrow_left.gif" /></a>
							<?php } else { ?>
							<span class="button_simple"><img src="images/arrow_left-1.gif" /></span>
							<?php } ?>

							<?php if ($page < $total_pages) { ?>
							<a href="index.php?section=reports&item=search_user_search_account&report=<?php echo $_GET['report']; ?>&include_inactives=<?php echo $searchvar['include_inactives']; ?>&find=<?php echo $searchvar['find']; ?>&where=<?php echo $searchvar['where']; ?>&sort=<?php echo $sort; ?>&order=<?php echo $order; ?>&page=<?php echo $page+1; ?>" class="button"><img src="images/arrow_right.gif" /></a>
							<?php } else { ?>
							<span class="button_simple"><img src="images/arrow_right-1.gif" /></span>
							<?php } ?>
						</form>
					</div>
				</td>
			</tr>
		</tfoot>
	</table>
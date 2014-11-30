<?php

function logg($message) {
	// Global Variables
	global $configuration;
	
	// Open File for Appending
	$f = fopen($configuration->log_file, 'a');
	
	// Prepare Message
	$text = date("Y-m-d H:i:s") . " " . $message . "\n";
	
	// Append Message to Log
	fputs($f, $text);
	
	// Close File
	fclose($f);
}

function get_user_uid() {
	return (isset($_SESSION['user_id']))? $_SESSION['user_id'] : 0;
}

function user_get_name() {
	$user = new User(get_user_uid());
	return $user->username;
}

function now() {
	return date("Y-m-d H:i:s");
}

function get_query_count($query) {
	# Get Count
	$count_query = substr($query, 0, strpos($query, "SELECT") + 7) . " COUNT(*) " . substr($query, strrpos($query, "FROM"));
	$num_records = MVC::DB()->fetch_single($count_query);
	
	# Return Count
	return $num_records;
}

function paginated_listing($query, $this_page="", $prefix="", $max=20) {
	# Local Variables
	$head = array();
	
	# Get Page Variables
	$page = (isset($_GET[$prefix . 'results_page']))? $_GET[$prefix . 'results_page'] : 1;
	$p = (isset($_GET['p']))? $_GET['p'] : 'home';
	$this_page = (strlen($this_page))? $this_page : "?p=" . $p;
	
	# Get Count
	$num_records = get_query_count($query);
	$num_pages = ceil($num_records / $max);
	
	# Get Starting Record
	$starting_record = ($page - 1) * $max;
	
	# Get Data
	$data = MVC::DB()->fetch($query . " LIMIT {$starting_record}, {$max}");
	
	# Ensure that Data was returned
	if (!sizeof($data)) { return; }
	
	# Construct Body
	$body = array();
	$row_num = 0;
	foreach ($data as $item) {
		$item_arr = get_object_vars($item);
		$body[$row_num] = array();
		foreach ($item_arr as $key => $value) {
			$body[$row_num][] = $value;
		}
		$row_num++;
	}
	
	# Generate Headings
	$obj_data = get_object_vars($item);
	foreach ($obj_data as $item => $content) {
		$head[] = $item;
	}
	
	# Generate Headings
	$headings = "
		<tr>
	";
	foreach ($head as $item) {
		$headings .= "
			<th>{$item}</th>
			";
	}
	$headings .= "
		</tr>
	";
	
	# Generate Rows
	$rows = "";
	foreach ($body as $row) {
		$rows .= "
		<tr>
		";
		foreach ($row as $item) {
			$rows .= "
			<td>{$item}</td>
			";
		}
		$rows .= "
		</tr>
		";
	}
	
	# Output Page selection
	$page_select = "";
	if ($num_records > $max){
		$page_select .= "<script>\n";
		$page_select .= "	function gotoURL(me){\n";
		$page_select .= "		window.location.replace('$this_page&{$prefix}results_page=' + me.value);\n";
		$page_select .= "	}\n";
		$page_select .= "</script>\n";
		$page_select .= "<div align='right' style='padding:0;margin:0;'>\n";
		$page_select .= "	Page : <SELECT name='results_pages' onchange='gotoURL(this);'>\n";
		for ($x = 0; $x < $num_pages; $x++){
			$selected = ($page == ($x + 1))? " SELECTED" : "";
			$page_select .= "		<OPTION value='" . ($x + 1) . "'{$selected}>" . ($x + 1) . "</OPTION>\n";
		}
		$page_select .= "	</SELECT>\n";
		$page_select .= "</div>\n";
	}
	
	# Navigation Buttons
	$buttons = "";
	if ($num_records > $max){
		$previous_link = ($page > 1)? "$this_page&$prefix" . "results_page=" . ($page - 1) : "";
		$next_link = (($page * $_GLOBALS['max_results']) < $num_records)? "$this_page&$prefix" . "results_page=" . ($page + 1) : "";
		$buttons .= "<br>" . nav_buttons($previous_link, $next_link);
	}
	
	# Generate HTML
	$html = "
	{$page_select}
	
	<table class='table' style='margin-top:20px;'>
		{$headings}
		{$rows}
	</table>
	
	{$buttons}
	";
	
	# Return HTML
	return $html;
}

/**
 * Returns the HTML for a next button
 * @param string $link The URL the button will link to.
 * @return string
 */
function next_button($link){
	$button = "<a href='$link'><i class='glyphicon glyphicon-chevron-right'></i></a>";
	return $button;
}

/**
 * Returns the HTML for a previous button
 * @param string $link The URL the button will link to.
 * @return string
 */
function previous_button($link){
	$button = "<a href='$link'><i class='glyphicon glyphicon-chevron-left'></i></a>";
	return $button;
}

/**
 * Generates the HTML for a pair of navigation buttons; Next and Previous.
 * @param string $link_next The URL for the Next Button
 * @param string $link_previous The URL for the Previous Button
 * @param string $align The alignment of the nav buttons. default = 'center'
 * @return string
 */
function nav_buttons($link_previous, $link_next, $align="center"){
	$html = "<table align='center'><tr>";
	if ($link_previous){
		$html .= "<td>" . previous_button($link_previous) . "</td>";
	}
	if ($link_next){
		$html .= "<td>" . next_button($link_next) . "</td>";
	}
	$html .= "</tr></table>";
	return $html;
}

function redirect($location) {
	print "<script>window.location.href = \"{$location}\";</script>";
}


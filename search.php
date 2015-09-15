<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<meta name="description" content="Shmoogle is a search engine that gives you the option to find answers for every simple thing about computers" /> 
<TITLE> Shmoogle-it </TITLE>
  <link type="text/css" rel="stylesheet" href="templates/standard/search.css">
  <!-- suggest script -->
	<style type="text/css">@import url("include/js_suggest/SuggestFramework.css");</style>
	<script type="text/javascript" src="include/js_suggest/SuggestFramework.js"></script>
	<script type="text/javascript">window.onload = initializeSuggestFramework;</script>
  <!-- /suggest script -->
<link href="style.css" rel="stylesheet" type="text/css">
<link href="master.css" rel="stylesheet" type="text/css">
<style type="text/css">
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
</style>
</HEAD>

<body>
<div id="container">
 <div id="header">
<!--(c) 2012 Shmoogle-it-->

<div id="top">
<!--(c) 2012 Shmoogle-it-->
<div id="ball2"></div>
<div id="search"><a style="color:#FFF;" href="http://shmoogle-it.com/" class="talk">Search</a></div>
<div id="ball3"></div>
<div id="toptutorials"><a style="color:#FFF;" href="http://shmoogle-it.com/Top%20tutorials.html">Top Tutorials</a></div>
<div id="ball4"></div>
<div id="about"><a style="color:#FFFFFF" href="http://shmoogle-it.com/About%20Shmoogle.html">About Us</a></div>
<div id="ball5"></div>
<div id="contact"><a style="color:#FFFFFF" href="http://shmoogle-it.com/contact.php">Contact Us</a>

</div>
</div>





<?php


//error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING); 
error_reporting(E_ALL); 
$include_dir = "./include"; 
include ("$include_dir/commonfuncs.php");
//extract(getHttpVars());

if (isset($_GET['query']))
	$query = $_GET['query'];
if (isset($_GET['search']))
	$search = $_GET['search'];
if (isset($_GET['domain'])) 
	$domain = $_GET['domain'];
if (isset($_GET['type'])) 
	$type = $_GET['type'];
if (isset($_GET['catid'])) 
	$catid = $_GET['catid'];
if (isset($_GET['category'])) 
	$category = $_GET['category'];
if (isset($_GET['results'])) 
	$results = $_GET['results'];
if (isset($_GET['start'])) 
	$start = $_GET['start'];
if (isset($_GET['adv'])) 
	$adv = $_GET['adv'];
	
	
$include_dir = "./include"; 
$template_dir = "./templates"; 
$settings_dir = "./settings"; 
$language_dir = "./languages";


require_once("$settings_dir/database.php");
require_once("$language_dir/en-language.php");
require_once("$include_dir/searchfuncs.php");
require_once("$include_dir/categoryfuncs.php");


include "$settings_dir/conf.php";


include "$language_dir/$language-language.php";
include "$template_dir/$template/header.html";

if ($type != "or" && $type != "and" && $type != "phrase") { 
	$type = "and";
}

if (preg_match("/[^a-z0-9-.]+/", $domain)) {
	$domain="";
}


if ($results != "") {
	$results_per_page = $results;
}

if (get_magic_quotes_gpc()==1) {
	$query = stripslashes($query);
} 

if (!is_numeric($catid)) {
	$catid = "";
}

if (!is_numeric($category)) {
	$category = "";
} 



if ($catid && is_numeric($catid)) {

	$tpl_['category'] = sql_fetch_all('SELECT category FROM '.$mysql_table_prefix.'categories WHERE category_id='.(int)$_REQUEST['catid']);
}
	
$count_level0 = sql_fetch_all('SELECT count(*) FROM '.$mysql_table_prefix.'categories WHERE parent_num=0');
$has_categories = 0;

if ($count_level0) {
	$has_categories = $count_level0[0][0];
}




function getmicrotime(){
    list($usec, $sec) = explode(" ",microtime());
    return ((float)$usec + (float)$sec);
    }



function poweredby () {
	global $sph_messages;
    print $sph_messages[''];?> 
    <?php 
}


function saveToLog ($query, $elapsed, $results) {
        global $mysql_table_prefix;
    if ($results =="") {
        $results = 0;
    }
    $query =  "insert into ".$mysql_table_prefix."query_log (query, time, elapsed, results) values ('$query', now(), '$elapsed', '$results')";
	mysql_query($query);
                    
	echo mysql_error();
                        
}

switch ($search) {
	case 1:

		if (!isset($results)) {
			$results = "";
		}
		$search_results = get_search_results($query, $start, $category, $type, $results, $domain);
		require("$template_dir/$template/search_results.html");
	break;
	default:
		if ($show_categories) {
			if ($_REQUEST['catid']  && is_numeric($catid)) {
				$cat_info = get_category_info($catid);
			} else {
				$cat_info = get_categories_view();
			}
			require("$template_dir/$template/categories.html");
		}
	break;
	}

include "$template_dir/$template/footer.html";
?>


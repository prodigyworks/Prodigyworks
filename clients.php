<?php session_start(); 
$theRecords = "";
if(isset($_GET['ourAdmin'])){$theRecords = "yes"; $theRecordsLink = "";}

# EDITED LINE 54 AND LINE 3 AND LINE 1123 ON 20.04.12



$date = date("l jS F Y"); 
$y = date("Y"); 
$m = date("M"); 
include ('MySQL_connect.php');
//check to see if already logged in
if (isset($_SESSION['user_id']))
{
$loggedname = $_SESSION['user_id'];
}
else
{
//if not logged in check for username and password
$username = $_GET['username'];
$password = $_GET['password'];
//if not set redirect to login page
if( ( !$username ) or ( !$password ) )
{
header( "Location:index.php" ); exit(); 
}
 

$referrer = $_GET['referrer'];
if($referrer=='admin')
{
$md5_pass = $password;
$calview='yes';
//echo"admin login.........$md5_pass"; exit();
}
else
{
$md5_pass = md5($password);
//echo'standard login.........'; exit();
} 
///////////////////////
//$md5_pass = md5($password);
$sql = "SELECT * FROM `users` WHERE ((`user_name` = '$username') OR (`email` = '$username')) and password = '$md5_pass' ";
$rs = mysql_query( $sql, $conn ) or die( "Could not execute query" );
$num = mysql_numrows( $rs );
if( $num != 0 )
{ 
$query_client = mysql_query("SELECT * FROM `users` WHERE password='$md5_pass' AND ((`user_name` = '$username') OR (`email` = '$username'))"); 
$row_client = mysql_fetch_array($query_client) or die( "incorrect password" );
$_SESSION['user_id'] = $row_client['user_id'];
$loggedname = $row_client['user_id'];
$name = $row_client['user_name'];
if($theRecords != "yes"){$theRecordsLink = "?filterUser=$name&sentBy=plain";}
$email = $row_client['email'];
$initials = $row_client['initials'];
$staffno = $row_client['staffNo'];
}
else
{
header( "Location:index.php?loginerror=yes" ); exit();  
}
}
/////////////////////

$query_client = mysql_query("SELECT * FROM `users` WHERE user_id='$loggedname' "); 
$row_client = mysql_fetch_array($query_client) or die( "incorrect password" );
$name = $row_client['user_name'];
$email = $row_client['email'];
if($theRecords != "yes"){$theRecordsLink = "?filterUser=$name&sentBy=plain";}
$initials = $row_client['initials'];
$staffno = $row_client['staffNo'];

if(isset($_GET['upcoming'])){$upcoming = $_GET['upcoming'];}
else{$upcoming = 14;}

$_SESSION['nickname'] = $name;

$nickname = isset($_SESSION['nickname']) ? $_SESSION['nickname'] : "Hidden"; 

if($calview){
header( "Location:/diary/crm/" ); exit();  
}





?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title> <?php echo $name ?>'s Diary : Pestokill CRM </title>
<meta http-equiv="Content-Language" content="en-gb" />
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<meta name="ROBOTS" content="NOINDEX,NOFOLLOW" />
<meta name="GOOGLEBOT" content="NOFOLLOW, NOINDEX" /> 
<link rel="stylesheet" href="default.css" type ="text/css" />
<link rel="shortcut icon" href="favicon.ico" />

  <script language="javascript" type="text/javascript">
    <!--
      var httpObject = null;
      var link = "";
      var timerID = 0;
      var nickName = "<?php echo $nickname; ?>";

      // Get the HTTP Object
      function getHTTPObject(){
         if (window.ActiveXObject) return new ActiveXObject("Microsoft.XMLHTTP");
         else if (window.XMLHttpRequest) return new XMLHttpRequest();
         else {
            alert("Your browser does not support AJAX.");
            return null;
         }
      }   

      // Change the value of the outputText field
      function setOutput(){
         if(httpObject.readyState == 4){
            var response = httpObject.responseText;
            var objDiv = document.getElementById("result");
            objDiv.innerHTML += response;
            objDiv.scrollTop = objDiv.scrollHeight;
            var inpObj = document.getElementById("msg");
            inpObj.value = "";
            inpObj.focus();
         }
      }

      // Change the value of the outputText field
      function setAll(){
         if(httpObject.readyState == 4){
            var response = httpObject.responseText;
            var objDiv = document.getElementById("result");
            objDiv.innerHTML = response;
            objDiv.scrollTop = objDiv.scrollHeight;
         }
      }

      // Implement business logic    
      function doWork(){    
         httpObject = getHTTPObject();
         if (httpObject != null) {
            link = "message.php?nick="+nickName+"&msg="+document.getElementById('msg').value;
            httpObject.open("GET", link , true);
            httpObject.onreadystatechange = setOutput;
            httpObject.send(null);
         }
      }

      // Implement business logic    
      function doReload(){    
         httpObject = getHTTPObject();
         var randomnumber=Math.floor(Math.random()*10000);
         if (httpObject != null) {
            link = "message.php?all=1&rnd="+randomnumber;
            httpObject.open("GET", link , true);
            httpObject.onreadystatechange = setAll;
            httpObject.send(null);
         }
      }

      function UpdateTimer() {
         doReload();   
         timerID = setTimeout("UpdateTimer()", 5000);
      }
    
    
      function keypressed(e){
         if(e.keyCode=='13'){
            doWork();
         }
      }
    //-->
    </script>   





<script type="text/javascript" src="crm/js/dtpicker.js"></script>
<script type="text/javascript" src="crm/js/cpicker.js"></script>
<script type="text/javascript" src="crm/js/poptext.js"></script>
<script type="text/javascript" src="crm/js/general.js"></script>

<script type="text/javascript">
<!--
	function open_window(link,w,h) //open popup window script
	{
	    var width = w;
         var height = h;

	    var left = parseInt((screen.availWidth/2) - (width/2));
        var top = parseInt((screen.availHeight/2) - (height/2));
		var win = "width="+w+",height="+h+", top="+top+", left="+left+"; menubar=no,location=no,resizable=no,scrollbars=yes";
		newWin = window.open(link,'newWin',win);
		newWin.focus();
	}
	
// End -->
</script>



<script type="text/javascript">
<!--
function MM_jumpMenu(targ,selObj,restore){ //v3.0
  eval(targ+".location='"+selObj.options[selObj.selectedIndex].value+"'");
  if (restore) selObj.selectedIndex=0;
}
//-->
</script>


<link type="text/css" href="javascript/jquery-ui/css/jquery-ui-1.8.2.custom.css" rel="stylesheet" />
<link rel="stylesheet" href="javascript/tabs/jquery.tabs.css" type="text/css">

<script type="text/javascript" src="javascript/jquery-1.3.2.min.js"></script>

<script language="Javascript" src="javascript/jquery.quicksearch.js"></script>

<script language="Javascript" src="javascript/jquery.validationEngine.js"></script>
<script language="Javascript" src="javascript/jquery.validationEngine-en.js"></script>
<script language="Javascript" src="javascript/main.js"></script>
<script type="text/javascript" src="javascript/jquery-ui/jquery-ui-1.8.2.custom.min.js"></script>
<script type="text/javascript" src="javascript/jquery-ui/jquery-ui-timepicker-addon-0.5.js"></script>

<script type="text/javascript" src="javascript/jquery.timers-1.2.js"></script>
<script type="text/javascript" src="javascript/tabs/jquery.tabs.min.js"></script>


<script type="text/javascript" src="javascript/tooltip.js"></script>

<script type="text/javascript">
  $(function() {
     $('#container-1').tabs();

  });
  
  
</script>



</head>

<body onLoad="UpdateTimer();">

<div id="maincontainer">

<div id="topsection">
<div class="top">
<h4 class="floatR">current user : <?php echo $name ?></h4>
<h4>Pestokill</h4>
</div>
<div class="navBar">

&nbsp;
  
<div class="nav1" style="position:relative; float:left; height:25px; overflow:hidden">
<strong>Change Upcoming Events Period (days):</strong> 
</div>

<div class="nav2" style="position:relative; float:left; height:25px; overflow:hidden">

<form method="get" name="form" id="form">
<table width="300" border="0">
  <tr>
    <td valign="middle">
    <select name="upcoming" id="upcoming" onchange="MM_jumpMenu('parent',this,0)">
    
    <?php echo "<option value=\"clients.php?upcoming=$upcoming\">$upcoming</option>"; ?>
            <?php
	if($upcoming!=7){echo"<option value=\"clients.php?upcoming=7\">7</option>";}		
    if($upcoming!=14){echo"<option value=\"clients.php?upcoming=14\">14</option>";}	
	if($upcoming!=21){echo"<option value=\"clients.php?upcoming=21\">21</option>";}
	if($upcoming!=28){echo"<option value=\"clients.php?upcoming=28\">28</option>";}
	   ?>
    </select>&nbsp;</td>
  </tr>
</table>
  </form></div>

<div class="nav3" style="position:relative; float:right; height:25px; overflow:hidden">
<a href="#x" onclick="x=eventWin('crm/index.php?page=10&surveyer=<?php echo $name?>'); x.focus();return false">Add Event</a>&nbsp; | &nbsp;
<a href="crm?user_admin_name=<?php echo $name?><? echo $theRecordsLink ?>">Calandar View</a>&nbsp; | &nbsp;<a href="search.php">Search</a>&nbsp; | &nbsp;<a href="index.php?logout=yes">Logout</a>
</div>

 
</div>
</div>

</div>

<div id="contentwrapper">
<div id="contentcolumn">
<div class="innertube">
  
<div id="container-1" class="commentBlock">
  <ul>
      <li><a href="#events"><span>Events</span></a></li>
      <li><a href="#surveys"><span>Surveys</span></a></li>
      <li><a href="#tasks"><span>Tasks</span></a></li>
  </ul>

<div id="events">
<h1><?php echo $name ?>'s Diary (upcoming appointments)</h1><?php

/* $hn = "localhost";
$db = "web123-pesto";
$un = "web123-pesto";
$pw = "kill9775"; */

        
$hn = "localhost";
$db = "web121-diary-1";
$un = "web121-diary-1";
$pw = "pesto9775";
/*
                            $hn = "localhost";
                            $db = "web121-diary-1";
                            $un = "root";
                            $pw = "root";*/
$timeZone = "Europe/London";
$rowsToShow = 3;
$weeksToShow = 5;
$upcomingDays = $upcoming;
$startHour = 6;
$language = "crm/lang/en_us.php";
require $language;

error_reporting(E_ALL & ~E_NOTICE );

// Current LuxCal version
define("LCV","1.4");

// set time zone
date_default_timezone_set($timeZone);

if (!$link) {
	// start session
	//session_set_cookie_params(60*60*24*30); //expire time 30 days
	//session_start();
	//header("Cache-control: private");

	//if (!$_SESSION['user_id']) $_SESSION['user_id'] = 1; // 1 = public access

	// establish database connection
	$link = mysql_connect ($hn, $un, $pw) or die ("Could not connect to database, try again later");
	mysql_select_db($db,$link);

	//	set permissions
	$query = mysql_query("SELECT user_name, sedit, post, view FROM users WHERE user_id = ".$_SESSION["user_id"]." limit 1");
	if ($row = mysql_fetch_row($query)) {
		$uname = $row[0];
		$sedit = ( $row[1] ) ? true : false;
		$spost = ( $row[2] or $sedit ) ? true : false;
		$sview = ( $row[3] or $spost ) ? true : false;
	} else { $view = $sedit = $spost = false; }
}
if ($_POST["newDate"]) {
	if (preg_match("%([0-9]{1,2})[\/\.-]+([0-9]{1,2})[\/\.-]+([0-9]{4})%",$_POST["newDate"],$dater)) {
		$cDate = $dater[3]."-".str_pad($dater[2], 2, "0", STR_PAD_LEFT)."-".str_pad($dater[1], 2, "0", STR_PAD_LEFT);
	}
} else {
	$cDate = ($_REQUEST["cDate"]) ? $_REQUEST["cDate"] : date("Y-m-d"); // to date  (yyyy-mm-dd)
}

//if no access, login required
if (!$sview) {
	$page = 9;
	$msg = $xx["idx_log_in"];
}

eval($pages[$page][1]);
$pageTitle = $pages[$page][3];

//require ($pages[$page][2] == "S") ? 'crm/canvas/header_s.php' : 'crm/canvas/header.php';
//echo "page: ".$_REQUEST["page"]." date: ".$_REQUEST["evDate"]." start: ".$_REQUEST["evTimeS"]."end: ".$_REQUEST["evTimeE"];
//require $pages[$page][0];
//require 'canvas/footer.php';

function showGrid($date) {
	global $wkDays, $title, $start_time, $end_time, $venue, $description, $color, $background, $xx;
	if ($start_time[$date]) {
		ksort($start_time[$date]);
		$curT = mktime(0,0,0,substr($date,5,2),substr($date,8,2),substr($date,0,4));
		echo "<br /><h5>".$wkDays[date("N", $curT)-1]." ".date("j F Y", $curT)."</h5>\n";
		while (list($t) = each($start_time[$date])) {
			while (list($id,$time) = each($start_time[$date][$t])) {
				echo "<table width=\"100%\" class=\"point\" onclick=\"x=eventWin('crm/index.php?page=11&amp;id=".$id."'); x.focus(); return false\">";
				
				
				$q_user = mysql_query("SELECT * FROM `events` WHERE `event_id` = '$id'"); 
			$r_user = mysql_fetch_array($q_user) or die(mysql_error());
		$user = $r_user['user_id'];
		$comment = $r_user['comment'];
		$price = $r_user['price'];
	$eml = $r_user['eml'];
	$tel = $r_user['tel'];
	$postcode = $r_user['postcode'];			
	$rawdesc=stripslashes($description[$id]);
	
	$contact = $r_user['contact'];
$completed = $r_user['completed'];

if($completed=='1'){$comp='<img src="completed.png" width="60" height="40" alt="completed" />';}
else{$comp='&nbsp;';}


if($rawdesc){
$desc = str_replace("�", "", $rawdesc);
$newdesc="<p><strong>Description</strong><br/>$desc</p>";
}

if($comment){
$com = str_replace("�", "", $comment);
$comm ="<p><strong>Comment : </strong>$com</p>";
}

if($price){
$price = str_replace("�", "", $price);
$pr ="&nbsp; | &nbsp;<strong>Price : </strong>$price";
}

if($eml){
$em ="<p><strong>Email : </strong><a href=\"mailto:$eml\">$eml</a></p>";
}


if($tel){
$tell ="<p><strong>Telephone : </strong>$tel</p>";
}			
				
				
				echo "<tr><td width=\"90px\">";
				echo ($time == "" and $end_time[$date][$t][$id] == "") ? $xx["upc_all_day"] : $time;
				if ($end_time[$date][$t][$id]) echo " - ".$end_time[$date][$t][$id];
				echo "</td><td><h5";
				if ($color[$id]) echo " style=\"color: ".$color[$id]."; background: ".$background[$id].";\"";
				echo ">&nbsp;";
				echo stripslashes($title[$id])."</h5></td></tr>\n";
		
		
			if ($venue[$id]) { echo "<tr><td>&nbsp;</td><td><strong>Location:</strong> ".stripslashes($venue[$id])."{$pr}</td></tr>\n"; }
				//if ($description[$id]) { echo "<tr><td>&nbsp;</td><td>{$newdesc}{$tell}{$comm}</td></tr>\n"; }
				
				 echo "<tr><td align=\"center\" valign=\"middle\">$comp</td><td>{$newdesc}{$tell}{$comm}</td></tr>\n";
				 
				 
				echo "</table>\n<table width=\"100%\" border=\"0\">\n<tr>\n<td width=\"90px\">&nbsp;</td>\n<td>{$em}</td>\n</tr>\n";
				
				
								echo "</table>\n<table width=\"100%\" border=\"0\">\n<tr>\n<td width=\"90px\">&nbsp;</td>\n<td>";
				
					if($postcode){echo"<a class=\"mls\" href=\"javascript:open_window('crm/map.php?ref=$id&amp;upx=yes',520, 600)\">view map</a>";}
			
				
			echo "</td>\n</tr>\n</table>\n<br/><hr><br/>";
				
				
			}
		}
	}
}

global $start_time;

require 'crm/views/retrieve2.php';

if (!$upcomingDays) $upcomingDays = 7;

$cTime = mktime( 0, 0, 0, substr($cDate,5,2), substr($cDate,8,2), substr($cDate,0,4) ); // Unix time of current date
$eTime = $cTime + (($upcomingDays-1) * 86400); // Unix time of end date

retrieve($cDate, date("Y-m-d", $eTime));

if ($start_time) {
	echo "<div class=\"container\">";
	echo "<div class=\"eventBg\">\n";
	echo '<h4>',$wkDays[date('N', $cTime)-1]," ",date('d.m.Y', $cTime)," - ",$wkDays[date('N', $eTime)-1]," ",date('d.m.Y', $eTime),'</h4>'."\n<br />\n";
	ksort($start_time);
	while (list($k) = each($start_time)) {
		showGrid($k);
	}
	echo "</div><br />";
	echo "</div>";
}
?>


</div>

<div id="surveys">
<h3>My Survey list</h3>
<?php

if (!isset($_SESSION['adminlev03'])){
  $where = "where surveyor = '".$_SESSION['cal4585495admin']."' || surveyor = '".$_SESSION['user_name']."'";
}
$q = "select * from survey $where";
$master_records = msq_select_many($q);

if($master_records){
	foreach($master_records as $master){
		
    echo '<div style="float:right;">
					<a href="delete_survey.php?id='.$master['survey_id'].'"><img src="images/close.png" width="16" /></a><br /><br />
					<a href="#x" onclick="x=eventWin(\'crm/admin4854.php?page=22&survey_id='.$master['survey_id'].'\'); x.focus();return false"><img src="user_edit.png" /></a>
					</div>';
				
		echo '<table cellpadding="8" cellspacing="0" border="1" width="95%">';
		
		echo '<tr bgcolor="cccccc">';
		echo '<td width="20%"><b> Survey ID</b></td>';
		echo '<td width="30%">&nbsp;'.$master['recordid'].'</td>';
		echo '<td width="20%"><b> Surveyor</b></td>';
		echo '<td width="30%">&nbsp;'.$master['surveyor'].'</td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '<td width="20%"><b> Service Type</b></td>';
		echo '<td width="30%">&nbsp;'.$master['service_type'].'</td>';
		echo '<td width="20%"><b> Requirement</b></td>';
		echo '<td colspan="3">&nbsp;'.$master['requirement'].'</td>';
		
		echo '</tr>';
		echo '<td width="20%"><b> Specific Requirements</b></td>';
		echo '<td width="30%">&nbsp;'.$master['interest'].'</td>';
		echo '<tr>';
		
		echo '</tr>';
		
		echo '<tr>';
		echo '<td width="20%"><b> Int Monitors</b></td>';
		echo '<td width="30%">&nbsp;'.$master['int_monitor'].'</td>';
		echo '<td width="20%"><b> Ext Monitors</b></td>';
		echo '<td width="30%">&nbsp;'.$master['ext_monitor'].'</td>';
		echo '</tr>';
		
		
		
		echo '<tr>';
		echo '<td width="20%"><b> Pest Serv Freq</b></td>';
		echo '<td width="30%">&nbsp;'.$master['past_serv_freq'].'</td>';
		echo '<td width="20%"><b> Fly Killers Efk</b></td>';
		echo '<td width="30%">&nbsp;'.$master['fly_killers_efk'].'</td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '<td width="20%"><b>Fly Killers Glue</b></td>';
		echo '<td width="30%">&nbsp;'.$master['fly_killers_glue'].'</td>';
		echo '<td width="20%"><b>Moth Traps</b></td>';
		echo '<td width="30%">&nbsp;'.$master['moth_traps'].'</td>';
		echo '</tr>';
		
		
		echo '<tr>';
		echo '<td width="20%"><b> Wasp Traps</b></td>';
		echo '<td width="30%">&nbsp;'.$master['wasp_traps'].'</td>';
		echo '<td width="20%"><b> Flying Serv Freq</b></td>';
		echo '<td width="30%">&nbsp;'.$master['flying_serv_freq'].'</td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '<td width="20%"><b> Audit</b></td>';
		echo '<td width="30%">&nbsp;'.$master['audit'].'</td>';
		echo '<td width="20%"><b> Charge</b></td>';
		echo '<td width="30%">&nbsp;'.$master['charge'].'</td>';
		echo '</tr>';
		
			echo '<tr>';
		echo '<td width="20%"><b>Time on Site</b></td>';
		echo '<td width="30%">&nbsp;'.$master['start_time'].'</td>';
		echo '<td width="20%"><b> No. of Men</b></td>';
		echo '<td width="30%">&nbsp;'.$master['no_of_men'].'</td>';
		echo '</tr>';
			
		echo '<tr>';
		echo '<td width="20%"><b> Accepted</b></td>';
		echo '<td width="30%">&nbsp;'.$master['accepted'].'</td>';
		echo '<td width="20%"><b> Start Date</b></td>';
		echo '<td width="30%">&nbsp;'.$master['start_date'].'</td>';
		echo '</tr>';
		
		
		echo '<tr>';
		echo '<td width="20%"><b> Raised Date</b></td>';
		echo '<td width="30%">&nbsp;'.$master['date_raised'].'</td>';
		echo '<td width="20%"><b> Followup Date</b></td>';
		echo '<td width="30%">&nbsp;'.$master['follow_up'].'</td>';
		echo '</tr>';
		
		
	
		
		if($master['file_1']) {
				$f1_extn = '.'.end(explode('.', $master['file_1']));
				$file_1_name = 'file_1_'.$master['survey_id'].$f1_extn;
			}
			
			if($master['file_2']) {
				$f2_extn = '.'.end(explode('.', $master['file_2']));
				$file_2_name = 'file_2_'.$master['survey_id'].$f2_extn;
			}
			
			if($master['file_3']) {
				$f3_extn = '.'.end(explode('.', $master['file_3']));
				$file_3_name = 'file_3_'.$master['survey_id'].$f3_extn;
			}
		
		echo '<tr>';
		echo '<td width="20%"><b> Files</b></td>';
		echo '<td colspan="3">
						<a target="_blank" href="../quotes/'.$file_1_name.'">'.$master['file_1'].'</a><br />
						<a target="_blank" href="../quotes/'.$file_2_name.'">'.$master['file_2'].'</a><br />
						<a target="_blank" href="../quotes/'.$file_3_name.'">'.$master['file_3'].'</a><br />
					</td>';
		echo '</tr>';
		
		echo '<tr>';
		echo '<td width="20%"><b> Survey Status</b></td>';
		echo '<td width="30%">&nbsp;'.$master['surveystatus'].'</td>';
		echo '</tr>';
		
		echo '</table>';
		echo '<br/><br/>';
	}
}

  ?>


</div>

<div id="tasks">
     <?php
                            function surveyer_list() {
                                $q = "SELECT * from users order by user_name";
                                $query = mysql_query($q);
                                if (!$query) echo "Database Error : ".$q."<br />";
                                else {
                                    while ($row=mysql_fetch_array($query)) {
                                        echo "<option value=\"".$row['user_name']."\" >".$row['user_name']."</option>\n";
                                    }
                                }
                            }

                            function account_code() {
                                $q = "SELECT * from master order by recordid";
                                $query = mysql_query($q);
                                if (!$query) echo "Database Error : ".$q."<br />";
                                else {
                                    while ($row=mysql_fetch_array($query)) {
                                        echo "<option value=\"".$row['recordid']."\" >".$row['recordid']."</option>\n";
                                    }
                                }
                            }

                            if($_POST['task_submit']) {
                                if(!$_POST['is_private']) $is_private = 0;
                                else $is_private = 1;

                                if($_FILES['file']['name']) {
                                    $fl_name = $_FILES['file']['name'];
                                    $extn = '.'.end(explode('.', $fl_name));
                                }

                                $q = "insert into task_list set
        logged_by_id = '".$_SESSION['cal4585495admin']."'
        , logged_by = '".$_POST['logged_by']."'
        , date_logged = '".date('Y-m-d')."'
        , assigned_to = '".$_POST['assigned_to']."'
        , task_description = '".$_POST['task_description']."'
        , priority = '".$_POST['priority']."'
        , account_code = '".$_POST['account_code']."'
        , comments = '".$_POST['comments']."'
        , items_needed = '".$_POST['items_needed']."'
        , status = '".$_POST['status']."'
        , is_private = '".$is_private."'
        , file = '".$fl_name."'
        , date_in = '".$_POST['date_in']."'";


                                                                $priority_text_mail = '000000';
                                                                $status_text_mail = '000000';

                                                                if($_POST['priority'] == 1) {
                                                                    $priority_color_mail = 'red';
                                                                    $_POST['priority'] = '1 - Urgent & Important- 3 days';
                                                                } else if($_POST['priority'] == 2) {
                                                                    $priority_color_mail = 'amber';
                                                                    $_POST['priority'] = '2 - Import not Urgent within 7 days';
                                                                } else if($_POST['priority'] == 3) {
                                                                    $priority_color_mail = 'blue';
                                                                    $priority_text_mail = 'ffffff';
                                                                    $_POST['priority'] = '3 - Important 15 days';
                                                                } else if($_POST['priority'] == 4) {
                                                                    $priority_color_mail = 'green';
            $_POST['priority'] = '4 - Not Important 30 days';
        }
 if($_POST['status'] == 'In progress') {
            $status_color_mail = 'red';
        } else if($_POST['status'] == 'Delayed') {
            $status_color_mail = 'amber';
        } else if($_POST['status'] == 'More info needed') {
            $status_color_mail = 'blue';
            $status_text_mail = 'ffffff';
        } else if($_POST['status'] == 'Done') {
            $status_color_mail = 'green';
        }
$privateval="No";
if($is_private==1){
$privateval="Yes";
}
                                $query = mysql_query($q);
                                $tl_id = mysql_insert_id();


                                if($_FILES['file']['name']) {
                                    move_uploaded_file($_FILES['file']['tmp_name'], 'quotes/tl_file_'.$tl_id.$extn);
                                }

                                require_once('class.phpmailer.php');

                                $q = "SELECT email from users where user_name = '".$_POST['assigned_to']."' limit 1";
                                $user_info = msq_select_one($q);



                                $from = "webserver@pestokill.co.uk"; // from email address
                                $from_name = "Webserver"; // from name
                                 $to = $user_info['email']; // email address 
 
                                $to_name = $user_info['user_name']; //to name

$filepath ='quotes/tl_file_'.$tl_id.$extn;
                                //$subject = "Submission from Website";
                                $htmltext="<html><body>A new task has been assigned to you <br /><br />
  <div> <table cellpadding=\"8\" cellspacing=\"0\" border=\"1\" width=\"95%\">
                                                            <tr>
                                                                <td valign=\"top\" width=\"20%\"><b>Logged By</b></td><td valign=\"top\">".$_POST['logged_by'] ."</td>
                                                                <td valign=\"top\" width=\"20%\"><b>Logged Date</b></td><td valign=\"top\">".$_POST['date_logged'] ."</td>
                                                            </tr>
															<tr>
                                                                <td valign=\"top\"><b>Assigned to</b></td><td valign=\"top\">".$_POST['assigned_to'] ."</td>
                                                                <td valign=\"top\"><b>Priority</b></td><td valign=\"top\" bgcolor=\"".$priority_color_mail ."\" style=\"color:#".$priority_text_mail ."\">".$_POST['priority'] ."</td>
                                                            </tr><tr>
                                                                <td valign=\"top\"><b>Task Description</b></td><td colspan=\"3\" valign=\"top\">".$_POST['task_description'] ."</td>
                                                            </tr><tr>
                                                                <td valign=\"top\"><b>Account Code</b></td><td valign=\"top\">".$_POST['account_code'] ."</td>
                                                                <td valign=\"top\"><b>Status</b></td><td valign=\"top\" bgcolor=\"".$status_color_mail ."\" style=\"color:#".$status_text_mail ."\">".$_POST['status'] ."</td>
                                                            </tr>
															<tr>
                                                                <td valign=\"top\"><b>Comments</b></td><td colspan=\"3\" valign=\"top\">".$_POST['comments'] ."</td>
                                                            </tr><tr>
                                                                <td valign=\"top\"><b>Items Needed</b></td><td colspan=\"3\" valign=\"top\">".$_POST['items_needed'] ."</td>
                                                            </tr>
															<tr>
                                                                <td valign=\"top\"><b>Private</b></td><td valign=\"top\">". $privateval."</td>
                                                                <td valign=\"top\"><b>File</b></td>
                                                                <td valign=\"top\"><a target=\"_blank\" href=\"http://www.pestokill.co.uk/diary/".$filepath."\">".$fl_name ."</a></td>
                                                            </tr>
                                                        </table>
</div></body></html>";
    
                              
                                $Mailer = New PHPMailer;

    $Mailer->From = $from;
    $Mailer->FromName = $from_name;

    $Mailer->AddAddress($to, $to_name);

    $Mailer->Subject = 'New task assigned';
    $Mailer->ContentType = "text/html";
    $Mailer->Body = $htmltext;

    $Mailer->Send();
}
?>
      <style>
                                input.submit_button {
                                    background-color: #FFFFFF;
                                    border-color: #666666;
                                    border-style: solid;
                                    border-width: 1px;
                                    color: #666666;
                                    cursor: pointer;
                                    font-size: 12px;
                                    padding: 4px 8px;
                                }

                                td {
                                    padding: 2px;
                                }
                            </style>

                            <script type="text/javascript">

                                function build_qtip(task_id, message){
                                    // Only create tooltips when document is ready
                                    $('#'+task_id).qtip({

                                        content: message,

                                        show: { solo: true },

                                        hide: { fixed : true, delay: 2000 },

                                        position: {
                                            corner: {
                                                type: 'relative',
                                                target: 'bottomRight',
                                                tooltip: 'topLeft'
                                            }
                                        },

                                        style: {
                                            width: 200,
                                            padding: 5,
                                            background: '#ffffff',
                                            color: '#000',
                                            textAlign: 'left',
                                            border: {
                                                width: 4,
                                                radius: 4,
                                                color: '#42577B'
                                            },

                                            tip: true,
                                            name: 'blue' // Inherit the rest of the attributes from the preset dark style
                                        }
                                    })
                                }

                            </script>
 <h3>Add task</h3>
                            <form name="task_list" method="post" enctype="multipart/form-data">
                                <input type="hidden" name="task_submit" value="new">
                                    <table width="100%" cellpadding="5px" cellspacing="4px" border="0" bgcolor="#cccccc" style="padding:2px;">
                                        <tr>
                                            <td>Logged By:</td><td><select name="logged_by" id="logged_by" style="width:110px;"><?php surveyer_list(); ?></select></td>
                                            <td>Assigned to:</td><td><select name="assigned_to" id="assigned_to" style="width:110px;"><?php surveyer_list(); ?></select></td>
                                        </tr><tr>
                                            <td valign="top">Task Description:</td><td colspan="3"><textarea name="task_description" style="width:98%;" ></textarea></td>
                                        </tr><tr>
                                            <td>Priority:</td>
                                            <td>
                                                <input type="radio" name="priority" value="1" id="priority_1" > 1&nbsp;&nbsp;
                                                    <input type="radio" name="priority" value="2" id="priority_2" > 2&nbsp;&nbsp;
                                                        <input type="radio" name="priority" value="3" id="priority_3" > 3&nbsp;&nbsp;
                                                            <input type="radio" name="priority" value="4" id="priority_4" > 4&nbsp;&nbsp;
                                                                <script type="text/javascript">
                                                                    build_qtip('priority_1', 'Urgent & Important- 3 days');
                                                                    build_qtip('priority_2', 'Import not Urgent within 7 days');
                                                                    build_qtip('priority_3', 'Important 15 days');
                                                                    build_qtip('priority_4', 'Not Important 30 days');
                                                                </script>
                                                                </td>
                                                                <td>Account Code:</td><td><select name="account_code" id="account_code" style="width:110px;"><?php account_code(); ?></select></td>
                                                            </tr><tr>
                                                            <td valign="top">Comments:</td><td colspan="3"><textarea name="comments" style="width:98%;" ></textarea></td>
                                                        </tr><tr>
                                                            <td valign="top">Items Needed:</td><td colspan="3"><textarea name="items_needed" style="width:98%;" ></textarea></td>
                                                        </tr><tr>
                                                            <td>Status:</td>
                                                            <td>
                                                                <select name="status" id="status">
                                                                    <option value="In progress"<?php echo ($_POST["notify"] == "1") ? ' selected="true">' : ">";?>In progress</option>
                                                                    <option value="Delayed"<?php echo ($_POST["notify"] == "2") ? ' selected="true">' : ">";?>Delayed</option>
                                                                    <option value="More info needed"<?php echo ($_POST["notify"] == "3") ? ' selected="true">' : ">";?>More info needed</option>
                                                                    <option value="Done"<?php echo ($_POST["notify"] == "4") ? ' selected="true">' : ">";?>Done</option>
                                                                </select>
                                                            </td>
                                                            <td>Private:</td><td><input type="checkbox" name="is_private" value="1" /></td>
                                                        </tr><tr>
                                                            <td>Upload File:</td><td colspan="3"><input type="file" name="file" /></td>
                                                        </tr><tr>
                                                            <td>Date In:</td><td colspan="3"><input type="text" name="date_in" id="date_in" size="10" /><input class="button" type="button" title="<?php echo $xx["select_date"]; ?>" value=" change date " id="btn1" /></td>
                                                        </tr>
                                                        <tr><td colspan="4"><hr style="color:#9B9BDD;margin-top:5px;margin-bottom:10px;" /></td></tr>
                                                        <tr><td colspan="4"><input class="submit_button" type="submit" name="add_task" value="Add Task" /></td></tr>
                                                        </table>

                                                        <script type="text/javascript">//<![CDATA[
                                                            Calendar.setup({
                                                                inputField : "date_in",
                                                                trigger    : "btn1",
                                                                onSelect   : function() { this.hide() },
                                                                showTime   : 12,
                                                                dateFormat : "%Y-%m-%d"
                                                            });
                                                            //]]></script>

                                                        </form>

<?php  

// MYSQL SPECIFIC STUFF

function msq_select_many($query) {
  $rs = mysql_query($query);
  if(mysql_error()) trigger_error(mysql_error(), mysql_errno());
  if(!$rs) {
    return FALSE;
    
  } elseif(!mysql_num_rows($rs)) {
    return FALSE;

  } else {
    while ($row = mysql_fetch_array($rs, MYSQL_ASSOC)) $row_list[]=$row;
    return $row_list;
  }
}

/**
*   Generic SELECT query
*/
function msq_select_one($query) {
  $rs = mysql_query($query);
  if(mysql_error()) trigger_error(mysql_error(), mysql_errno());
  if(!$rs) {
  
    return FALSE;
    
  } elseif(!mysql_num_rows($rs)) {
    return FALSE;

  } else {
    $row = mysql_fetch_array($rs, MYSQL_ASSOC);
    return $row;
  }
}


/**
*   Generic UPDATE query
*/
function msq_update($query) {
  $success = mysql_query($query);
  if(mysql_error()) trigger_error(mysql_error(), mysql_errno());
  if(!$success) {
   
  }
  return $success;
}

if (!isset($_SESSION['adminlev03'])){
  $where = "where assigned_to = '".$_SESSION['cal4585495admin']."' || logged_by_id = '".$_SESSION['cal4585495admin']."' || logged_by = '".$_SESSION['cal4585495admin']."'";
}
$q = "select * from task_list $where";
$task_list = msq_select_many($q);

?>

<h3>My task list</h3>
<div style="float:right;">
<input class="submit_button" type="button" value="Export All" onclick="document.location.href='Export.php'" />
</div>
<br clear="both" />
<br />
<?php
if($task_list){
  foreach($task_list as $task){
    
    if($task['file']) {
      $f3_extn = '.'.end(explode('.', $task['file']));
      $file_name = 'tl_file_'.$task['id'].$f3_extn;
    }
    
  if($task['logged_by_id'] == $_SESSION['cal4585495admin'] || isset($_SESSION['adminlev03'])
      || $task['assigned_to'] == $_SESSION['cal4585495admin'] || $task['logged_by'] == $_SESSION['cal4585495admin']){
  ?>
  <div style="float:right;">
  
  <?php
  if($task['logged_by_id'] == $_SESSION['cal4585495admin'] || isset($_SESSION['adminlev03'])
        || $task['assigned_to'] == $_SESSION['cal4585495admin'] || $task['logged_by'] == $_SESSION['cal4585495admin']){
  ?>
  <a href="delete_task.php?id=<?php echo $task['id'] ?>"><img src="images/close.png" width="16" /></a><br /><br />
  <?php
  }
  ?>
  
  <a href="#x" onclick="x=eventWin('crm/admin4854.php?page=21&task_id=<?php echo $task['id'] ?>'); x.focus();return false"><img src="user_edit.png" /></a>
  </div>
  <?php
  }
    
	$priority_text = '000000';
  $status_text = '000000';
  $priority_color = 'ffffff';

  
  if($task['priority'] == 1){
    $priority_color = 'red';
    $task['priority'] = '1 - Urgent & Important- 3 days';
  } else if($task['priority'] == 2){
    $priority_color = 'amber';
    $task['priority'] = '2 - Import not Urgent within 7 days';
  } else if($task['priority'] == 3){
    $priority_color = 'blue';
    $priority_text = 'ffffff';
    $task['priority'] = '3 - Important 15 days';
  } else if($task['priority'] == 4){
    $priority_color = 'green';
    $task['priority'] = '4 - Not Important 30 days';
  }
  
  if($task['status'] == 'In progress'){
    $status_color = 'red';
  } else if($task['status'] == 'Delayed'){
    $status_color = 'amber';
  } else if($task['status'] == 'More info needed'){
    $status_color = 'blue';
    $status_text = 'ffffff';
  } else if($task['status'] == 'Done'){
    $status_color = 'green';
  }
    
?>


<table cellpadding="8" cellspacing="0" border="1" width="95%">
  <tr>
    <td valign="top" width="20%"><b>Logged By</b></td><td valign="top"><?php echo $task['logged_by'] ?></td>
    <td valign="top" width="20%"><b>Logged Date</b></td><td valign="top"><?php echo $task['date_logged'] ?></td>
  </tr><tr>
    <td valign="top"><b>Assigned to</b></td><td valign="top"><?php echo $task['assigned_to'] ?></td>
    <td valign="top"><b>Priority</b></td><td valign="top" bgcolor="<?php echo $priority_color ?>" style="color:#<?php echo $priority_text ?>"><?php echo $task['priority'] ?></td>
  </tr><tr>
    <td valign="top"><b>Task Description</b></td><td colspan="3" valign="top"><?php echo $task['task_description'] ?></td>
  </tr><tr>
    <td valign="top"><b>Account Code</b></td><td valign="top"><?php echo $task['account_code'] ?></td>
    <td valign="top"><b>Status</b></td><td valign="top" bgcolor="<?php echo $status_color ?>" style="color:#<?php echo $status_text ?>"><?php echo $task['status'] ?></td>
  </tr><tr>
    <td valign="top"><b>Comments</b></td><td colspan="3" valign="top"><?php echo $task['comments'] ?></td>
  </tr><tr>
    <td valign="top"><b>Items Needed</b></td><td colspan="3" valign="top"><?php echo $task['items_needed'] ?></td>
  </tr><tr>
    <td valign="top"><b>Private</b></td><td valign="top"><?php echo ($task['is_private']) ? 'Yes' : 'No' ?></td>
    <td valign="top"><b>File</b></td>
    <td valign="top"><a target="_blank" href="quotes/<?php echo $file_name ?>"><?php echo $task['file'] ?></a></td>
  </tr>
</table>
<br /><br />
<?php
  }
}
?>
</div>

</div>


</div>
</div>
</div>

<div id="leftcolumn">
<div class="innertube">

<h3>User Details</h3>
<p class="f4"><strong>ID : </strong><?php echo $user_id ?><?php echo $loggedname ?></p>
<p class="f4"><strong>Name : </strong><?php echo $name ?></p>
<p class="f4"><strong>Email : </strong><?php echo $email ?></p>
<p class="f4"><strong>View Leads : </strong><a href="simpleContact/loggedCalls.php?surveyor=<? echo $staffno ?>" target="_blank">View</a></p>
<p class="f4"><strong>Input Leads : </strong><a href="simpleContact/logCo.php?initials=<? echo $staffno ?>" target="_blank">Go</a></p>

<h3>Category Colour Codes</h3>
<?php 
$q = "SELECT * FROM categories ORDER BY sequence";
	$query = mysql_query($q);
     echo "<table cellspacing=\"0\" cellpadding=\"5\">\n";
			while ($row=mysql_fetch_row($query)) 
			
			{
				
				$font= $row[3];
				
				echo "<tr><td onclick=\"parent.location.href='clients.php?filter1=".$row[2]."&filter1name=".$row[1]."&sort=$sort&searchname=$searchname&searchvenue=$searchvenue'\" valign=\"middle\" width=\"220px\" style=\"cursor: pointer; cursor: hand; color: ".$row[3]."; background-color: ".$row[4].";\"><span class=\"f3\"><font color=\"$font\"> &nbsp;".$row[2]."&nbsp;&nbsp;".$row[1]."</font></span></td></tr>";
				
				
				//echo "<tr><td valign=\"middle\" width=\"200px\" style=\"color: ".$row[3]."; background-color: ".$row[4].";\">&nbsp;<span class=\"f3\"><font color=\"$font\">&nbsp;&nbsp;".$row[1]."</font></span></td>";
				
				}
				
		
	

?>

<tr><td onclick="parent.location.href='clients.php'" valign="middle" width="200px" style="cursor:pointer; cursor:hand; color:#fffff; background-color:#333;"><span class="f3">&nbsp;&raquo;&nbsp;&nbsp;View All</span></td></tr>
</table>








</div>
</div>



<div id="rightcolumn">
<div class="innertube">
  
<div id="box1" style="position:relative; padding:5px; margin-top:10px; margin-bottom:5px; border:#CCC 1px solid">
<?php
echo "<a class=\"useredit\" href=\"clients.php\" ><strong>Home</strong></a><br/>";
echo "<a class=\"useredit\" href=\"salesSupport.php\" target=\"_blank\" ><strong>Sales Support</strong></a><br/>";
echo "<a class=\"useredit\" href=\"simpleContact/loggedCalls.php?surveyor=$staffno\" target=\"_blank\"><strong>View Leads</strong></a><br/>";
echo "<a class=\"useredit\" href=\"simpleContact/logCo.php?initials=$staffno\" target=\"_blank\"><strong>Enter Lead</strong></a><br/>";
//echo "<a class=\"useredit\" href=\"http://www.diary.pestokill.co.uk/client.php\" target=\"_blank\"><strong>Cient Entry Form</strong></a><br/>";

echo "<a class=\"useredit\" href=\"#x\" onclick=\"x=eventWin('crm/admin4854.php?page=14'); x.focus();return false\"><strong>Add Master Record</strong></a><br/>";
echo "<a class=\"useredit\" href=\"master_client_view.php{$theRecordsLink}\" ><strong>All Master Records</strong></a><br/>";
echo "<a class=\"useredit\" href=\"clients.php#tasks\" ><strong>Task list</strong></a><br/>";
echo "<a class=\"useredit\" href=\"company_client.php{$theRecordsLink}\"><strong>Track by company</strong></a><br/>";
echo "<a class=\"useredit\" href=\"survey_client.php{$theRecordsLink}\" ><strong>All Surveys</strong></a><br/>";
echo '<a class="useredit" onclick="x=eventWin(\'crm/pdf_activityplan_getdate.php?surveyor_name='.$name.'\'); x.focus();return false" href=""><strong>Daily activity report</strong></a><br/>';
echo "<a class=\"useredit\" href=\"performance.php?ds=".date('Y-m', strtotime(date("Y-m-d", strtotime(date("Y-m-d"))) . "- 1 month")).'-01'."\" ><strong>Performance</strong></a><br/>";
?>


</div>
<div style="height:150px; background-color:#F2F2F2; border:#CCC 1px solid">
                <script type="text/javascript">
					$(document).ready(function(){
						$('#sPost').click(function(){
							var pestoPcode = $('#cPost').val();
							$("#pCodeRes").html('Fetching Result..').show();
							$.post('getPcode.php', {pestoPcode: pestoPcode}, function(data){
								$("#pCodeRes").hide().html(data).fadeIn(600).delay(2000);
							});
						});
					});
				</script>
                <div style="padding:2px">
                <font size="2.5em" color="#000000" style="font-weight:bold">Check Postcode</font>
                <br />
                <input id="cPost" maxlength="4" style="width:50px; border-radius:7px; -webkit-border-radius:7px; -moz-border-radius:7px; background-color:#CCC" />&nbsp;&nbsp;&nbsp;&nbsp; 
               <input type="button" id="sPost" style="width:30px; border:#000000 1px solid;border-radius:7px; -webkit-border-radius:7px; -moz-border-radius:7px; background-color:#CCC" value="Go" /><br />
                <div id="pCodeRes">
                </div>
                </div>
                </div>
<h3>Live Chat</h3>

<?php 

if (!isset($_SESSION['nickname']) ){ 
    createForm();
} else  { 
      $name    = isset($_POST['name']) ? $_POST['name'] : "Unnamed";
      $_SESSION['nickname'] = $name;
    ?>
      
     <div id="result">
     <?php 
        $data = file("msg.html");
        foreach ($data as $line) {
        	echo $line;
        }
     ?>
      </div>
      <div id="sender" onKeyUp="keypressed(event);">
      new msg:
      <textarea name="msg" id="msg" style="width:222px" cols="5" rows="5"></textarea>
      
         <button class="loginbutton" onClick="doWork();"> Send </button>
      </div>   
<?php            
    }

?>

<!--<table width="100%" border="0" cellpadding="5" cellspacing="2" bgcolor="#f1f1f1">
  <tr>
    <td colspan="2" bgcolor="#EAEAEA">&nbsp;<span class="f2">Sales Figures <?php echo $m ?></span></td>
  </tr>
  <tr>
    <td width="90" bgcolor="#EAEAEA">&nbsp;<span class="f2">Quotes</span></td>
    <td bgcolor="#E5E5E5">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#EAEAEA">&nbsp;<span class="f2">Job</span></td>
    <td bgcolor="#E5E5E5">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#EAEAEA">&nbsp;<span class="f2">Contract</span></td>
    <td bgcolor="#E5E5E5">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#EAEAEA">&nbsp;<span class="f2">Accepted</span></td>
    <td bgcolor="#E5E5E5">&nbsp;</td>
  </tr>
  <tr>
    <td bgcolor="#EAEAEA">&nbsp;<span class="f2">Appointments</span></td>
    <td bgcolor="#E5E5E5">&nbsp;</td>
  </tr>
</table> -->


</div>
</div>

<div id="footer"><a href="#">&copy;<?php echo $y ?> Pestokill Limited</a></div>

</div>
</body>
</html>

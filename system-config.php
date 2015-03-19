<?php
	class BreadCrumb {
	    // property declaration
	    public $page = "";
	    public $label = "";
	}
	
	class BreadCrumbManager {
		public static function initialise() {
			if (! isset($_SESSION['PW_BREADCRUMBMANAGER'])) {
				$_SESSION['PW_BREADCRUMBMANAGER'] = array();
			}
		}
		
		public static function add($pageName, $pageLabel) {
			$bc = new BreadCrumb();
			$bc->page = $pageName;
			$bc->label = $pageLabel;
			
			$_SESSION['PW_BREADCRUMBMANAGER'][count($_SESSION['PW_BREADCRUMBMANAGER'])] = $bc;
		}
		
		public static function remove($index) {
			unset($_SESSION['PW_BREADCRUMBMANAGER'][$index]);
		}
		
		public static function showBreadcrumbTrail() {
			$first = true;
			
			echo "<h4 class='breadcrumb'>";
			
			for ($i = count($_SESSION['PW_BREADCRUMBMANAGER']) - 1; $i >= 0; $i--) {
				if (! $first) {
					echo "<span>&nbsp;/&nbsp;</span>";
				}
				
				$first = false;
				
				echo "<a href='" .$_SESSION['PW_BREADCRUMBMANAGER'][$i]->page . "' ";
				
				if ($i == 0) {
					echo "class='lastchild'";
				}
				
				echo ">" . $_SESSION['PW_BREADCRUMBMANAGER'][$i]->label . "</a>";
			} 
			
			echo "</h4>";
		}
		
		public static function fetchParent($id) {
			$qry = "SELECT A.pageid, B.pagename, B.label FROM pagenavigation A " .
					"INNER JOIN pages B " .
					"ON B.pageid = A.pageid " .
					"WHERE A.childpageid = $id";
			$result = mysql_query($qry);
			
			//Check whether the query was successful or not
			if ($result) {
				if (mysql_num_rows($result) == 1) {
					$member = mysql_fetch_assoc($result);
					
					if ($id != $member['pageid']) {
						self::add($member['pagename'], $member['label']);
						self::fetchParent($member['pageid']);
					}
					
				} else if (mysql_num_rows($result) == 0) {
					if ($id > 1) { /* Not a home connection */
						self::add("index.php", "Home");
					}
				}
			} else {
				echo "<h1>SH</h1>";
			}
		}
		
		public static function calculate() {
			unset($_SESSION['PW_BREADCRUMBMANAGER']);
			
			self::initialise();
    		self::add($_SESSION['PW_pagename'], $_SESSION['PW_title']);
			self::fetchParent($_SESSION['PW_pageid']);
	    	
	    	if (isAuthenticated()) {
		    	if (isset($_SESSION['PW_lastconnectiontime'])) {
		    		$lastsessiontime = time() - $_SESSION['PW_lastconnectiontime'];
		    		
		    		/* 5 minutes. */
		    		if ($lastsessiontime >= 300) {	//Unset the variables stored in session
						unset($_SESSION['PW_SESS_MEMBER_ID']);
						unset($_SESSION['PW_SESS_FIRST_NAME']);
						unset($_SESSION['PW_SESS_LAST_NAME']);
						unset($_SESSION['PW_ROLES']);
	
		    			header("location: system-login-timeout.php");
		    		}
		    	}
	    	}
	    	
	   		$_SESSION['PW_lastconnectiontime'] = time();
	    }
	}
	
	class SessionManagerClass {
		public static function initialise() {
			//Start session
			session_start();
			
			error_reporting(E_ALL ^ E_DEPRECATED);
	/*
			define('DB_HOST', 'localhost');
		    define('DB_USER', 'root');
		    define('DB_PASSWORD', 'root');
		    define('DB_DATABASE', 'prodigyworks');
		    */
			define('DB_HOST', 'prodigyworks.co.uk.mysql');
		    define('DB_USER', 'prodigyworks_co');
		    define('DB_PASSWORD', 'i6qFAWND');
		    define('DB_DATABASE', 'prodigyworks_co');
		    
		    $_SESSION['PW_pagename'] = substr($_SERVER["PHP_SELF"], strripos($_SERVER["PHP_SELF"], "/") + 1);
		    
		    BreadCrumbManager::initialise();
		    
		    self::initialiseDB();
			self::initialisePageData();

			BreadCrumbManager::calculate();
		}
		
	    public static function initialiseDB() {
			//Connect to mysql server
			$link = mysql_connect(DB_HOST, DB_USER, DB_PASSWORD);
			
			if (!$link) {
				die('Failed to connect to server: ' . mysql_error());
			}
			
			if (! isset($_SESSION['PW_ROLES'])) {
				$_SESSION['PW_ROLES'] = array();
				$_SESSION['PW_ROLES'][0] = "PUBLIC";
				$_SESSION['PW_ROLES'][1] = "NOAUTH";
			}
			
			//Select database
			$db = mysql_select_db(DB_DATABASE);
			
			if(!$db) {
				die("Unable to select database");
			}
			
	    }
	    
        function initialisePageData() {
			$qry = "SELECT DISTINCT A.* FROM pages A " .
					"INNER JOIN pageroles B " .
					"ON B.pageid = A.pageid " .
					"WHERE A.pagename = '" . $_SESSION['PW_pagename'] . "' " .
					"AND B.roleid IN (" . ArrayToInClause($_SESSION['PW_ROLES']) . ")";
			$result = mysql_query($qry);
			
			//Check whether the query was successful or not
			if ($result) {
				if (mysql_num_rows($result) == 1) {
					$member = mysql_fetch_assoc($result);
					
					$_SESSION['PW_pageid'] = $member['pageid'];
					$_SESSION['PW_title'] = $member['label'];
					
					echo "<script>document.title = '" . $member['label'] . " - Prodigy Works';</script>\n";
					
				} else {
	    			header("location: system-access-denied.php");
				}
			}
	    }
	    
	}

    SessionManagerClass::initialise();
    
    function isUserInRole($roleid) {
    	if (! isAuthenticated()) {
    		return false;
    	}
    	
    	for ($i = 0; $i < count($_SESSION['PW_ROLES']); $i++) {
    		if ($roleid == $_SESSION['PW_ROLES'][$i]) {
    			return true;
    		}
    	}
		
		return false;
    }

	function isAuthenticated() {
		return ! (!isset($_SESSION['PW_SESS_MEMBER_ID']) || (trim($_SESSION['PW_SESS_MEMBER_ID']) == ''));
	}
	
	function showErrors() {
		if( isset($_SESSION['PW_ERRMSG_ARR']) && is_array($_SESSION['PW_ERRMSG_ARR']) && count($_SESSION['PW_ERRMSG_ARR']) >0 ) {
			echo '<ul class="err">';
			foreach($_SESSION['PW_ERRMSG_ARR'] as $msg) {
				echo '<li>',$msg,'</li>'; 
			}
			echo '</ul>';
			unset($_SESSION['PW_ERRMSG_ARR']);
		}
	}
    
    function showSubMenu($id) {
		$qry = "SELECT DISTINCT B.pagename, B.label FROM pagenavigation A " .
				"INNER JOIN pages B " .
				"ON A.childpageid = B.pageid " .
				"INNER JOIN pageroles C " .
				"ON C.pageid = B.pageid " .
				"WHERE A.pageid = " . $id . " " .
				"AND A.pagetype = 'M' " .
				"AND C.roleid IN (" . ArrayToInClause($_SESSION['PW_ROLES']) . ") " .
				"ORDER BY A.sequence";
		$result=mysql_query($qry);

		//Check whether the query was successful or not
		if($result) {
			
			if (mysql_num_rows($result) >  0) {
				echo "<ul class='submenu'>\n";
		
				/* Show children. */
				while (($member = mysql_fetch_assoc($result))) {
					if ($member['pagename'] == $_SESSION['PW_pagename']) {
						echo "<li class='selected submenuitem'>" ;
						
					} else {
						echo "<li class='submenuitem'>";
					}
					
					echo "<a href='" . $member['pagename'] . "'>" . $member['label'] . "</a></li>\n";
				}
		
				echo "</ul>\n";
			}
		}
    }

    function findParentMenu($id, $ancestors) {
		$qry = "SELECT pageid, pagetype " .
				"FROM pagenavigation " .
				"WHERE childpageid = $id";
		$result=mysql_query($qry);

		//Check whether the query was successful or not
		if($result) {
			
			if (mysql_num_rows($result) > 0) {
				$member = mysql_fetch_assoc($result);
				$ancestors[count($ancestors)] = $member['pageid'];
				
				if ($member['pagetype'] == "M" ||
					$member['pagetype'] == "L") {
					$ancestors = findParentMenu($member['pageid'], $ancestors);
				}
				
			} else {
				$ancestors[count($ancestors)] = 1;
			}
		}
		
		return $ancestors;
    }
    
    function showMenu() {
    	nestPages($_SESSION['PW_pageid'], array($_SESSION['PW_pageid']));
    }
    
    function nestPages($id, $ancestors) {
		$qry = "SELECT DISTINCT A.*, B.* FROM pagenavigation A " .
				"INNER JOIN pages B " .
				"ON A.childpageid = B.pageid " .
				"INNER JOIN pageroles C " .
				"ON C.pageid = B.pageid " .
				"WHERE A.pageid = " . $id . " " .
				"AND A.pagetype = 'P' " .
				"AND C.roleid IN (" . ArrayToInClause($_SESSION['PW_ROLES']) . ") " .
				"ORDER BY A.sequence";
		$result=mysql_query($qry);
		
		//Check whether the query was successful or not
		if($result) {
			
			if (mysql_num_rows($result) == 0) {
				$ancestors = findParentMenu($id, $ancestors);
				
				nestPages($ancestors[count($ancestors) - 1], $ancestors);
				
			} else {
				$result=mysql_query($qry);
				$highestPage = 0;

				while (($member = mysql_fetch_assoc($result))) {
					
					for ($index = 0; $index < count($ancestors); $index++) {
						if ($ancestors[$index] == $member['pageid']) {
							
							if ($highestPage < $member['pageid']) {
								$highestPage = $member['pageid'];
							}
						}
					}
				}
		
				$result=mysql_query($qry);
				
				echo "<ul class='menu'>\n";
		
				/* Show children. */
				while (($member = mysql_fetch_assoc($result))) {

					if ($highestPage == $member['pageid']) {
						echo "<li class='selected menuitem'>" ;
						
					} else {
						echo "<li class='menuitem'>";
					}
					
				    showSubMenu($member['childpageid']);

					echo "<a href='" . $member['pagename'] . "'>" . $member['label'] . "</a></li>\n";
				}
		
				echo "</ul>\n";
			}
		}
    }
	
	/**  
	 * Convert time into decimal time.  
	 * @param string $time The time to convert  
	 * @return integer The time as a decimal value.  
	 */
	function time_to_decimal($time) {     
		$timeArr = explode(':', $time);     
		
		$decTime = ($timeArr[0]) + ((($timeArr[1] * 100) / 60) / 100);
		
		return $decTime; 
	} 
	
	function ArrayToString($arr) {
		$count = count($arr);
		$str = "[";
		
		for ($i = 0; $i < $count; $i++) {
			if ($i > 0) {
				$str = $str . ", ";
			}
			
			$str = $str . "\"" . $arr[$i] . "\"";
		}
		
		$str = $str . "]";
		
		return $str;
	}
	
	function ArrayToInClause($arr) {
		$count = count($arr);
		$str = "";
		
		for ($i = 0; $i < $count; $i++) {
			if ($i > 0) {
				$str = $str . ", ";
			}
			
			$str = $str . "\"" . $arr[$i] . "\"";
		}
		
		return $str;
	}
	
	function escapeQuote($stringLiteral) {
		$searches = array( "'", "\n", "\r" );                 
		$replacements = array( "&apos;", "", "");
		
		return str_replace( $searches, $replacements, $stringLiteral ); 
	}
	
	function hotspot($hotspotid, $hotspotname, $roleid, $publishroleid, $overridefile) {
		//Array to store validation errors
		$errmsg_arr = array();
		$sizeManually = false;
		
		//Validation error flag
		$errflag = false;
		
		if (isset($_SESSION['PW_SESS_MEMBER_ID'])) {
			$createdby = $_SESSION['PW_SESS_MEMBER_ID'];
	
		} else {
			$createdby = "";
		}

		if (isset($overridefile) && $overridefile != "") {
			$filename = "HS_" . $overridefile . "_" . $hotspotid;
			
		} else {
			$filename = "HS_" . $_SESSION['PW_title'] . "_" . $hotspotid;
		}

		echo "<div hotspotid='" . $hotspotid . "' file='" . $filename . "' role='" . $roleid . "' publishrole='" . $roleid . "' hotspotname='" . $hotspotname . "' class='hotspot'>";
		
		/* Look for current pending versions. */
		$qry = "SELECT B.image FROM documents A " .
				"INNER JOIN documentversions B " .
				"ON  B.documentid = A.documentid " .
				"WHERE A.filename='$filename' " .
				"AND B.status = 'P' " .
				"AND B.createdby = '$createdby'";
		$result = mysql_query($qry);
		
		//Check whether the query was successful or not
		if($result) {
			if(mysql_num_rows($result) == 1) {
				//Login Successful
				$member = mysql_fetch_assoc($result);
		
				echo $member['image'];
				
			} else {
				/* Look for live versions. */
				$qry = "SELECT B.image FROM documents A " .
						"INNER JOIN documentversions B " .
						"ON B.documentversionid = A.documentversionid " .
						"AND B.documentid = A.documentid " .
						"WHERE A.filename='$filename'";
				$result=mysql_query($qry);
				
				//Check whether the query was successful or not
				if($result) {
					if(mysql_num_rows($result) == 1) {
						//Login Successful
						$member = mysql_fetch_assoc($result);
				
						echo $member['image'];
			
					} else {
						$sizeManually = true;
					}
				}
			}
		}
		
		echo "</div>";
		
		if ($sizeManually && isAuthenticated()) {
			echo "<script>";
			echo "$('[hotspotid= \"$hotspotid\"]').css('height', '100px');";
			echo "</script>";
		}
	}
?>

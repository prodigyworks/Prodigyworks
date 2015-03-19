<?php
	include("system-header.php"); 
	
	function deploy() {
		$articled = $_POST['pk1'];
		$notes = mysql_escape_string($_POST['pk2']);
		
		$qry = "UPDATE ols_article SET " .
				"reasonforcancellation = '$notes', " .
				"published = 'X', " .
				"cancelleddate = NOW() " .
				"WHERE id = $articled";
		$result = mysql_query($qry); 
		
		if (! $result) {
			die ($qry . " = " . mysql_error());
		}
	}
?>
<script type="text/javascript" src="js/plupload.js"></script>
<script type="text/javascript" src="js/plupload.flash.js"></script>
<script type="text/javascript" src="scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	function deployAlert() {
		$("#deployalert").dialog("open");
	}

	$(document).ready(function() {
			$("#deployalert").dialog({
					modal: true,
					autoOpen: false,
					width: 700,
					show:"fade",
					hide:"fade",
					title:"Alert",
					open: function(event, ui){
						$("#notes").focus();
					},
					buttons: {
						Ok: function() {
							$("#deploynotes").val($("#notes").val());
							$("#deployform").submit();
						},
						Cancel: function() {
							$(this).dialog("close");
						}
					}
				});

		tinyMCE.init({ 
	        // General options 
	        mode : "textareas", 

			// Location of TinyMCE script
			script_url : 'scripts/tiny_mce/tiny_mce.js',
			editor_selector :"tinyMCE",

			// General options
			theme : "advanced",
			plugins : "phpimage,pre,autolink,lists,pagebreak,style,layer,table,save,advhr,advimage,advlink,emotions,iespell,inlinepopups,insertdatetime,preview,media,searchreplace,print,contextmenu,paste,directionality,fullscreen,noneditable,visualchars,nonbreaking,xhtmlxtras,template,advlist",

			// Theme options
			theme_advanced_buttons1 : "save,|,phpimage,pre,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
			theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,cleanup,help,code,|,insertdate,inserttime,preview,|,forecolor,backcolor",
			theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,charmap,emotions,iespell,media,advhr,|,print,|,ltr,rtl,|,fullscreen",
			theme_advanced_buttons4 : "insertlayer,moveforward,movebackward,absolute,|,styleprops,|,cite,abbr,acronym,del,ins,attribs,|,visualchars,nonbreaking,template,pagebreak",
			theme_advanced_toolbar_location : "top",
			theme_advanced_toolbar_align : "left",
			theme_advanced_statusbar_location : "none",
			theme_advanced_resizing : true,

			// Example content CSS (should be your site CSS)
			content_css : "css/content.css",

			// Drop lists for link/image/media/template dialogs
			template_external_list_url : "lists/template_list.js",
			external_link_list_url : "lists/link_list.js",
			external_image_list_url : "lists/image_list.js",
			media_external_list_url : "lists/media_list.js",

			// Replace values for the template plugin
			template_replace_values : {
				username : "Some User",
				staffid : "991234"
			}
		});
	});
</script>
<div class="modal" id="deployalert">
	<h2>Email Alert</h2>
	<textarea id="notes" name="notes" cols=152 rows=10 class="tinyMCE" style="height:340px;width: 340px"></textarea>
</div>
<form id="deployform" method="POST" action="sitealertsave.php">
	<input type="hidden" id="deploynotes" name="deploynotes" />
   	<span class="wrapper"><a class='link1' href="javascript:deployAlert()"><em><b>Create Alert</b></em></a></span>
	<br>
	<br>
	
	<table cellspacing=0 cellpadding=0 width='100%' class='grid list' id="articletable">
	    <thead>
	      <tr>
	        <td width='20px'>&nbsp;</td>
	        <td>Last Name</td>
	        <td>First Name</td>
	        <td>Login</td>
	      </tr>
	    </thead>
	    <tbody>
	    	<?php
	    		$qry = "SELECT * FROM ols_members A " .
	    				"WHERE A.sitealerts = 1 " .
	    				"ORDER BY A.lastname, A.firstname";
				$result = mysql_query($qry);
				
				if ($result) {
					while (($member = mysql_fetch_assoc($result))) {
						echo "<tr>";
						echo "<td><input type='checkbox' id='checked' name='checked[]' checked value='" .$member['member_id'] . "' /></td>";
						echo "<td>" . $member['lastname'] . "</td>";
						echo "<td>" . $member['firstname'] . "</td>";
						echo "<td>" . $member['login'] . "</td>";
						echo "</tr>";
					}
				} else {
					die($qry . " = " . mysql_error());
				}
	    	?>
	    </tbody>
	</table>
</form>
<?php
	include("system-footer.php"); 
?>
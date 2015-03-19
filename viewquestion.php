<?php
	include("system-header.php"); 
	$questionid = $_GET['id'];
	$published = "N";
	$publishedrole = "";
	
	$qry = "SELECT A.id, A.title, A.body, A.architecture, A.publishedrole, A.published, " .
			"DATE_FORMAT(A.createddate, '%d/%m/%Y') AS createddate, " .
			"B.login, " .
			"C.name AS techname, " .
			"D.name AS techversion, " .
			"E.name AS opname, " .
			"F.name AS opversion " .
			"FROM ols_question A " .
			"INNER JOIN ols_members B " .
			"ON B.member_id = A.memberid " .
			"INNER JOIN ols_technology C " .
			"ON C.id = A.technologyid " .
			"INNER JOIN ols_technologyversion D " .
			"ON D.id = A.technologyversionid " .
			"INNER JOIN ols_operatingsystem E " .
			"ON E.id = A.opsystemid " .
			"INNER JOIN ols_operatingsystemversion F " .
			"ON F.id = A.opsystemversionid " .
			"WHERE A.id = $questionid";
	$result = mysql_query($qry);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			$published = $member['published'];
			$publishedrole = $member['publishedrole'];
?>
<form method="POST" class="entryform" id='articleform' action='askquestionsave.php'>
	<h2>Title : <?php echo $member['title']; ?></h2>
	
	<label>Technology</label>
	<input type='text' class="textbox70" readonly value="<?php echo $member['techname']; ?>" />
	
	<label>Version</label>
	<input type='text' class="textbox10" readonly value="<?php echo $member['techversion']; ?>" />
	
	<label>Operating System</label>
	<input type='text' class="textbox70" readonly value="<?php echo $member['opname']; ?>" />
	
	<label>Version</label>
	<input type='text' class="textbox10" readonly value="<?php echo $member['opversion']; ?>" />
	
	<label>Architecture</label>
	<input type='text' class="textbox10" readonly value="<?php echo $member['architecture']; ?>" />
	<br>
	<br>
	<hr>
	<label>Question</label>
	<div class="body">
		<?php echo $member['body']; ?>
	</div>
</form>

<?php
		}
	} else {
		die($qry . " = " . mysql_error());
	}
?>
	<hr><p>Attached files. Click to view</p>	
<?php
	$qry = "SELECT B.* " .
			"FROM ols_questiondocuments A " .
			"INNER JOIN ols_documents B " .
			"ON B.id = A.documentid " .
			"WHERE A.questionid = $questionid";
	$result = mysql_query($qry);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			echo "<a target='_new' href='viewdocuments.php?id=" . $member['id'] ."'>" . $member['filename'] . "</a><br>";
		}
		
	} else {
		die($qry . " = " . mysql_error());
	}
	
	if ($published == "Y") {
?>
<script type="text/javascript" src="js/plupload.js"></script>
<script type="text/javascript" src="js/plupload.flash.js"></script>
<script type="text/javascript" src="scripts/tiny_mce/tiny_mce.js"></script>
<script type="text/javascript">
	$(document).ready(function() {
//		$('textarea.tinymce').tinymce({
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
<br>
<hr />
<br>
<h4>Answers</h4>
<br>
<?php
	$qry = "SELECT A.*, B.login " .
			"FROM ols_questionanswers A " .
			"INNER JOIN ols_members B " .
			"ON B.member_id = A.memberid " .
			"WHERE A.questionid = $questionid " .
			"AND A.published = 'Y' " .
			"ORDER BY A.id";
	$result = mysql_query($qry);
	
	if ($result) {
		while (($member = mysql_fetch_assoc($result))) {
			echo "<h5>Author : " . $member['login'] . "</h5>";
			echo "<h6>Posted : " . $member['createddate'] . "</h6>";
			
			echo "<div class='answer'>" . $member['body'] . "</div>";
			echo "<hr><p>Attached files. Click to view</p>";	

			$qry = "SELECT B.* " .
					"FROM ols_questionanswerdocuments A " .
					"INNER JOIN ols_documents B " .
					"ON B.id = A.documentid " .
					"WHERE A.answerid = " . $member['id'];
			$itemresult = mysql_query($qry);
			
			if ($itemresult) {
				while (($itemmember = mysql_fetch_assoc($itemresult))) {
					echo "<a target='_new' href='viewdocuments.php?id=" . $itemmember['id'] ."'>" . $itemmember['filename'] . "</a><br>";
				}
				
			} else {
				die($qry . " = " . mysql_error());
			}

			echo "<br><hr><br>";
		}
		
	} else {
		die($qry . " = " . mysql_error());
	}

	if (isAuthenticated() && isUserInRole($publishedrole)) {
?>
<hr />
<br>
<form method="post"  action='answerquestionsave.php' id="answerform">
	<h4>Comment</h4>
	<input type="hidden" id="questionid" name="questionid" value="<?php echo $questionid; ?>" />
	<textarea id="answerbody" name="answerbody" rows="15" cols="60" style="height:240px;width: 340px" class="tinyMCE"></textarea>
	
	<label>Attach File</label>
	<div id="upload_container">
	    <div id="filelist"></div>
	    <br />
	   	<span class="wrapper "><a id="pickfiles" class='link1 rgap5' href="javascript:;"><em><b>Select Files</b></em></a></span>
	   	<span class="wrapper"><a id="uploadfiles" class='link1' href="javascript:;"><em><b>Upload Files</b></em></a></span>
	   	<br>
	   	<br>
	</div>
	<hr />
	<br>
  	<span class="wrapper"><a id="submitanswer" class='link1' href="javascript:$('#answerform').submit();"><em><b>Submit</b></em></a></span>
</form>
<script type="text/javascript">
	function preview() {
		$("#previewdiv").html($("#articlebody").html());
		$("#previewdialog").dialog("open");
	}
	
	$(document).ready(function() {
			$("#previewdialog").dialog({
					modal: true,
					autoOpen: false,
					width: 800,
					title: "Preview",
					show:"fade",
					hide:"fade",
					buttons: {
						Ok: function() {
							$(this).dialog("close");
						}
					}
				});
		});
	
	var uploader = new plupload.Uploader({
		runtimes : 'gears,html5,flash,silverlight,browserplus',
		browse_button : 'pickfiles',
		container: 'upload_container',
		max_file_size : '10mb',
		url : 'system-documentupload.php',
		resize : {width : 320, height : 240, quality : 90},
		flash_swf_url : 'js/plupload.flash.swf',
		silverlight_xap_url : 'js/plupload.silverlight.xap',
		filters : [
			{title : "Image files", extensions : "jpg,gif,png"},
			{title : "Zip files", extensions : "zip"}
		]
	});
	
	uploader.bind('FilesAdded', function(up, files) {
		for (var i in files) {
			$('#filelist').html($('#filelist').html() + '<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>');
		}
	});
	
	uploader.bind('UploadProgress', function(up, file) {
			$("#" + file.id + " b").html('<span>' + file.percent + "%</span>");
		});
	
	$('#uploadfiles').click(function() {
			uploader.start();
			return false;
		});
	
	uploader.init();
	
	$("#technologyid").change(
			function() {
				getJSONData(
						'findtechnologyversion.php?id=' + $("#technologyid").val(), 
						"#technologyversionid", 
						function() {
						}
					);
			}
		);
	
	$("#operatingsystemid").change(
			function() {
				getJSONData(
						'findopsystemversion.php?id=' + $("#operatingsystemid").val(), 
						"#opsystemversionid", 
						function() {
						}
					);
			}
		);
</script>
	
<?php
		}
	}
	
	include("system-footer.php"); 
?>
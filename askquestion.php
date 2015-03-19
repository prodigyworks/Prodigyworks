<?php
	require_once("system-db.php"); 
	
	redirectWithoutRole("PREMIUM", "premiummember.php");
	
	require_once("system-header.php"); 
?>
<style>
	.previewdiv {
		width: 750px;
		height: 300px;
	}
</style>
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
			theme_advanced_buttons1 : "save,|,pre, phpimage,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",
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
<?php 
	if (isset($_GET['requestchat']) && $_GET['requestchat'] == "true") {
?>
<form method="POST" class="entryform" id='articleform' action='requestexpertsetup.php'>
<?php 
	} else {
?>
<form method="POST" class="entryform" id='articleform' action='askquestionsave.php'>
<?php 
	}
?>
<form method="POST" class="entryform" id='articleform' action='askquestionsave.php?requestchat='>
	<label>Technology</label>
	<?php createCombo("technologyid", "id", "name", "ols_technology"); ?>
	
	<label>Version</label>
	<select id="technologyversionid" name="technologyversionid" style="width:200px">
		<option value="0"></option>
	</select>	
	
	<label>Operating System</label>
	<?php createCombo("operatingsystemid", "id", "name", "ols_operatingsystem"); ?>
	
	<label>Version</label>
	<select id="opsystemversionid" name="opsystemversionid" style="width:200px">
		<option value="0"></option>
	</select>	
	
	<label>Architecture</label>
	<SELECT id='architecture' name='architecture'>
		<OPTION value="32">32 Bit</OPTION>
		<OPTION value="64">64 Bit</OPTION>
	</SELECT>
	
	<label>Title</label>
	<input type="text" value="" id="title" name="title" style="width:260px" />
	
	<label>Question</label>
	<textarea id="questionbody" name="questionbody" rows="15" cols="60" style="height:340px;width: 340px" class="tinyMCE"></textarea>
	
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
  	<span class="wrapper"><a id="uploadfiles" class='link1' href="javascript:$('#articleform').submit();"><em><b>Submit</b></em></a></span>
</form>
<div id="previewdialog" class="modal">
	<div id='previewdiv'></div>
</div>
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
	include("system-footer.php"); 
?>
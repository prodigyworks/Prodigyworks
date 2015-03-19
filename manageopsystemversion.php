<?php 
	include("system-header.php"); 
	include("confirmdialog.php");
	
	createConfirmDialog("confirmdialog", "Remove operatingSystem ?", "removeVersion");
?>

<script src='js/jquery.picklists.js' type='text/javascript'></script>

<!--  Start of content -->
<input type="text" id="newVersion" name="newVersion" value="" />
<input type="button" style="display:inline" value="Add" onclick="call('addVersion', { pk1: $('#newVersion').val() })"></input>

<table width="100%" class="grid list" id="operatingSystemlist" width=100% cellspacing=0 cellpadding=0>
<thead>
	<tr>
		<td>Version</td>
		<td width='20px'></td>
	</tr>
</thead>
<?php
	
	function addVersion() {
		$operatingSystemid = $_GET['id'];
		$operatingSystem = $_POST['pk1'];
		
		$qry = "INSERT INTO ols_operatingsystemversion (operatingSystemid, name) VALUES ($operatingSystemid, '$operatingSystem')";
		$result=mysql_query($qry);
		
		if (! $result) {
			 die('Invalid query: ' . mysql_error());

		}
		
	} 
	
	function removeVersion() {
		$id = $_POST['pk1'];
		
		$qry = "DELETE FROM ols_operatingsystemversion WHERE id = $id";
		$result=mysql_query($qry);

		if (! $result) {
			 die('Invalid query: ' . mysql_error());

		}
	}
	
	$operatingSystemid = $_GET['id'];
	$qry = "SELECT * FROM ols_operatingsystemversion " .
			"WHERE operatingsystemid = $operatingSystemid " .
			"ORDER BY name";
			
	$result=mysql_query($qry);
	$rowNumber = 0;
	
	//Check whether the query was successful or not
	if($result) {
		
		while($member = mysql_fetch_assoc($result)) {
			echo "<tr><td>";
			echo $member['name'];
			echo "</td>";
			
			echo "<td title='Delete'><img onclick='deleteVersion(\"" . $member['id'] . "\")' src='images/delete.png' /></td>"; 
			
			echo "</tr>";
		} 			
		
	} else {
		 die('Invalid query: ' . mysql_error());

	}
?>
</table>
<script>
	var currentVersion = null;
	
	$(document).ready(function() {
		$("#versions").pickList({
				removeText: 'Remove Version',
				addText: 'Add Version',
				testMode: false
			});
		
		$("#versionDialog").dialog({
				autoOpen: false,
				modal: true,
				width: 800,
				title: "Versions",
				buttons: {
					Ok: function() {
						$("#versionsForm").submit();
					},
					Cancel: function() {
						$(this).dialog("close");
					}
				}
			});
	});
	
function removeVersion() {
	call("removeVersion", {
		"pk1": currentVersion
	});
}
function deleteVersion(operatingSystem) {
	currentVersion = operatingSystem;
	$("#confirmdialog .confirmdialogbody").html("You are about to remove operating system <b><i>'"  + operatingSystem + "'</i></b>.<br>Are you sure ?");
	$("#confirmdialog").dialog("open");
}
</script>
<!--  End of content -->

<?php include("system-footer.php"); ?>

<?php 
	include("system-header.php"); 
	include("confirmdialog.php");
	
	createConfirmDialog("confirmdialog", "Remove technology ?", "removeTechnology");
?>

<script src='js/jquery.picklists.js' type='text/javascript'></script>

<!--  Start of content -->
<input type="text" id="newTechnology" name="newTechnology" value="" />
<input type="button" style="display:inline" value="Add" onclick="call('addTechnology', { pk1: $('#newTechnology').val() })"></input>

<table width="100%" class="grid list" id="technologylist" width=100% cellspacing=0 cellpadding=0>
<thead>
	<tr>
		<td>Technology</td>
		<td width='20px'></td>
		<td width='20px'></td>
	</tr>
</thead>
<?php
	
	function addTechnology() {
		$technology = $_POST['pk1'];
		
		$qry = "INSERT INTO ols_technology (name) VALUES ('$technology')";
		$result=mysql_query($qry);
		
		if (! $result) {
			 die('Invalid query: ' . mysql_error());

		}
		
	} 
	
	function removeTechnology() {
		$id = $_POST['pk1'];
		
		$qry = "DELETE FROM ols_technology WHERE id = $id";
		$result=mysql_query($qry);

		if (! $result) {
			 die('Invalid query: ' . mysql_error());

		}
	}
	
	$qry = "SELECT * FROM ols_technology ORDER BY name";
			
	$result=mysql_query($qry);
	$rowNumber = 0;
	
	//Check whether the query was successful or not
	if($result) {
		
		while($member = mysql_fetch_assoc($result)) {
			echo "<tr><td>";
			echo $member['name'];
			echo "</td>";
			
			echo "<td title='Version Technology'><img onclick='versionTechnology(\"" . $member['id'] . "\")' src='images/version.png' /></td>";
			echo "<td title='Delete'><img onclick='deleteTechnology(" . $member['id'] . ", \"" . $member['name'] . "\")' src='images/delete.png' /></td>"; 
			
			echo "</tr>";
		} 			
		
	} else {
		 die('Invalid query: ' . mysql_error());

	}
?>
</table>
<script>
	var currentTechnology = null;
	
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
	
function removeTechnology() {
	call("removeTechnology", {
		"pk1": currentTechnology
	});
}

function deleteTechnology(id, technology) {
	currentTechnology = id;
	
	$("#confirmdialog .confirmdialogbody").html("You are about to remove technology <b><i>'"  + technology + "'</i></b>.<br>Are you sure ?");
	$("#confirmdialog").dialog("open");
}

function versionTechnology(id) {
	window.location.href = "managetechnologyversion.php?id=" + id;
}
</script>
<!--  End of content -->

<?php include("system-footer.php"); ?>

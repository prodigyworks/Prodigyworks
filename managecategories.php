<?php 
	include("system-header.php"); 
	include("confirmdialog.php");
	
	createConfirmDialog("confirmdialog", "Remove category ?", "removeCategory");
?>

<!--  Start of content -->
<input type="text" id="newCategory" name="newCategory" value="" />
<input type="button" style="display:inline" value="Add" onclick="call('addCategory', { pk1: $('#newCategory').val() })"></input>

<table width="100%" class="grid list" id="categorylist" width=100% cellspacing=0 cellpadding=0>
<thead>
	<tr>
		<td>Category</td>
		<td width='20px'></td>
	</tr>
</thead>
<?php
	
	function addCategory() {
		$category = $_POST['pk1'];
		
		$qry = "INSERT INTO ols_questioncategory (name) VALUES ('$category')";
		$result=mysql_query($qry);
		
		if (! $result) {
			 die('Invalid query: ' . mysql_error());

		}
		
	} 
	
	function removeCategory() {
		$id = $_POST['pk1'];
		
		$qry = "DELETE FROM ols_questioncategory WHERE id = $id";
		$result=mysql_query($qry);

		if (! $result) {
			 die('Invalid query: ' . mysql_error());

		}
	}
	
	$qry = "SELECT * FROM ols_questioncategory ORDER BY name";
			
	$result=mysql_query($qry);
	$rowNumber = 0;
	
	//Check whether the query was successful or not
	if($result) {
		
		while($member = mysql_fetch_assoc($result)) {
			echo "<tr><td>";
			echo $member['name'];
			echo "</td>";
			
			echo "<td title='Delete'><img onclick='deleteCategory(" . $member['id'] . ", \"" . $member['name'] . "\")' src='images/delete.png' /></td>"; 
			
			echo "</tr>";
		} 			
		
	} else {
		 die('Invalid query: ' . mysql_error());

	}
?>
</table>
<script>
	var currentCategory = null;
	
	function removeCategory() {
		call("removeCategory", {
			"pk1": currentCategory
		});
	}
	
	function deleteCategory(id, category) {
		currentCategory = id;
		
		$("#confirmdialog .confirmdialogbody").html("You are about to remove category <b><i>'"  + category + "'</i></b>.<br>Are you sure ?");
		$("#confirmdialog").dialog("open");
	}
</script>
<!--  End of content -->

<?php include("system-footer.php"); ?>

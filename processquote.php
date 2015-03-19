<?php 
	require_once("quotationitem.php");
	include("system-header.php"); 
	
	function delete() {
		$_SESSION['QUOTATION'][$_POST['pk1']]->deleted = true;
	}
?>
<form class="contentform" method="post" action="newquoteitem.php">
	<div class="left">
		<input type="hidden" id="callback" name="callback" value="confirmedquote.php" />
		
		<label>Site<label>
		<?php createCombo("siteid", "id", "name", "datatech_sites"); ?>
		<br>
		
		<label>Customer Name<label>
		<?php createCombo("customerid", "id", "name", "datatech_clients"); ?>
		<br>
		
		<label>CCF / Customer PO Number<label>
		<input type="text" id="ccfpo" name="ccfpo" value="" style="width:100px" align=right />
		<br>
		
		<label>CCF / Customer PO Value<label>
		<input type="text" id="ccfpovalue" name="ccfpovalue" value="" style="width:100px" align=right />
		<br>
	</div>
	
	<div class="right">
		
		<label>Cab Install Date<label>
		<input type="text" id="cabinstalldate" name="cabinstalldate" class="datepicker" value="" />
		<br>
		
		<label>Contact<label>
		<?php createCombo("contactid", "member_id", "login", "datatech_members"); ?>
		<br>
		
		<label>Required By<label>
		<input type="text" id="requiredby" name="requiredby" class="datepicker" value="" />
		<br>
		
		<label>COST CODE<label>
		<select id="costcode" name="costcode">
			<option value="CAPEXCCF">CAPEX DEAL RELATED CCF</option>
			<option value="CAPEXINTERNAL">CAPEX NON DEAL RELATED - INTERNAL</option>
			<option value="OPEXCCF">OPEX DEAL RELATED CCF</option>
			<option value="OPEXINTERNAL">OPEX NON DEAL RELATED - INTERNAL</option>
		</select>
		<br>
		
	</div>
	
	<label>Notes</label>
	<textarea id="notes" name="notes" cols=76 rows=6></textarea>
	<br>
	
	<input class="proceed" type="submit" value="CONFIRM" />
</form>

<?php include("system-footer.php"); ?>
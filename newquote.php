<?php 
	require_once("quotationitem.php");
	include("system-header.php"); 
	
	if (! isset($_SESSION['QUOTATIONHEADER'])) {
		$_SESSION['QUOTATIONHEADER'] = new QuotationHeader();
	}
	
	$header = $_SESSION['QUOTATIONHEADER'];
?>
<form class="contentform" method="post" action="newquoteitem.php?fromheader=true">
	<input type="button" class="dataButton" value="NOTES" id="btnHeanerNotes" name="btnHeanerNotes" />
	
	<div>
		
		<label>Site<label>
		<?php createCombo("siteid", "id", "name", "datatech_sites"); ?>
		<br>
		
		<label>Customer Name<label>
		<?php createCombo("customerid", "id", "name", "datatech_clients"); ?>
		<br>
		
		<label>CCF / Customer PO Number<label>
		<input type="text" id="ccfpo" name="ccfpo" style="width:100px" value="<?php echo $header->ccf; ?>" align=right />
		<br>
		
		<label>CCF / Customer PO Value<label>
		<input type="text" id="ccfpovalue" name="ccfpovalue" style="width:100px" value="<?php echo $header->ccfvalue; ?>" align=right />
		<br>
		
		<label>Cab Install Date<label>
		<input type="text" id="cabinstalldate" name="cabinstalldate" class="datepicker" value="<?php echo $header->cabinstalldate; ?>" />
		<br>
		
		<label>Contact<label>
		<?php createCombo("contactid", "member_id", "login", "datatech_members"); ?>
		<br>
		
		<label>Required By<label>
		<input type="text" id="requiredby" name="requiredby" class="datepicker" value="<?php echo $header->requiredbydate; ?>" />
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
	
	<div class="modal" id="notesDialog">
		<label>NOTES</label>
		<textarea id="notespopup" name="notespopup" cols=80 rows=5><?php echo $header->notes; ?></textarea>
	</div>
	
	<input type="hidden" id="notes" name="notes" value="<?php echo $header->notes; ?>" />
	<input class="proceed commandButton" type="submit" value="PROCEED" />
</form>

<script>
	$(document).ready(function() {
			$("#costcode").val("<?php echo $header->costcode; ?>");
			$("#customerid").val("<?php echo $header->customer; ?>");
			$("#siteid").val("<?php echo $header->siteid; ?>");
			$("#contactid").val("<?php echo $header->contactid; ?>");
			
			<?php
				if (isset($_SESSION['QUOTATION'])) {
					echo '$("#siteid").attr("disabled", true);';
				}
			?>
			
			$("#notespopup").change(
					function() {
						$("#notes").val($("#notespopup").val());
					}
				);

			$("#notesDialog").dialog({
					autoOpen: false,
					modal: true,
					width: 400,
					height: 300,
					title: "Notes",
					buttons: {
						Ok: function() {
							$(this).dialog("close");
						}
					}
				});
				
			/* Notes entry. */				
			$("#btnHeanerNotes").click(
					function() {
						$("#notesDialog").dialog("open");
					}
				);
		});

</script>

<?php include("system-footer.php"); ?>
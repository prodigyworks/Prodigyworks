<?php
	require_once("crud.php");
	
	class LibLengthCrud extends Crud {
		/* Post script event. */
		public function postScriptEvent() {
?>
			function postDeleteEvent(id) {
				callAjax(
						"cruddelete.php", 
						{ 
							table: "<?php echo "{$_SESSION['DB_PREFIX']}pricebreaks"; ?>",
							pkname: "productlengthid",
							id: id
						}
					);
			}
		        

			function onOpenEditDialog() {
				$("#length").focus();
			}
			
			function price_onchange() {
				$("#price").val(getRealNumber($("#price").val(), 2));
			}
			
			function fromunit_onchange() {
				$("#fromunit").val(getRealNumber($("#fromunit").val(), 0));
			}
			
			function tounit_onchange() {
				$("#tounit").val(getRealNumber($("#tounit").val(), 0));
			}
			
			function length_onchange() {
				$("#length").val(getRealNumber($("#length").val(), 0));
			}
			
			function format_decimal(el, cval, opts) {
				return new Number(el).toFixed(2);
			}
<?php
		}
		
		public function postAddScriptEvent() {
?>
			callAjax(
					"finddata.php", 
					{ 
						sql: "SELECT A.name AS productname, B.name as categoryname, C.name AS parentname FROM <?php echo $_SESSION['DB_PREFIX'];?>products A INNER JOIN <?php echo $_SESSION['DB_PREFIX'];?>categories B ON B.id = A.categoryid INNER JOIN <?php echo $_SESSION['DB_PREFIX'];?>categories C ON C.id = B.parentcategoryid WHERE A.id = <?php echo $_GET['id']; ?>"
					},
					function(data) {
						if (data.length > 0) {
							var node = data[0];

							$("#productname").val(node.productname);
							$("#parentname").val(node.parentname);
							$("#categoryname").val(node.categoryname);
						}
					},
					false
				);
<?php
		}
		
		function __construct() {
	        parent::__construct();
	        
	        $this->onOpenEditDialog = "onOpenEditDialog";
			$this->title = "Long Lines";
			$this->postDeleteEvent = "postDeleteEvent";
			$this->table = "{$_SESSION['DB_PREFIX']}productlengths";
			$this->dialogwidth = 580;
			$this->sql = 
					"SELECT A.*, AA.name AS productname, B.name as categoryname, C.name AS parentname, " .
					"E.price, E.fromunit, E.tounit, E.id AS pbid " .
					"FROM {$_SESSION['DB_PREFIX']}productlengths A " .
					"INNER JOIN {$_SESSION['DB_PREFIX']}products AA " .
					"ON AA.id = A.productid " .
					"INNER JOIN {$_SESSION['DB_PREFIX']}categories B " .
					"ON B.id = AA.categoryid " .
					"INNER JOIN {$_SESSION['DB_PREFIX']}categories C " .
					"ON C.id = B.parentcategoryid " .
					"INNER JOIN {$_SESSION['DB_PREFIX']}pricebreaks E " .
					"ON E.productlengthid = A.id " .
					"WHERE A.productid = " . $_GET['id'] . " " .
					"ORDER BY A.length";
			
			$this->columns = array(
					array(
						'name'       => 'id',
						'length' 	 => 6,
						'pk'		 => true,
						'showInView' => false,
						'editable'	 => false,
						'bind' 	 	 => false,
						'filter'	 => false,
						'label' 	 => 'ID'
					),
					array(
						'name'       => 'productid',
						'showInView' => false,
						'editable'	 => false,
						'length' 	 => 10,
						'default'	 => $_GET['id'],
						'label' 	 => 'Product ID'
					),
					array(
						'name'       => 'parentname',
						'length' 	 => 20,
						'readonly'	 => true,
						'bind'		 => false,
						'label' 	 => 'Type'
					),
					array(
						'name'       => 'categoryname',
						'length' 	 => 20,
						'readonly'	 => true,
						'bind'		 => false,
						'label' 	 => 'Category'
					),
					array(
						'name'       => 'productname',
						'length' 	 => 60,
						'readonly'	 => true,
						'bind'		 => false,
						'label' 	 => 'Product'
					),
					array(
						'name'       => 'length',
						'length' 	 => 12,
						'align'	 	 => 'right',
						'onchange'	 => 'length_onchange',
						'label' 	 => 'Length (M)'
					),
					array(
						'name'       => 'fromunit',
						'length' 	 => 10,
						'align'	 	 => 'right',
						'bind'		 => false,
						'onchange'	 => 'fromunit_onchange',
						'label' 	 => 'From Unit'
					),
					array(
						'name'       => 'tounit',
						'length' 	 => 10,
						'align'	 	 => 'right',
						'bind'		 => false,
						'onchange'	 => 'tounit_onchange',
						'label' 	 => 'To Unit'
					),
					array(
						'name'       => 'pbid',
						'length' 	 => 6,
						'showInView' => false,
						'editable'	 => false,
						'bind' 	 	 => false,
						'filter'	 => false,
						'label' 	 => 'ID'
					),
					array(
						'name'       => 'price',
						'length' 	 => 15,
						'align'	 	 => 'right',
						'onchange'	 => 'price_onchange',
						'bind'		 => false,
						'formatter'	 => 'format_decimal',
						'label' 	 => 'Price'
					)
				);
				
		}
		
		public function postInsertEvent() {
			$price = $_POST['price'];
			$fromunit = $_POST['fromunit'];
			$tounit = $_POST['tounit'];
			$id = 0;
			
			if (mysql_errno() == 0) {
				$id = mysql_insert_id();
				
			} else {
				if (mysql_errno() == 1062) {
					$qry = "SELECT id FROM {$_SESSION['DB_PREFIX']}productlengths " .
							"WHERE productid = " . $_GET['id'] . " " .
							"AND length = " . $_POST['length'];
					$result = mysql_query($qry);
					
					//Check whether the query was successful or not
					if ($result) {
						while (($member = mysql_fetch_assoc($result))) {
							$id = $member['id'];
						}
					}
					
				} else {
					logError(mysql_error());
				}
			}
			
			$qry = "INSERT INTO {$_SESSION['DB_PREFIX']}pricebreaks " .
					"(" .
					"productlengthid, fromunit, tounit, price" .
					") " .
					"VALUES " .
					"(" .
					"$id, $fromunit, $tounit, $price" .
					")";
			$result = mysql_query($qry);
			
			if (! $result) {
				logError($qry . " - " . mysql_error());
			}
		}
		
		public function postUpdateEvent($id) {
			$pbid = $_POST['pbid'];
			$price = $_POST['price'];
			$fromunit = $_POST['fromunit'];
			$tounit = $_POST['tounit'];
			
			$qry = "UPDATE {$_SESSION['DB_PREFIX']}pricebreaks SET " .
					"price = $price," .
					"fromunit = $fromunit, " .
					"tounit = $tounit " .
					"WHERE id = $pbid";
			$result = mysql_query($qry);
			
			if (! $result) {
				logError($qry . " - " . mysql_error());
			}
		}
	}
?>

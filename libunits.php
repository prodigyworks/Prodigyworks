<?php
	require_once("crud.php");
	
	class LibLengthCrud extends Crud {
		/* Post script event. */
		public function postScriptEvent() {
?>
			function onOpenEditDialog() {
				$("#fromunit").focus();
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
						sql: "SELECT E.length, A.name AS productname, B.name as categoryname, C.name AS parentname FROM <?php echo $_SESSION['DB_PREFIX'];?>productlengths E INNER JOIN <?php echo $_SESSION['DB_PREFIX'];?>products A ON A.id = E.productid INNER JOIN <?php echo $_SESSION['DB_PREFIX'];?>categories B ON B.id = A.categoryid INNER JOIN <?php echo $_SESSION['DB_PREFIX'];?>categories C ON C.id = B.parentcategoryid WHERE E.id = <?php echo $_GET['id']; ?>"
					},
					function(data) {
						if (data.length > 0) {
							var node = data[0];

							$("#productname").val(node.productname);
							$("#parentname").val(node.parentname);
							$("#categoryname").val(node.categoryname);
							$("#length").val(node.length);
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
			$this->table = "{$_SESSION['DB_PREFIX']}pricebreaks";
			$this->dialogwidth = 580;
			$this->sql = 
					"SELECT A.length, AA.name AS productname, B.name as categoryname, C.name AS parentname, " .
					"E.id, E.productlengthid, E.price, E.fromunit, E.tounit " .
					"FROM {$_SESSION['DB_PREFIX']}productlengths A " .
					"INNER JOIN {$_SESSION['DB_PREFIX']}products AA " .
					"ON AA.id = A.productid " .
					"INNER JOIN {$_SESSION['DB_PREFIX']}categories B " .
					"ON B.id = AA.categoryid " .
					"INNER JOIN {$_SESSION['DB_PREFIX']}categories C " .
					"ON C.id = B.parentcategoryid " .
					"INNER JOIN {$_SESSION['DB_PREFIX']}pricebreaks E " .
					"ON E.productlengthid = A.id " .
					"WHERE E.productlengthid = " . $_GET['id'] . " " .
					"ORDER BY E.fromunit, E.tounit";
			
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
						'name'       => 'productlengthid',
						'showInView' => false,
						'editable'	 => false,
						'length' 	 => 10,
						'default'	 => $_GET['id'],
						'label' 	 => 'Product Length ID'
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
						'readonly'	 => true,
						'bind'		 => false,
						'label' 	 => 'Length (M)'
					),
					array(
						'name'       => 'fromunit',
						'length' 	 => 10,
						'align'	 	 => 'right',
						'onchange'	 => 'fromunit_onchange',
						'label' 	 => 'From Unit'
					),
					array(
						'name'       => 'tounit',
						'length' 	 => 10,
						'align'	 	 => 'right',
						'onchange'	 => 'tounit_onchange',
						'label' 	 => 'To Unit'
					),
					array(
						'name'       => 'price',
						'length' 	 => 15,
						'align'	 	 => 'right',
						'onchange'	 => 'price_onchange',
						'formatter'	 => 'format_decimal',
						'label' 	 => 'Price'
					)
				);
				
		}
	}
?>

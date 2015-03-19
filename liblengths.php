<?php
	require_once("crud.php");
	
	class LibLengthCrud extends Crud {
		
		public function getUnitApplication() {
			return "manageunits.php";
		}
		
		/* Post script event. */
		public function postScriptEvent() {
?>
			function onOpenEditDialog() {
				$("#length").focus();
			}
			
			function length_onchange() {
				$("#length").val(getRealNumber($("#length").val(), 0));
			}
			
			function onDblClick(id) {
				subApp("<?php echo $this->getUnitApplication(); ?>", id);
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
	        $this->onDblClick = "onDblClick";
			$this->table = "{$_SESSION['DB_PREFIX']}productlengths";
			$this->dialogwidth = 580;
			$this->sql = 
					"SELECT A.*, AA.name AS productname, B.name as categoryname, C.name AS parentname " .
					"FROM {$_SESSION['DB_PREFIX']}productlengths A " .
					"INNER JOIN {$_SESSION['DB_PREFIX']}products AA " .
					"ON AA.id = A.productid " .
					"INNER JOIN {$_SESSION['DB_PREFIX']}categories B " .
					"ON B.id = AA.categoryid " .
					"INNER JOIN {$_SESSION['DB_PREFIX']}categories C " .
					"ON C.id = B.parentcategoryid " .
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
					)
				);
				
			$this->subapplications = array(
					array(
						'title'		  => 'Units',
						'imageurl'	  => 'images/length.png',
						'application' => $this->getUnitApplication()
					)
				);
		}
	}
?>

<?php
require_once('php-sql-parser.php');
require_once('php-sql-creator.php');

class ProductView  {
	private $orderColumn = "";
	private $fromrow = 0;
	private $torow = 10;
	private $pagesize = 10;
	private $rowcount = 0;
	private $pages = 1;
	private $sortby = "";
	private $sortdirection = "ASC";
	
	public $sql = "";
	public $rowsql = "";
	
	function __construct() {
		require_once('system-db.php');
		
		start_db();
		initialise_db();
		
		$this->pagesize = ($this->torow - $this->fromrow);
	}
	
	public function run() {
		$this->view();
	}
	
	public function view() {
		require_once("system-header.php");
		
		$this->createView();
	}
	
	public function createView() {
	?>
	<div id="productlist">
	</div>
	<div id="buttons">
		<button id="startButton">Start</button>
		<button id="prevButton">Prev</button>
		<button id="nextButton">Next</button>
		<button id="endButton">End</button>
	</div>
		
	<script>
		var sortByColumn = "<?php echo $this->sortby; ?>";
		var sortByDirection = "<?php echo $this->sortdirection; ?>";
		var fromRow = 0;
		var toRow = "<?php echo $this->torow; ?>";
		var pages = "<?php echo $this->pages; ?>";
		
		function refresh() {
			document.body.style.cursor = "wait";
			
			setTimeout(refreshData, 0);
		}
		
		function refreshData() {
			callAjax(
					"finddata.php", 
					{ 
						sql: "<?php echo $this->sql; ?>",
						direction: sortByDirection,
						from: fromRow,
						to: <?php echo $this->pagesize; ?>
					},
					function(data) {
						var marker = false;
						
						var i = 0;
						var indexNo = 1;
						var item;
						var view = "";
						
						for (i = 0; i < data.length; i++) {
							var node = data[i];
/*							
							if (node.imageid != null && node.imageid != 0) {
								view += "<img src='system-imageviewer.php?id=" + node.imageid + "' /> ";
								
							} else {
								view += "<img src='images/noimage.png' /> ";
							}
	*/						
							if (node.type == "P") {
								view += "<a href='product.php?id=" + node.id + "'><h2>" + node.name + "</h2></a>\n";
								view += "<div>" + node.maindescription + "</a></div><br><br>\n";
								
							} else {
								view += "<a target='_new' href='viewdocuments.php?id=" + node.id + "'><h2>" + node.name + "</h2></a>\n";
								view += "<div>" + node.maindescription + "</a></div><br><br>\n";
							}
	
						}
						
						var frompage = (fromRow / <?php echo $this->pagesize; ?>);
						var idx = 0;
						
						for (var i = frompage; i < pages; i++, idx++) {
							if (idx > 5) {
								break;
							}
							
							view += "<span class='pagenumber'>" + (i + 1) + "</span>";
						}
						
						$("#productlist").html(view);
						
						document.body.style.cursor = "default";
					}
			);
		}
		
		$(document).ready(
				function() {
					<?php
					$this->rowcount = $this->getRowCount();
					$this->pages = intval($this->rowcount / $this->pagesize);
					
					if (($this->rowcount % $this->pagesize) > 0) {
						$this->pages++;
					}
					
					if ($this->pages == 0) {
						$this->pages = 1;
					}
					?>
					pages = <?php echo $this->pages; ?>;
					refreshData();
							
					var marker = false;
						
						
					$("#startButton").click(
							function() {
								fromRow = 0;
								toRow = <?php echo $this->pagesize; ?>;
								
								refresh();
							}
						);
						
					$("#endButton").click(
							function() {
								fromRow = <?php echo intval(($this->pages - 1) * ($this->pagesize)) ; ?>;
								toRow = <?php echo $this->pagesize; ?>;
								
								refresh();
							}
						);
						
					$("#prevButton").click(
							function() {
								if (fromRow > 0) {
									fromRow = parseInt(fromRow) - parseInt(<?php echo $this->pagesize; ?>);
									toRow = <?php echo $this->pagesize; ?>;
									
									refresh();
								}
							}
						);
						
					$("#nextButton").click(
							function() {
								if (toRow < <?php echo $this->rowcount; ?>) {
									fromRow = parseInt(fromRow) + parseInt(<?php echo $this->pagesize; ?>);
									toRow = <?php echo $this->pagesize; ?>;
									
									refresh();
								}
							}
						);
						
				}
			);
	
	</script>
	<?php
		require_once("system-footer.php");
	}

	
	private function getRowCount() {
		$result = mysql_query($this->rowsql);
		$amount = 0;
		
		if (! $result) {
			logError($parser->parsed . " = " . mysql_error());
		}
		
		//Check whether the query was successful or not
		if ($result) {
			while (($member = mysql_fetch_assoc($result))) {
				$amount += $member['a'];
			}
			
		} else {
			logError($this->rowsql . " - " . mysql_error());
		}
		
		return $amount;
	}
}
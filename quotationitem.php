<?php
	class PanelItem {
	    public $from;
	    public $to = array();
	}
	
	class LongLineItem {
	    public $item = array();
	}
	
	class PanelSubItemItem {
	    public $area = "";
	    public $cabinet = "";
	    public $position;
	    public $location;
	    public $ulocation;
	}
	
	class LongLineSubItem {
	    public $fromarea = "";
	    public $fromcabinet = "";
	    public $toarea = "";
	    public $tocabinet = "";
	    public $notes = "";
	}
	
	class QuotationItem {
	    // property declaration
	    public $type = "";
	    public $cat1 = "";
	    public $cat2 = "";
	    public $cat3 = "";
	    public $productid = 0;
	    public $productdesc = "";
	    public $qty = 0;
	    public $price = 0;
	    public $total = 0;
	    public $length = 0;
	    public $productlengthid = 0;
	    public $inout = "";
	    public $notes = "";
	    public $deleted = false;
	    public $longlineitems;
	    public $panelitems;
	}
	
	class QuotationHeader {
	    public $siteid = 0;
	    public $contactid = 0;
	    public $costcode = "";
	    public $customer = "";
	    public $ccf = "";
	    public $ccfvalue = "";
	    public $cabinstalldate = "";
	    public $requiredbydate = "";
	    public $notes = "";
	}

?>

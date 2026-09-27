<?php 
$season = (!empty($this->input->get('season', TRUE))) ? $this->input->get('season', TRUE) : '';
$auction_center = (!empty($this->input->get('auction_center', TRUE))) ? $this->input->get('auction_center', TRUE) : '';
$sale_no = (!empty($this->input->get('sale_no', TRUE))) ? $this->input->get('sale_no', TRUE) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Grade Wise Top Price Realized SALE NO - <?php echo $sale_no ; ?></title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
<style>
	body{
		font-family:verdana !important;
		background-color: white !important;
		margin-top:10px;
	}

    table {
        width: 100%;
        border-collapse: collapse;
        font-size:12px;
    }

    th {
        border: 1px solid #000;
        padding: 5px 5px;
        text-align: center;
        text-transform: uppercase;
    }

    td {
        border: 1px solid #000;
        padding: 5px 5px;
        text-align: left;
        width: 12.5%;
    }

    td:nth-child(2) {
        width: 25%;
    }
    
    /* Serial column styling */
    td:first-child {
        text-align: center;
        width: 5%;
        font-weight: bold;
    }

	@media print{    
		thead { display: table-header-group; }
		.no-print, .no-print * {
            display: none !important;
        }
		a[href]:after { content: none !important; }
		/* DATA GRID ALIGN */
		.col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
			float: left;
		}
		.col-sm-12 { width: 100%; }
		.col-sm-11 { width: 91.66666667%; }
		.col-sm-10 { width: 83.33333333%; }
		.col-sm-9 { width: 75%; }
		.col-sm-8 { width: 66.66666667%; }
		.col-sm-7 { width: 58.33333333%; }
		.col-sm-6 { width: 50%; }
		.col-sm-5 { width: 41.66666667%; }
		.col-sm-4 { width: 33.33333333%; }
		.col-sm-3 { width: 25%; }
		.col-sm-2 { width: 22%; }
		.col-sm-1 { width: 8.33333333%; }
	}

    @page{
		size: A4 portrait;
	}
</style>
</head>
<body>
<?php
// Drop the temporary table if it exists
$this->db->query("DROP TABLE IF EXISTS grade_wise_top_price_realized");

// Create the temporary table directly
$this->db->query("
    CREATE TABLE grade_wise_top_price_realized AS
    SELECT 
		invoice_entry.garden_id, 
		garden_and_company_setup.garden_name,
		tea_grade_setup.grade_category,
		tea_grade_setup.grade_type,
        CONCAT(tea_grade_setup.grade_name,' ',invoice_entry.additional_grade) AS grade_name, 
        invoice_entry.chest_number, 
        IFNULL(sale_entry.sold_price,0) AS sold_price,
        b1.short_key_code AS buyer1, 
		(CASE
			WHEN `sale_entry`.buyer_type1='Internal' THEN 'INT'
			WHEN `sale_entry`.buyer_type1='Export' THEN 'EXP'
			ELSE NULL
		END) AS buyer_type1,
        b2.short_key_code AS buyer2,
		(CASE
			WHEN `sale_entry`.buyer_type2='Internal' THEN 'INT'
			WHEN `sale_entry`.buyer_type2='Export' THEN 'EXP'
			ELSE NULL
		END) AS buyer_type2,
        b3.short_key_code AS buyer3,
		(CASE
			WHEN `sale_entry`.buyer_type3='Internal' THEN 'INT'
			WHEN `sale_entry`.buyer_type3='Export' THEN 'EXP'
			ELSE NULL
		END) AS buyer_type3

    FROM invoice_entry
    LEFT JOIN tea_grade_setup ON invoice_entry.grade_id = tea_grade_setup.id
    LEFT JOIN garden_and_company_setup ON invoice_entry.garden_id = garden_and_company_setup.id
    LEFT JOIN sale_entry ON invoice_entry.id = sale_entry.invoice_id
    LEFT JOIN buyer_setup b1 ON sale_entry.buyer1 = b1.id
    LEFT JOIN buyer_setup b2 ON sale_entry.buyer2 = b2.id
    LEFT JOIN buyer_setup b3 ON sale_entry.buyer3 = b3.id
    WHERE invoice_entry.published = 'Yes'
        AND invoice_entry.season = " . $this->db->escape($season) . "
        AND invoice_entry.auction_center = " . $this->db->escape($auction_center) . "
        AND invoice_entry.sale_no = " . $this->db->escape($sale_no) . "
        AND invoice_entry.soft_delete = 1
    ORDER BY invoice_entry.lot_no ASC
");
?>

<div class="container">

<div class="row no-print">
   <div class="form-group col-sm-12 text-right">
       <a class="btn-default" href="#" onclick="window.print(); return false;">
           <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print
       </a>
   </div>
</div>

<div class="row">
    <div class="col-sm-12 form-group text-center">
        <b style="font-size:14px; font-weight:800;"><u>Top Prices Realized In Our Catalogue In Sale No. <?php echo $sale_no ; ?></u></b>
    </div>  

    <div class="col-sm-12 form-group">
        <?php 
        // Define the fixed order of categories
        $category_order = array(
            'BROKS(C)', 'FNGS(C)', 'DM(PF)(C)', 'DM(C)', 'RD(C)', 'PD(C)', 'DUST(C)', 'CD(C)',
            'BROKS', 'FNGS', 'DM(PF)', 'DM', 'RD', 'PD', 'DUST', 'CD'
        );
        
        $query = $this->db->query("SELECT grade_type, (CASE WHEN `grade_type` = 'Clone' THEN 'A' ELSE 'B' END) AS grade_type_wise FROM grade_wise_top_price_realized GROUP BY grade_type ORDER BY grade_type_wise ASC");
        $grade_types = $query->result_array();
        
        foreach ($grade_types as $grade_type) { 
        ?>
        <table class="selectable-table" style="margin-bottom:15px;">
            <thead>
                <tr>
                    <th>SL</th>
                    <th>CATEGORY</th>
                    <th>GARDEN</th>
                    <th>GRADE</th>
                    <th>PRICE TK.</th>
                    <th>PKGS</th>
                    <th>BUYER</th>
                    <th>EXP/INT</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                // Initialize serial number
                $serial = 1;
                
                // Get all categories that exist for this grade_type
                $query = $this->db->query("
                    SELECT DISTINCT grade_category
                    FROM grade_wise_top_price_realized 
                    WHERE grade_type='" . $grade_type['grade_type'] . "'
                    ORDER BY FIELD(grade_category, '" . implode("','", $category_order) . "')
                ");
                $categories = $query->result_array();
                
                // Process each category in the fixed order
                foreach ($categories as $category) {
                    // For each category, get the top price record
                    $record_query = $this->db->query("
                        SELECT 
                            garden_name,
                            grade_name,
                            MAX(sold_price) AS max_price, 
                            chest_number,
                            (CASE
                                WHEN `buyer1` != '' AND `buyer2` != '' AND `buyer3` != '' THEN CONCAT(`buyer1`, ' / ', `buyer2`, ' /', `buyer3`)
                                WHEN `buyer1` != '' AND `buyer2` != '' THEN CONCAT(`buyer1`, ' / ', `buyer2`)
                                WHEN `buyer1` != '' THEN `buyer1`
                                WHEN `buyer2` != '' THEN `buyer2`
                                WHEN `buyer3` != '' THEN `buyer3`
                                ELSE ''
                            END) AS buyer,
                            (CASE
                                WHEN `buyer_type1` != '' AND `buyer_type2` != '' AND `buyer_type3` != '' THEN CONCAT(`buyer_type1`, ' / ', `buyer_type2`, ' / ', `buyer_type3`)
                                WHEN `buyer_type1` != '' AND `buyer2` != '' THEN CONCAT(`buyer_type1`, ' / ', `buyer_type2`)
                                WHEN `buyer_type1` != '' THEN `buyer_type1`
                                WHEN `buyer_type2` != '' THEN `buyer_type2`
                                WHEN `buyer_type3` != '' THEN `buyer_type3`
                                ELSE ''
                            END) AS type
                        FROM grade_wise_top_price_realized 
                        WHERE grade_category = '" . $category['grade_category'] . "'
                        AND grade_type='" . $grade_type['grade_type'] . "'
                        AND sold_price = (SELECT MAX(sold_price) FROM grade_wise_top_price_realized WHERE grade_category = '" . $category['grade_category'] . "' AND grade_type='" . $grade_type['grade_type'] . "')
                        GROUP BY garden_id, grade_category
                        LIMIT 1
                    ");
                    
                    $record = $record_query->row_array();
                    
                    if ($record) {
                ?>
                <tr>
                    <td><?php echo $serial++; ?></td>
                    <td><?php echo $category['grade_category'] ; ?></td>
                    <td><?php echo $record['garden_name'] ; ?></td>
                    <td><?php echo $record['grade_name'] ; ?></td>
                    <td style="text-align:right;"><?php echo number_format($record['max_price'],2,'.','') ; ?></td>
                    <td style="text-align:center;"><?php echo $record['chest_number'] ; ?></td>
                    <td><?php echo $record['buyer'] ; ?></td>
                    <td style="text-align:center;"><?php echo $record['type'] ; ?></td>
                </tr>
                <?php 
                    }
                }
                ?>
            </tbody>
        </table>
        <?php } ?>
    </div>
</div>

</div> 
<!-- container -->
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script>
    $('.selectable-table tbody tr').click(function() {
        $(this).toggleClass('row-highlighted');
        $(this).css('background-color', $(this).hasClass('row-highlighted') ? 'cyan' : '');
    });
</script>
</body>
</html>
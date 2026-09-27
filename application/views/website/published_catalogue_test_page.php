<!DOCTYPE html>
<html lang="en">
<head>
<title>CATALOGUE PUBLISHED TEST PURPOSE</title>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="<?php echo base_url().'assets/bootstrap/3.4.1/css/bootstrap.min.css'; ?>">
<link rel="icon" href="<?php echo base_url().'assets/img/favicons/favicon.ico'; ?>">
<link rel="stylesheet" type="text/css" href="<?php echo base_url().'assets/css/bootstrap-glyphicons.css'; ?>">

<script src="<?php echo base_url().'assets/js/multi_filter_dt/jquery-3.3.1.js'; ?>"></script>		
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/jquery.min.js'; ?>"></script>
<script src="<?php echo base_url().'assets/bootstrap/3.4.1/js/bootstrap.min.js'; ?>"></script>

<style>
body {
    margin: 0;
    padding: 0;
}

.print-item {
    width: 100%;
    height: 297mm;
    box-sizing: border-box;
    border: 1px solid red;
    margin-bottom: 30px;
    padding: 10px;
}

@media print {
    body {
        margin: 0;
        padding: 0;
    }

    @page {
        size: A4;
        /* Set the fixed height for each printed page */
        height: 250mm;
    }

    .print-item {
        width: 210mm;
        height: 297mm;
        box-sizing: border-box;
        border: 2px solid red;
        padding: 10px;

        /* Add page break before each item */
        page-break-before: always;
    }

    /* Remove page break before the first item */
    .print-item:first-child {
        page-break-before: auto;
    }
}
</style>
</head>

<body>
<div class="container">
    <div class="col-sm-12">
        <?php
            for ($i = 1; $i <= 10; $i++) {
                echo "<div class='print-item'>
                    <h4>Page - $i</h4>
                    
                    <!-- Add your content for each item here -->
                    The lychee (Litchi chinensis) is a tropical tree in the soapberry family, Sapindaceae, which is native to South China, Malaysia, and northern Vietnam. It has also been introduced throughout Southeast Asia, South Asia and in parts of Southern Africa. A tall evergreen tree, it bears small fleshy sweet fruits which feature a dark brown inedible seed, a layer of translucent white fleshy sweet aril, and a pinkish-red, rough-textured soft shell on the outside. The aril has a floral scent and is most often eaten fresh.
                </div>";
            }
        ?>

        <?php
            for ($i = 20; $i <= 30; $i++) {
                echo "<div class='print-item'>
                    <h4>Page - $i</h4>
                    
                    <!-- Add your content for each item here -->
                    This photograph, which has been focus-stacked from 15 separate images, shows a whole lychee fruit, an opened fruit, and a seed.
                </div>";
            }
        ?>
    </div>
</div>
</body>
</html>
<style>
#page{
	<?php if($psize_data == 'A4'){ ?>
	width: 8.5in;
    height: 10.0in;
	<?php }else if($psize_data == 'Legal'){ ?>
	width: 8.5in;
    height: 13.0in;
	<?php }else{ ?>
	width: 8.5in;
    height: 10.0in;
	<?php } ?>
	display: block;
	margin: 0 auto;
	margin-bottom: 0.5cm;
	box-shadow: 0 0 0.5cm rgba(0,0,0,0.5);
}

@media print {
  #page{
    margin: 0;
    box-shadow: 0;
  }
}
</style>
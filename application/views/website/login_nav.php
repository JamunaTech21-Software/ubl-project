<style>
.dropdown-submenu {
	position: relative;
}

.dropdown-submenu a::after {
	transform: rotate(-90deg);
	position: absolute;
	right: 6px;
	top: .8em;
}

.dropdown-submenu .dropdown-menu {
	top: 0;
	left: 100%;
	margin-left: .1rem;
	margin-right: .1rem;
}
</style>
<nav class="navbar">
	<div class="container-fluid">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>                        
			</button>
			<a class="navbar-brand" href="#">
				<img src="<?php echo base_url().'assets/img/logo.jpg'; ?>" style="max-width:35px; margin-top: -10px;" alt="LOGO" />
			</a>
		</div>

		<div class="collapse navbar-collapse" id="myNavbar">
			<ul class="nav navbar-nav">

				<li class="<?=($this->uri->segment(2)==='web_user_home')?'active':''?>"><a href="<?php print site_url("MY_Controller/web_user_home"); ?>">Home</a></li>
				
				<li style="display:none;" class="<?=($this->uri->segment(2)==='brokers_pre_auction_table')?'active':''?>"><a href="<?php print site_url("MY_Controller/brokers_pre_auction_table"); ?>">Pre Auction</a></li>
				
				<li style="display:none;" class="<?=($this->uri->segment(2)==='brokers_auction_table')?'active':''?>"><a href="<?php print site_url("MY_Controller/brokers_auction_table"); ?>">Auction</a></li>
				
				<li style="display:none;" class="<?=($this->uri->segment(2)==='brokers_post_auction_table')?'active':''?>"><a href="<?php print site_url("MY_Controller/brokers_post_auction_table"); ?>">Post Auction</a></li>
				
				<!--<li class="<?=($this->uri->segment(2)==='web_user_add_new')?'active':''?>"><a href="<?php print site_url("MY_Controller/web_user_add_new"); ?>">Setup</a></li>-->

				<!-- SETUP MENU -->
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <span class="glyphicon glyphicon-plus" aria-hidden="true"></span> Setup
				</a>
				<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='buyer_setup') ? 'active' : ''?>" href="<?php echo site_url('MY_Controller/buyer_setup'); ?>">Buyer Setup</a>
					</li>	
					
					
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='company_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/company_setup'); ?>">Company Setup</a>
					</li>
					
					
				
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='garden_and_company_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/garden_and_company_setup'); ?>">Garden Setup</a>
					</li>
			
					
					
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='warehouse_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/warehouse_setup'); ?>">Warehouse Setup</a>
					</li>
					
					
					
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='grade_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/grade_setup'); ?>">Grade Setup</a>
					</li>
					
					
					
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='sample_draw_process') ?'active' : '' ?>" href="<?php echo site_url('MY_Controller/sample_draw_process'); ?>">Sample Draw</a>
					</li>
					
					
					
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='season_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/season_setup'); ?>">Season Setup</a>
					</li>
					
					
					
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='current_season_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/current_season_setup'); ?>">Current Season</a>
					</li>
					
					
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='sale_schedule_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/sale_schedule_setup'); ?>">Sale Schedule</a>
					</li>

					<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Catalogue &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<span class="glyphicon glyphicon-chevron-right"></span></a>
						<ul class="dropdown-menu">
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='page_size_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/page_size_setup'); ?>">Page Size Setup</a>
							</li>
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='catalogue_header_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/catalogue_header_setup'); ?>">Catalogue Header</a>
							</li>
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='catalogue_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/catalogue_setup'); ?>">Catalog Setup</a>
							</li>
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='chest_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/chest_setup'); ?>">Chest Setup</a>
							</li>
							
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='sale_condtion_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/sale_condtion_setup'); ?>">Condition of sales</a>
							</li>
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='brokers_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/brokers_setup'); ?>">Broker Setup</a>
							</li>
							
						</ul>
					</li>

					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='other_voucher_setup') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=other_voucher_setup">Add Other A/C</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='bank_setup') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=bank_setup">Add Bank A/C</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='add_new_hr') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=add_new_hr">Add HR Profile</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='contact_person') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/contact_person'); ?>">Add Contact</a>
					</li>

				</ul>
			</li>
			<!-- END SETUP MENU -->

			<!-- DATA ENTRY MENU -->
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <span class="glyphicon glyphicon-inbox" aria-hidden="true"></span> Data Entry
				</a>
				<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='invoice_entry') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/invoice_entry'); ?>">Invoice Entry</a>
					</li>	
					
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='pre_catalogue') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/pre_catalogue'); ?>">Invoice Verify</a>
					</li>
					
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='valuation_entry') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/valuation_entry'); ?>">Valuation Entry</a>
					</li>

					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='manage_page_number') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/manage_page_number'); ?>">Page & Catalog Publish</a>
					</li>

					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='sale_order_setup') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/sale_order_setup'); ?>">Sale Order Entry</a>
					</li>
					
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='tea_cup_testing') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/tea_cup_testing'); ?>">Tea Cup Testing</a>
					</li>
					
					
					<!--<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='all_garden_avg') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/all_garden_avg'); ?>">Other Brokers Average Entry</a>
					</li>-->
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='all_garden_avg_mult') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=all_garden_avg_mult">All Garden Average Entry</a>
					</li>

					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='all_broker_buyer_purchase') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/all_broker_buyer_purchase'); ?>">All Brokers Buyer Purchase Entry</a>
					</li>

					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='all_brokers_average') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/all_brokers_average'); ?>">Crop Data Entry</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='market_report_ent') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/market_report_ent'); ?>">Market Report Entry</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='sale_entry') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/sale_entry'); ?>">Sale Entry</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='garden_letter_entry') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/garden_letter_entry'); ?>">Garden Letter Entry</a>
					</li>
				
				</ul>
			</li>
			<!-- END DATA ENTRY MENU -->

			<!-- TRANSACTION MENU -->
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <span class="glyphicon glyphicon-user" aria-hidden="true"></span> Accounts
				</a>
				<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
					<!--<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='bulk_sale_entry') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/bulk_sale_entry'); ?>">Bulk Sale Entry</a>
					</li>	-->

					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='garden_cost_entry') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/garden_cost_entry'); ?>">Garden Cost Entry</a>
					</li>

					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='buyer_receive') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=buyer_receive">Buyer Receive</a>
					</li>

					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='garden_adjustment') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/garden_adjustment'); ?>">Garden Adj.</a>
					</li>

					<!--<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='garden_payment') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=garden_payment">Garden Payment</a>
					</li>-->

					<!--<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='vat_payment') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=vat_payment">Vat Payment</a>
					</li>-->
					
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='ait_payment') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=ait_payment">AIT Payment</a>
					</li>
					
					
					<!--<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='tea_board_payment') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=tea_board_payment">Tea Brd. Payment</a>
					</li>-->

					<!--<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='warehouse_payment') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=warehouse_payment">W/H Payment</a>
					</li>-->
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='other_receive_voucher') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=other_receive_voucher">Other Receive</a>
					</li>
					<!--<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='other_payment_voucher') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=other_payment_voucher">Other Payment</a>
					</li>-->
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='present_bank') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=present_bank">Present Bank</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='bank_deposit') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=bank_deposit">Bank Deposit</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='bank_withdraw') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=bank_withdraw">Bank Withdraw</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='attendance_bulk_entry') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=attendance_bulk_entry">HR Salary</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='hr_bonus_entry') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=hr_bonus_entry">Bonus Entry</a>
					</li>
					<!--<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='salary_payment') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=salary_payment">Salary Payment</a>
					</li>-->
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='suspense_account') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=suspense_account">Suspense Account</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='multi_voucher') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=multi_voucher">Multi Voucher Payment</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='banking_authorization') ? 'active' : '' ?>" href="../../../ubl_accounts/login.php?ubl_web_user_id=<?php print md5(sha1($ubl_web_user_id)); ?>&page_name=banking_authorization">Banking Authorization</a>
					</li>
				
				</ul>
			</li>
			<!-- END TRANSACTION MENU -->

				<!--<li class="<?=($this->uri->segment(2)==='brokers_entry')?'active':''?>"><a href="<?php print site_url("MY_Controller/brokers_entry"); ?>">Data Entry</a></li>-->
				
				<!--<li class="<?=($this->uri->segment(2)==='brokers_entry')?'active':''?>"><a href="<?php print site_url("MY_Controller/brokers_transection"); ?>">Transaction</a></li>-->
				
				<?php /*<li class="<?=($this->uri->segment(2)==='brokers_reports')?'active':''?>"><a href="<?php print site_url("MY_Controller/brokers_reports"); ?>">Reports</a></li>*/ ?>

			

				<!-- START REPORT MENU -->
				<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
				  <span class="glyphicon glyphicon-print" aria-hidden="true"></span> Report
				</a>
				<ul class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">	
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='market_report') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/market_report'); ?>">Market Report</a>
					</li>
					<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Warehouse Ledger</a>
						<ul class="dropdown-menu">
							
							<li>
								<a class="dropdown-item <?= ($this->uri->segment(2) === 'warehouse_ledger_details') ? 'active' : '' ?>"
								href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?= md5(sha1($ubl_web_user_id)); ?>&page_name=warehouse_ledger_details.php?rt=whr&date=<?= date('Y-m-d'); ?>&to=<?= date('Y-m-d'); ?>">
								Warehouse Vat Ledger
								</a>
							</li>
							<li>
								<a class="dropdown-item <?= ($this->uri->segment(2) === 'warehouse_ledger_details3') ? 'active' : '' ?>"
								href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?= md5(sha1($ubl_web_user_id)); ?>&page_name=warehouse_ledger_details3.php?rt=whr&date=<?= date('Y-m-d'); ?>&to=<?= date('Y-m-d'); ?>">
								Warehouse Tax Ledger
								</a>
							</li>

							<li>
								<a class="dropdown-item <?= ($this->uri->segment(2) === 'warehouse_ledger_details2') ? 'active' : '' ?>"
								href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?= md5(sha1($ubl_web_user_id)); ?>&page_name=warehouse_ledger_details2.php?rt=whr&date=<?= date('Y-m-d'); ?>&to=<?= date('Y-m-d'); ?>">
								Warehouse Rent Ledger
								</a>
							</li>
						</ul>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='pre_sale_advice_garden_letter') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/pre_sale_advice_garden_letter'); ?>">Garden Letter</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='pre_sale_advice') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/pre_sale_advice'); ?>">Tea Parameter</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='garden_sale_account_bs') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/garden_sale_account_bs'); ?>">Sales Account B/S</a>
					</li>
					<li>
						<a class="dropdown-item <?= ($this->uri->segment(2) === 'sus_acc_receive_report') ? 'active' : '' ?>"
						href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?= md5(sha1($ubl_web_user_id)); ?>&page_name=sus_acc_receive_report.php?date=<?= date('Y-m-d'); ?>&to=<?= date('Y-m-d'); ?>">
						Suspense Account Receive Report
						</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='optimised_sales_report') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/optimised_sales_report'); ?>">Optimized Sales Report</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='all_garden_buyer_report') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/all_garden_buyer_report'); ?>">Buyers Report Sale To Sale</a>
					</li>

					<li>
						<a class="dropdown-item <?= ($this->uri->segment(2) === 'buyer_ledger_details') ? 'active' : '' ?>"
						href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?= md5(sha1($ubl_web_user_id)); ?>&page_name=buyer_ledger_details.php?date=<?= date('Y-m-d'); ?>&to=<?= date('Y-m-d'); ?>">
						Buyer Ledger Report
						</a>
					</li>

					<li>
						<a class="dropdown-item <?= ($this->uri->segment(2) === 'buyer_receive_report') ? 'active' : '' ?>"
						href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?= md5(sha1($ubl_web_user_id)); ?>&page_name=buyer_receive_report.php?date=<?= date('Y-m-d'); ?>&to=<?= date('Y-m-d'); ?>">
						Buyer Receive Report
						</a>
					</li>
					<li>
						<a class="dropdown-item <?= ($this->uri->segment(2) === 'garden_ledger_details') ? 'active' : '' ?>"
						href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?= md5(sha1($ubl_web_user_id)); ?>&page_name=garden_ledger_details.php?date=<?= date('Y-m-d'); ?>&to=<?= date('Y-m-d'); ?>">
						Garden Ledger Details
						</a>
					</li>
					<li>
						<a class="dropdown-item <?= ($this->uri->segment(2) === 'garden_payment_list_report') ? 'active' : '' ?>"
						href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?= md5(sha1($ubl_web_user_id)); ?>&page_name=garden_payment_list_report.php?date=<?= date('Y-m-d'); ?>&to=<?= date('Y-m-d'); ?>">
						Garden Payment Report
						</a>
					</li>
					<li>
						<a class="dropdown-item <?= ($this->uri->segment(2) === 'multi_voucher_report') ? 'active' : '' ?>"
						href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?= md5(sha1($ubl_web_user_id)); ?>&page_name=multi_voucher_report.php?date=<?= date('Y-m-d'); ?>&to=<?= date('Y-m-d'); ?>">
						Multi Voucher Report
						</a>
					</li>
					<li>
						<a class="dropdown-item <?= ($this->uri->segment(2) === 'ait_payment_report') ? 'active' : '' ?>"
						href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?= md5(sha1($ubl_web_user_id)); ?>&page_name=ait_payment_report.php?date=<?= date('Y-m-d'); ?>&to=<?= date('Y-m-d'); ?>">
						AIT Ledger Report
						</a>
					</li>
					<li>
						<a class="dropdown-item <?= ($this->uri->segment(2) === 'hr_salary_report') ? 'active' : '' ?>"
						href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?= md5(sha1($ubl_web_user_id)); ?>&page_name=hr_salary_report.php">
						Salary Sheet Report
						</a>
					</li>
					<li>
						<a class="dropdown-item <?= ($this->uri->segment(2) === 'hr_bonus_report') ? 'active' : '' ?>"
						href="../../../ubl_accounts/report_login.php?ubl_web_user_id=<?= md5(sha1($ubl_web_user_id)); ?>&page_name=hr_bonus_report.php">
						Bonus Report
						</a>
					</li>
					<li>
						<a class="dropdown-item <?=($this->uri->segment(2)==='seller_statement') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/seller_statement'); ?>">Seller Statement Reports</a>
					</li>
					<!--<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Multi Stock Report</a>
						<ul class="dropdown-menu">
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='loose_tea_stock_report') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/loose_tea_stock_report?print_mode=&report_type=Summary&date='.date("Y-m-d")); ?>">Loose Tea Stock</a>
							</li>
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='bland_stock_report') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/bland_stock_report/stock_ledger'); ?>">Bland Tea Stock</a>
							</li>
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='finish_goods_report') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/finish_goods_report/stock_ledger'); ?>">Finish Goods Stock</a>
							</li>
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='loose_tea_history_report') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/loose_tea_history_report'); ?>">Loose Tea History</a>
							</li>
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='bland_tea_history_report') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/bland_tea_history_report'); ?>">Bland Tea History</a>
							</li>
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='fg_history_report') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/fg_history_report'); ?>">Finish Goods History</a>
							</li>
						</ul>
					</li>
					<li class="dropdown-submenu"><a class="dropdown-item dropdown-toggle" href="#">Bland & FG Report</a>
						<ul class="dropdown-menu">
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='bland_tea_report') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/bland_tea_report'); ?>">Bland Tea Report</a>
							</li>
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='fg_report') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/fg_report'); ?>">Finish Goods Report</a>
							</li>
							
							<li>
								<a class="dropdown-item <?=($this->uri->segment(2)==='bland_gl_report') ? 'active' : '' ?>" href="<?php echo site_url('MY_Controller/bland_gl_report'); ?>">Bland Gain & Loss Report</a>
							</li>
						</ul>
					</li>-->
				</ul>
			</li>
			<!-- END REPORT MENU -->
			</ul>
		  
			<ul class="nav navbar-nav navbar-right">
				<li><a href="#"><span class="glyphicon glyphicon-user text-danger"></span> <?php print $website_title; ?></a></li>
				<!--<li><a href="<?php print site_url("MY_Controller/change_password_page"); ?>">Change Password</a></li>-->
				<li><a href="<?php print site_url("MY_Controller/web_user_logout"); ?>">Logout</a></li>
			</ul>
		</div>
	</div>
</nav>
<script>
$('.dropdown-menu a.dropdown-toggle').on('click', function(e) {
  if (!$(this).next().hasClass('show')) {
    $(this).parents('.dropdown-menu').first().find('.show').removeClass("show");
  }
  var $subMenu = $(this).next(".dropdown-menu");
  $subMenu.toggleClass('show');

  $(this).parents('li.nav-item.dropdown.show').on('hidden.bs.dropdown', function(e) {
    $('.dropdown-submenu .show').removeClass("show");
  });
  return false;
});
</script>
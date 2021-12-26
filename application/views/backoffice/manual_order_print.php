<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.5.0/jQuery.print.min.js" integrity="sha512-v47mwszhm/1yl1kS+WVWt/bGs22h1F3maY5OD2oSm39Rzrqy8irjMwEDfjmP8U8Y8mTono4UZIutiXj56TGiiw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
<title><?php echo $meta_title;?></title>
<div class="page-content container" id="printable">
	<div class="page-header text-blue-d2">
		<h1 class="page-title text-secondary-d1">
			Order
			<small class="page-info">
				<i class="fa fa-angle-double-right text-80"></i>
				ID: <?php echo $order_records->entity_id; ?>
			</small>
		</h1>
	</div>

	<div class="container px-0">
		<div class="row mt-4">
			<div class="col-12 col-lg-10 offset-lg-1">
				<div class="row">
					<div class="col-12">
						<div class="text-center text-150">

							<span class="text-default-d3"><img src="<?php echo base_url(); ?>/assets/admin/img/logo.png" alt="" width="200" height="200" /> </span>
						</div>
					</div>
				</div>
				<!-- .row -->

				<hr class="row brc-default-l1 mx-n1 mb-4" />
				<?php 
				$user_detail = unserialize($menu_item->user_detail); 
				$address = json_decode($user_detail['address']);
				?>
				<div class="row">
					<div class="col-sm-6">
						<div>
							<span class="text-sm text-grey-m2 align-middle">Ordered By:</span>
							<span class="text-600 text-110 text-blue align-middle"><?php echo $user_detail['first_name'] . ' ' . $user_detail['last_name']; ?></span>
						</div>
						<div>
							<span class="text-sm text-grey-m2 align-middle">Will Deliver To:</span>
							<span class="text-600 text-110 text-blue align-middle"><?php echo $user_detail['delivery_name']; ?></span>
						</div>
						<div class="text-grey-m2">
							<div class="my-1">
								<?php echo '<br> Road No: ' . $address->RoadNo . ', Block No: ' . $address->BlockNo . ','  ?>
							</div>
							<div class="my-1">
								<?php echo 'Flat No: ' . $address->FlatNo  . ', House No: ' . $address->Houseno . ' , ' . $address->area; ?>
							</div>
							<div class="my-1"><i class="fa fa-phone fa-flip-horizontal text-secondary"></i> <b class="text-600"><?php echo $user_detail['delivery_number']; ?></b></div>
						</div>
					</div>

					<!-- /.col -->

					<div class="text-95 col-sm-6 align-self-start d-sm-flex justify-content-end">
						<hr class="d-sm-none" />
						<div class="text-grey-m2">
							<div class="mt-1 mb-2 text-secondary-m1 text-600 text-125">
								Invoice
							</div>

							<div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">ID:</span> #<?php echo $order_records->entity_id; ?></div>

							<div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Order Date:</span> <?php $date = date("d-m-Y h:i A", strtotime($order_records->order_date));
																																						echo $date; ?></div>

							<div class="my-2"><i class="fa fa-circle text-blue-m2 text-xs mr-1"></i> <span class="text-600 text-90">Delivery Date:</span> <?php echo date("d-m-Y", strtotime($order_records->delivery_time)); ?></div>
						</div>
					</div>
					<!-- /.col -->
				</div>

				<div class="mt-4">

					<div class="row border-b-2 brc-default-l2"></div>

					<!-- or use a table instead -->

					<div class="table-responsive">
						<table class="table table-striped table-borderless border-0 border-b-2 brc-default-l1">
							<thead class="bg-none bgc-default-tp1">
								<tr class="text-white">
									<th class="opacity-2">#</th>
									<th>Item Name</th>
									<th>Description</th>
									<th>Qty</th>
									<th>Unit Price</th>
									<th>Custom Fee</th>
									<th>Vat</th>
									<th width="140">Amount</th>
								</tr>
							</thead>

							<tbody class="text-95 text-secondary-d3">
								<tr></tr>

								<?php $item_detail = unserialize($menu_item->item_detail);
								if (!empty($item_detail)) {

									$i = 1;
									$subtotal = 0;
									foreach ($item_detail as $key => $value) { ?>
										<tr>
											<td><?php echo $i; ?></td>
											<td><?php echo $value['item_name']; ?></td>
											<td><?php echo $value['description']; ?></td>
											<td><?php echo $value['qty_no']; ?></td>
											<td><?php echo $value['rate']; ?></td>
											<td><?php echo $value['custom_fee']; ?></td>
											<td><?php echo $value['add_vat']; ?></td>
											<td><?php echo $sub = ($value['qty_no'] * $value['rate']) + $value['add_vat'] + $value['custom_fee'];
												$subtotal = $sub + $subtotal;
												?></td>
										<?php $i++;
									} ?>
										</tr>
									<?php }
									?>

							</tbody>
						</table>
					</div>


					<div class="row mt-3">
						<div class="col-12 col-sm-7 text-grey-d2 text-95 mt-2 mt-lg-0">
							
						</div>

						<div class="col-12 col-sm-5 text-grey text-90 order-first order-sm-last">
							<div class="row my-2">
								<div class="col-7 text-right">
									SubTotal
								</div>
								<div class="col-5">
									<span class="text-120 text-secondary-d1"><?php echo $subtotal; ?></span>
								</div>
							</div>

							<div class="row my-2">
								<div class="col-7 text-right">
									Delivery Charge
								</div>
								<div class="col-5">
									<span class="text-110 text-secondary-d1"><?php echo $order_records->delivery_charge; ?></span>
								</div>
							</div>
							<div class="row my-2">
								<div class="col-7 text-right">
									Total Amount
								</div>
								<div class="col-5">
									<span class="text-110 text-secondary-d1"><?php echo $order_records->total_rate; ?></span>
								</div>
							</div>
							<div class="row my-2">
								<div class="col-7 text-right">
									Advance
								</div>
								<div class="col-5">
									<span class="text-110 text-secondary-d1"><?php echo $order_records->advance; ?></span>
								</div>
							</div>
	

							<div class="row my-2 align-items-center bgc-primary-l3 p-2">
								<div class="col-7 text-right">
									Due Payment
								</div>
								<div class="col-5">
									<span class="text-150 text-success-d3 opacity-2"><?php echo $order_records->due; ?></span>
								</div>
							</div>
						</div>
					</div>

					<hr />

					
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	window.print();
</script>

<style>
	body {
		margin-top: 20px;
		color: #484b51;
	}

	.text-secondary-d1 {
		color: #728299 !important;
	}

	.page-header {
		margin: 0 0 1rem;
		padding-bottom: 1rem;
		padding-top: .5rem;
		border-bottom: 1px dotted #e2e2e2;
		display: -ms-flexbox;
		display: flex;
		-ms-flex-pack: justify;
		justify-content: space-between;
		-ms-flex-align: center;
		align-items: center;
	}

	.page-title {
		padding: 0;
		margin: 0;
		font-size: 1.75rem;
		font-weight: 300;
	}

	.brc-default-l1 {
		border-color: #dce9f0 !important;
	}

	.ml-n1,
	.mx-n1 {
		margin-left: -.25rem !important;
	}

	.mr-n1,
	.mx-n1 {
		margin-right: -.25rem !important;
	}

	.mb-4,
	.my-4 {
		margin-bottom: 1.5rem !important;
	}

	hr {
		margin-top: 1rem;
		margin-bottom: 1rem;
		border: 0;
		border-top: 1px solid rgba(0, 0, 0, .1);
	}

	.text-grey-m2 {
		color: #888a8d !important;
	}

	.text-success-m2 {
		color: #86bd68 !important;
	}

	.font-bolder,
	.text-600 {
		font-weight: 600 !important;
	}

	.text-110 {
		font-size: 110% !important;
	}

	.text-blue {
		color: #478fcc !important;
	}

	.pb-25,
	.py-25 {
		padding-bottom: .75rem !important;
	}

	.pt-25,
	.py-25 {
		padding-top: .75rem !important;
	}

	.bgc-default-tp1 {
		background-color: rgba(121, 169, 197, .92) !important;
	}

	.bgc-default-l4,
	.bgc-h-default-l4:hover {
		background-color: #f3f8fa !important;
	}

	.page-header .page-tools {
		-ms-flex-item-align: end;
		align-self: flex-end;
	}

	.btn-light {
		color: #757984;
		background-color: #f5f6f9;
		border-color: #dddfe4;
	}

	.w-2 {
		width: 1rem;
	}

	.text-120 {
		font-size: 120% !important;
	}

	.text-primary-m1 {
		color: #4087d4 !important;
	}

	.text-danger-m1 {
		color: #dd4949 !important;
	}

	.text-blue-m2 {
		color: #68a3d5 !important;
	}

	.text-150 {
		font-size: 150% !important;
	}

	.text-60 {
		font-size: 60% !important;
	}

	.text-grey-m1 {
		color: #7b7d81 !important;
	}

	.align-bottom {
		vertical-align: bottom !important;
	}
</style>
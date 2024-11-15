<?php
	include_once("../processPhp/Query.php");
	use ProcessPhp\Query;

	require_once("../connection.php");
	session_start();

	if (!isset($_SESSION['officials_Id'])) {
		header('Location: ../index.php');
	}
	if ($_SESSION['user_type'] == "Cashier") {
		header('Location: dashboard.php');
	}
	$user_type = $_SESSION['user_type'];

	// Categories
	$connection = $conn->query(query:
		"SELECT
			`category_Id` AS `id`,
			`category_Name` AS `name`
		FROM
			category_table
		ORDER BY
			category_Id
		DESC
	");
	$categories = fetchAll($connection);

	// Products
	$products = Query::table('products as p')
		->select([
			"p.product_Id AS id",
			"p.item_code AS item_code",
			"p.barcode AS barcode",
			"p.category_Id",
			"p.category_product_Id",
			"p.product_type_Id",
			"p.type_Id",
			"p.stocks AS stocks",
			"p.prize AS price",
			"p.archive AS is_archived",
			// OTHER TABLE NAMES
			"c.category_Name AS category_name",
			"cp.product_Name AS product_name",
			"cpi.product_item_name AS product_item_name",
			"cpit.product_item_type_name AS product_item_type_name"
		])
		->where('p.archive', 'No')
		->innerJoin('category_table as c', [['p.category_Id', 'c.category_Id']])
		->innerJoin('category_product_table as cp', [['p.category_product_Id', 'cp.category_product_Id']])
		->innerJoin('category_product_item_table as cpi', [['p.product_type_Id', 'cpi.category_product_item_Id']])
		->innerJoin('category_product_item_type_table as cpit', [['p.type_Id', 'cpit.category_product_item_type_Id']])
		->orderBy("p.product_Id", "DESC")
		->get();
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>FMJ ELECTRONICS</title>

		<link rel="stylesheet" href="../styles/sidebar.css">
		<link rel="stylesheet" href="../styles/dashboard.css">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">

		<?php // Font Links Start?>
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Saira+Condensed:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
		<?php // Font Links End?>

		<?php // JS for jQuery ?>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>

		<?php // Bootstrap Select Picker ?>
		<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
	</head>

	<body>
		<?php require_once("../templates/topNav.php") ?>

		<div class="main-container">
			<div class="left-container">
				<?php require_once("../templates/leftNav.php") ?>
			</div>

			<div class="right-container">
				<div class="row m-0 p-0">
					<div class="col-md-12">
						<div class="main-title">
							<i class="fa-solid fa-layer-group"></i><span>PRODUCTS</span>
						</div>

						<div class="addBtn-container d-flex justify-content-between mb-3">
							<form action="product_search.php" method="post" class="d-flex mr-5 mt-3">
								<input type="text" value="" name="search" id="search" class="form-control mr-1" placeholder="Search">
								<input type="submit" name="searchSubmit" value="Search" id="searchSubmit" class="btn btn-dark ml-1">
							</form>

							<?php // Button trigger modal ?>
							<button type="button" class="btn btn-primary mt-3" data-toggle="modal" data-target="#addProduct">
								ADD PRODUCT
							</button>

							<div class="modal fade" id="addProduct" tabindex="-1" role="dialog" aria-labelledby="addProductLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="addProductLabel">ADD PRODUCT</h5>

											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>

										<div class="modal-body">
											<form id="add-form" class="form needs-validation" novalidate>
												<?php // ACTION ?>
												<input type="hidden" name="action" value="addProduct">

												<div class="form-group">
													<label for="category-dropdown" style="font-size: 18px; font-weight: 600;" class="important-before">Category</label>

													<?php // CATEGORIES ?>
													<select name="category_Id" id="category-dropdown" data-dd-content="category" data-dd-target="#product-dropdown" data-live-search="true" class="form-control" required>
														<option class="font-weight-bold" style="font-size:18px;" value selected disabled hidden>Select Category</option>

														<?php if (count($categories) > 0):
															foreach ($categories as $c): ?>
																<option value="<?= $c->id ?>" class="font-weight-bold" style="font-size:18px;"><?= $c->name ?></option>
														<?php endforeach;
														endif; ?>
													</select>
												</div>

												<?php // PRODUCTS ?>
												<div class="form-group">
													<label for="product-dropdown" style="font-size: 18px; font-weight: 600;" class="important-before">Product</label>

													<select name="category_product_Id" id="product-dropdown" data-dd-content="product" data-dd-target="#product-type-dropdown" data-live-search="true" class="form-control" required>
														<option class="font-weight-bold" style="font-size:18px;" value selected disabled hidden>Select Product</option>
													</select>
												</div>

												<?php // PRODUCT TYPES ?>
												<div class="form-group">
													<label for="product-type-dropdown" style="font-size: 18px; font-weight: 600"class="important-before">Product Type</label>

													<select name="product_type_Id" id="product-type-dropdown" data-dd-content="productType" data-dd-target="#type-dropdown" data-live-search="true" class="form-control" required>
														<option class="font-weight-bold" style="font-size:18px;" value selected disabled hidden>Select Product Type</option>
													</select>
												</div>

												<?php // TYPES ?>
												<div class="form-group">
													<label for="type-dropdown" style="font-size: 18px; font-weight: 600"class="important-before">Type</label>

													<select name="type_Id" id="type-dropdown" data-dd-content="type" data-live-search="true" class="form-control" required>
														<option class="font-weight-bold" style="font-size:18px;" value selected disabled hidden>Select Type</option>
													</select>
												</div>

												<?php // BARCODE ?>
												<div class="form-group">
													<label for="barcode" style="font-size: 18px; font-weight: 600"class="important-before">Barcode</label>
													<input type="text" id="barcode" name="barcode" class="form-control" required>
												</div>

												<div class="row form-group">
													<?php // STOCKS ?>
													<div class="col-md-6">
														<label for="stocks" style="font-size: 18px; font-weight: 600"class="important-before">Stocks</label>
														<input type="number" id="stocks" name="stocks" class="form-control" min="0" required>
													</div>

													<?php // PRICE ?>
													<div class="col-md-6">
														<label for="price" style="font-size: 18px; font-weight: 600"class="important-before">Price</label>
														<input type="number" id="price" name="prize" class="form-control" min="0" step="0.25" required>
													</div>
												</div>

												<?php // Close Button ?>
												<div class="modal-footer">
													<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
													<input type="submit" value="ADD" class="btn btn-primary">
												</div>
											</form>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="table-container">
							<table class="table table-hover table-border table-sm">
								<thead>
									<tr>
										<th scope="col" class="text-center d-none" style="font-size: 20px; font-weight: 700">PRODUCT ID</th>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ITEM CODE</th>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">BARCODE</th>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">CATEGORY</th>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">PRODUCT</th>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">PRODUCT TYPES</th>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">TYPES</th>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">STOCKS</th>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">PRICE</th>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ACTION</th>
									</tr>
								</thead>

								<?php // PRODUCTS TABLE ?>
								<tbody id="tableBody">
									<?php
										if (count($products) > 0):
											foreach ($products as $p): ?>

											<tr class="text-center font-weight-bold">
												<td class="d-none" style="font-size: 18px;"><?= $p->id; ?></td>
												<td style="font-size: 18px;"><?= $p->item_code; ?></td>
												<td style="font-size: 18px;"><?= $p->barcode; ?></td>
												<td style="font-size: 18px;"><?= $p->category_name; ?></td>
												<td style="font-size: 18px;"><?= $p->product_name; ?></td>
												<td style="font-size: 18px;"><?= $p->product_item_name; ?></td>
												<td style="font-size: 18px;"><?= $p->product_item_type_name; ?>
												<td style="font-size: 18px;"><?= $p->stocks; ?></td>
												<td style="font-size: 18px;">&#8369;  <?= number_format($p->price, 2, ".", " "); ?></td>

												<?php // ACTIONS ?>
												<td class="d-flex justify-content-around align-items-center">
													<?php // Button trigger modal ?>
													<div class="d-flex justify-content-around align-items-center">
														<?php // EDIT ?>
														<button type="button" class="btn btn-secondary btn-sm mx-1" data-toggle="modal" data-target="#edit-product-modal-<?= $p->id ?>" data-id="<?= $p->id ?>">
															EDIT
														</button>

														<div class="modal fade text-left" id="edit-product-modal-<?= $p->id ?>" tabindex="-1" aria-hidden="true">
															<div class="modal-dialog" role="document">
																<form class="modal-content edit-form">
																	<div class="modal-header">
																		<h5 class="modal-title">EDIT PRODUCT</h5>

																		<button type="button" class="close" data-dismiss="modal" aria-label="Close" title="Close">
																			<span aria-hidden="true">&times;</span>
																		</button>
																	</div>

																	<div class="modal-body">
																		<input type="hidden" name="product_Id" value="<?= $p->id ?>">

																		<?php // ITEM CODE ?>
																		<div class="form-group">
																			<label for="product-item-code-<?= $p->id ?>" style="font-size: 18px; font-weight: 600"class="important-before">Item Code</label>
																			<input type="text" id="product-item-code-<?= $p->id ?>" name="item_code" class="form-control" value="<?= $p->item_code ?>" readonly>
																		</div>

																		<?php // BAR CODE ?>
																		<div class="form-group">
																			<label for="product-bar-code-<?= $p->id ?>" style="font-size: 18px; font-weight: 600"class="important-before">Bar Code</label>
																			<input type="text" id="product-bar-code-<?= $p->id ?>" name="barcode" class="form-control" value="<?= $p->barcode ?>">
																		</div>

																		<?php // CATEGORY ?>
																		<div class="form-group">
																			<label for="product-category-<?= $p->id ?>" style="font-size: 18px; font-weight: 600"class="important-before">Category</label>
																			<input type="text" id="product-category-<?= $p->id ?>" name="category_Name" class="form-control" value="<?= $p->category_name ?>" readonly>
																		</div>

																		<?php // PRODUCT ?>
																		<div class="form-group">
																			<label for="product-category-product-<?= $p->id ?>" style="font-size: 18px; font-weight: 600"class="important-before">Product</label>
																			<input type="text" id="product-category-product-<?= $p->id ?>" name="product_Name" class="form-control" value="<?= $p->product_name ?>" readonly>
																		</div>

																		<?php // PRODUCT ITEM ?>
																		<div class="form-group">
																			<label for="product-item-<?= $p->id ?>" style="font-size: 18px; font-weight: 600"class="important-before">Product Type</label>
																			<input type="text" id="product-item-<?= $p->id ?>" name="product_item_name" class="form-control" value="<?= $p->product_item_name ?>" readonly>
																		</div>

																		<?php // PRODUCT ITEM TYPE ?>
																		<div class="form-group">
																			<label for="product-item-type-<?= $p->id ?>" style="font-size: 18px; font-weight: 600"class="important-before">Type</label>
																			<input type="text" id="product-item-type-<?= $p->id ?>" name="product_item_type_name" class="form-control" value="<?= $p->product_item_type_name ?>" readonly>
																		</div>

																		<div class="row">
																			<?php // STOCKS ?>
																			<div class="col-md-6">
																				<label for="stocks-<?= $p->id ?>" style="font-size: 18px; font-weight: 600"class="important-before">Stocks</label>
																				<input type="text" id="stocks-<?= $p->id ?>" name="stocks" class="form-control" value="<?= $p->stocks ?>" readonly>
																			</div>

																			<?php // PRICE ?>
																			<div class="col-md-6">
																				<label for="price-<?= $p->id ?>" style="font-size: 18px; font-weight: 600"class="important-before">Price</label>
																				<input type="number" id="price-<?= $p->id ?>" name="prize" class="form-control" value="<?= number_format($p->price, 2,) ?>" min="0" step="0.25">
																			</div>
																		</div>
																	</div>

																	<div class="modal-footer">
																		<button type="button" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
																		<input type="submit" id="update_productBtn" value="UPDATE" class="btn btn-primary">
																	</div>
																</form>
															</div>
														</div>

														<?php // ARCHIVE ?>
														<button type="button" class="btn btn-danger btn-sm mx-1" data-toggle="modal" data-target="#archive-product-modal-<?= $p->id?>" data-id="<?= $p->id ?>">
															ARCHIVE
														</button>

														<div class="modal fade text-left" id="archive-product-modal-<?= $p->id ?>" tabindex="-1" aria-hidden="true">
															<div class="modal-dialog">
																<div class="modal-content">
																	<div class="modal-body d-flex justify-content-center align-items-center" style="height: 200px; width: 100%; flex-direction: column;  ">
																		<p class="h5">Are you sure you want to archive Product?</p>
																		<form action="" id="form-archive-product">
																			<input type="text" name="id" class="d-none">
																		</form>
																		<div class="d-flex justify-content-center align-items-center mt-3 px-5 w-100">
																			<button type="button" style="width: 49%;" class="btn btn-default mr-1" data-dismiss="modal">Close</button>
																			<button type="submit" style="width: 49%;" form="form-delete-user" class="btn btn-danger ml-1" id="archive_btn" data-dismiss="modal">Archive</button>
																		</div>
																	</div>
																</div>
															</div>
														</div>
													</div>
												</td>
											</tr>
									<?php endforeach;
									endif; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
		<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

		<?php // Sweetalert Cdn Start ?>
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<?php // Sweetalert Cdn End ?>

		<?php // Bootstrap Select Picker ?>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

		<script>
			$(document).ready(function() {
				// ARCHIVE REQUEST AJAX
				$("#archive_btn").click(function(e) {
					e.preventDefault();
					console.log("napindot si a");
					// e.preventDefault();
					$.ajax({
						url: "../processPhp/archive_process.php",
						method: "POST",
						data: $("#form-archive-product").serialize() + "&action=archivemainProduct",
						success: function(response) {
							if (response == "successArchive") {
								Swal.fire({
									position: 'center',
									icon: 'success',
									title: 'Successfully Archived!',
									showConfirmButton: false,
									timer: 1300
								}).then(function() {
									window.location = "products.php";
								})
							} else if (response == "errorArchive") {
								Swal.fire({
									position: 'center',
									icon: 'success',
									title: 'There is an error, Please try again',
									showConfirmButton: false,
									timer: 1300
								}).then(function() {
									window.location = "products.php";
								})
							}
						}
					})
				});

				$('.editBtn').on('click', function() {
					console.log("Clikced")
					$('#edit_product').modal('show');
					$tr = $(this).closest('tr');
					var data = $tr.children("td").map(function() {
						return $(this).text();
					}).get();
					$('#edit_product_Id').val(data[0]);
					$('#edit_item_code').val(data[1]);
					$('#edit_bar_code').val(data[2]);
					$('#edit_category_Id').val(data[3]);
					$('#edit_category_product_Id').val(data[4]);
					$('#edit_product_type_Id').val(data[5]);
					$('#edit_type_Id').val(data[6]);
					$('#stocks').val(data[7]);
					$('#prize').val(data[8]);
				});

				// FOR EDIT PRODUCT AJAX
				$("#update_productBtn").click(function(e) {
					e.preventDefault();
					$.ajax({
						url: "../processPhp/edit_process.php",
						method: "POST",
						data: $("#edit-form").serialize() + '&action=editProduct',
						success: function(response) {
							console.log(response)
							if (response == "editedSuccess") {
								Swal.fire({
									position: 'center',
									icon: 'success',
									title: 'Edited Product Successfully!',
									showConfirmButton: false,
									timer: 1500
								}).then(function() {
									window.location = "./products.php";
								})
							} else if (response == "error") {
								Swal.fire({
									position: 'center',
									icon: 'error',
									title: 'There is an error. Please Try Again!',
									showConfirmButton: false,
									timer: 1500
								}).then(function() {
									window.location = "./products.php";
								})
							}
						}
					})
				});

				$("#addBtn").on('click', function(e) {
					e.preventDefault();
					// console.log($("#add-form").serialize());

					// $.ajax({
					// 	url: "../processPhp/add_process.php",
					// 	method: 'POST',
					// 	data: $("#add-form").serialize() + '&action=addProducts',
					// 	success: function(response) {
					// 		console.log(response);
					// 		if (response == "addedSuccess") {
					// 			Swal.fire({
					// 				position: 'center',
					// 				icon: 'success',
					// 				title: 'Added Category Successfully!',
					// 				showConfirmButton: false,
					// 				timer: 1500
					// 			}).then(function() {
					// 				window.location = "./products.php";
					// 			});
					// 		} else if (response == "error") {
					// 			Swal.fire({
					// 				position: 'center',
					// 				icon: 'error',
					// 				title: 'There is an error. Please Try Again!',
					// 				showConfirmButton: false,
					// 				timer: 1500
					// 			}).then(function() {
					// 				window.location = "./products.php";
					// 			})
					// 		} else if (response == "productExist") {
					// 			Swal.fire({
					// 				position: 'center',
					// 				icon: 'error',
					// 				title: 'The product already exist in the table!',
					// 				showConfirmButton: false,
					// 				timer: 1500
					// 			});
					// 		} else if (response == "fieldRequired") {
					// 			Swal.fire({
					// 				position: 'center',
					// 				icon: 'error',
					// 				title: 'All fields are required. Please try again thankyou!',
					// 				showConfirmButton: false,
					// 				timer: 1500
					// 			})
					// 		}
					// 	},
					// 	error: function(response) {
					// 		console.log(response);
					// 		Swal.fire({
					// 			position: 'center',
					// 			icon: 'error',
					// 			title: 'There is an error. Please Try Again!',
					// 			showConfirmButton: false,
					// 			timer: 1500
					// 		});
					// 	}
					// })
				});

				// GET PROCESS
				const urls = {
					category: "../processPhp/getProduct_process.php",
					product: "../processPhp/getProductType_process.php",
					productType: "../processPhp/getType_process.php"
				};

				const defaultOption = {
					product: `<option class="font-weight-bold" style="font-size:18px;" value selected disabled hidden>Select Product</option>`,
					productType: `<option class="font-weight-bold" style="font-size:18px;" value selected disabled hidden>Select Product Type</option>`,
					type: `<option class="font-weight-bold" style="font-size:18px;" value selected disabled hidden>Select Type</option>`
				};

				const dropdowns = [
					"#category-dropdown",
					"#product-dropdown",
					"#product-type-dropdown",
					"#type-dropdown"
				];

				async function submitForm(url, formData) {
					return $.ajax({
						method: `POST`,
						url: url,
						data: formData,
					});
				}

				$(dropdowns.join(`, `)).on(`change`, (e) => {
					let obj = $(e.target);

					if (dropdowns.includes(`#${obj.attr(`id`)}`)) {
						let content = obj.data(`dd-content`);

						if (content != `type`) {
							let formData = {};
							formData[`${obj.attr(`name`)}`] = obj.val();

							submitForm(`${urls[content]}`, formData)
								.then((r) => {
									if (r.status != 200) {
										console.warn(r.message);
										return;
									}

									$(obj.data(`dd-target`)).html(r.data);

									let objIndex = dropdowns.indexOf(`#${obj.attr(`id`)}`);
									for (let i = objIndex + 2; i < dropdowns.length; i++) {
										if (i > dropdowns.length - 1) break;

										let item = dropdowns[i];
										let itemCat = $(item).data(`dd-content`);
										$(item).html(defaultOption[itemCat]);
									}
								}, (r) => {
									console.warn(r.responseText);
								});
						}
					}
				});

				// ADD PROCESS
				$(`#add-form`).on(`submit`, (e) => {
					e.preventDefault();

					let obj = $(e.target);
					let isValid = obj[0].checkValidity();

					obj.addClass(`was-validated`);
					if (isValid) {
						let formData = obj.serialize();
						submitForm("../processPhp/add_process.php", formData)
							.then((r) => {
								if (r.status == 200) {
									showFlash(r.message, false);
								} else {
									console.warn(r);
									showFlash(r.message);
								}
							}, (r) => {
								console.warn(r.responseText);
								showFlash(r.responseText);
							});
					}
				});

				// TODO: Implement Edit and Archive Processes

				// UTIL
				function showFlash(title = 'Failed', failed = true) {
					data = {
						position: 'center',
						icon: failed ? 'warning' : 'success',
						title: title,
						showConfirmButton: false,
						timer: 1300
					};

					if (failed) {
						data.icon = `warning`;
					}

					Swal.fire(data).then(() => {
						window.location.reload();
					});
				}
			});
		</script>
	</body>
</html>

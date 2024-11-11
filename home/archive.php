<?php
	require_once("../connection.php");
	session_start();
	if (!isset($_SESSION['officials_Id'])) {
		header('Location: ../index.php');
	}
	if ($_SESSION['user_type'] == "Cashier" || $_SESSION['user_type'] == "Staff") {
		header('Location: dashboard.php');
	}
	$user_type = $_SESSION['user_type'];

	// FETCH CATEGORIES
	$connection = $conn->query("SELECT `category_table`.`category_Id`, `category_table`.`category_Name` FROM category_table WHERE archive='Yes' ORDER BY category_Id DESC");
	$categories = [];
	while ($row = $connection->fetch_assoc())
		$categories[$row["category_Id"]] = $row["category_Name"];

	// FETCH SUBCATEGORIES
	$connection = $conn->query("SELECT `category_product_table`.`category_product_Id`, `category_product_table`.`product_Name` FROM category_product_table WHERE archive='Yes' ORDER BY category_product_Id DESC");
	$subcategories = [];
	while ($row = $connection->fetch_assoc())
		$subcategories[$row["category_product_Id"]] = $row["product_Name"];

	// FETCH PRODUCT ITEMS
	$connection = $conn->query("SELECT `category_product_item_table`.`category_product_item_Id`, `category_product_item_table`.`product_item_name` FROM category_product_item_table WHERE archive='Yes' ORDER BY category_product_item_Id DESC");
	$productItems = [];
	while ($row = $connection->fetch_assoc())
		$productItems[$row["category_product_item_Id"]] = $row["product_item_name"];

	// FETCH PRODUCT VARIANTS
	$connection = $conn->query("SELECT `category_product_item_type_table`.`category_product_item_type_Id`, `category_product_item_type_table`.`product_item_type_name` FROM category_product_item_type_table WHERE archive='Yes' ORDER BY category_product_item_type_Id DESC");
	$productVariants = [];
	while ($row = $connection->fetch_assoc())
		$productVariants[$row["category_product_item_type_Id"]] = $row["product_item_type_name"];
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
		<!-- Font Links Start-->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
		<link href="https://fonts.googleapis.com/css2?family=Saira+Condensed:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
		<!-- Font Links End-->
		<!-- JS for jQuery -->
		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	</head>

	<body class="d-flex flex-column" style="min-height: 100vh;">
		<?php require_once("../templates/topNav.php") ?>

		<div class="main-container" style="flex-grow: 1;">
			<div class="left-container">
				<?php require_once("../templates/leftNav.php") ?>
			</div>

			<div class="right-container">
				<div class="row m-0 p-0">
					<div class="col-md-12">
						<div class="main-title">
							<i class="fa-solid fa-layer-group"></i><span>ARCHIVES</span>
						</div>

						<a href="settings.php" class="btn btn-dark text-light mt-3">
							Back
						</a>

						<div class="row mt-3">
							<div class="col-md-12">
								<div class="row">
									<div class="col-md-3 p-3" id="cold" style="border-radius: 5px; border-top: 10px solid #606FF2; border-left: 10px solid #606FF2; border-right: 10px solid #606FF2;">
										<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
											<a class="nav-link nav-link-archive active text-center font-weight-bold" id="v-pills-categories-tab" data-toggle="pill" href="#v-pills-categories" role="tab" aria-controls="v-pills-categories" aria-selected="true">CATEGORIES</a>
											<a class="nav-link nav-link-archive text-center mt-2 font-weight-bold" id="v-pills-subcategories-tab" data-toggle="pill" href="#v-pills-subcategories" role="tab" aria-controls="v-pills-subcategories" aria-selected="false">SUBCATEGORIES</a>
											<a class="nav-link nav-link-archive text-center mt-2 font-weight-bold" id="v-pills-items-tab" data-toggle="pill" href="#v-pills-items" role="tab" aria-controls="v-pills-items" aria-selected="false">PRODUCT ITEMS</a>
											<a class="nav-link nav-link-archive text-center mt-2 font-weight-bold" id="v-pills-item-variants-tab" data-toggle="pill" href="#v-pills-item-variants" role="tab" aria-controls="v-pills-item-variants" aria-selected="false">PRODUCT VARIANTS</a>
											<a class="nav-link nav-link-archive text-center mt-2 font-weight-bold" id="v-pills-products-tab" data-toggle="pill" href="#v-pills-products" role="tab" aria-controls="v-pills-products" aria-selected="false">PRODUCTS</a>
											<a class="nav-link nav-link-archive text-center mt-2 font-weight-bold" id="v-pills-accounts-tab" data-toggle="pill" href="#v-pills-accounts" role="tab" aria-controls="v-pills-accounts" aria-selected="false">ACCOUNTS</a>
											<a class="nav-link nav-link-archive text-center mt-2 font-weight-bold" id="v-pills-suppliers-tab" data-toggle="pill" href="#v-pills-suppliers" role="tab" aria-controls="v-pills-suppliers" aria-selected="false">SUPPLIERS</a>
										</div>
									</div>

									<div class="col-md-9">
										<div class="tab-content" id="v-pills-tabContent">
											<!-- Category -->
											<div class="tab-pane fade show active" id="v-pills-categories" role="tabpanel" aria-labelledby="v-pills-categories-tab">
												<div class="table-container">
													<table class="table table-hover table-border table-sm">
														<thead>
															<tr class="text-center">
																<th scope="col" class="d-none">CATEGORY ID</th>
																<th scope="col" class="col-10">CATEGORY NAME</th>
																<th scope="col" class="col-2">ACTION</th>
															</tr>
														</thead>

														<tbody>
															<?php if (count($categories) > 0):
																foreach ($categories as $id => $name): ?>
																	<tr>
																		<td class="d-none"><?= $id ?></td>
																		<td><?= $name ?></td>

																		<td>
																			<div class="d-flex justify-content-around align-items-center">
																				<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#category-modal-<?= $id ?>" data-id="<?= $id ?>">
																					UNARCHIVE
																				</button>

																				<div id="category-modal-<?= $id ?>" class="modal fade">
																					<div class="modal-dialog">
																						<div class="modal-content">
																							<div class="modal-body d-flex justify-content-center align-items-center w-100 flex-column" style="height: 200px;">
																								<p class="h5">Are you sure you want to restore <b><?= $name ?></b>?</p>

																								<form class="unarchive" id="unarchive-category-<?= $id ?>">
																									<input type="hidden" name="id" value="<?= $id ?>">
																									<input type="hidden" name="action" value="restoreCategory">
																									<input type="submit" class="d-none" id="submit-unarchive-category-<?= $id ?>">
																								</form>

																								<div class="d-flex justify-content-center align-items-center mt-3 flex-row w-100">
																									<button type="button" class="btn btn-default w-25 mx-2" data-dismiss="modal">Close</button>
																									<label for="submit-unarchive-category-<?= $id ?>" class="btn btn-danger w-25 mx-2" tabindex="0">Update</label>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</td>
																	</tr>
															<?php endforeach;
																else: ?>
																<tr>
																	<td colspan="2" class="text-center">No Archived Categories</td>
																</tr>
															<?php endif; ?>
														</tbody>
													</table>
												</div>
											</div>

											<!-- Subcategory -->
											<div class="tab-pane fade" id="v-pills-subcategories" role="tabpanel" aria-labelledby="v-pills-subcategories-tab">
												<div class="table-container">
													<table class="table table-hover table-border table-sm">
														<thead>
															<tr class="text-center">
																<th scope="col" class="d-none">SUBCATEGORY ID</th>
																<th scope="col" class="col-10">SUBCATEGORY NAME</th>
																<th scope="col" class="col-2">ACTION</th>
															</tr>
														</thead>

														<tbody>
															<?php if (count($subcategories) > 0):
																foreach ($subcategories as $id => $name): ?>
																	<tr>
																		<td class="d-none"><?= $id ?></td>
																		<td><?= $name ?></td>

																		<td>
																			<div class="d-flex justify-content-around align-items-center">
																				<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#subcategory-modal-<?= $id ?>" data-id="<?= $id ?>">
																					UNARCHIVE
																				</button>

																				<div id="subcategory-modal-<?= $id ?>" class="modal fade">
																					<div class="modal-dialog">
																						<div class="modal-content">
																							<div class="modal-body d-flex justify-content-center align-items-center w-100 flex-column" style="height: 200px;">
																								<p class="h5">Are you sure you want to restore <b><?= $name ?></b>?</p>

																								<form class="unarchive" id="unarchive-subcategory-<?= $id ?>">
																									<input type="hidden" name="id" value="<?= $id ?>">
																									<input type="hidden" name="action" value="restoreSubcategory">
																									<input type="submit" class="d-none" id="submit-unarchive-subcategory-<?= $id ?>">
																								</form>

																								<div class="d-flex justify-content-center align-items-center mt-3 flex-row w-100">
																									<button type="button" class="btn btn-default w-25 mx-2" data-dismiss="modal">Close</button>
																									<label for="submit-unarchive-subcategory-<?= $id ?>" class="btn btn-danger w-25 mx-2" tabindex="0">Update</label>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</td>
																	</tr>
															<?php endforeach;
																else: ?>
																<tr>
																	<td colspan="2" class="text-center">No Archived Subcategories</td>
																</tr>
															<?php endif; ?>
														</tbody>
													</table>
												</div>
											</div>

											<!-- Product Items -->
											<div class="tab-pane fade" id="v-pills-items" role="tabpanel" aria-labelledby="v-pills-items-tab">
												<div class="table-container">
													<table class="table table-hover table-border table-sm">
														<thead>
															<tr class="text-center">
																<th scope="col" class="d-none">PRODUCT ITEM ID</th>
																<th scope="col" class="col-10">PRODUCT ITEM NAME</th>
																<th scope="col" class="col-2">ACTION</th>
															</tr>
														</thead>

														<tbody>
															<?php if (count($productItems) > 0):
																foreach ($productItems as $id => $name): ?>
																	<tr>
																		<td class="d-none"><?= $id ?></td>
																		<td><?= $name ?></td>

																		<td>
																			<div class="d-flex justify-content-around align-items-center">
																				<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#items-modal-<?= $id ?>" data-id="<?= $id ?>">
																					UNARCHIVE
																				</button>

																				<div id="items-modal-<?= $id ?>" class="modal fade">
																					<div class="modal-dialog">
																						<div class="modal-content">
																							<div class="modal-body d-flex justify-content-center align-items-center w-100 flex-column" style="height: 200px;">
																								<p class="h5">Are you sure you want to restore <b><?= $name ?></b>?</p>

																								<form class="unarchive" id="unarchive-items-<?= $id ?>">
																									<input type="hidden" name="id" value="<?= $id ?>">
																									<input type="hidden" name="action" value="restoreProductItem">
																									<input type="submit" class="d-none" id="submit-unarchive-items-<?= $id ?>">
																								</form>

																								<div class="d-flex justify-content-center align-items-center mt-3 flex-row w-100">
																									<button type="button" class="btn btn-default w-25 mx-2" data-dismiss="modal">Close</button>
																									<label for="submit-unarchive-items-<?= $id ?>" class="btn btn-danger w-25 mx-2" tabindex="0">Update</label>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</td>
																	</tr>
															<?php endforeach;
																else: ?>
																<tr>
																	<td colspan="2" class="text-center">No Archived Product Items</td>
																</tr>
															<?php endif; ?>
														</tbody>
													</table>
												</div>
											</div>

											<!-- Product Variant -->
											<div class="tab-pane fade" id="v-pills-item-variants" role="tabpanel" aria-labelledby="v-pills-item-variants-tab">
												<div class="table-container">
													<table class="table table-hover table-border table-sm">
														<thead>
															<tr class="text-center">
																<th scope="col" class="d-none">PRODUCT VARIANT ID</th>
																<th scope="col" class="col-10">PRODUCT VARIANT NAME</th>
																<th scope="col" class="col-2">ACTION</th>
															</tr>
														</thead>

														<tbody>
															<?php if (count($productVariants) > 0):
																foreach ($productVariants as $id => $name): ?>
																	<tr>
																		<td class="d-none"><?= $id ?></td>
																		<td><?= $name ?></td>

																		<td>
																			<div class="d-flex justify-content-around align-items-center">
																				<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#variants-modal-<?= $id ?>" data-id="<?= $id ?>">
																					UNARCHIVE
																				</button>

																				<div id="variants-modal-<?= $id ?>" class="modal fade">
																					<div class="modal-dialog">
																						<div class="modal-content">
																							<div class="modal-body d-flex justify-content-center align-items-center w-100 flex-column" style="height: 200px;">
																								<p class="h5">Are you sure you want to restore <b><?= $name ?></b>?</p>

																								<form class="unarchive" id="unarchive-variants-<?= $id ?>">
																									<input type="hidden" name="id" value="<?= $id ?>">
																									<input type="hidden" name="action" value="restoreProductVariant">
																									<input type="submit" class="d-none" id="submit-unarchive-variants-<?= $id ?>">
																								</form>

																								<div class="d-flex justify-content-center align-items-center mt-3 flex-row w-100">
																									<button type="button" class="btn btn-default w-25 mx-2" data-dismiss="modal">Close</button>
																									<label for="submit-unarchive-variants-<?= $id ?>" class="btn btn-danger w-25 mx-2" tabindex="0">Update</label>
																								</div>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</td>
																	</tr>
															<?php endforeach;
																else: ?>
																<tr>
																	<td colspan="2" class="text-center">No Archived Product Variants</td>
																</tr>
															<?php endif; ?>
														</tbody>
													</table>
												</div>
											</div>

											<!-- Products -->
											<div class="tab-pane fade" id="v-pills-products" role="tabpanel" aria-labelledby="v-pills-products-tab">
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
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">PRIZE</th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ACTION</th>
															</tr>
														</thead>

														<tbody id="tableBody">
															<?php
															$sql = "SELECT p.product_Id, p.item_code, p.barcode, p.category_Id, p.category_product_Id, p.product_type_Id, p.type_Id, p.stocks, p.prize, p.archive, c.category_Id, c.category_Name, cp.category_product_Id, cp.product_Name, cpi.category_product_item_Id, cpi.product_item_name, cpit.category_product_item_type_Id, cpit.product_item_type_name FROM products p INNER JOIN category_table c ON p.category_Id = c.category_Id INNER JOIN category_product_table cp ON p.category_product_Id = cp.category_product_Id INNER JOIN category_product_item_table cpi ON p.product_type_Id = cpi.category_product_item_Id INNER JOIN category_product_item_type_table cpit ON p.type_Id = cpit.category_product_item_type_Id WHERE p.archive='Yes'";
															$result = $conn->query($sql);
															?>
															<?php if ($result->num_rows > 0) { ?>
																<?php while ($row = $result->fetch_assoc()) { ?>
																	<tr>
																		<td class="td-product text-center d-none" style="font-size: 18px; font-weight: 700"><?php echo $row['product_Id']; ?></td>
																		<td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['item_code']; ?></td>
																		<td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['barcode']; ?></td>
																		<td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['category_Name']; ?></td>
																		<td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['product_Name']; ?></td>
																		<td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['product_item_name']; ?></td>
																		<td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['product_item_type_name']; ?>
																		<td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['stocks']; ?></td>
																		<td class="td-product text-center" style="font-size: 18px; font-weight: 700"><?php echo $row['prize']; ?></td>
																		<td class="d-flex justify-content-around align-items-center">
																			<!-- <button type="button" class="btn btn-danger btn-sm ml-2" data-id="<?php echo $row['product_Id']; ?>" onclick="confirmDeleteProduct(this);">
																			ARCHIVE
																		</button>
																		<div id="myModal" class="modal fade" >
																			<div class="modal-dialog">
																				<div class="modal-content">
																					<div class="modal-body d-flex justify-content-center align-items-center" style="height: 200px; width: 100%; flex-direction: column;  ">
																						<p class="h5">Are you sure you want to Restore Product?</p>
																						<form action="" id="form-archive-product">
																							<input type="text" name="id" class="d-none">
																						</form>
																						<div class="d-flex justify-content-center align-items-center mt-3 px-5" style="flow-direction: column; width: 100%;" >
																							<button type="button" style="width: 49%;" class="btn btn-default mr-1" data-dismiss="modal">Close</button>
																							<button type="submit" style="width: 49%;" form="form-delete-user" class="btn btn-danger ml-1" id="archive_product_btn" data-dismiss="modal">Update</button>
																						</div>
																					</div>
																				</div>
																			</div>
																		</div> -->
																			<button type="button" class="btn btn-danger btn-sm" data-id="<?php echo $row['product_Id']; ?>" onclick="confirmDeleteProduct(this);">
																				UNARCHIVE
																			</button>
																			<div id="myModalProduct" class="modal fade">
																				<div class="modal-dialog">
																					<div class="modal-content">
																						<div class="modal-body d-flex justify-content-center align-items-center" style="height: 200px; width: 100%; flex-direction: column;  ">
																							<p class="h5">Are you sure you want to Restore Products?</p>
																							<form action="" id="form-archive-product">
																								<input type="text" name="id" class="d-none">
																							</form>
																							<div class="d-flex justify-content-center align-items-center mt-3 px-5" style="flow-direction: column; width: 100%;">
																								<button type="button" style="width: 49%;" class="btn btn-default mr-1" data-dismiss="modal">Close</button>
																								<button type="submit" style="width: 49%;" form="form-delete-user" class="btn btn-danger ml-1" id="archive_product_btn" data-dismiss="modal">Update</button>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</td>
																	</tr>
																<?php } ?>
															<?php } ?>
														</tbody>
													</table>
												</div>
											</div>

											<!-- Account -->
											<div class="tab-pane fade" id="v-pills-accounts" role="tabpanel" aria-labelledby="v-pills-accounts-tab">
												<div class="table-container mt-3">
													<table class="table table-hover table-border table-sm">
														<thead>
															<tr>
																<th scope="col" class="text-center d-none" style="font-size: 20px; font-weight: 700">ID</th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">FIRST NAME</th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">LAST NAME</th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">EMAIL</th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">USER TYPE</th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">STATUS</th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">DATE CREATED</th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ACTION</th>
															</tr>
														</thead>
														<tbody>
															<?php
															$sql = "SELECT * FROM officials WHERE status='Inactive' ORDER BY officials_Id DESC";
															$result = $conn->query($sql);
															?>
															<?php if ($result->num_rows > 0) { ?>
																<?php while ($row = $result->fetch_assoc()) { ?>
																	<tr>
																		<td class="text-center d-none" style="font-size: 20px;"><?php echo $row['officials_Id']; ?></td>
																		<td class="text-center" style="font-size: 20px;"><?php echo $row['first_name']; ?></td>
																		<td class="text-center" style="font-size: 20px;"><?php echo $row['last_name']; ?></td>
																		<td class="text-center" style="font-size: 20px;"><?php echo $row['email_address']; ?></td>
																		<td class="text-center" style="font-size: 20px;"><?php echo $row['user_type']; ?></td>
																		<td class="text-center" style="font-size: 20px;"><?php echo $row['status']; ?></td>
																		<td class="text-center" style="font-size: 20px;"><?php echo date("F j Y", strtotime($row['date_created'])); ?></td>
																		<td class="d-flex justify-content-around align-items-center">
																			<button type="button" class="btn btn-danger btn-sm ml-2" data-id="<?php echo $row['officials_Id']; ?>" onclick="confirmDeleteAccount(this);">
																				UNARCHIVE
																			</button>
																			<div id="myModalAccount" class="modal fade">
																				<div class="modal-dialog">
																					<div class="modal-content">
																						<div class="modal-body d-flex justify-content-center align-items-center" style="height: 200px; width: 100%; flex-direction: column;  ">
																							<p class="h5">Are you sure you want to Restore this Account?</p>
																							<form action="" id="form-archive-account">
																								<input type="text" name="id" class="d-none">
																							</form>
																							<div class="d-flex justify-content-center align-items-center mt-3 px-5" style="flow-direction: column; width: 100%;">
																								<button type="button" style="width: 49%;" class="btn btn-default mr-1" data-dismiss="modal">Close</button>
																								<button type="submit" style="width: 49%;" form="form-delete-user" class="btn btn-danger ml-1" id="archive_account_btn" data-dismiss="modal">Update</button>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</td>
																	</tr>
																<?php
																} ?>
															<?php } ?>
														</tbody>
													</table>
												</div>
											</div>

											<!-- Supplier -->
											<div class="tab-pane fade" id="v-pills-suppliers" role="tabpanel" aria-labelledby="v-pills-suppliers-tab">
												<div class="table-container mt-3">
													<table class="table table-hover table-border table-sm">
														<thead>
															<tr>
																<th scope="col" class="text-center d-none" style="font-size: 20px; font-weight: 700">ID</th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">DATE CREATED </th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">SUPPLIER </th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">CONTACT PERSON</th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">STATUS</th>
																<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ACTION</th>
															</tr>
														</thead>
														<tbody id="">
															<?php
															$sql = "SELECT * FROM supplier WHERE status='Inactive' ORDER BY supplier_Id DESC";
															$result = $conn->query($sql);
															?>
															<?php if ($result->num_rows > 0) { ?>
																<?php while ($row = $result->fetch_assoc()) { ?>
																	<tr>
																		<td class="text-center d-none" style="font-size: 20px;"><?php echo $row['supplier_Id']; ?></td>
																		<td class="text-center" style="font-size: 20px;"><?php echo date("F j Y", strtotime($row['date_created'])); ?></td>
																		<td class="text-center" style="font-size: 20px;"><?php echo $row['name']; ?></td>
																		<td class="text-center" style="font-size: 20px;"><?php echo $row['contact_person']; ?></td>
																		<td class="text-center" style="font-size: 20px;"><?php echo $row['status']; ?></td>
																		<td class="d-flex justify-content-center align-items-center">
																			<button type="button" class="btn btn-danger btn-sm ml-2" data-id="<?php echo $row['supplier_Id']; ?>" onclick="confirmDeleteSupplier(this);">
																				UNARCHIVE
																			</button>
																			<div id="myModalSupplier" class="modal fade">
																				<div class="modal-dialog">
																					<div class="modal-content">
																						<div class="modal-body d-flex justify-content-center align-items-center" style="height: 200px; width: 100%; flex-direction: column;  ">
																							<p class="h5">Are you sure you want to Restore this Supplier?</p>
																							<form action="" id="form-archive-supplier">
																								<input type="text" name="id" class="d-none">
																							</form>
																							<div class="d-flex justify-content-center align-items-center mt-3 px-5" style="flow-direction: column; width: 100%;">
																								<button type="button" style="width: 49%;" class="btn btn-default mr-1" data-dismiss="modal">Close</button>
																								<button type="submit" style="width: 49%;" form="form-delete-user" class="btn btn-danger ml-1" id="archive_supplier_btn" data-dismiss="modal">Update</button>
																							</div>
																						</div>
																					</div>
																				</div>
																			</div>
																		</td>
																	</tr>
																<?php
																} ?>
															<?php } ?>
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
		<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

		<!-- Sweetalert Cdn Start -->
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<!-- Sweetalert Cdn End -->

		<script>
			const hashes = [
				`#v-pills-categories`,
				`#v-pills-subcategories`,
				`#v-pills-items`,
				`#v-pills-item-variants`,
				`#v-pills-products`,
				`#v-pills-accounts`,
				`#v-pills-suppliers`,
			];

			$(() => {
				// HASH HANDLING
				let loadedHash = window.location.hash;

				if (hashes.includes(loadedHash))
					$(`[href="${loadedHash}"]`).trigger(`click`);

				// Sets the URL hash.
				$(`[data-toggle=pill][role=tab]`).on(`click`, (e) => {
					let hash = $(e.target).attr(`href`);
					window.location.hash = hash;
				});

				$(window).on(`hashchange`, () => {
					let currentHash = window.location.hash;

					if (hashes.includes(currentHash))
						$(`[href="${currentHash}"]`).trigger(`click`);
				});
			});

			// UNARCHIVE REQUEST AJAX
			$(`.unarchive`).on(`submit`, sendRequest);

			function sendRequest(e) {
				e.preventDefault();
				let obj = $(e.target);
				let modal = obj.closest(`.modal`);

				modal.modal(`hide`);

				function showFlash(failed = true) {
					data = {
						position: 'center',
						icon: 'success',
						title: 'Successfully Restored!',
						showConfirmButton: false,
						timer: 1300
					};

					if (failed) {
						data.icon = `warning`;
						data.title = `There is an error, please try again.`;
					}

					Swal.fire(data).then(() => {
						reloadHash = "";
						for (hash of hashes)
							if ($(hash).hasClass(`active`))
								reloadHash = hash;

						window.location = `archive.php${reloadHash}`;
						window.location.reload();
					});
				}

				$.ajax({
					url: `../processPhp/archive_process.php`,
					method: "POST",
					data: obj.serialize(),
					success: (response) => {
						if (window.location.origin == `http://localhost`) {
							Swal.fire({
								title: `Response`,
								width: `100%`,
								html: response,
								icon: `info`
							});

							console.log(response)
						}

						switch (response) {
							case `success`:
								showFlash(false);
								break;
							case `error`:
								showFlash();
								break;
						}
					},
					error: (response) => {
						console.warn(response);
						showFlash();
					}
				});
			}
		</script>
	</body>
</html>

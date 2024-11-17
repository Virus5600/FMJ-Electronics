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
	$productTypes = [];
	while ($row = $connection->fetch_assoc())
		$productTypes[$row["category_product_item_Id"]] = $row["product_item_name"];

	// FETCH PRODUCT VARIANTS
	$connection = $conn->query("SELECT `category_product_item_type_table`.`category_product_item_type_Id`, `category_product_item_type_table`.`product_item_type_name` FROM category_product_item_type_table WHERE archive='Yes' ORDER BY category_product_item_type_Id DESC");
	$productVariants = [];
	while ($row = $connection->fetch_assoc())
		$productVariants[$row["category_product_item_type_Id"]] = $row["product_item_type_name"];

	// FETCH PRODUCTS
	$connection = $conn->query("SELECT p.product_Id as id, p.item_code as item_code, p.barcode as barcode, p.category_Id, p.category_product_Id, p.product_type_Id, p.type_Id, p.stocks as stocks, p.prize as price, p.archive as archive, c.category_Id, c.category_Name as category, cp.category_product_Id, cp.product_Name as subcategory, cpi.category_product_item_Id, cpi.product_item_name as product, cpit.category_product_item_type_Id, cpit.product_item_type_name as variant FROM products p INNER JOIN category_table c ON p.category_Id = c.category_Id INNER JOIN category_product_table cp ON p.category_product_Id = cp.category_product_Id INNER JOIN category_product_item_table cpi ON p.product_type_Id = cpi.category_product_item_Id INNER JOIN category_product_item_type_table cpit ON p.type_Id = cpit.category_product_item_type_Id WHERE p.archive = 'Yes';");
	$products = [];
	while ($row = $connection->fetch_assoc())
		$products[$row["id"]] = [
			"item_code" => $row["item_code"],
			"barcode" => $row["barcode"],
			"category" => $row["category"],
			"subcategory" => $row["subcategory"],
			"product" => $row["product"],
			"variant" => $row["variant"],
			"stocks" => $row["stocks"],
			"price" => $row["price"]
		];

	// FETCH USERS
	$connection = $conn->query("SELECT officials_Id AS id, CONCAT(first_name, \" \", last_name) AS name, email_address AS email, user_type, status, date_created FROM officials WHERE STATUS = 'Inactive' ORDER BY officials_Id DESC;");
	$users = [];
	while ($row = $connection->fetch_assoc())
		$users[$row["id"]] = [
			"name" => $row["name"],
			"email" => $row["email"],
			"user_type" => $row["user_type"],
			"date_created" => date("F j, Y", strtotime($row["date_created"]))
		];

	// FETCH SUPPLIERS
	$connection = $conn->query("SELECT supplier_Id AS id, name, contact_person, date_created FROM supplier WHERE STATUS = 'Inactive' ORDER BY supplier_Id DESC;");
	$suppliers = [];
	while ($row = $connection->fetch_assoc())
		$suppliers[$row["id"]] = [
			"name" => $row["name"],
			"contact_person" => $row["contact_person"],
			"date_created" => date("F j, Y", strtotime($row["date_created"]))
		];
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
									<div class="col-md-2 p-3" id="cold" style="border-radius: 5px; border-top: 10px solid #606FF2; border-left: 10px solid #606FF2; border-right: 10px solid #606FF2;">
										<div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
											<a class="nav-link nav-link-archive hover-effect active text-center font-weight-bold" id="v-pills-categories-tab" data-toggle="pill" href="#v-pills-categories" role="tab" aria-controls="v-pills-categories" aria-selected="true">CATEGORIES</a>
											<a class="nav-link nav-link-archive hover-effect text-center mt-2 font-weight-bold" id="v-pills-subcategories-tab" data-toggle="pill" href="#v-pills-subcategories" role="tab" aria-controls="v-pills-subcategories" aria-selected="false">CATEGORY PRODUCTS</a>
											<a class="nav-link nav-link-archive hover-effect text-center mt-2 font-weight-bold" id="v-pills-items-tab" data-toggle="pill" href="#v-pills-items" role="tab" aria-controls="v-pills-items" aria-selected="false">PRODUCT TYPES</a>
											<a class="nav-link nav-link-archive hover-effect text-center mt-2 font-weight-bold" id="v-pills-item-variants-tab" data-toggle="pill" href="#v-pills-item-variants" role="tab" aria-controls="v-pills-item-variants" aria-selected="false">TYPES</a>
											<a class="nav-link nav-link-archive hover-effect text-center mt-2 font-weight-bold" id="v-pills-products-tab" data-toggle="pill" href="#v-pills-products" role="tab" aria-controls="v-pills-products" aria-selected="false">PRODUCTS</a>
											<a class="nav-link nav-link-archive hover-effect text-center mt-2 font-weight-bold" id="v-pills-accounts-tab" data-toggle="pill" href="#v-pills-accounts" role="tab" aria-controls="v-pills-accounts" aria-selected="false">ACCOUNTS</a>
											<a class="nav-link nav-link-archive hover-effect text-center mt-2 font-weight-bold" id="v-pills-suppliers-tab" data-toggle="pill" href="#v-pills-suppliers" role="tab" aria-controls="v-pills-suppliers" aria-selected="false">SUPPLIERS</a>
										</div>
									</div>

									<div class="col-md-10">
										<div class="tab-content" id="v-pills-tabContent">
											<!-- Category -->
											<div class="tab-pane fade show active" id="v-pills-categories" role="tabpanel" aria-labelledby="v-pills-categories-tab">
												<div class="table-container">
													<table class="table table-hover table-border table-sm text-nowrap">
														<thead>
															<tr class="center-text">
																<th scope="col" class="d-none">CATEGORY ID</th>
																<th scope="col" class="col-10">CATEGORY NAME</th>
																<th scope="col" class="col-2">ACTION</th>
															</tr>
														</thead>

														<tbody>
															<?php if (count($categories) > 0):
																foreach ($categories as $id => $name): ?>
																	<tr class="center-text">
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
																									<input type="hidden" name="type" value="category">
																									<input type="hidden" name="action" value="restore">
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
													<table class="table table-hover table-border table-sm text-nowrap">
														<thead>
															<tr class="center-text">
																<th scope="col" class="d-none">CATEGORY PRODUCT ID</th>
																<th scope="col" class="col-10">CATEGORY PRODUCT NAME</th>
																<th scope="col" class="col-2">ACTION</th>
															</tr>
														</thead>

														<tbody>
															<?php if (count($subcategories) > 0):
																foreach ($subcategories as $id => $name): ?>
																	<tr class="center-text">
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
																									<input type="hidden" name="type" value="subcategory">
																									<input type="hidden" name="action" value="restore">
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
																	<td colspan="2" class="text-center">No Archived Category Product</td>
																</tr>
															<?php endif; ?>
														</tbody>
													</table>
												</div>
											</div>

											<!-- Product Items -->
											<div class="tab-pane fade" id="v-pills-items" role="tabpanel" aria-labelledby="v-pills-items-tab">
												<div class="table-container">
													<table class="table table-hover table-border table-sm text-nowrap">
														<thead>
															<tr class="center-text">
																<th scope="col" class="d-none">PRODUCT TYPE ID</th>
																<th scope="col" class="col-10">PRODUCT TYPE NAME</th>
																<th scope="col" class="col-2">ACTION</th>
															</tr>
														</thead>

														<tbody>
															<?php if (count($productTypes) > 0):
																foreach ($productTypes as $id => $name): ?>
																	<tr class="center-text">
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
																									<input type="hidden" name="type" value="productItem">
																									<input type="hidden" name="action" value="restore">
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
																	<td colspan="2" class="text-center">No Archived Product Type</td>
																</tr>
															<?php endif; ?>
														</tbody>
													</table>
												</div>
											</div>

											<!-- Product Variant -->
											<div class="tab-pane fade" id="v-pills-item-variants" role="tabpanel" aria-labelledby="v-pills-item-variants-tab">
												<div class="table-container">
													<table class="table table-hover table-border table-sm text-nowrap">
														<thead>
															<tr class="center-text">
																<th scope="col" class="d-none">TYPE ID</th>
																<th scope="col" class="col-10">TYPE NAME</th>
																<th scope="col" class="col-2">ACTION</th>
															</tr>
														</thead>

														<tbody>
															<?php if (count($productVariants) > 0):
																foreach ($productVariants as $id => $name): ?>
																	<tr class="center-text">
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
																									<input type="hidden" name="type" value="productVariant">
																									<input type="hidden" name="action" value="restore">
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
																	<td colspan="2" class="text-center">No Archived Type</td>
																</tr>
															<?php endif; ?>
														</tbody>
													</table>
												</div>
											</div>

											<!-- Products -->
											<div class="tab-pane fade1" id="v-pills-products" role="tabpanel" aria-labelledby="v-pills-products-tab">
												<div class="table-container overflow-x-auto">
													<table class="table table-hover table-border table-sm text-nowrap">
														<thead>
															<tr class="center-text">
																<th scope="col" class="d-none">PRODUCT ID</th>
																<th scope="col" class="col-2">ITEM CODE</th>
																<th scope="col" class="col-1">BARCODE</th>
																<th scope="col" class="col-2">CATEGORY</th>
																<th scope="col" class="col-2">PRODUCT</th>
																<th scope="col" class="col-2">PRODUCT TYPES</th>
																<th scope="col" class="col-2">TYPES</th>
																<th scope="col" class="col-1">STOCKS</th>
																<th scope="col" class="col-2">PRICE</th>
																<th scope="col" class="col-2">ACTION</th>
															</tr>
														</thead>

														<tbody>
															<?php if (count($products) > 0):
																foreach ($products as $id => $product): ?>
																	<tr style="font-size: 18px; font-weight: 700;" class="center-text default">
																		<td class="d-none"><?= $id ?></td>

																		<?php foreach ($product as $key => $data) {
																			if ($key == 'price') {
																				echo "<td>&#8369; " . number_format($data, 2, '.', ' ') . "</td>";
																			}
																			else {
																				echo "<td>$data</td>";
																			}
																		} ?>

																		<td>
																			<div class="d-flex justify-content-around align-items-center">
																				<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#products-modal-<?= $id ?>" data-id="<?= $id ?>">
																					UNARCHIVE
																				</button>

																				<div id="products-modal-<?= $id ?>" class="modal fade" tabindex="-1" aria-hidden="true">
																					<div class="modal-dialog">
																						<div class="modal-content">
																							<div class="modal-body d-flex justify-content-center align-items-center w-100 flex-column" style="height: auto;">
																								<p class="h5">Are you sure you want to restore this product?</p>

																								<br>
																								<p class="h5">This product is:</p>

																								<div class="border rounded w-100">
																									<table class="table table-borderless m-0">
																										<tr>
																											<td class="text-left">Category:</td>
																											<td class="text-right"><?= $product['category'] ?></td>
																										</tr>
																										<tr>
																											<td class="text-left">Product:</td>
																											<td class="text-right"><?= $product['subcategory'] ?></td>
																										</tr>
																										<tr>
																											<td class="text-left">Product Type:</td>
																											<td class="text-right"><?= $product['product'] ?></td>
																										</tr>
																										<tr>
																											<td class="text-left">Type:</td>
																											<td class="text-right"><?= $product['variant'] ?></td>
																										</tr>
																									</table>
																								</div>

																								<form class="unarchive" id="unarchive-products-<?= $id ?>">
																									<input type="hidden" name="id" value="<?= $id ?>">
																									<input type="hidden" name="type" value="product">
																									<input type="hidden" name="action" value="restore">
																									<input type="submit" class="d-none" id="submit-unarchive-products-<?= $id ?>">
																								</form>

																								<div class="d-flex justify-content-center align-items-center mt-3 flex-row w-100">
																									<button type="button" class="btn btn-default w-25 mx-2" data-dismiss="modal">Close</button>
																									<label for="submit-unarchive-products-<?= $id ?>" class="btn btn-danger w-25 mx-2 my-0" tabindex="0">Update</label>
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
																	<td colspan="10" class="text-center">No Archived Product</td>
																</tr>
															<?php endif; ?>
														</tbody>
													</table>
												</div>
											</div>

											<!-- Account -->
											<div class="tab-pane fade" id="v-pills-accounts" role="tabpanel" aria-labelledby="v-pills-accounts-tab">
												<div class="table-container">
													<table class="table table-hover table-border table-sm text-nowrap">
														<thead>
															<tr class="center-text">
																<th scope="col" class="d-none">USER ID</th>
																<th scope="col" class="col-3">NAME</th>
																<th scope="col" class="col-2">EMAIL</th>
																<th scope="col" class="col-2">USER TYPE</th>
																<th scope="col" class="col-3">DATE CREATED</th>
																<th scope="col" class="col-2">ACTION</th>
															</tr>
														</thead>

														<tbody>
															<?php if (count($users) > 0):
																foreach ($users as $id => $user): ?>
																	<tr class="center-text">
																		<td class="d-none"><?= $id ?></td>

																		<?php foreach ($user as $key => $data):
																			if ($key == "name") $name = $data; ?>
																		<td><?= $data ?></td>
																		<?php endforeach; ?>

																		<td>
																			<div class="d-flex justify-content-around align-items-center">
																				<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#users-modal-<?= $id ?>" data-id="<?= $id ?>">
																					UNARCHIVE
																				</button>

																				<div id="users-modal-<?= $id ?>" class="modal fade">
																					<div class="modal-dialog">
																						<div class="modal-content">
																							<div class="modal-body d-flex justify-content-center align-items-center w-100 flex-column" style="height: 200px;">
																								<p class="h5">Are you sure you want to activate <b><?= $name ?></b>'s account?</p>

																								<form class="unarchive" id="unarchive-users-<?= $id ?>">
																									<input type="hidden" name="id" value="<?= $id ?>">
																									<input type="hidden" name="type" value="account">
																									<input type="hidden" name="action" value="restore">
																									<input type="submit" class="d-none" id="submit-unarchive-users-<?= $id ?>">
																								</form>

																								<div class="d-flex justify-content-center align-items-center mt-3 flex-row w-100">
																									<button type="button" class="btn btn-default w-25 mx-2" data-dismiss="modal">Close</button>
																									<label for="submit-unarchive-users-<?= $id ?>" class="btn btn-danger w-25 mx-2" tabindex="0">Update</label>
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
																	<td colspan="6" class="text-center">No Inactive User</td>
																</tr>
															<?php endif; ?>
														</tbody>
													</table>
												</div>
											</div>

											<!-- Supplier -->
											<div class="tab-pane fade" id="v-pills-suppliers" role="tabpanel" aria-labelledby="v-pills-suppliers-tab">
												<div class="table-container">
													<table class="table table-hover table-border table-sm text-nowrap">
														<thead>
															<tr class="center-text">
																<th scope="col" class="d-none">SUPPLIER ID</th>
																<th scope="col" class="col-3">SUPPLIER</th>
																<th scope="col" class="col-3">CONTACT PERSON</th>
																<th scope="col" class="col-3">DATE CREATED</th>
																<th scope="col" class="col-3">ACTION</th>
															</tr>
														</thead>

														<tbody>
															<?php if (count($suppliers) > 0):
																foreach ($suppliers as $id => $supplier): ?>
																	<tr class="center-text">
																		<td class="d-none"><?= $id ?></td>

																		<?php foreach ($supplier as $key => $data):
																			if ($key == "name") $name = $data; ?>
																		<td><?= $data ?></td>
																		<?php endforeach; ?>

																		<td>
																			<div class="d-flex justify-content-around align-items-center">
																				<button type="button" class="btn btn-danger btn-sm" data-toggle="modal" data-target="#suppliers-modal-<?= $id ?>" data-id="<?= $id ?>">
																					UNARCHIVE
																				</button>

																				<div id="suppliers-modal-<?= $id ?>" class="modal fade">
																					<div class="modal-dialog">
																						<div class="modal-content">
																							<div class="modal-body d-flex justify-content-center align-items-center w-100 flex-column" style="height: 200px;">
																								<p class="h5">Are you sure you want to activate supplier <b><?= $name ?></b>?</p>

																								<form class="unarchive" id="unarchive-suppliers-<?= $id ?>">
																									<input type="hidden" name="id" value="<?= $id ?>">
																									<input type="hidden" name="action" value="restoreSupplier">
																									<input type="submit" class="d-none" id="submit-unarchive-suppliers-<?= $id ?>">
																								</form>

																								<div class="d-flex justify-content-center align-items-center mt-3 flex-row w-100">
																									<button type="button" class="btn btn-default w-25 mx-2" data-dismiss="modal">Close</button>
																									<label for="submit-unarchive-suppliers-<?= $id ?>" class="btn btn-danger w-25 mx-2" tabindex="0">Update</label>
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
																	<td colspan="6" class="text-center">No Inactive Supplier</td>
																</tr>
															<?php endif; ?>
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
						switch (response.status) {
							case 200:
								showFlash(false);
								break;

							default:
								console.warn(response);
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

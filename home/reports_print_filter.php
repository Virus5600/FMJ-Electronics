<?php
	require_once("../connection.php");
	session_start();
	if (!isset($_SESSION['officials_Id'])) {
		header('Location: ../index.php');
	}
	if ($_SESSION['user_type'] == "Staff") {
		header('Location: dashboard.php');
	}

	// Fetches the user's name for the report
	$user = $_SESSION['officials_Id'];
	$sql = "SELECT `first_name`, `last_name` FROM `officials` WHERE `officials_Id` = '$user'";
	$result = $conn->query($sql);
	$user = $result->fetch_assoc();
	$user = $user['first_name'] . " " . $user['last_name'];

	// Get all categories for the daily sales
	$sql = "SELECT `category_Id`, `category_Name` FROM `category_table`";
	$result = $conn->query($sql);
	$categories = [];
	while ($row = $result->fetch_assoc())
		$categories[$row['category_Id']] = $row['category_Name'];

	// Fetches the transaction using the filter
	$from;
	$to;
	$totalSales = 0;
	if (isset($_POST['printFilter'])) {
		$from = $_POST['from'];
		$to = $_POST['to'];

		// Fetches the transaction first
		$sql = "SELECT * FROM transactions_table WHERE date_added BETWEEN '$from 00:00:00' AND '$to 23:59:59' GROUP BY transaction_Number ORDER BY transaction_Id DESC";
		$result = $conn->query($sql);
		$num = 1;

		// Get all products for the daily sales
	}
	$user_type = $_SESSION['user_type'];
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
		<!-- Bootstrap Select Picker -->
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
					<div class="col-12">
						<div class="main-title">
							<i class="fa-solid fa-layer-group"></i><span>REPORTS </span>
						</div>
					</div>
				</div>

				<div class="row mt-3">
					<div class="col-12">
						<div class="container mb-5">
							<div class="card ">
								<div class="card-header">
									<div class="d-flex align-items-center justify-content-between">
										<h5 class="card-title mb-0 mr-3">Print Reports</h5>

										<div class="d-inline-flex">
											<div class="btn-group mr-2" role="group" aria-label="Report Actions">
												<a href="reports.php" class="btn btn-secondary text-light">Back</a>
												<button class="btn btn-success" onclick="printDiv('printThis')">Print</button>
											</div>

											<button type="button" class="btn btn-primary active" data-toggle="button" aria-pressed="true" autocomplete="off" onclick="toggleCompress(this)" title="Daily Sales will be printed on separate pages.">Expanded Daily Sales (ON)</button>
										</div>
									</div>
								</div>

								<div class="card-body m-5 " id="printThis">
									<div class="d-flex flex-wrap justify-content-center pb-3 px-5">
										<div class="w-100">
											<img src="./img/LogoAdam.png" class="float-left position-absolute" alt="" style="width: 80px; height: 80px;">

											<div class="row mt-5">
												<div class="col-5 d-flex justify-content-center align-items-center mx-auto flex-column">
													<h3 style="font-weight: 900" class="mb-0">FMJ ELECTRONICS</h3>
													<h5 style="font-weight: 700" class="font-italic text-center">"Sells Appliances, Lights, Electronics, and Electrical Parts"</h5>
												</div>
											</div>

											<div class="text-center mx-auto mt-3">
												<p class="mb-0" style="font-size: 14px;">1930 Quezon Avenue, Binangonan, 1940 Rizal Philippines</p>
												<p class="mb-0" style="font-size: 14px;">Mobile: (63) 919 636 9191</p>
												<p class="mb-0" style="font-size: 14px;">E-mail: godofredoagarap@gmail.com</p>
											</div>
										</div>
									</div>

									<!-- SALES REPORT -->
									<div class="d-flex flex-column px-5 mt-5 my-0 mx-auto page-break-after">
										<table class="table table-hover table-bordered table-sm">
											<thead>
												<tr>
													<th colspan="5">
														<h2 class="text-center font-weight-bold">Sales Report</h2>
														<h3 class="h4 text-center"><b>Generated By:</b> <?= $user ?></h3>
														<h4 class="h5 text-center">(<?= date("F j, Y", strtotime($from)) . " - " . date("F j, Y", strtotime($to))?>)</h4>
													</th>
												</tr>

												<tr class="text-center" style="font-size: 20px; font-weight: 700;">
													<th scope="col">#</th>
													<th scope="col">TRANSACTION NO.</th>
													<th scope="col">PAYMENT METHOD</th>
													<th scope="col">TIME AND DATE</th>
													<th scope="col">TOTAL AMOUNT</th>
												</tr>
											</thead>

											<tbody id="cartTable">
												<?php if ($result->num_rows > 0):
													while ($row = $result->fetch_assoc()): ?>
														<tr class="text-center" style="font-size: 20px;">
															<td><?= $num; ?></td>
															<td><?= $row['transaction_Number']; ?></td>
															<td><?= ucwords($row['payment_method']); ?></td>
															<td><?= date("F j Y, h:i:s A", strtotime($row['date_added'])); ?></td>
															<td class="col-2">
																<span class="float-left">&#x20b1;</span>
																<span class="float-right"><?= number_format($row["final_total_amount"], 2, ".", " ") ?></span>
															</td>
														</tr>
												<?php
														$totalSales += $row["final_total_amount"];
														$num++;
													endwhile;
												else:
													echo "<tr><td>No Data Found!</td>.</tr>";
												endif; ?>
											</tbody>

											<?php if ($result->num_rows > 0): ?>
											<tfoot>
												<tr class="font-weight-bold" style="font-size: 20px;">
													<td colspan="4" class="text-right">
														Total Sales:
													</td>

													<td colspan="1" class="text-left col-2">
														<span class="float-left">&#x20b1;</span>
														<span class="float-right"><?= number_format($totalSales, 2, ".", " ") ?></span>
													</td>
												</tr>
											</tfoot>
											<?php endif; ?>
										</table>
									</div>

									<!-- DAILY SALES REPORT -->
									<?php
										// Grouped by transaction date & item name
										$sql = "SELECT CAST(`t`.`date_added` AS DATE) AS `transaction_date`, `c`.`product_item_type_name` AS `item_name`, `p`.`category_Id` AS `cat_id`, (`t`.`price` * `t`.`qty`) AS `sales`, `t`.`item_code` AS `item_code` FROM `transactions_table` AS `t` INNER JOIN `products` AS `p` ON `t`.`product_Id` = `p`.`product_Id` INNER JOIN `category_product_item_type_table` AS `c` ON `p`.`type_Id` = `c`.`category_product_item_type_Id` WHERE date_added BETWEEN '$from 00:00:00' AND '$to 23:59:59' GROUP BY `transaction_date`, `c`.`product_item_type_name` ORDER BY `transaction_date` DESC;";
										$result = $conn->query($sql);
										$results = [];

										// If there are results
										if ($result->num_rows > 0) {
											// Loop through the results
											while ($row = $result->fetch_assoc()) {
												// If the transaction date already exists in the results array,
												if (array_key_exists($row["transaction_date"], $results)) {
													if (array_key_exists($row["item_name"], $results[$row["transaction_date"]])) {
														$results[$row["transaction_date"]][$row["item_name"]]["sales"] += (float) $row["sales"];
													}
													else {
														$results[$row["transaction_date"]][$row["item_name"]] = [
															"category" => $categories[$row["cat_id"]],
															"sales" => (float) $row["sales"]
														];
													}
												}
												// Otherwise, create a new array with the transaction date as the key and its values will contain the category name and total price.
												else {
													$results[$row["transaction_date"]] = [
														$row["item_name"] => [
															"category" => $categories[$row["cat_id"]],
															"sales" => (float) $row["sales"]
														]
													];
												}
											}
										}

										// Sort the results by date
										ksort($results);
									?>

									<!-- Actual Table Printing -->
									<?php foreach ($results as $dates => $items):
										$totalSales = 0;
									?>
									<div class="d-flex flex-column px-5 mt-5 w-100 my-0 mx-auto page-break-after daily-sales">
										<table class="table table-hover table-bordered table-sm">
											<thead>
												<tr>
													<th colspan="7">
														<h2 class="text-center font-weight-bold">Daily Sales Report</h2>
														<h3 class="h4 text-center"><b>Generated By:</b> <?= $user ?></h3>
														<h4 class="h5 text-center">(<?= date("D, F j, Y", strtotime($dates)) ?>)</h4>
													</th>
												</tr>

												<tr>
													<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">Product</th>
													<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">Category Name</th>
													<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">Sales</th>
												</tr>
											</thead>

											<tbody>
												<?php foreach ($items as $itemName => $item): ?>
												<tr>
													<td class="col-5 text-center" style="font-size: 18px;"><?= $itemName ?></td>
													<td class="col-5 text-center" style="font-size: 18px;"><?= $item["category"] ?></td>
													<td class="col-2 text-center" style="font-size: 18px;">
														<span class="float-left">&#x20b1;</span>
														<span class="float-right"><?= number_format($item["sales"], 2, ".", " ") ?></span>
														<?php $totalSales += (float) $item["sales"] ?>
													</td>
												</tr>
												<?php endforeach; ?>
											</tbody>

											<tfoot>
												<tr class="font-weight-bold" style="font-size: 20px;">
													<td colspan="2" class="text-right">
														Total Sales:
													</td>

													<td colspan="1" class="text-left">
														<span class="float-left">&#x20b1;</span>
														<span class="float-right"><?= number_format($totalSales, 2, ".", " ") ?></span>
													</td>
												</tr>
											</tfoot>
										</table>
									</div>
									<?php endforeach; ?>

									<!-- STOCKS REPORT -->
									<div class="d-flex flex-column px-5 mt-5 w-100 my-0 mx-auto page-break-after">
										<?php
											$sql = "SELECT `p`.`product_Id`, `p`.`item_code`, `p`.`stocks`, `p`.`type_Id`, `cpit`.`product_item_type_name` FROM `products` AS `p` INNER JOIN `category_product_item_type_table` AS `cpit` ON `p`.`type_Id` = `cpit`.`category_product_item_type_Id` WHERE `p`.`stocks` <= 100";
											$result = $conn->query($sql);
										?>

										<table class="table table-hover table-bordered table-sm">
											<thead>
												<tr>
													<th colspan="3">
														<h2 class="text-center font-weight-bold">Low Stocks Report</h2>
														<h3 class="h4 text-center"><b>Generated By:</b> <?= $user ?></h3>
														<h3 class="h5 text-center">As of <?php echo (new DateTime("now", new DateTimeZone("Asia/Manila")))->format("F j, Y - h:i A") ?></h3>
													</th>
												</tr>
												<tr>
													<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700;">Item Code</th>
													<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700;">Product Name</th>
													<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700;">Remaining Stocks</th>
												</tr>
											</thead>

											<tbody>
												<?php
												$num = 1;
												if ($result->num_rows > 0):
													while ($row = $result->fetch_assoc()):
												?>

												<tr>
													<td class="text-center" style="font-size: 20px;"><?php echo $row['item_code']; ?></td>
													<td class="text-center" style="font-size: 20px;"><?php echo $row['product_item_type_name']; ?></td>
													<td class="text-center" style="font-size: 20px;"><?php echo $row['stocks']; ?></td>
												</tr>

												<?php
														$num++;
													endwhile;
												endif; ?>
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

		<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
		<!-- <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script> -->
		<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
		<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

		<!-- Sweetalert Cdn Start -->
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<!-- Sweetalert Cdn End -->

		<script>
			function printDiv(divName) {
				var printContents = document.getElementById(divName).innerHTML;
				var originalContents = document.body.innerHTML;
				document.body.innerHTML = printContents;
				window.print();
				document.body.innerHTML = originalContents;
			}

			function toggleCompress(obj) {
				obj = $(obj);

				// Compress the daily sales
				if (obj.attr(`aria-pressed`) === `false`) {
					$(`.daily-sales`).addClass(`page-break-after`);
					obj.text(`Expanded Daily Sales (ON)`);
				}
				// Expand the daily sales
				else {
					$(`.daily-sales`).removeClass(`page-break-after`);
					obj.text(`Expanded Daily Sales (OFF)`);
				}
			}
		</script>
	</body>
</html>

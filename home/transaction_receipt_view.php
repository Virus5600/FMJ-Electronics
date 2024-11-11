<?php
require_once("../connection.php");
session_start();
if (!isset($_SESSION['officials_Id'])) {
	header('Location: ../index.php');
}
if (isset($_POST['viewBtn']) || $_GET['transaction_number']) {
	$transactionNo = $_GET['transaction_number'];
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
							<i class="fa-solid fa-layer-group"></i><span>TRANSACTION RECEIPT </span>
						</div>
					</div>
				</div>
				<div class="row mt-3">
					<div class="col-12">
						<div class="container mb-5 d-inline-flex flex-column gap-y-3">
							<div class="card my-3">
								<div class="card-header">
									<div class="d-flex align-items-center justify-content-between">
										<!-- <h5 class="card-title mb-0 mr-3">Print Transaction Receipt</h5> -->
										<a href="transaction.php" class="btn btn-secondary btn-border btn-round">
											Back
										</a>
										<div>
											<button class="btn btn-success btn-border btn-round" onclick="printDiv('printThis')">
												Print
											</button>
										</div>
									</div>
								</div>
								<div class="card-body m-5 " id="printThis">
									<div class="d-flex flex-wrap justify-content-center pb-3 px-5">
										<div class="" style="width: 100%">
											<div class="row mt-5">
												<div class="col-4">
													<div class="d-flex justify-content-end align-items-center">
														<img src="./img/LogoAdam.png" class="" alt="" style="width: 80px; height: 80px;">
													</div>
												</div>
												<div class="col-5 d-flex justify-content-center align-items-center" style="flex-direction: column">
													<h3 style="font-weight: 900" class="mb-0">FMJ ELECTRONICS</h3>
													<h5 style="font-weight: 700" class="font-italic text-center">"Sells Appliances, Lights, Electronics, and Electrical Parts"</h5>
												</div>
												<div class="col-3 d-flex justify-content-center " style="flex-direction: column">
												</div>
											</div>
											<div class="text-center ml-5 mt-3">
												<p class="mb-0" style="font-size: 14px;">1930 Quezon Avenue, Binangonan, 1940 Rizal Philippines</p>
												<p class="mb-0" style="font-size: 14px;">Mobile: (63) 919 636 9191</p>
												<p class="mb-0" style="font-size: 14px;">E-mail: godofredoagarap@gmail.com</p>
											</div>
											<div class="row mt-3" style="width: 100%; margin: 0 auto;">
												<div class="col-12">
													<div class="row">
														<div class="col-4 text-right ml-auto">
															<span class="h6 mr-2">Transaction No.:</span>
															<span class="font-weight-bold h5"><?php echo $transactionNo ?></span>
														</div>
													</div>
												</div>
											</div>
											<div class="row" style="width: 100%; margin: 0 auto;">
												<div class="col-12">
													<div class="row">
														<?php
														// $sql = "SELECT * FROM transactions_table WHERE transaction_Number='$transactionNo' ORDER BY transaction_Id DESC";
														$sql = "SELECT t.transaction_Id, t.transaction_Number, t.product_Id, t.item_code, t.qty, t.price, t.total_amount, t.final_total_amount, t.payment, t.change, t.date_added, p.product_Id, p.type_Id, c.category_product_item_type_Id, c.product_item_type_name FROM transactions_table t INNER JOIN products p ON t.product_id = p.product_Id INNER JOIN category_product_item_type_table c ON c.category_product_item_type_Id = p.type_Id WHERE t.transaction_Number='$transactionNo' GROUP BY t.transaction_Number";
														$result = $conn->query($sql);
														?>
														<div class="col-8 text-right ml-auto">
															<span class="h6 mr-2">Payment Date & Time:</span>
															<?php if ($result->num_rows == 1) { ?>
																<?php while ($row = $result->fetch_assoc()) { ?>
																	<span class="font-weight-bold h5"><?php echo date("F j Y", strtotime($row['date_added'])); ?></span>
																<?php } ?>
															<?php } ?>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
									<div class="row px-5 w-100 mx-auto my-0">
										<?php
										// $sql = "SELECT * FROM transactions_table WHERE transaction_Number='$transactionNo' ORDER BY transaction_Id DESC";
										$sql = "SELECT t.transaction_Id, t.transaction_Number, t.product_Id, t.item_code, t.qty, t.price, t.total_amount, t.final_total_amount, t.payment, t.payment_method, t.change, t.date_added, p.product_Id, p.type_Id, c.category_product_item_type_Id, c.product_item_type_name FROM transactions_table t INNER JOIN products p ON t.product_id = p.product_Id INNER JOIN category_product_item_type_table c ON c.category_product_item_type_Id = p.type_Id WHERE t.transaction_Number='$transactionNo'";
										$result = $conn->query($sql);
										$num = 1;
										$affectedItems = [];
										$affectedItemNames = [];
										?>
										<div class="col-12">
											<div class="table-container mt-3">
												<table class="table table-sm ">
													<thead>
														<tr>
															<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700; ">#</th>
															<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ITEM CODE</th>
															<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">PRODUCT NAME</th>
															<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">QTY</th>
															<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">UNIT PRICE</th>
															<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">TOTAL</th>
														</tr>
													</thead>
													<tbody id="cartTable" class="">
														<?php if ($result->num_rows > 0) { ?>
															<?php while ($row = $result->fetch_assoc()) { ?>
																<tr>
																	<td class="text-center" style="font-size: 16px; border: 1px solid gray !important">
																		<?php
																		echo $num;
																		?>
																	</td>
																	<td class="text-center" style="font-size: 16px; border: 1px solid gray !important"><?php echo $row['item_code']; ?></td>
																	<td class="text-center" style="font-size: 16px; border: 1px solid gray !important"><?php echo $row['product_item_type_name']; ?>
																	<td class="text-center" style="font-size: 16px; border: 1px solid gray !important"><?php echo $row['qty']; ?></td>
																	<td class="text-center" style="font-size: 16px; border: 1px solid gray !important"><?php echo $row['price']; ?></td>
																	<td class="text-center" style="font-size: 16px; border: 1px solid gray !important"><?php echo $row['total_amount']; ?></td>
																	<?php

																	if (!in_array($row["product_Id"], $affectedItems)) {
																		array_push($affectedItems, $row["product_Id"]);
																		$affectedItemNames[$row["item_code"]] = $row["product_item_type_name"];
																	}
																	?>
																</tr>
															<?php
																$num++;
															} ?>
														<?php } ?>
													</tbody>
												</table>
												<div class="row">
													<div class="col-4 ml-auto">
														<div class="row">
															<div class="col-7 text-right">
																<div>
																	<span class="font-weight-bold" style="font-size: 18px">Payment Method: </span>
																</div>
																<div>
																	<span class="font-weight-bold" style="font-size: 18px">Total Price: </span>
																</div>
																<div>
																	<span class="font-weight-bold" style="font-size: 18px">Payment: </span>
																</div>
																<div>
																	<span class="font-weight-bold" style="font-size: 18px">Change: </span>
																</div>
															</div>
															<?php
															// $sql = "SELECT * FROM transactions_table WHERE transaction_Number='$transactionNo' ORDER BY transaction_Id DESC";
															$sql = "SELECT t.transaction_Id, t.transaction_Number, t.product_Id, t.item_code, t.qty, t.price, t.total_amount, t.final_total_amount, t.payment, t.payment_method, t.change, t.date_added, p.product_Id, p.type_Id, c.category_product_item_type_Id, c.product_item_type_name FROM transactions_table t INNER JOIN products p ON t.product_id = p.product_Id INNER JOIN category_product_item_type_table c ON c.category_product_item_type_Id = p.type_Id WHERE t.transaction_Number='$transactionNo' GROUP BY t.transaction_Number";
															$result = $conn->query($sql);

															if ($result->num_rows == 1) $row = $result->fetch_assoc();
															?>
															<div class="col-5 text-center">
																<div style="border-bottom: 1px solid gray">
																	<span class="font-weight-bold" style="font-size: 18px"><?php echo $row['payment_method'] ?></span>
																</div>
																<div style="border-bottom: 1px solid gray">
																	<span class="font-weight-bold " style="font-size: 18px; "><?php echo $row['final_total_amount'] ?></span>
																</div>
																<div style="border-bottom: 1px solid gray">
																	<span class="font-weight-bold" style="font-size: 18px"><?php echo $row['payment'] ?></span>
																</div>
																<div style="border-bottom: 1px solid gray">
																	<span class="font-weight-bold" style="font-size: 18px"><?php echo $row['change'] ?></span>
																</div>
															</div>
														</div>
														<div class="row mt-3">
															<div class="col-9 text-right ml-auto">
																<?php date_default_timezone_set('Asia/Manila'); ?>
																<p style="font-weight: bold" class="text-right"><?php echo date("Y-m-d  h:i:sa") ?></p>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>

							<div class="card my-3">
								<h2 class="card-header">Remaining Stocks</h2>

								<div class="card-body table-container">
									<div class="row px-5 mx-auto my-0">
										<div class="col-12">
											<div class="table-container mt-3">
												<table class="table table-sm">
													<thead>
														<tr>
															<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700;">Item Code</th>
															<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700;">Product Name</th>
															<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700;">Remaining Stock</th>
														</tr>
													</thead>
			
													<tbody>
														<?php
														$sql = "SELECT `item_code`, `stocks` FROM products where product_Id IN (" . implode(",", $affectedItems) . ")";
														$result = $conn->query($sql);
														if ($result->num_rows > 0):
															while ($row = $result->fetch_assoc()):
														?>
														<tr class="fa-beat-hover">
															<td class="text-center" style="font-size: 16px; border: 1px solid gray !important;">
																<?php
																if ($row["stocks"] <= 100) echo "<i class=\"fas fa-triangle-exclamation text-warning mr-1\"></i>";
																else echo "<i class=\"fas fa-triangle-exclamation opacity-0 text-none mr-1\"></i>";
																?>
																<?php echo $row['item_code'] ?>
															</td>
															<td class="text-center" style="font-size: 16px; border: 1px solid gray !important;"><?php echo $affectedItemNames[$row['item_code']] ?></td>
															<td class="text-center" style="font-size: 16px; border: 1px solid gray !important;"><?php echo $row['stocks'] ?></td>
														</tr>
														<?php
															endwhile;
														endif;
														?>
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
		<!-- <?php
				$sql = "SELECT * FROM transactions_table WHERE transaction_Number='$transactionNo' ORDER BY transaction_Id DESC";
				$result = $conn->query($sql);
				$num = 1;
				?>
							<div class="table-container mt-3">
								<table class="table table-hover table-border table-sm">
									<thead>
										<tr>
											<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">#</th>
											<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ITEM CODE</th>
											<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">PAYMENT</th>
											<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">TOTAL AMOUNT</th>
											<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">CHANGE</th>
											<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">TIME AND DATE</th>
										</tr>
									</thead>
									<tbody id="cartTable">
										<?php if ($result->num_rows > 0) { ?>
											<?php while ($row = $result->fetch_assoc()) { ?>
										<tr>
											<td class="text-center" style="font-size: 20px;">
												<?php
												echo $num;
												?>
											</td>
											<td class="text-center" style="font-size: 20px;" ><?php echo $row['item_code']; ?></td>
											<td class="text-center" style="font-size: 20px;" ><?php echo $row['payment']; ?></td>
											<td class="text-center" style="font-size: 20px;"><?php echo $row['total_amount']; ?></td>
											<td class="text-center" style="font-size: 20px;" ><?php echo $row['change']; ?></td>
											<td class="text-center" style="font-size: 20px;"><?php echo $row['date_added']; ?></td>
										</tr>
											<?php
												$num++;
											} ?>
										<?php } ?>
									</tbody>
								</table>
							</div> -->
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

			$(document).ready(async function() {
				let affectedItems = <?php echo json_encode($affectedItems) ?>;
				let affectedItemNames = <?php echo json_encode($affectedItemNames) ?>;
				let affectedItemsData = [];
				
				for (item of affectedItems) {
					await $.ajax({
						method: "POST",
						url: "../processPhp/getItemDescription_process.php",
						data: {
							product_ids: item
						}
					}).done(function(data) {
						for (let i = 0; i < data.length; i++) {
							data[i]['stocks'] = parseInt(data[i]['stocks']);
							if (data[i]['stocks'] <= 100) {
								affectedItemsData.push(data[i]);
							}
						}
					});
				}

				if (affectedItemsData.length > 0) {
					let htmlWarning = `<table class="table table-sm table-striped table-hover"><thead><tr><th>Item Code</th><th>Product Name</th><th>Remaining Stock</th></tr></thead><tbody>`;

					for (data of affectedItemsData)
						htmlWarning += `<tr><td>${data['item_code']}</td><td>${affectedItemNames[data["item_code"]]}</td><td>${data['stocks']}</td></tr>`;

					htmlWarning += `</tbody></table>`;

					Swal.fire({
						position: 'center',
						icon: 'warning',
						title: 'Low on stock!',
						html: htmlWarning,
						showConfirmButton: true,
					});
				}
			});
		</script>
	</body>
</html>
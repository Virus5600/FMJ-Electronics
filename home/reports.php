<?php
require_once("../connection.php");
session_start();
if (!isset($_SESSION['officials_Id'])) {
	header('Location: ../index.php');
}
if ($_SESSION['user_type'] == "Staff") {
	header('Location: dashboard.php');
}
$user_type = $_SESSION['user_type'];
// LAST MONTH SALE
$sqll = "SELECT FORMAT(SUM(final_total_amount), 2) AS sales_count_last_month FROM transactions_table WHERE DATE_FORMAT(date_added, '%Y-%m') = DATE_FORMAT(CURRENT_DATE - INTERVAL 1 MONTH, '%Y-%m')";
$resultl = $conn->query($sqll);
// THIS MONTH SALE
$sqlt = "SELECT FORMAT(SUM(final_total_amount), 2) AS sales_count_this_month FROM transactions_table WHERE MONTH(date_added) = MONTH(CURRENT_DATE) AND YEAR(date_added) = YEAR(CURRENT_DATE)";
$resultt = $conn->query($sqlt);
// THIS WEEK SALE
$sqlw = "SELECT FORMAT(SUM(final_total_amount), 2) AS sales_count_this_week FROM transactions_table WHERE YEARWEEK(date_added) = YEARWEEK(NOW())";
$resultw = $conn->query($sqlw);
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
					<div class="col-md-12">
						<div class="main-title">
							<i class="fa-solid fa-layer-group"></i><span>REPORTS</span>
						</div>
						<div class="row m-0 p-0 mt-3">
							<div class="col-md-4 box">
								<div class="box-content-container">
									<i class="fa-solid fa-money-bill-wave"></i>
									<p>
										<?php
										if ($resultl->num_rows > 0) {
											while ($row = $resultl->fetch_assoc()) {
												echo $row['sales_count_last_month'];
											}
										}
										?>
									</p>
									<h3>Last Month Sales</h3>
								</div>
							</div>
							<div class="col-md-4 box">
								<div class="box-content-container">
									<i class="fa-solid fa-money-bill-wave"></i>
									<p>
										<?php
										if ($resultt->num_rows > 0) {
											while ($row = $resultt->fetch_assoc()) {
												echo $row['sales_count_this_month'];
											}
										}
										?>
									</p>
									<h3>This Month Sales</h3>
								</div>
							</div>
							<div class="col-md-4 box">
								<div class="box-content-container">
									<i class="fa-solid fa-money-bill-wave"></i>
									<p>
										<?php
										if ($resultw->num_rows > 0) {
											while ($row = $resultw->fetch_assoc()) {
												echo $row['sales_count_this_week'];
											}
										}
										?>
									</p>
									<h3>This Week Sales</h3>
								</div>
							</div>
						</div>
						<?php
						$sql = "SELECT * FROM transactions_table GROUP BY transaction_Number ORDER BY transaction_Id DESC";
						$result = $conn->query($sql);
						$num = 1;
						?>
						<form action="reports_print_filter.php" method="POST" class="">
							<div class="row">
								<div class="col-md-4">
									<label for="" class="font-weight-bold">From Date</label>
									<input type="date" name="from" class="form-control">
								</div>
								<div class="col-md-4">
									<label for="" class="font-weight-bold">To Date</label>
									<input type="date" name="to" class="form-control">
								</div>
								<div class="col-md-2">
									<div>
										<label for="" class="font-weight-bold " style="visibility: hidden">To Date</label>
									</div>
									<input type="submit" name="printFilter" class="btn btn-secondary btn-block" value="Filter">
								</div>
								<div class="col-md-2">
									<div>
										<label for="" class="font-weight-bold " style="visibility: hidden">To Date</label>
									</div>
									<a href="reports_print.php" class="btn btn-primary btn-block">Print Report</a>
								</div>
							</div>
						</form>
						<div class="table-container mt-3">
							<table class="table table-hover table-border table-sm">
								<thead>
									<tr>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">#</th>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">TRANSACTION NO.</th>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">PAYMENT METHOD</th>
										<th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">TOTAL AMOUNT</th>
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
												<td class="text-center" style="font-size: 20px;"><?php echo $row['transaction_Number']; ?></td>
												<td class="text-center" style="font-size: 20px;"><?php echo ucwords($row['payment_method']); ?></td>
												<td class="text-center" style="font-size: 20px;"><?php echo $row['final_total_amount']; ?></td>
												<td class="text-center" style="font-size: 20px;"><?php echo date("F j Y, h:i:s A", strtotime($row['date_added'])); ?></td>
											</tr>
										<?php
											$num++;
										} ?>
									<?php } ?>
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
		<!-- Sweetalert Cdn Start -->
		<script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
		<!-- Sweetalert Cdn End -->
	</body>
</html>
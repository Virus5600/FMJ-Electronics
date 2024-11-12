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
				<div class="col-md-4 box">
					<a href="transaction_receipt.php" class="box-content-container d-flex justify-content-center align-items-center card-link hover-effect" style="flex-direction: column">
						<i class="fa-solid fa-receipt" style="font-size: 58px; margin-bottom: 10px"></i>
						<h3>TRANSACTION RECEIPT</h3>
					</a>
				</div>

				<?php if ($_SESSION['user_type'] == "Admin") { ?>
					<div class="col-md-4 box">
						<a href="accounts.php" class="box-content-container d-flex justify-content-center align-items-center card-link hover-effect" style="flex-direction: column">
							<i class="fa-solid fa-user" style="font-size: 58px; margin-bottom: 10px"></i>
							<h3>ACCOUNTS</h3>
						</a>
					</div>
				<?php } ?>

				<?php if ($_SESSION['user_type'] == "Admin") { ?>
					<div class="col-md-4 box">
						<a href="archive.php" class="box-content-container d-flex justify-content-center align-items-center card-link hover-effect" style="flex-direction: column">
							<i class="fa-solid fa-archive" style="font-size: 58px; margin-bottom: 10px"></i>
							<h3>ARCHIVES</h3>
						</a>

					</div>
				<?php } ?>
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

	<!-- Bootstrap Select Picker -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

	<script>
		$(() => {
			$('#receiptBtn').on('click', function(e) {
				e.preventDefault();

				var data = {
					carts: cart,
					transactioNumbers: transactionNo.value,
					totals: total.value,
					payments: payment.value,
					changes: change.value
				};

				console.log(data);

				$.ajax({
					url: "../processPhp/add_process.php",
					method: "POST",
					data: data,
					success: function(response) {
						console.log(response);

						if (response == "addedSuccess") {
							Swal.fire({
								position: 'center',
								icon: 'success',
								title: 'Successfully Added!',
								showConfirmButton: false,
								timer: 1300
							}).then(function() {
								window.location = "transaction.php";
							});
						} else if (response == "error") {
							Swal.fire({
								position: 'center',
								icon: 'error',
								title: 'There is an error, Please try again',
								showConfirmButton: false,
								timer: 1300
							}).then(function() {
								window.location = "transaction.php";
							});
						}
					}
				});
			});
		})
	</script>

</body>

</html>

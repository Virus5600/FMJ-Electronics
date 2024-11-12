<?php
	require_once("../connection.php");
	session_start();

	if (!isset($_SESSION['officials_Id'])) {
		header('Location: ../index.php');
	}

	if ($_SESSION['user_type'] == "Cashier") {
		header('Location: dashboard.php');
	}

	$user_type = $_SESSION['user_type'];

	// FETCHING OF CATEGORY
	$connection  = $conn->query("SELECT `category_Id` AS `id`, `category_Name` AS `name` FROM category_table WHERE archive='No' ORDER BY `category_Id` DESC");
	$categories = [];
	foreach ($connection->fetch_all(MYSQLI_ASSOC) as $item)
		array_push($categories, (object) $item);
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
							<i class="fa-solid fa-layer-group"></i><span>CATEGORIES</span>
						</div>

						<div class="addBtn-container d-flex justify-content-end mb-3">
							<!-- Button trigger modal -->
							<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#exampleModal">
								ADD CATEGORY
							</button>


							<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
								<div class="modal-dialog" role="document">
									<div class="modal-content">
										<div class="modal-header">
											<h5 class="modal-title" id="exampleModalLabel">ADD CATEGORY</h5>
											<button type="button" class="close" data-dismiss="modal" aria-label="Close">
												<span aria-hidden="true">&times;</span>
											</button>
										</div>
										<div class="modal-body">
											<div class="row">
												<div class="col-md-12">
													<form id="add-form">
														<label for="" style="font-size: 18px; font-weight: 600"><span class="text-danger">* </span>Category Name</label>
														<input type="text" name="categoryName" class="form-control">

														<div class="modal-footer">
															<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
															<input type="submit" id="addBtn" value="ADD" class="btn btn-primary">
														</div>
													</form>

												</div>
											</div>
										</div>

									</div>
								</div>
							</div>
						</div>

						<div class="table-container">
							<table class="table table-hover table-border table-sm text-nowrap">
								<thead>
									<tr class="center-text">
										<th scope="col" class="d-none">CATEGORY ID</th>
										<th scope="col" class="col-9">CATEGORY NAME</th>
										<th scope="col" class="col-3">ACTION</th>
									</tr>
								</thead>

								<tbody>
									<?php if (count($categories) > 0):
										foreach ($categories as $category): ?>
											<tr class="center-text">
												<td class="d-none"><?= $category->id ?></td>
												<td><?= $category->name ?></td>

												<td>
													<div class="d-flex justify-content-around align-items-center">
														<!-- VIEW -->
														<div class="container-fluid">
															<form action="category_product.php" method="POST">
																<input type="hidden" name="categoryId" value="<?= $category->id ?>">
																<input type="hidden" name="categoryName" value="<?= $category->name ?>">
																<input type="submit" name="categoryBtn" class="btn btn-info btn-sm w-100" value="VIEW">
															</form>
														</div>

														<!-- EDIT -->
														<div class="container-fluid">
															<button type="button" class="btn btn-secondary btn-sm w-100 editBtn" data-toggle="modal" data-target="#edit-category-modal-<?= $category->id ?>" data-id="<?= $category->id ?>">
																EDIT
															</button>

															<div id="edit-category-modal-<?= $category->id ?>" class="modal fade" tabindex="-1" aria-hidden="true">
																<div class="modal-dialog" role="document">
																	<form class="edit-category-form modal-content">
																		<div class="modal-header">
																			<h5 class="modal-title">EDIT <?= strtoupper($category->name) ?></h5>

																			<button type="button" class="close" data-dismiss="modal" aria-label="Close">
																				<span aria-hidden="true">&times;</span>
																			</button>
																		</div>

																		<div class="modal-body text-left">
																			<div class="row">
																				<div class="col-md-12">
																					<input type="hidden" name="id" value="<?= $category->id ?>">
																					<input type="hidden" name="action" value="editCategory">

																					<label for="categoryName<?= $category->id ?>" class="important-left" style="font-size: 18px; font-weight: 600">Category Name</label>
																					<input type="text" name="category_Name" id="categoryName<?= $category->id ?>" value="<?= $category->name ?>" class="form-control">
																				</div>
																			</div>
																		</div>

																		<div class="modal-footer">
																			<button type="button" id="" class="btn btn-secondary" data-dismiss="modal">CLOSE</button>
																			<input type="submit" value="UPDATE" class="btn btn-primary editCategory">
																		</div>
																	</form>
																</div>
															</div>
														</div>

														<!-- ARCHIVE -->
														<div class="container-fluid">
															<button type="button" class="btn btn-danger btn-sm w-100" data-toggle="modal" data-target="#archive-category-modal-<?= $category->id ?>" data-id="<?= $category->id ?>">
																ARCHIVE
															</button>

															<div id="archive-category-modal-<?= $category->id ?>" class="modal fade">
																<div class="modal-dialog">
																	<div class="modal-content">
																		<div class="modal-body d-flex justify-content-center align-items-center w-100 flex-column" style="height: 200px;">
																			<p class="h5">Are you sure you want to archive <b><?= $category->name ?></b>?</p>

																			<form class="archive-category-form" id="archive-category-<?= $category->id ?>">
																				<input type="hidden" name="id" value="<?= $category->id ?>">
																				<input type="hidden" name="action" value="archiveCategory">
																				<input type="submit" class="d-none" id="submit-archive-category-<?= $category->id ?>">
																			</form>

																			<div class="d-flex justify-content-center align-items-center mt-3 flex-row w-100">
																				<button type="button" class="btn btn-default w-25 mx-2" data-dismiss="modal">Close</button>
																				<label for="submit-archive-category-<?= $category->id ?>" class="btn btn-danger w-25 mx-2" tabindex="0">Archive</label>
																			</div>
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
											<td colspan="2" class="text-center">No Categories Yet</td>
										</tr>
									<?php endif; ?>
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

		<script>
			$(document).ready(function() {
				// LOCAL FUNCTIONS
				function showFlash(failed = true) {
					data = {
						position: 'center',
						icon: 'success',
						title: 'Success!',
						showConfirmButton: false,
						timer: 1300
					};

					if (failed) {
						data.icon = `warning`;
						data.title = `There is an error, please try again.`;
					}

					Swal.fire(data).then(() => {
						window.location.reload();
					});
				}
				function successCallback(response) {
					if (window.location.origin == `http://localhost`) {
						Swal.fire({
							title: `Response`,
							width: `100%`,
							html: response,
							icon: `info`
						});

						console.log(response);
					}

					switch (response) {
						case `success`:
							showFlash(false);
							break;
						case `error`:
							showFlash();
							break;
					}
				}
				function errorCallback(response) {
					console.warn(response);
					showFlash();
				}

				// ARCHIVE CATEGORY AJAX
				$(`.archive-category-form`).on(`submit`, (e) => {
					e.preventDefault();
					let form = $(e.target);

					$.ajax({
						url: "../processPhp/archive_process.php",
						method: "POST",
						data: form.serialize(),
						success: successCallback,
						error: errorCallback

					})
				});

				$("#archive_btn").click(function(e) {
					e.preventDefault();
					console.log("napindot si a");
					// e.preventDefault();

					$.ajax({
						url: "../processPhp/archive_process.php",
						method: "POST",
						data: $("#form-archive-category").serialize() + "&action=archiveCategory",
						success: function(response) {

							if (response == "successArchive") {
								Swal.fire({
									position: 'center',
									icon: 'success',
									title: 'Successfully Archived!',
									showConfirmButton: false,
									timer: 1300
								}).then(function() {
									window.location = "category.php";
								})
							} else if (response == "errorArchive") {
								Swal.fire({
									position: 'center',
									icon: 'success',
									title: 'There is an error, Please try again',
									showConfirmButton: false,
									timer: 1300
								}).then(function() {
									window.location = "category.php";
								})
							}

						}
					})
				});

				// EDIT CATEGORY AJAX
				$(".edit-category-form").on(`submit`, (e) => {
					e.preventDefault();
					let form = $(e.target);

					$.ajax({
						url: "../processPhp/edit_process.php",
						method: "POST",
						data: form.serialize(),
						success: successCallback,
						error: errorCallback
					})
				});

				$("#addBtn").click(function(e) {
					e.preventDefault();

					$.ajax({
						url: "../processPhp/add_process.php",
						method: "POST",
						data: $("#add-form").serialize() + '&action=addCategory',
						success: function(response) {
							console.log(response)
							if (response == "addedSuccess") {

								Swal.fire({
									position: 'center',
									icon: 'success',
									title: 'Added Category Successfully!',
									showConfirmButton: false,
									timer: 1500
								}).then(function() {
									window.location = "./category.php";
								})

							} else if (response == "error") {

								Swal.fire({
									position: 'center',
									icon: 'error',
									title: 'There is an error. Please Try Again!',
									showConfirmButton: false,
									timer: 1500
								}).then(function() {
									window.location = "./category.php";
								})

							}
						}
					})
				});
			})
		</script>

	</body>
</html>

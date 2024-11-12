<div class="nav-container">
	<ul>
		<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Admin" || $_SESSION['user_type'] == "Cashier" || $_SESSION['user_type'] == "Staff"): ?>
			<li class="hover-effect">
				<a href="dashboard.php" class="w-100">
					<i class="fa-solid fa-house"></i><span>DASHBOARD</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Admin" || $_SESSION['user_type'] == "Staff"): ?>
			<li class="hover-effect">
				<a href="category.php" class="w-100">
					<i class="fa-solid fa-layer-group"></i>CATEGORY
				</a>
			</li>
		<?php endif; ?>

		<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Admin" || $_SESSION['user_type'] == "Staff"): ?>
			<li class="hover-effect">
				<a href="products.php" class="w-100">
					<i class="fa-brands fa-product-hunt"></i>PRODUCTS
				</a>
			</li>
		<?php endif; ?>

		<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Admin" || $_SESSION['user_type'] == "Cashier"): ?>
			<li class="hover-effect">
				<a href="transaction.php" class="w-100">
					<i class="fa-solid fa-receipt"></i>TRANSACTION
				</a>
			</li>
		<?php endif; ?>

		<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Admin"): ?>
			<li class="hover-effect">
				<a href="audit_trail.php" class="w-100">
					<i class="fa-solid fa-shop-lock"></i>AUDIT TRAIL
				</a>
			</li>
		<?php endif; ?>

		<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Admin" || $_SESSION['user_type'] == "Cashier"): ?>
			<li class="hover-effect">
				<a href="reports.php" class="w-100">
					<i class="fa-solid fa-file"></i>REPORTS
				</a>
			</li>
		<?php endif; ?>

		<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Admin" || $_SESSION['user_type'] == "Staff"): ?>
			<li class="hover-effect">
				<a href="inventory.php" class="w-100">
					<i class="fa-solid fa-warehouse"></i>INVENTORY
				</a>
			</li>
		<?php endif; ?>

		<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Admin"): ?>
			<li class="hover-effect">
				<a href="supplier.php" class="w-100">
					<i class="fa-solid fa-truck-ramp-box"></i>SUPPLIER
				</a>
			</li>
		<?php endif; ?>

		<?php if (isset($_SESSION['user_type']) && $_SESSION['user_type'] == "Admin" || $_SESSION['user_type'] == "Cashier"): ?>
			<li class="hover-effect">
				<a href="settings.php" class="w-100">
					<i class="fa-solid fa-gear"></i>SETTINGS
				</a>
			</li>
		<?php endif; ?>
	</ul>
</div>

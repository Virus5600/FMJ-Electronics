<div class="nav-container">
    <ul>
        <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=="Admin" || $_SESSION['user_type']=="Cashier" || $_SESSION['user_type']=="Staff"){?>
            <li>
                <a href="dashboard.php" class="">
                    <i class="fa-solid fa-house"></i><span>DASHBOARD</span>
                </a>
            </li>
        <?php } ?>

        <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=="Admin" || $_SESSION['user_type']=="Staff"){?>
            <li><a href="category.php">
                    <i class="fa-solid fa-layer-group"></i>CATEGORY
                </a>
            </li>
         <?php } ?>
        
         <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=="Admin" || $_SESSION['user_type']=="Staff"){?>
        <li><a href="products.php">
                <i class="fa-brands fa-product-hunt"></i>PRODUCTS
            </a>
        </li>
         <?php } ?>

         <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=="Admin" || $_SESSION['user_type']=="Cashier"){?>
            <li><a href="transaction.php">
                <i class="fa-solid fa-receipt"></i>TRANSACTION</a>
            </li>
        <?php } ?>
        
        <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=="Admin"){?>
        <li>
            <a href="audit_trail.php">
                <i class="fa-solid fa-shop-lock"></i>AUDIT TRAIL
            </a>
        </li>
        <?php } ?>
        
        <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=="Admin" || $_SESSION['user_type']=="Cashier"){?>
        <li><a href="reports.php">
            <i class="fa-solid fa-file"></i>REPORTS</a>
        </li>
        <?php } ?>

        <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=="Admin" || $_SESSION['user_type']=="Staff"){?>
        <li><a href="inventory.php">
            <i class="fa-solid fa-warehouse"></i>INVENTORY</a>
        </li>
        <?php } ?>

        <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=="Admin"){?>
        <li><a href="supplier.php">
        <i class="fa-solid fa-truck-ramp-box"></i>SUPPLIER</a>
        </li>
        <?php } ?>

        <?php if(isset($_SESSION['user_type']) && $_SESSION['user_type']=="Admin" || $_SESSION['user_type']=="Cashier"){?>
            <li><a href="settings.php">
                <i class="fa-solid fa-gear"></i>SETTINGS</a>
            </li>
        <?php } ?>
        
    </ul>
</div>
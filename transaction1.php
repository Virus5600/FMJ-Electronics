<?php 

    require_once("../connection.php");

    session_start();

    if(!isset($_SESSION['officials_Id'])){
        header('Location: ../index.php');
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
        <?php require_once("../templates/topNav.php")?>

        <div class="main-container">

            <div class="left-container">
                <?php require_once("../templates/leftNav.php")?>
            </div>

            <div class="right-container">
                <div class="row m-0 p-0">
                    <div class="col-md-12">
                        <div class="main-title">
                            <i class="fa-solid fa-layer-group"></i><span>TRANSACTION</span>
                        </div>

                        <form >

                            <div class="row">
                                <div class="col-md-5 pr-0">
                                    
                                </div>
                                <div class="col-md-5 pl-0">
                                    
                                    <div class="row">
                                        <div class="col-md-4 pr-0  d-flex justify-content-end align-items-center">
                                            <label for="" class="mb-0 font-weight-bold text-light bg-success" style="font-size: 22px; padding: 1px 20px;">Stocks:</label>
                                        </div>

                                        <div class="col-md-8">
                                            <input type="text" name="stocks" id="stocks" class="form-control-sm form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; " value="0" readonly>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    
                                </div>
                            </div>


                            <div class="row mt-2">
                                <div class="col-md-5 pr-0">
                                    <div class="row">
                                        <div class="col-md-5 pr-0 d-flex justify-content-end align-items-center">
                                            <label for="" class="mb-0 font-weight-bold" style="font-size: 22px">Transaction No.:</label>
                                        </div>

                                        <div class="col-md-7 ">
                                            <?php 
                                                $sqlt = "SELECT transaction_Number FROM transactions_table ORDER BY transaction_Number DESC LIMIT 1";
                                                $resultt = $conn->query($sqlt);

                                                if($resultt){
                                                    $rowt = $resultt->fetch_assoc();
                                                    $count = $rowt['transaction_Number'];

                                                    $newEntryNumber = str_pad((int)$count + 1, 6, '00000', STR_PAD_LEFT);

                                                    echo '<input type="text" name="" value="'.$newEntryNumber.'" class="form-control-sm form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; ">';
                                                }
                                            ?>

                                    
                                            
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 pl-0">
                                    
                                    <div class="row">
                                        <div class="col-md-4 pr-0 d-flex justify-content-end align-items-center">
                                            <label for="" class="mb-0 font-weight-bold" style="font-size: 22px">Price:</label>
                                        </div>

                                        <div class="col-md-8">
                                            <input type="text" name="price" id="price" class="form-control-sm form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; " value="0" readonly>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="transactionBtn" id="addItem">ADD ITEM</button>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-5 pr-0">
                                    <div class="row">
                                        <div class="col-md-5 pr-0 d-flex justify-content-end align-items-center">
                                            <label for="" class="mb-0 font-weight-bold" style="font-size: 22px">Item Description:</label>
                                        </div>

                                        <div class="col-md-7">
                                            <select name="item-dropdown" id="selectpicker" data-live-search="true" class="form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; ">
                                                <option value="">Select Item Description</option>
                                                <?php 
                                                    $sqlp = "SELECT p.product_Id, p.item_code, p.category_Id, p.category_product_Id, p.product_type_Id, p.type_Id, p.stocks, p.prize, p.archive, c.category_Id, c.category_Name, cp.category_product_Id, cp.product_Name, cpi.category_product_item_Id, cpi.product_item_name, cpit.category_product_item_type_Id, cpit.product_item_type_name FROM products p INNER JOIN category_table c ON p.category_Id = c.category_Id INNER JOIN category_product_table cp ON p.category_product_Id = cp.category_product_Id INNER JOIN category_product_item_table cpi ON p.product_type_Id = cpi.category_product_item_Id INNER JOIN category_product_item_type_table cpit ON p.type_Id = cpit.category_product_item_type_Id WHERE p.archive='No'";
                                                    $resultp = $conn->query($sqlp);

                                                    if($resultp->num_rows > 0){
                                                        while($rowsp = $resultp->fetch_assoc()){
                                                            echo '<option value="'.$rowsp['product_Id'].'">'.$rowsp['product_item_type_name'].'</option>';
                                                        }
                                                    }
                                                ?>
                                                
                                            </select>
                                            <!-- <input type="text" name="itemDescription" class="form-control-sm form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; "> -->
                                        </div>
                                    </div>
                                </div> 
                                <div class="col-md-5 pl-0">
                                    

                                    <div class="row">
                                        <div class="col-md-4 pr-0 d-flex justify-content-end align-items-center">
                                            <label for="" class="mb-0 font-weight-bold" style="font-size: 22px">Quantity:</label>
                                        </div>

                                        <div class="col-md-8">
                                            <input type="number" name="quantity" id="quantity" class="form-control-sm form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; " value="0">
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    <button type="button" class="scanBtn">SCAN</button>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-md-5 pr-0">
                                    <div class="row">
                                        <div class="col-md-5 pr-0 d-flex justify-content-end align-items-center">
                                            <label for="" class="mb-0 font-weight-bold" style="font-size: 22px">Item Code:</label>
                                        </div>

                                        <div class="col-md-7 ">
                                            <input type="text" name="itemCode" id="itemCode" class="form-control-sm form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; " value="N/A" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-5 pl-0">
                                    <div class="row">
                                        <div class="col-md-4 pr-0 d-flex justify-content-end align-items-center">
                                            <label for="" class="mb-0 font-weight-bold" style="font-size: 22px">Total Amount:</label>
                                        </div>

                                        <div class="col-md-8">
                                            <input type="number" name="amount" id="amount" class="form-control-sm form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; " value="0.00" readonly>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-md-2">
                                    
                                </div>
                            </div>

                        <div class="table-container mt-3">
                            <table class="table table-hover table-border table-sm">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">#</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ITEM CODE</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ITEM DESCRIPTION</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">PRICE</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">QTY</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">TOTAL AMOUNT</th>
                                        <th scope="col" class="text-center" style="font-size: 20px; font-weight: 700">ACTION</th>
                                    </tr>
                                </thead>
                                <tbody id="cartTable">
                                    <!-- <tr>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700">
                                            <input type="text" name="itemDescription" class="form-control-sm form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; ">
                                        </td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700">
                                            <input type="text" name="itemDescription" class="form-control-sm form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; ">
                                        </td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700">
                                            <input type="text" name="itemDescription" class="form-control-sm form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; ">
                                        </td>
                                        <td class="td-product text-center" style="font-size: 18px; font-weight: 700">
                                            <input type="text" name="itemDescription" class="form-control-sm form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; ">
                                        </td>
                                            <td class="td-product text-center" style="font-size: 18px; font-weight: 700">
                                        <input type="text" name="itemDescription" class="form-control-sm form-control" style="background-color: lightgray; color: black; font-size: 18px; text-align: center; font-weight: 600; ">
                                        </td>
                                    </tr> -->
                                </tbody>
                            </table>
                        </div>

                            <div class="row ml-1" >
                                <div class="col-md-5 p-3" style="background-color: #606FF2; border-radius: 5px">
                                    <div class="row ">
                                        <div class="col-md-6 d-flex justify-content-center">
                                            <label for="" class="mb-0 font-weight-bold"  style="background-color: #0012b1; width: 100%; text-align: center; font-size: 20px; color: white">Total:</label>
                                        </div>
                                        <div class="col-md-6 pl-0">
                                            <input type="text" id="total" value="0" class="form-control-sm form-control" style="color: black; font-size: 18px; text-align: center; font-weight: 600; " readonly>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6 d-flex justify-content-center">
                                            <label for="" class="mb-0 font-weight-bold" style="background-color: #0012b1; width: 100%; text-align: center; font-size: 20px; color: white">Payment:</label>
                                        </div>
                                        <div class="col-md-6 pl-0">
                                            <input type="text" id="payment" class="form-control-sm form-control" style="color: black; font-size: 18px; text-align: center; font-weight: 600; " value="0">
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-6 d-flex justify-content-center">
                                            <label for="" class="mb-0 font-weight-bold" style="background-color: #0012b1; width: 100%; text-align: center; font-size: 20px; color: white">Change:</label>
                                        </div>
                                        <div class="col-md-6 pl-0">
                                            <input type="text" id="change" class="form-control-sm form-control" style="color: black; font-size: 18px; text-align: center; font-weight: 600; " value="0" readonly>
                                        </div>
                                    </div>

                                    <div class="row mt-2">
                                        <div class="col-md-12 ">
                                            <input type="submit" value="RECEIPT" id="receiptBtn">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-7">

                                </div>
                            </div>

                           
                        </form>
                        
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

    <!-- Bootstrap Select Picker -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>

    <script>

        $('#payment').on('change', function(){
            // let payment = this.value;
            // console.log(payment)
            let total = parseInt($('#total').val());
            let payment = parseInt($('#payment').val());
            let change = parseInt($('#change').val());
            

            if(isNaN(payment)){
                $('#payment').val(0);
            }

            if(payment < 0){
                $('#payment').val(0);
            }

            else if(payment >= 0){
                $('#change').val(total - payment);
            }
            

            console.log(payment)
            

            // let price = parseInt($('#price').val());

            // let amount = (isNaN(quantity)? 0 : quantity) * (isNaN(price)? 0 : price);
            // $('#amount').val(amount);
        })

        function confirmDelete(self){
            var id = self.getAttribute("data-id");

            document.getElementById("form-archive-product").id.value = id;
            $("#myModal").addClass("animate__fadeInDown");
            $("#myModal").modal("show");
            
        }

        var cart = [];

        

        

        $('#addItem').on('click', function(){

            let price = parseInt(document.getElementById('price').value);

            let selectpicker = document.getElementById('selectpicker');
            let selectedIndexPicker = selectpicker.selectedIndex;
            let selectedOptionTextPicker = selectpicker.options[selectedIndexPicker].text;

            let quantity = parseInt(document.getElementById('quantity').value);
            let itemCode = document.getElementById('itemCode').value;
            let amount = parseInt(document.getElementById('amount').value);
            let stocks = parseInt(document.getElementById('stocks').value);

            // console.log(price)
            // console.log(selectedOptionTextPicker)
            // console.log(quantity)
            // console.log(itemCode)
            // console.log(amount)



            // function addToCart() {
                // const productName = document.getElementById("productName").value;
                // const quantity = parseInt(document.getElementById("quantity").value);

            if (selectedOptionTextPicker != "Select Item Description" && quantity >= 1) {
                const item = {
                    selectedOptionTextPicker,
                    itemCode,
                    price,
                    quantity,
                    amount
                };

                cart.push(item);

                

                let hasDuplicates = false;

                for (let i = 0; i < cart.length; i++) {
                    for (let j = i + 1; j < cart.length; j++) {
                        if (cart[i].selectedOptionTextPicker === cart[j].selectedOptionTextPicker && cart[i].itemCode === cart[j].itemCode) {
                        hasDuplicates = true;
                        break;
                        }
                    }
                    if (hasDuplicates) {
                        break;
                    }
                }

                // console.log("Array has duplicates:", hasDuplicates);

                if(hasDuplicates === true){
                    Swal.fire({
                        position: 'center',
                        icon: 'error',
                        title: 'You already have Item like that!',
                        showConfirmButton: false,
                        timer: 1500  
                    }).then(function(){
                    })
                    cart.pop()
                }

                else{

                    if(quantity > stocks){
                        Swal.fire({
                            position: 'center',
                            icon: 'error',
                            title: 'Out of Stock!',
                            showConfirmButton: false,
                            timer: 1500  
                        }).then(function(){
                        })
                        cart.pop()
                    }

                    else{

                        const keyToSum = 'amount';

                        const sum = cart.reduce((accumulator, currentValue) =>{
                            return accumulator + currentValue[keyToSum];
                        }, 0);

                        console.log(sum);

                        $('#total').val(sum);

                        updateCartTable();

                        
                        


                        
                    }

                    

                }

                let total = parseInt($('#total').val());
                let payment = parseInt($('#payment').val());
                let change = parseInt($('#change').val());

                // if(payment >= 0){
                //     $('#change').val(total - payment);
                // }



                
                

                console.log(cart)
                
            } 
            else if(selectedOptionTextPicker == "Select Item Description") {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Please enter valid Item Description.',
                    showConfirmButton: false,
                    timer: 1500  
                }).then(function(){
                })
            }

            else if(quantity <= 0) {
                Swal.fire({
                    position: 'center',
                    icon: 'error',
                    title: 'Please enter valid Quantity.',
                    showConfirmButton: false,
                    timer: 1500  
                }).then(function(){
                })
            }



        })

        

        function updateQuantity(index, newQuantity) {
                
                cart[index].quantity = parseInt(newQuantity);
                cart[index].amount = calculateTotalAmount(cart[index].quantity);
                
                
                console.log("Index: " + index);
                console.log("Amount: " + cart[index].amount);

                // cart[index].amount = cart[index].amount;

                // console.log(cart[index].amount)

                // const keyToSum = 'amount';

                // const sum = cart.reduce((accumulator, currentValue) =>{
                //     return accumulator + currentValue[keyToSum];
                // }, 0);

                // console.log(sum);

                // $('#total').val(sum);

                // let total = parseInt($('#total').val());
                // let payment = parseInt($('#payment').val());
                // let change = parseInt($('#change').val());
                
                updateCartTable();  

                // if(payment >= 0){
                //     $('#change').val(total - payment);
                // }
                
        }

        function calculateTotalAmount(quantity) {
                // You can implement your own logic for calculating total amount based on the product and quantity.
                // For simplicity, let's assume each item costs $10.
                const pricePerItem = price.value;
                return parseInt(quantity) * parseInt(pricePerItem);
        }

        function updateCartTable() {
            const tableBody = document.getElementById("cartTable");

            // console.log(tableBody)
            tableBody.innerHTML = "";

            cart.forEach((item, index) => {
                const row = tableBody.insertRow();
                // <td>${index + 1}</td>
                row.innerHTML = `
                    <td>${index}</td>
                    <td>${item.itemCode}</td>
                    <td>${item.selectedOptionTextPicker}</td>
                    <td>${item.price}</td>
                    <td><input type="number" value="${item.quantity}" onchange="updateQuantity(${index}, this.value)"></td>
                    <td>${item.amount}</td>
                    <td><button onclick="removeFromCart(${index})" class="btn btn-danger" >Remove</button></td>
                `;
                // <td><button onclick="removeFromCart(${index})" id="removeCart" class="btn btn-danger" >Remove</button></td>
            });

            // $("#removeCart1").click(function(e){
            //     e.preventDefault();
            //     alert("we")

                

                
            // })

            

            

            
        }

        function removeFromCart(index) {
            cart.splice(index, 1);

            const keyToSum = 'amount';

            const sum = cart.reduce((accumulator, currentValue) =>{
                return accumulator + currentValue[keyToSum];
            }, 0);

            console.log(sum);

            $('#total').val(sum);

            updateCartTable();

            let total = parseInt($('#total').val());
            let payment = parseInt($('#payment').val());
            let change = parseInt($('#change').val());
            


            if(payment >= 0){
                $('#change').val(total - payment);
            }

        }

        // Iinsert na natin sa database yung mga items


        

        //Dito na tayo sa remove button
            

        // function removeFromCart(index) {
        //     cart.splice(index, 1);
        //     updateCartTable();
        // }

        function resetForm() {
            document.getElementById("selectedOptionTextPicker").value = "";
            document.getElementById("itemCode").value = "";
            document.getElementById("price").value = "";
            document.getElementById("quantity").value = "";
            document.getElementById("amount").value = "";
        }

        

        $(document).ready(function(){

            $('#selectpicker').selectpicker();

            

            

            

            

            $('#selectpicker').on('change', function(){
                let product_id = this.value;

                if(product_id == ''){
                    // console.log("Wlang value");

                    $("#price").val(0);
                    $("#itemCode").val("No Results");
                    $("#quantity").val(0);
                    $("#amount").val(0);
                }

                else{

                    console.log(product_id);

                    $.ajax({
                        method: "POST",
                        url: "../processPhp/getItemDescription_process.php",
                        data: {product_ids: product_id},
                        success: function(response){
                            console.log(response);
                            $("#quantity").val(0);
                            $("#amount").val(0);
                            $("#itemCode").val(response[0].item_code);
                            $("#price").val(response[0].prize);
                            $("#stocks").val(response[0].stocks);
                        }
                    })

                }

                
            })

            $('#quantity').on('change', function(){

                let quantity = parseInt($('#quantity').val());
                

                if(isNaN(quantity)){
                    $('#quantity').val(0);
                }
            

                let price = parseInt($('#price').val());

                let amount = (isNaN(quantity)? 0 : quantity) * (isNaN(price)? 0 : price);
                $('#amount').val(amount);

             
            })

            // ARCHIVE REQUEST AJAX

            $("#archive_btn").click(function(e){
                    e.preventDefault();
                    console.log("napindot si a");
                    // e.preventDefault();

                    $.ajax({
                        url: "../processPhp/archive_process.php",
                        method: "POST",
                        data: $("#form-archive-product").serialize() + "&action=archivemainProduct",
                        success : function (response){

                            if(response == "successArchive"){
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'Successfully Archived!',
                                        showConfirmButton: false,
                                        timer: 1300  
                                    }).then(function(){
                                        window.location = "products.php";
                                    })
                            }

                            else if(response == "errorArchive"){
                                    Swal.fire({
                                        position: 'center',
                                        icon: 'success',
                                        title: 'There is an error, Please try again',
                                        showConfirmButton: false,
                                        timer: 1300  
                                    }).then(function(){
                                        window.location = "products.php";
                                    })
                            }
                            
                        }
                    })
            })

            $('.editBtn').on('click', function(){
                console.log("Clikced")
                $('#edit_product').modal('show');

                $tr = $(this).closest('tr');

                var data = $tr.children("td").map(function(){
                    return $(this).text();
                }).get();

                $('#edit_product_Id').val(data[0]);
                $('#edit_item_code').val(data[1]);
                $('#edit_category_Id').val(data[2]);
                $('#edit_category_product_Id').val(data[3]);
                $('#edit_product_type_Id').val(data[4]);
                $('#edit_type_Id').val(data[5]);
                $('#stocks').val(data[6]);
                $('#prize').val(data[7]);

            });


            // FOR EDIT PRODUCT AJAX

            $("#update_productBtn").click(function(e){
                e.preventDefault();

                $.ajax({
                    url: "../processPhp/edit_process.php",
                    method: "POST",
                    data: $("#edit-form").serialize() + '&action=editProduct',
                    success: function (response){
                        console.log(response)
                        if(response == "editedSuccess"){

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Edited Product Successfully!',
                                showConfirmButton: false,
                                timer: 1500  
                            }).then(function(){
                                window.location = "./products.php";
                            })

                        }

                        else if(response == "error"){

                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'There is an error. Please Try Again!',
                                showConfirmButton: false,
                                timer: 1500  
                            }).then(function(){
                                window.location = "./products.php";
                            })

                        }
                    }
                })
            })

            $("#addBtn").on('click', function(e){
                e.preventDefault();

                

                $.ajax({
                    url: "../processPhp/add_process.php",
                    method: 'POST',
                    data: $("#add-form").serialize() + '&action=addProducts',
                    success: function(response){

                        if(response == "addedSuccess"){

                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: 'Added Category Successfully!',
                                showConfirmButton: false,
                                timer: 1500  
                            }).then(function(){
                                window.location = "./products.php";
                            })

                        }

                        else if(response == "error"){

                            Swal.fire({
                                position: 'center',
                                icon: 'error',
                                title: 'There is an error. Please Try Again!',
                                showConfirmButton: false,
                                timer: 1500  
                            }).then(function(){
                                window.location = "./products.php";
                            })

                        }
                        
                    }
                })
            })

            $('#category-dropdown').on('change', function(){
                var category_id = this.value;

                // console.log(category_id)
                
                $.ajax({
                    method: "POST",
                    url: "../processPhp/getProduct_process.php",
                    data: {category_ids: category_id},
                    success: function(response){
                        console.log(response);
                        $("#product-dropdown").html(response);
                    }
                })
            })

            $('#product-dropdown').on('change', function(){
                var product_id = this.value;

                // console.log(product_id)
                
                $.ajax({
                    method: "POST",
                    url: "../processPhp/getProductType_process.php",
                    data: {product_ids: product_id},
                    success: function(response){
                        console.log(response);
                        $("#product-type-dropdown").html(response);
                    }
                })
            })

            $('#product-type-dropdown').on('change', function(){
                var type_id = this.value;

                // console.log(type_id)
                
                $.ajax({
                    method: "POST",
                    url: "../processPhp/getType_process.php",
                    data: {type_ids: type_id},
                    success: function(response){
                        console.log(response);
                        $("#type-dropdown").html(response);
                    }
                })
            })

        })

        

        

    </script>

</body>
</html>
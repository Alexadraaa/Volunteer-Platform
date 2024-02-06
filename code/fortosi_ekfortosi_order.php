<?php
session_start();
include("connection.php");

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    echo "User ID: $userId";}
    
    if (isset($_POST['order_id'])) {
        // Sanitize the input to prevent SQL injection
        $order_id = mysqli_real_escape_string($conn, $_POST['order_id']);
        $order_type = mysqli_real_escape_string($conn, $_POST['or_type']);


        if($order_type="Αίτημα"){
            $update_base_req = "UPDATE base set base.num = 
            (select b.num-r.re_number from base b
            join requests r on b.product_id=r.re_pr_id
            join orders o on r.re_or_id=o.or_id
            where or_id=$order_id)";
        }
        elseif($order_type="Προσφορά"){
        $update_base_of = "UPDATE base set base.num =
        (select b.num+of.o_number
        from base b 
        join offers of on b.product_id=of.o_pr_id
        join orders o on of.o_or_id=o.or_id
        where or_id=$order_id)";
        }
    
        if (mysqli_query($conn,$update_base_req) && mysqli_query($conn,$update_base_of) ){
            echo "Order updated successfully";
        } else {
            echo "Error updating order " . mysqli_error($conn);
        }
    } else {
        echo "Order ID not provided in the request";
    }
    
    // Close the database connection
    mysqli_close($conn);
    
    ?>
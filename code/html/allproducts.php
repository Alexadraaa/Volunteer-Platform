<?php
include("connection.php");
$sql = "SELECT product,product_id FROM base";
$result = $conn->query($sql);


// Display product rows dynamically
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productName = $row['product'];
        $productId = $row['product_id'];
        /*
        echo  $productId;
/*
        echo '<tr>';
        echo '<td>' . $productName . '</td>';
        echo '<td><input type="number" class="quantity-input" value="1" id="quantityInput_' . $productName . '"></td>';
        echo '<td><input type="checkbox" class="product-checkbox" value="' . $productName . '"></td>';
        echo '</tr>';*/
/*        echo '<tr>';
echo '<td>' . $productName . '</td>';
echo '<td><input type="number" class="quantity-input" value="1" id="quantityInput_' . $productName . '" data-product-id="' . $productName . '"></td>';
echo '<td><input type="checkbox" class="product-checkbox" value="' . $productName . '"></td>';
echo '</tr>';*/
echo '<tr>';
echo '<td>' . $productName . '</td>';
echo '<td><input type="number" class="quantity-input" value="1" id="quantityInput_' . $productName . '" data-product-id="' . $productId . '"></td>';
echo '<td><input type="checkbox" class="product-checkbox" value="' . $productName . '" data-product-id="' . $productId . '"></td>';
echo '</tr>';


/*
        echo '<tr>';
        echo '<td>' . $productName . '</td>';
        echo '<td>
                <select class="quantity-select" id="quantitySelect_' . $productName . '" onchange="updateQuantity(this.value, \'' . $productName . '\')">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <input type="hidden" class="quantity-input" id="quantityInput_' . $productName . '" value="1">
              </td>';
        echo '<td><input type="checkbox" class="product-checkbox" value="' . $productName . '"></td>';
        echo '</tr>';
        echo '<tr>';
        echo '<td>' . $productName . '</td>';
        echo '<td>
                <select class="quantity-select" id="quantitySelect_' . $productName . '" onchange="updateQuantityInfo(this.value, \'' . $productName . '\', document.getElementById(\'quantityInput_' . $productName . '\'))">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <input type="hidden" class="quantity-input" id="quantityInput_' . $productName . '" value="1">
              </td>';
        echo '<td><input type="checkbox" class="product-checkbox" value="' . $productName . '" onchange="updateQuantityInfo(this.checked, \'' . $productName . '\', document.getElementById(\'quantityInput_' . $productName . '\'))"></td>';
        echo '</tr>';*/
    }
} else {
    echo '<tr><td colspan="3">No products found</td></tr>';
}

$conn->close();
?>

<?php
/*
include("connection.php");
$sql = "SELECT product FROM base";
$result = $conn->query($sql);


// Display product rows dynamically
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $productName = $row['product'];
/*
        echo '<tr>';
        echo '<td>' . $productName . '</td>';
        echo '<td><input type="number" class="quantity-input" value="1" id="quantityInput_' . $productName . '"></td>';
        echo '<td><input type="checkbox" class="product-checkbox" value="' . $productName . '"></td>';
        echo '</tr>';*/

        /*
        echo '<tr>';
        echo '<td>' . $productName . '</td>';
        echo '<td>
                <select class="quantity-select" id="quantitySelect_' . $productName . '" onchange="updateQuantityInfo(this.value, \'' . $productName . '\', document.getElementById(\'quantityInput_' . $productName . '\'), document.getElementById(\'quantitySelect_' . $productName . '\'))">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                </select>
                <input type="hidden" class="quantity-input" id="quantityInput_' . $productName . '" value="1">
              </td>';
        echo '<td><input type="checkbox" class="product-checkbox" value="' . $productName . '" onchange="updateQuantityInfo(this.checked, \'' . $productName . '\', document.getElementById(\'quantityInput_' . $productName . '\'), document.getElementById(\'quantitySelect_' . $productName . '\'))"></td>';
        echo '</tr>';
    }
} else {
    echo '<tr><td colspan="3">No products found</td></tr>';
}

$conn->close();
?*/
<?php
session_start();
include("../../connection.php");

$userId = $_SESSION['user_id'];

$query = "SELECT a.an_id, a.announcement_content, a.announcement_date, b.product, b.product_id
FROM announcements a
JOIN announcement_product_mapping apm ON a.an_id = apm.an_id
JOIN base b ON apm.an_product_id = b.product_id
WHERE a.is_uploaded = 1
ORDER BY a.announcement_date DESC;";

$result = mysqli_query($conn, $query);

$announcementsData = [];

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $announcementId = $row['an_id'];
        $announcementContent = $row['announcement_content'];
        $announcementDate = $row['announcement_date'];
        $formattedDate = date("F j, Y", strtotime($announcementDate));

        $product = $row['product'];
        $productID = $row['product_id'];

        // check if the announcement is already in the array
        $announcementIndex = array_search($announcementId, array_column($announcementsData, 'an_id'));

        if ($announcementIndex !== false) {
            // Add the product to the existing announcement
            $announcementsData[$announcementIndex]['products'][] = [
                'product' => $product,
                'product_id' => $productID
            ];
        } else {
            // create a new entry for the announcement with an array for products
            $announcementsData[] = [
                'an_id' => $announcementId,
                'announcement_content' => $announcementContent,
                'announcement_date' => $formattedDate,
                'products' => [
                    [
                        'product' => $product,
                        'product_id' => $productID
                    ]
                ]
            ];
        }
    }
} else {
    echo "Όχι Διαθέσιμες Προσφορές";
}

mysqli_close($conn);
//echo json_encode($announcementsData);
?>


<!DOCTYPE html>

<!--  This is the announcement page for the civilian user. It contains information about the announcements and the ability to donate products. -->
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ανακοινώσεις</title>
    <link rel="stylesheet" type="text/css" href="..\css\announcementscitizen.css">
    <link rel="stylesheet" type="text/css" href="..\css\umf.css">
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyC_nXA2oQ_YYbhvUp2MComLx7GwZLWVAxw&callback=initMap"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="..\js\announcementscitizen.js" ></script>
    <script src="..\js\umf.js" ></script>
    <script>
        var announcementsData = <?php echo json_encode($announcementsData); ?>;
        var userId = <?php echo json_encode($userId); ?>;
    </script>
    <style>
    footer {
      bottom: 0;
      left: 0;
      width: 100%;
      background-color: #333;
      padding: 20px;
    }

    </style>
    <script src="..\js\announcementscitizen.js" ></script>
   </head>
<body>

    <!-- menu toggle button -->
    <div id="menu-toggle" onclick="toggleMenu()">&#9776;</div>

    <header>
        <h1>Ανακοινώσεις</h1>
    </header>

      <!-- side navigation menu -->
      <div id="mySidenav">
        <a id="close-btn" class="closebtn" onclick="toggleMenu()">&times;</a>
        <a href="mainpagecitizen.php" onclick="toggleMenu()">Αρχική</a>
        <a href="requests.php" onclick="toggleMenu()">Υπηρεσίες</a>
        <a href="contact.php" onclick="toggleMenu()">Επικοινωνία</a>
    </div>

   <!-- user container -->
    <div id="user-container">
        <button id="imageButton" onclick="toggleUserMenu()">
            <img src="../../img/profil.png" alt="Button Image">
            <div id="userMenu" class="dropdown-content">
                <a href="orders.php">Λίστα Αιτημάτων/Προσφορών</a>
                <a href="profilsection.php">Προφίλ</a>
                <a href="../../initialpage.php">Αποσύνδεση</a>
            </div>
        </button>
    </div>

    <div id="header-content">
    <!--
    <div class="announcement" data-date="<?php echo $formattedDate; ?>">
      <div class="announcement-date"><?php echo $formattedDate; ?></div>
         <h2>Ανακοίνωση</h2>
           <p><?php echo $announcementContent; ?></p>
        <button onclick="showContributionPopup('<?php echo  $formattedDate; ?>')">Κάνε Δωρεά</button>
</div>
-->
</div>
  <!-- contribution popup that the user can donate products -->
    <div id="contribution-popup" class="popup-menu">
        <div id="contribution-popup-header" class="popup-menu-header" onmousedown="dragElement(document.getElementById('contribution-popup'))">
            <p>Η προσφορά σου μετράει!</p>
            <span class="close-btn" onclick="closeContributionPopup()">x</span>
        </div>
        <div class="popup-menu-content" id="contribution-content">
            <!-- content will be dynamically added here -->
            <p id="announcement-info"></p>
            <div id="donation-items">
                <!-- donation items will be dynamically added here -->
            </div>
            <button onclick="submitOffer()" class="donate-button">Δώρισε</button>
        </div>
    </div>

    <footer>
        <div class="footer-section">
          <div></div>  
          <p>Με την βοήθειά σου ,στηρίζεις τον καθημερινό διαμερισμό προιόντων σε ανθρώπους που το έχουν ανάγκη.Δώρισε σήμερα!</p>
          <a href="announcementscitizen.php" class="button">
            <img src="../../img/donate.png" alt="Donate Now">
        </a>
        </div>
            <hr class="divider">
            <div class="section2">
                    <div class="column">
                        <h3>Επικοινωνία</h3>
                        <ul>
                          <li>Τηλέφωνο(χωρίς χρέωση):+306946930521</li>
                          <li>Κινητό:+306946930521</li>
                      </ul>
                      <div id="social-media" class="left-align-icons" style="margin-top: 10px;">
                        <a href="#" class="fa fa-facebook icon-small" target="_blank" rel="noopener noreferrer"></a>
                        <a href="#" class="fa fa-twitter icon-medium" target="_blank" rel="noopener noreferrer"></a>
                        <a href="mailto:thebestteam@outlook.com" class="fa fa-envelope icon-small"></a>
                    </div>
                    </div>
                    <div class="column">
                        <h3>Links</h3>
                        <ul>
                            <li><a href="mainpagecitizen.php">Η Ομάδα Μας</a></li>
                            <li><a href="requests.php">Υπηρεσίες</a></li>
                            <li><a href="contact.php">Επικοινωνία</a></li>
                        </ul>
                    </div>
                    <div class="column">
                        <h3>Τοποθεσία</h3>
                        <p>Πλατεία Γεωργίου,Πάτρα</p>
                       <div id="map"></div>  
                 </div>
                </div>
    </footer>
</body>
</html>

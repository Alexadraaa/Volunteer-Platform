var selectedQuantities = {}; 

function createAnnouncementElements() {
    var headerContent = document.getElementById('header-content');

    var distinctAnnouncements = getDistinctAnnouncements(announcementsData);

    distinctAnnouncements.forEach(function(announcement) {
        var formattedDate = new Date(announcement.announcement_date).toLocaleDateString('el-GR', { year: 'numeric', month: 'long', day: 'numeric' });

        var announcementDiv = document.createElement('div');
        announcementDiv.className = 'announcement';
        announcementDiv.setAttribute('data-date', formattedDate);

        var dateDiv = document.createElement('div');
        dateDiv.className = 'announcement-date';
        dateDiv.textContent = formattedDate;

        var titleHeading = document.createElement('h2');
        titleHeading.textContent = 'Ανακοίνωση';

        var contentParagraph = document.createElement('p');
        contentParagraph.textContent = announcement.announcement_content;

        var button = document.createElement('button');
        button.textContent = 'Κάνε Δωρεά';
        button.onclick = function() {
            showContributionPopup(announcement, announcement.an_id);
        };

        announcementDiv.appendChild(dateDiv);
        announcementDiv.appendChild(titleHeading);
        announcementDiv.appendChild(contentParagraph);
        announcementDiv.appendChild(button);

        headerContent.appendChild(announcementDiv);
    });
}

function getDistinctAnnouncements(announcementsData) {
    var uniqueAnnouncements = {};

    announcementsData.forEach(function(announcement) {
        var key = announcement.announcement_content + announcement.announcement_date;

        // check if the announcement with this key is not already added
        if (!uniqueAnnouncements[key]) {
            uniqueAnnouncements[key] = announcement;
        }
    });

    // convert the object values to an array
    return Object.values(uniqueAnnouncements);
}

window.onload = function() {
    createAnnouncementElements();
};

function showContributionPopup(announcement, announcementID) {
    var popup = document.getElementById('contribution-popup');
    if (popup.style.display === "none" || popup.style.display === "") {
        popup.style.display = "block";

        var announcementDate = announcement.announcement_date;
        var announcementContent = announcement.announcement_content;
        var products = announcement.products;  // Corrected variable name
        console.log(products);

        var donationItemsContainer = document.getElementById('donation-items');
        donationItemsContainer.innerHTML = '';

        // Dynamically add donation items based on products
        for (var i = 0; i < products.length; i++) {
            console.log(i + 1, products[i].product_id, products[i].product, donationItemsContainer, announcementID);
            addDonationItem(i + 1, products[i].product_id, products[i].product, donationItemsContainer, announcementID);
        }
    } else {
        popup.style.display = "none";
    }
}

function addDonationItem(lineNumber, product_id, product, container, announcementID) {
    var itemDiv = document.createElement('div');
    itemDiv.className = 'donation-item';

    var lineNumberLabel = document.createElement('span');
    lineNumberLabel.className = 'line-number';
    lineNumberLabel.textContent = lineNumber + '.';

    var productNameLabel = document.createElement('label');
    productNameLabel.textContent = product;

    var quantitySelect = document.createElement('select');
    quantitySelect.name = product_id; // Use product_id as the name
    for (var i = 0; i <= 100; i++) {
        var option = document.createElement('option');
        option.value = i;
        option.text = i;
        quantitySelect.appendChild(option);
    }

    // store selected quantity when the select value changes
    quantitySelect.addEventListener('change', function (event) {
        // use announcementID and product_id as keys to store selected quantities
        var key = `${announcementID}_${product_id}`;
        selectedQuantities[key] = parseInt(this.value, 10);

        console.log(`Selected Quantity for ${key}: ${selectedQuantities[key]}`);
    });

    itemDiv.appendChild(lineNumberLabel);
    itemDiv.appendChild(productNameLabel);
    itemDiv.appendChild(quantitySelect);
    container.appendChild(itemDiv);
    quantitySelect.style.width = '15px';
    quantitySelect.style.marginLeft = '20px';

    // initialize selected quantity to 0
    var key = `${announcementID}_${product_id}`;
    selectedQuantities[key] = 0;

    console.log(`Initial Quantity for ${key}: ${selectedQuantities[key]}`);
}

function submitOffer() {
    console.log('submitOffer function called');

    var xhr = new XMLHttpRequest();
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4) {
            if (xhr.status == 200) {
                console.log('Offer submitted successfully.');
                console.log('Response:', xhr.responseText);
                alert("Η προσφορά σας υποβλήθηκε επιτυχώς!");
                window.location.href = "mainpagecitizen.php";
            } else {
                console.error('Error submitting offer:', xhr.status, xhr.statusText);
                alert("Σφάλμα κατά την υποβολή της προσφοράς. Παρακαλούμε δοκιμάστε ξανά.");
            }
        }
    };

    var url = "submitorderoffers.php";
    console.log('Submitting offer data to:', url);

    xhr.open("POST", url, true);
    xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    var offersData = [];

    // Iterate through selectedQuantities
    for (var key in selectedQuantities) {
        if (selectedQuantities.hasOwnProperty(key)) {
            var splitKey = key.split("_");
            var announcementId = splitKey[0];
            var product = splitKey[1];
            var quantity = selectedQuantities[key];

            console.log('Adding offer data:', {
                o_c_id: userId,
                o_an_id: announcementId,
                o_pr_id: product,
                o_number: quantity
            });

            var offerData = {
                o_c_id: userId,
                o_an_id: announcementId,
                o_pr_id: product,
                o_number: quantity
            };
            offersData.push(offerData);
        }
    }

    var requestData = "offers=" + encodeURIComponent(JSON.stringify(offersData));
    console.log('Sending request data:', requestData);

    xhr.send(requestData);
}

function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    
    // calculate the initial position to center the element
    var rect = elmnt.getBoundingClientRect();
    pos3 = rect.left + rect.width / 2;
    pos4 = rect.top + rect.height / 2;

    if (document.getElementById(elmnt.id + "-header")) {
        document.getElementById(elmnt.id + "-header").onmousedown = function(event) {
            dragMouseDown(event);
        };
    } else {
        elmnt.onmousedown = function(event) {
            dragMouseDown(event);
        };
    }

    function dragMouseDown(e) {
        e = e || window.event;
        e.preventDefault();
   
        pos3 = e.clientX;
        pos4 = e.clientY;
        document.onmouseup = function(event) {
            closeDragElement(event);
        };

        document.onmousemove = function(event) {
            elementDrag(event);
        };
    }

    function elementDrag(e) {
        e = e || window.event;
        e.preventDefault();
      
        pos1 = pos3 - e.clientX;
        pos2 = pos4 - e.clientY;
        pos3 = e.clientX;
        pos4 = e.clientY;
      
        elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
        elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }

    function closeDragElement(e) {
        e = e || window.event;

        document.onmouseup = null;
        document.onmousemove = null;
    }
}

function donateItems() {
    var selectedItems = selectedQuantities;
    
    var xhr = new XMLHttpRequest();
    xhr.open("POST", "submitorder.php", true);
    xhr.setRequestHeader("Content-Type", "application/json");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4 && xhr.status === 200) {
            var response = xhr.responseText;
            alert(response); 
        }
    };

    var jsonData = JSON.stringify({ selectedItems: selectedItems });
    xhr.send(jsonData);
}

function closeContributionPopup() {
    var popup = document.getElementById('contribution-popup');
    popup.style.display = "none";
}
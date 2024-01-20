
  function showContributionPopup(date) {
          // Add logic to show the contribution popup
          console.log("Show popup for date: " + date);
    }
  
    sortAnnouncements();
  
    function sortAnnouncements() {
              var headerContent = document.getElementById('header-content');
              var announcements = Array.from(headerContent.getElementsByClassName('announcement'));
  
              // Sort announcements based on the data-date attribute
              announcements.sort(function (a, b) {
                  var dateA = new Date(a.getAttribute('data-date'));
                  var dateB = new Date(b.getAttribute('data-date'));
                  return dateA - dateB;
              });
  
              // Clear and re-append sorted announcements
              headerContent.innerHTML = '';
              announcements.forEach(function (announcement) {
                  headerContent.appendChild(announcement);
              });
          }
  
        function showContributionPopup(announcementId) {
              var popup = document.getElementById('contribution-popup');
              if (popup.style.display === "none" || popup.style.display === "") {
                  // Show the popup
                  popup.style.display = "block";
  
                  // Get the announcement based on the announcementId
                  var announcement = document.querySelector('.announcement[data-date="' + announcementId + '"]');
  
                  // Populate the popup with information from the announcement
                  var announcementDate = announcement.querySelector('.announcement-date').textContent;
                  var announcementTitle = announcement.querySelector('h2').textContent;
                  var announcementDescription = announcement.querySelector('p').textContent;
  
                  // Get the contribution content container
                  var contributionContent = document.getElementById('contribution-content');
  
              contributionContent.innerHTML = `
             
              <span class="close-btn" onclick="closeContributionPopup()">x</span>
              <div id="donation-items">
                  <!-- Donation items will be dynamically added here -->
              </div>
              <button onclick="donateItems()">Δώρισε</button>
          `;
  
                  // Add items for donation based on the announcement
                  if (announcementId === '2023-11-29') {
                      // Announcement 1 items
                      addDonationItem("Γάλα");
                      addDonationItem("Δημητριακά");
                  } else if (announcementId === '2023-11-28') {
                      // Announcement 2 items
                      addDonationItem("Νερό");
                      addDonationItem("Σαμπουάν");
                  }
  
                  var viewportWidth = window.innerWidth || document.documentElement.clientWidth;
                  var viewportHeight = window.innerHeight || document.documentElement.clientHeight;
                  var popupWidth = popup.offsetWidth;
                  var popupHeight = popup.offsetHeight;
  
                  var topPosition = Math.max(0, (viewportHeight - popupHeight) / 2);
                  var leftPosition = Math.max(0, (viewportWidth - popupWidth) / 2);
  
                  popup.style.top = topPosition + "px";
                  popup.style.left = leftPosition + "px";
              } else {
                  // Hide the popup
                  popup.style.display = "none";
              }
          }
  // Function to close the contribution menu
  function closeContributionPopup() {
      var popup = document.getElementById('contribution-popup');
      popup.style.display = "none";
  }
  
  function addDonationItem(itemName) {
      var donationItemsContainer = document.getElementById('donation-items');
      var itemDiv = document.createElement('div');
  
      var itemNameLabel = document.createElement('label');
      itemNameLabel.textContent = itemName + ": ";
  
      var quantitySelect = document.createElement('select');
      quantitySelect.name = itemName; // Set the name to identify the item
      for (var i = 0; i <= 10; i++) {
          var option = document.createElement('option');
          option.value = i;
          option.text = i;
          quantitySelect.appendChild(option);
      }
  
      itemDiv.appendChild(itemNameLabel);
      itemDiv.appendChild(quantitySelect);
      donationItemsContainer.appendChild(itemDiv);
  }
  
  
  function logout() {
      // Assuming initialpage.html is in the same directory, adjust the path as needed
      window.location.href = 'initialpage.html';
  }
  
  function donateItems() {
              // Your donateItems function
              var donationAmountInputs = document.querySelectorAll('#contribution-content input[type="number"]');
  
              var itemsContributedElement = document.getElementById('itemsContributed');
  
              // Assuming a maximum of 10 items can be donated
              var maxItems = 10;
              var totalDonation = 0;
  
              donationAmountInputs.forEach(function (input) {
                  var quantity = parseInt(input.value, 10) || 0;
                  totalDonation += quantity;
              });
  
              var currentContributed = parseInt(itemsContributedElement.textContent, 10) || 0; // Use 0 if the displayed value is not a valid number
              var newContributed = Math.min(currentContributed + totalDonation, maxItems);
  
              itemsContributedElement.textContent = newContributed + '/' + maxItems;
              if (newContributed >= maxItems) {
                  document.getElementById('donateButton').disabled = true;
              }
          }
  function updateDonationAmount() {
      var donationAmountInput = document.getElementById('donationAmount');
      var itemsContributedElement = document.getElementById('itemsContributed');
  
      // Assuming a maximum of 10 items can be donated
      var maxItems = 10;
  
      var donationAmount = parseInt(donationAmountInput.value, 10) || 0; // Use 0 if the input is not a valid number
      donationAmount = Math.max(1, Math.min(donationAmount, maxItems)); // Ensure the value is between 1 and maxItems
  
      itemsContributedElement.textContent = donationAmount + '/' + maxItems;
  }
  
  function incrementDonationAmount() {
      var donationAmountInput = document.getElementById('donationAmount');
      var currentValue = parseInt(donationAmountInput.value, 10) || 0;
      donationAmountInput.value = Math.min(currentValue + 1, 10);
      updateDonationAmount();
  }
  
  function decrementDonationAmount() {
      var donationAmountInput = document.getElementById('donationAmount');
      var currentValue = parseInt(donationAmountInput.value, 10) || 0;
      donationAmountInput.value = Math.max(currentValue - 1, 1);
      updateDonationAmount();
  }
  
  function dragElement(elmnt) {
    var pos1 = 0, pos2 = 0, pos3 = 0, pos4 = 0;
    if (document.getElementById(elmnt.id + "-header")) {
      // if present, the header is where you move the DIV from:
      document.getElementById(elmnt.id + "-header").onmousedown = dragMouseDown;
    } else {
      // otherwise, move the DIV from anywhere inside the DIV:
      elmnt.onmousedown = dragMouseDown;
    }
  
    function dragMouseDown(e) {
      e = e || window.event;
      e.preventDefault();
      // get the mouse cursor position at startup:
      pos3 = e.clientX;
      pos4 = e.clientY;
      document.onmouseup = closeDragElement;
      // call a function whenever the cursor moves:
      document.onmousemove = elementDrag;
    }
  
    function elementDrag(e) {
      e = e || window.event;
      e.preventDefault();
      // calculate the new cursor position:
      pos1 = pos3 - e.clientX;
      pos2 = pos4 - e.clientY;
      pos3 = e.clientX;
      pos4 = e.clientY;
      // set the element's new position:
      elmnt.style.top = (elmnt.offsetTop - pos2) + "px";
      elmnt.style.left = (elmnt.offsetLeft - pos1) + "px";
    }
  
    function closeDragElement() {
      // stop moving when mouse button is released:
      document.onmouseup = null;
      document.onmousemove = null;
    }
  }
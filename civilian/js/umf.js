/* footer */
function initMap() {
    var myLatLng = { lat: 38.246101605883496,  lng: 21.735149811102655};
   
    var map = new google.maps.Map(document.getElementById('map'), {
      zoom: 15,
      center: myLatLng
    });
  
    var marker = new google.maps.Marker({
      position: myLatLng,
      map: map,
      title: 'Our Location'
    });
}
/* user icon */
// Add an event listener to close the user menu when clicking outside of it
document.addEventListener("click", function (event) {
      
    var target = event.target;
    if (target !== userMenu && !userContainerContains(target)) {
        userMenu.style.display = 'none';
    }
});
function toggleUserMenu() {
    var userMenu = document.getElementById('userMenu');
    userMenu.style.display = (userMenu.style.display === 'block') ? 'none' : 'block';
}

function userContainerContains(element) {
    // Check if the user container or its children contain the element
    var userContainer = document.getElementById('user-container');
    return userContainer.contains(element);
}


/* menu bar */

function toggleMenu() {
    var sidenav = document.getElementById('mySidenav');
    var menuToggle = document.getElementById('menu-toggle');
    var headerContent = document.getElementById('header-content');
    var announcementsHeader = document.querySelector('header h1');
    
    if (sidenav.style.width === '0px' || sidenav.style.width === '') {
    sidenav.style.width = '16%';
    menuToggle.style.display = 'none';
    } else {
    sidenav.style.width = '0';
    menuToggle.style.display = 'block';
    document.body.style.backgroundColor = "";
    }
}

function scrollToContactForm() {
    document.getElementById('container').scrollIntoView({ behavior: 'smooth' });
}
    
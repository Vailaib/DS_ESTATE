// kwdikas gia to navbar otan to site einai se mobile
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM content loaded');
    const menuIcon = document.getElementById('menuIcon');
    const navLinks = document.getElementById('navLinks');

    // kanw toggle to mobile menu otan paththei to eikonidiko
    menuIcon.addEventListener('click', () => {
        navLinks.classList.toggle('show');
    });

    // otan pataw se ena link apo to menu kleinei to menu
    navLinks.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            navLinks.classList.remove('show');
        });
    });

    //kleinei to menu otan pataw eksw apo to menu 
    document.addEventListener('click', (event) => {
        if (!navLinks.contains(event.target) && !menuIcon.contains(event.target)) {
            navLinks.classList.remove('show');
        }
    });
});
//function gia na dixnw to modal
function showModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "block";

}
//kai gia na to kleinw
function closeModal() {
    var modal = document.getElementById("myModal");
    modal.style.display = "none";
}

var span = document.getElementsByClassName("close")[1];
span.onclick = function() {
    closeModal();
}

// otan o xrhsths pataei cancle kleinei to modal
document.getElementById("cancelLogout").onclick = function() {
    closeModal();
}

// otan o xrhsths pataei logout sto modal ton kanei redirect sto logout.php
document.getElementById("confirmLogout").onclick = function() {
    window.location.href = 'logout.php';
}

//to idio gia to login modal 
function showLoginModal() {
        var modal = document.getElementById("loginModal");
        modal.style.display = "block";
    }

    function closeLoginModal() {
        var modal = document.getElementById("loginModal");
        modal.style.display = "none";
    }
//kwdikas poy pairnei data apo to book.php kai to apothhkeuei
document.addEventListener('DOMContentLoaded', () => {
console.log('DOM content loaded');   
const checkInDateInput = document.getElementById('checkInDate');
const checkOutDateInput = document.getElementById('checkOutDate');
const checkAvailabilityButton = document.getElementById('checkAvailability');
const step1Element = document.querySelector('.step-1');
const step2Element = document.querySelector('.step-2');
const totalPriceElement = document.getElementById('totalPrice');
const discountRateElement = document.getElementById('discountRate');

//function poy kanei handle tis allages se hmerominies kai apenergopoiei to koympi otan mh logikes hmerominies exoyn epilexthei 
function handleDateChange() {
    const checkInDate = new Date(checkInDateInput.value);
    const checkOutDate = new Date(checkOutDateInput.value);

    //elegxei ean to checkout einai prin apo to check in kai tote apenergopoiei to koympi
    if (checkOutDate <= checkInDate) {
        checkAvailabilityButton.style.backgroundColor = 'gray';//to koympi ginete gkri ama einai mh logikes hmerominies
        checkAvailabilityButton.disabled = true;
    } else {
        checkAvailabilityButton.disabled = false;
        checkAvailabilityButton.style.backgroundColor = '#FF5A5F';
    }
}
checkInDateInput.addEventListener('change', handleDateChange);
checkOutDateInput.addEventListener('change', handleDateChange);

// function poy energopoiete otan patiete to koympi check availability sto book.php
checkAvailabilityButton.addEventListener('click', function () {
    //apothhkeuei to apairathto data se metablhtes 
    const checkInDate = checkInDateInput.value;
    const checkOutDate = checkOutDateInput.value;
    const listingId = document.querySelector('input[name="listing_id"]').value;
    const listingTitle = document.querySelector('input[name="listing_title"]').value;
    const price_per_night = document.querySelector('input[name="price_per_night"]').value;

    // check ean ola ta fields einai gemata
    if (!checkInDate || !checkOutDate || !listingId || !listingTitle || !price_per_night) {
        showNotification('Please fill in all fields.', 'error');
        return;
    }

    //function gia na dixnw notification sto book
    function showNotification(message, type) {
        const notificationElement = document.getElementById('notification');
        notificationElement.textContent = message;
        
        // ean einai available to dwmatio tote eina prasino to mynhma alliws kokkino
        if (type === 'success') {
            notificationElement.style.backgroundColor = '#4CAF50'; // Green background
        } else if (type === 'error') {
            notificationElement.style.backgroundColor = '#f44336'; // Red background
        }
    
        notificationElement.style.display = 'block';
    
        // to notification eksafanizete meta apo 3 seconds
        setTimeout(() => {
            notificationElement.style.display = 'none';
        }, 3000); 
    }

    //dhmiourgw formData antikeimeno gia na steilw to data mesw post 
    const formData = new FormData();
    formData.append('check_in_date', checkInDate);
    formData.append('check_out_date', checkOutDate);
    formData.append('listing_id', listingId);
    formData.append('title', listingTitle);
    formData.append('price_per_night', price_per_night);

    // ena console log poy xrhsimopoiousa gia debug 
    console.log('Sending data:', {
        check_in_date: checkInDate,
        check_out_date: checkOutDate,
        listing_id: listingId,
        title: listingTitle,
        price_per_night: price_per_night
    });

    //kanw ajax request sto check availability
    fetch('check_availability.php', {
        method: 'POST',
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            console.log('Response data:', data);
            if (data.available) {
                showNotification('Rooms are available!', 'success');
                // krubw to step 1 kai dixnw to step 2 (des html sto book.php)
                step1Element.style.display = 'none';
                step2Element.style.display = 'block';

                //kanw update to discount kai thn telikh timh sto form
                discountRateElement.textContent = (data.discountRate * 100).toFixed(0);
                totalPriceElement.textContent = data.totalPrice.toFixed(0);
                const totalPrice2 = document.getElementById('totalPriceInput');
                totalPrice2.value = data.totalPrice.toFixed(0);
            } else {
                //ean den eniai diathesimo to dwmatio dixnei sxetiko mynhma
                showNotification('Rooms are not available for the selected dates.', 'error');
            
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('An error occurred while checking availability. Please try again.', 'error');
        
        });
});
    });

    
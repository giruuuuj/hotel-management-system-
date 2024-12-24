const DEBUG = true;

function debugLog(message, data = null) {
    if (DEBUG) {
        console.log(`[DEBUG] ${message}`, data || '');
    }
}

// Update your form submission code
document.getElementById('bookingForm').addEventListener('submit', function(e) {
    e.preventDefault();
    debugLog('Form submitted');
    
    const formData = new FormData(this);
    
    // Log form data
    if (DEBUG) {
        for (let pair of formData.entries()) {
            debugLog('Form field', `${pair[0]}: ${pair[1]}`);
        }
    }
    
    fetch('process_booking.php', {
        method: 'POST',
        body: formData
    })
    .then(response => {
        debugLog('Response received', response);
        return response.json();
    })
    .then(data => {
        debugLog('Data processed', data);
        if (data.status === 'success') {
            alert('Booking successful!');
            this.reset();
        } else {
            alert('Error: ' + data.message);
        }
    })
    .catch(error => {
        debugLog('Error occurred', error);
        alert('An error occurred while processing your booking');
    });
}); 
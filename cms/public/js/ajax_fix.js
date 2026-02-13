// Add this to your main JavaScript file or before AJAX calls

// Fix CSRF token issue
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});

// Enhanced AJAX error handling
$(document).ajaxError(function(event, xhr, settings, thrownError) {
    console.log('AJAX Error Details:');
    console.log('URL:', settings.url);
    console.log('Status:', xhr.status);
    console.log('Response:', xhr.responseText);
    console.log('Error:', thrownError);
    
    let errorMessage = 'Network error occurred';
    
    if (xhr.status === 0) {
        errorMessage = 'Connection failed. Check your internet connection.';
    } else if (xhr.status === 404) {
        errorMessage = 'Requested page not found (404).';
    } else if (xhr.status === 500) {
        errorMessage = 'Internal server error (500).';
    } else if (xhr.status === 419) {
        errorMessage = 'Session expired. Please refresh the page.';
    } else if (xhr.responseJSON && xhr.responseJSON.message) {
        errorMessage = xhr.responseJSON.message;
    }
    
    alert(errorMessage);
});

// Test AJAX connection function
function testAjaxConnection() {
    $.ajax({
        url: '/ajax_test.php',
        method: 'POST',
        dataType: 'json',
        success: function(response) {
            console.log('AJAX Test Success:', response);
            alert('AJAX connection working!');
        },
        error: function(xhr, status, error) {
            console.log('AJAX Test Failed:', xhr.responseText);
            alert('AJAX connection failed: ' + error);
        }
    });
}
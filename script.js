$(document).ready(function () {
    $('#registrationForm').submit(function (event) {
        event.preventDefault(); // Prevent default form submission

        var formData = $(this).serialize(); // Get form data

        $.ajax({
            try:{
                url:'form_handler.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                $('#responseData').html(response); // Display the response data
                alert("Form submitted successfully!");
                $('#registrationForm').trigger('reset'); // Reset the form
            }
            },
                 
        });
    });
});




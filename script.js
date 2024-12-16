$(document).ready(function () {
    $('#registrationForm').submit(function (event) {
        event.preventDefault(); // Prevent default form submission

        var formData = $(this).serialize(); // Get form data

        $.ajax({
            url: 'form_handler.php',
            type: 'POST',
            data: formData,
            success: function (response) {
                $('#responseData').html(response); // Display the response data
                alert("Form submitted successfully!");
                $('#registrationForm').trigger('reset'); // Reset the form
            },
            error: function (xhr, status, error) {
                // Insert the error message into a dedicated error container
                $('#errorContainer').html(`<p class="error-message">There was an error submitting the form: ${error}</p>`);

                // Optionally log the error for debugging
                console.error("Error details:", {
                    status: status,
                    error: error,
                    responseText: xhr.responseText
                });
            }
        });
    });
});

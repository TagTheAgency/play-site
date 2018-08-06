$(function() {
    // Get the form.
    var form = $('#contactform');

    // Get the messages div.
	$(form).submit(function(event) {
    // Stop the browser from submitting the form.
    event.preventDefault();
	var formData = $(form).serialize();

	$.ajax({
	    type: 'POST',
	    url: $(form).attr('action'),
	    data: formData
	});

	});
});

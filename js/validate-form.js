jQuery( document ).ready(function() {
    jQuery(function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        jQuery("#register-form").validate({
          // Specify validation rules
          rules: {
            // The key name on the left side is the name attribute
            // of an input field. Validation rules are defined
            // on the right side
            name: {
                 required: true,
                 lettersonly: true
            }
          },
          // Specify validation error messages
          messages: {
            name: {
                required:"Por favor introduce tu nombre",
                lettersonly:"El nombre solo puede contener letras"
            }
          },
          // Make sure the form is submitted to the destination defined
          // in the "action" attribute of the form when valid
          submitHandler: function(form) {
            form.submit();
          }
        });
    });
});
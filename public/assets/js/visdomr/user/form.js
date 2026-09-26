"use strict";
// Class definition
var KTUserForm = function () {
    // Shared variables
    const form = document.querySelector('#kt_user_form');
    const submitButton = form.querySelector('#kt_user_submit');

    const inputPhone = document.querySelector('input[name="phone"]');
    var iti;

    var handleForm = () => {
        var validator = $(form).validate({
            rules: {
                first_name: {
                    required: true,
                },
                last_name: {
                    required: true,
                },
                email: {
                    required: true,
                },
            },
            submitHandler: function(e) {
                // Show loading indication
                submitButton.setAttribute('data-kt-indicator', 'on');

                // Disable button to avoid multiple click 
                submitButton.disabled = true;
                
                // Send ajax request
                // console.log('sdfs');
                axios.post(form.getAttribute('action'), new FormData(form))
                .then(function (response) {
                    var action = form.getAttribute('data-action');
                    //  console.log(action);
                    var successMsg = 'Updated Successfully.';
                    if( action == 'store' ) {
                        var successMsg = 'Created Successfully.';
                    }
                    Swal.fire({
                        text: successMsg,
                        icon: "success",
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        buttonsStyling: false,
                        confirmButtonText: "Ok",
                        customClass: {
                            confirmButton: "btn btn-primary"
                        }
                    }).then(function (result) {
                        if (result.isConfirmed) {
                            if( action == 'store' ) {
                                location.href = $( 'a.back_btn' ).attr( 'href' );
                            } else {
                                location.reload();
                            }
                        }
                    });
                })
                .catch((error) => {})
                .then(function () {
                    // always executed
                    // Hide loading indication
                    submitButton.removeAttribute('data-kt-indicator');

                    // Enable button
                    submitButton.disabled = false;
                });

                return false;
            }
        });
    }

    var handlePhoneInput = () => {
        if ( inputPhone !== null ) {
            Inputmask({
                "mask" : "(999) 999-9999",
                "autoUnmask" : true
            }).mask( inputPhone );

            iti = window.intlTelInput( inputPhone, {
                initialCountry: "us",
                onlyCountries: ["us"],
                separateDialCode: true,
                nationalMode: true,
                formatOnDisplay: true,
                preferredCountries: [ 'us'],
                hiddenInput: 'intl_phone',
                utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.1.1/build/js/utils.js',
            });

            inputPhone.addEventListener( 'blur', handlePhoneInputChange );
            inputPhone.addEventListener( 'change', handlePhoneInputChange );
            inputPhone.addEventListener( 'keyup', handlePhoneInputChange );
            
            jQuery.validator.addMethod( 'intlTelNumber', function( value, element ) {
                return this.optional( element ) || iti.isValidNumber();
            }, 'Please enter a valid International Phone Number' );
            jQuery.validator.addClassRules({
                intl_phone: {
                    intlTelNumber: true
                }
            });
        }
    }

    const handlePhoneInputChange = (e) => {
        if ( e.target.value ) {
            var inputHiddenPhone = document.querySelector('input[name="intl_phone"]');
            inputHiddenPhone.value = iti.getNumber();
        }
    };

    return {
        // Public functions
        init: function () {
            if (!form) {
                return;
            }

            handleForm();
            handlePhoneInput();

        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTUserForm.init();
});
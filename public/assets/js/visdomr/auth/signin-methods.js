"use strict";
// Class definition
var KTSignInMethod = function () {
    // Shared variables
    
    var initSettings = function () {
        // UI elements
        var signInMainEl = document.getElementById('kt_signin_email');
        var signInEditEl = document.getElementById('kt_signin_email_edit');
        var passwordMainEl = document.getElementById('kt_signin_password');
        var passwordEditEl = document.getElementById('kt_signin_password_edit');

        // button elements
        var signInChangeEmail = document.getElementById('kt_signin_email_button');
        var signInCancelEmail = document.getElementById('kt_signin_cancel');
        var passwordChange = document.getElementById('kt_signin_password_button');
        var passwordCancel = document.getElementById('kt_password_cancel');

        // toggle UI
        if( signInChangeEmail !== null ) {
            signInChangeEmail.querySelector('button').addEventListener('click', function () {
                toggleChangeEmail();
            });
        }

        if( signInCancelEmail !== null ) {
            signInCancelEmail.addEventListener('click', function () {
                toggleChangeEmail();
            });
        }

        if( passwordChange !== null ) {
            passwordChange.querySelector('button').addEventListener('click', function () {
                toggleChangePassword();
            });
        }

        if( passwordCancel !== null ) {
            passwordCancel.addEventListener('click', function () {
                toggleChangePassword();
            });
        }

        var toggleChangeEmail = function () {
            signInMainEl.classList.toggle('d-none');
            signInChangeEmail.classList.toggle('d-none');
            signInEditEl.classList.toggle('d-none');
        }

        var toggleChangePassword = function () {
            passwordMainEl.classList.toggle('d-none');
            passwordChange.classList.toggle('d-none');
            passwordEditEl.classList.toggle('d-none');
        }
    }

    var handleChangeEmail = function (e) {
        // form elements
        var form = document.getElementById('kt_signin_email_form');

        if( form !== null ) {
            var submitButton = form.querySelector('#kt_signin_email_submit');

            var validator = $( form ).validate({
                rules: {
                    email: {
                        required: true,
                        email: true
                    },
                    confirm_password: {
                        required: true,
                        minlength: 8,
                    }
                },
                messages: {
                    email: {
                        required: 'Email address is required.',
                        email: 'Not a valid email address'
                    },
                    confirm_password: {
                        required: 'Current Password is required.',
                        minlength: 'Minimum 8 characters',
                    }
                },
                submitHandler: function(e) {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;

                    axios.post(form.getAttribute('action'), new FormData(form))
                    .then(function (response) {
                        Swal.fire({
                            title: 'Success',
                            text: "Email updated successfully.",
                            icon: "success",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light-primary"
                            }
                        }).then(function(){
                            $('.current_email').html($('#email').val());
                            form.reset();
                            validator.resetForm();
                            $('#kt_signin_cancel').trigger('click');
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
                }
            });
        }
    }

    var handleChangePassword = function (e) {
        // form elements
        var form = document.getElementById('kt_signin_password_form');
        
        if( form !== null ) {
            var submitButton = form.querySelector('#kt_signin_password_submit');

            var validator = $( form ).validate({
                rules: {
                    current_password: {
                        required: true,
                        minlength: 8,
                    },
                    password: {
                        required: true,
                        minlength: 8,
                    },
                    password_confirmation: {
                        required: true,
                        equalTo: "#new_password",
                        minlength: 8,
                    }
                },
                messages: {
                    current_password: {
                        required: 'Current Password is required.',
                        minlength: 'Minimum 8 characters',
                    },
                    password: {
                        required: 'Password is required.',
                        minlength: 'Minimum 8 characters',
                    },
                    password_confirmation: {
                        required: 'Confirm password required',
                        equalTo: 'Password and confirm password are not equal',
                        minlength: 'Minimum 8 characters',
                    }
                },
                submitHandler: function(e) {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click
                    submitButton.disabled = true;

                    axios.post(form.getAttribute('action'), new FormData(form))
                    .then(function (response) {
                        Swal.fire({
                            title: 'Success',
                            text: "Password updated successfully.",
                            icon: "success",
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            buttonsStyling: false,
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn font-weight-bold btn-light-primary"
                            }
                        }).then(function(){
                            form.reset();
                            validator.resetForm();
                            $('#kt_password_cancel').trigger('click');
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
                }
            });
        }
    }

    return {
        // Public functions
        init: function () {
            initSettings();
            handleChangeEmail();
            handleChangePassword();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSignInMethod.init();
});
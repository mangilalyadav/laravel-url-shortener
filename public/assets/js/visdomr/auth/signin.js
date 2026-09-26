"use strict";
// Class definition
var Login = function () {
    var SignInForm = function () {
        $('#kt_sign_in_form').validate({
            errorElement: 'div',
            errorClass: 'is-invalid',
            validClass: 'is-valid',
            focusInvalid: true,
            rules: {
                email: {
                    required: true,
                    email: true
                },
                password: {
                    required: true,
                    minlength: 8,
                }
            },
            messages: {
                email: {
                    required: 'Email address is required.',
                    email: 'Not a valid email address'
                },
                password: {
                    required: 'Password is required.',
                    minlength: 'New Password must be at least 8 characters.',                    
                },
            },
            errorPlacement: function (error, element) {
                error.appendTo( element.parents('.fv-row') ).wrap( "<div class='fv-plugins-message-container invalid-feedback'></div>" );
            },
            highlight: function (element, errorClass, validClass) {
                $(element).addClass(errorClass).removeClass(validClass);
            },
            unhighlight: function (element, errorClass, validClass) {
                $(element).removeClass(errorClass).addClass(validClass);
            },
            submitHandler: function (e) {
                e.submit();
            }
        });
    }
    return {
        init: function () {
            SignInForm();
        }
    };
}();
jQuery( document ).ready(function() {
    Login.init();
});


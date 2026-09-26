"use strict";
// Class definition
var KTCounselorForm = function () {
    // Shared variables
    const form = document.querySelector('#kt_counselor_form');
    const submitButton = form.querySelector('#kt_counselor_submit');

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

     //Student Marks Form
     var initializeCountrySelect = () => {
        if ($('#country_select').length > 0) {
            $('#country_select').select2({
                placeholder: 'Select countries',
                ajax: {
                    url     : getCountriesUrl, 
                    type    : 'get', 
                    dataType: 'json', 
                    delay   : 250, 
                    data    : function (params) {
                        return {
                            q: params.term, 
                            page: params.page || 1 
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                    // console.log(data);
                        return {
                            results: $.map(data.data, function (item) {
                                return { text: item.name, id: item.id };
                            }),
                            pagination: {
                                more: (params.page * 10) < data.total 
                            }
                        };
                    },
                    cache: true 
                }
            });

            $('#country_select').change(function(){
                $('#state_select').val("").trigger('change');
                $("#state_select").prop("disabled", false);
            })
        } 
    }

    //Student Marks Form
    var initializeStateSelect = () => {
        if($('#state_select').length > 0){
            $('#state_select').select2({
                placeholder: 'Select states',
                ajax: {
                    url     : getStateUrl, 
                    type    : 'get', 
                    dataType: 'json', 
                    delay   : 250, 
                    data    : function (params) {
                        return {
                            q: params.term, 
                            country_id: $('#country_select').val(),
                            page: params.page || 1 
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: $.map(data.data, function (item) {
                                return { text: item.name, id: item.id };
                            }),
                            pagination: {
                                more: (params.page * 10) < data.total 
                            }
                        };
                    },
                    cache: true 
                }
            });
            $("#state_select").prop("disabled", true);

            $('#state_select').change(function(){
                $('#school_select').val("").trigger('change');
                
                $("#school_select").prop("disabled", true);
                if ($(this).val() != null) {
                    $("#school_select").prop("disabled", false);
                }
            })
        }
    }

    //Student Marks Form
    var initializeSchoolSelect = () => {
        if($('#school_select').length > 0){
            $('#school_select').select2({
                placeholder: 'Select schools',
                ajax: {
                    url     : getSchoolUrl, 
                    type    : 'get', 
                    dataType: 'json', 
                    delay   : 250, 
                    data    : function (params) {
                        return {
                            q: params.term, 
                            state_id: $('#state_select').val(),
                            page: params.page || 1 
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        return {
                            results: $.map(data.data, function (item) {
                                return { text: item.name, id: item.id };
                            }),
                            pagination: {
                                more: (params.page * 10) < data.total 
                            }
                        };
                    },
                    cache: true 
                }
            });
            $("#school_select").prop("disabled", true);
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

            // initializeCountrySelect();
            // initializeStateSelect();
            // initializeSchoolSelect();


        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTCounselorForm.init();
});
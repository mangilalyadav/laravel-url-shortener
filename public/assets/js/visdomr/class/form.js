"use strict";
// Class definition
var KTClassForm = function () {
    // Shared variables
    const form = document.querySelector('#kt_class_form');
    const submitButton = form.querySelector('#kt_class_submit');

    var handleForm = () => {
        var validator = $(form).validate({
            rules: {
                name: {
                    required: true,
                },
                school_id: {
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


    var initializeSchoolSelect = () => {
        if ($('#school_select').length > 0) {
            $('#school_select').select2({
                placeholder: 'Select schools',
                ajax: {
                    url     : getSchoolsUrl,
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
        }
    }

    


    return {
        // Public functions
        init: function () {
            if (!form) {
                return;
            }

            handleForm();
            initializeSchoolSelect();  


        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTClassForm.init();
});
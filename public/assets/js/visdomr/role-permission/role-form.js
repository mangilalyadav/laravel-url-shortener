"use strict";

// Class definition
var KTRoleForm = function () {
    // Shared variables
    const form = document.querySelector('#kt_role_form');
    const submitButton = form.querySelector('#kt_role_submit');

    var handleForm = () => {
        var validator = $( form ).validate({
            rules: {
                name: {
                    required: true,
                    minlength: 3
                },
                display_name: {
                    required: true,
                    minlength: 3
                },
            },
            submitHandler: function(e) {
                // Show loading indication
                submitButton.setAttribute('data-kt-indicator', 'on');

                // Disable button to avoid multiple click 
                submitButton.disabled = true;

                // Send ajax request
                axios.post(form.getAttribute('action'), new FormData(form))
                .then(function (response) {
                    var action = form.getAttribute('data-action');
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

    // Select all handler
    const handleSelectAll = () => {
        // Define variables
        const selectAll = form.querySelector('#kt_roles_select_all');
        const allCheckboxes = form.querySelectorAll('tr.permission_group_row [type="checkbox"]');
        const allCheckedCheckboxes = form.querySelectorAll('tr.permission_group_row [type="checkbox"]:checked');

        if( allCheckboxes.length == allCheckedCheckboxes.length ) {
            selectAll.checked = true;
        }

        // Handle check state
        selectAll.addEventListener('change', e => {
            // Apply check state to all checkboxes
            allCheckboxes.forEach(c => {
                c.checked = e.target.checked;
            });
        });

        allCheckboxes.forEach(c => {
            c.addEventListener('change', e => {
                var allCheckedPermissions = form.querySelectorAll('tr.permission_group_row [type="checkbox"]:checked');

                if( allCheckboxes.length == allCheckedPermissions.length ) {
                    selectAll.checked = true;
                } else {
                    selectAll.checked = false;
                }
            }); 
        });
    }

    return {
        // Public functions
        init: function () {
            if (!form) {
                return;
            }

            handleForm();
            handleSelectAll();
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTRoleForm.init();
});
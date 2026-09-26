"use strict";

// Class definition
var KTGroupsList = function() {
    // Shared variables
    var table = document.getElementById('kt_table_groups');
    var datatable;
    var toolbarBase;
    var toolbarSelected;
    var selectedCount;
    
    // Private functions
    // Init datatable
    var initGroupTable = () => {
        datatable = $(table).DataTable({
            'searchDelay': 500,
            'processing': true,
            'serverSide': true,
            'pageLength': 10,
            'order': [[1, 'asc']],
            'select': {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            'language': {
                'emptyTable': 'No groups available'
            },
            'ajax': $(table).data('url'),
            'columns': [
                { data: 'id' },
                { data: 'name' },
                { data: 'actions' },
            ],
            'columnDefs': [
                { targets: 0, orderable: false }, // Disable ordering on column 0 (checkbox)
                { targets: 2, orderable: false, searchable:false, sClass: 'text-end' }, // Disable ordering on column 6 (actions)
            ]
        });

        // Re-init functions on every table re-draw
        datatable.on('draw', function () {
            initToggleToolbar();
            handleDeleteRows();
            toggleToolbars();
        });
    }

    // Search Datatable
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-group-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Delete group
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-groups-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get group name
                const groupName = parent.querySelectorAll('td')[1].innerText;

                Swal.fire({
                    text: "Are you sure you want to delete " + groupName + "?",
                    icon: "warning",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        loadingBlockUI.block();

                        var actionURL = d.getAttribute('data-url');

                        axios.delete(actionURL)
                        .then(function (response) {
                            if (loadingBlockUI.isBlocked()) {
                                loadingBlockUI.release();
                            }
                            Swal.fire({
                                text: response.data.message,
                                icon: "success",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function (result) {
                                // Remove current row
                                datatable.row($(parent)).remove().draw();
                            }).then(function () {
                                // Detect checked checkboxes
                                toggleToolbars();

                                KTRolesList.reDraw();
                                KTPermissionsList.reDraw();
                            });
                        })
                        .catch((error) => {})
                        .then(function () {
                            if (loadingBlockUI.isBlocked()) {
                                loadingBlockUI.release();
                            }
                        });
                    }
                });
            })
        });
    }

    // Init toggle toolbar
    var initToggleToolbar = () => {
        // Toggle selected action toolbar
        // Select all checkboxes
        const checkboxes = table.querySelectorAll('[type="checkbox"]');

        // Select elements
        toolbarBase = document.querySelector('[data-kt-group-table-toolbar="base"]');
        toolbarSelected = document.querySelector('[data-kt-group-table-toolbar="selected"]');
        selectedCount = document.querySelector('[data-kt-group-table-select="selected_count"]');
        const deleteSelected = document.querySelector('[data-kt-group-table-select="delete_selected"]');

        // Toggle delete selected toolbar
        checkboxes.forEach(c => {
            // Checkbox on click event
            c.addEventListener('click', function () {
                setTimeout(function () {
                    toggleToolbars();
                }, 50);
            });
        });

        // Deleted selected rows
        if( deleteSelected !== null ) {
            deleteSelected.addEventListener('click', function () {
                var selected = [];
                $( table ).find( '.form-check-input:checked' ).each(function() {
                    selected.push( $( this ).val() );
                });
                Swal.fire({
                    text: "Are you sure you want to delete selected group(s)?",
                    icon: "warning",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, delete!",
                    cancelButtonText: "No, cancel",
                    customClass: {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton: "btn fw-bold btn-active-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        loadingBlockUI.block();

                        var actionURL = deleteSelected.getAttribute( 'data-url' );

                        axios.delete(actionURL, {
                            data: {
                                'ids': selected
                            }
                        })
                        .then(function (response) {
                            if (loadingBlockUI.isBlocked()) {
                                loadingBlockUI.release();
                            }
                            Swal.fire({
                                text: response.data.message,
                                icon: "success",
                                allowOutsideClick: false,
                                allowEscapeKey: false,
                                buttonsStyling: false,
                                confirmButtonText: "Ok",
                                customClass: {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function (result) {
                                // Remove all selected customers
                                checkboxes.forEach(c => {
                                    if (c.checked) {
                                        datatable.row($(c.closest('tbody tr'))).remove().draw();
                                    }
                                });

                                // Remove header checked box
                                const headerCheckbox = table.querySelectorAll('[type="checkbox"]')[0];
                                headerCheckbox.checked = false;
                            }).then(function(){
                                toggleToolbars(); // Detect checked checkboxes
                                initToggleToolbar(); // Re-init toolbar to recalculate checkboxes

                                KTRolesList.reDraw();
                                KTPermissionsList.reDraw();
                            });
                        })
                        .catch((error) => {})
                        .then(function () {
                            if (loadingBlockUI.isBlocked()) {
                                loadingBlockUI.release();
                            }
                        });
                    }
                });
            });
        }
    }

    // Toggle toolbars
    const toggleToolbars = () => {
        // Select refreshed checkbox DOM elements 
        const allCheckboxes = table.querySelectorAll('tbody [type="checkbox"]');

        // Detect checkboxes state & count
        let checkedState = false;
        let count = 0;

        // Count checked boxes
        allCheckboxes.forEach(c => {
            if (c.checked) {
                checkedState = true;
                count++;
            }
        });

        // Toggle toolbars
        if (checkedState) {
            selectedCount.innerHTML = count;
            toolbarBase.classList.add('d-none');
            toolbarSelected.classList.remove('d-none');
        } else {
            toolbarBase.classList.remove('d-none');
            toolbarSelected.classList.add('d-none');
        }
    }

    var handleModals = function () {
        $( '#kt_modal_edit_group' ).on( 'show.bs.modal', function (event) {
            var button = $( event.relatedTarget );
            var modal = $( this );
            var validator = modal.find( '.modal-content form' ).validate();

            // Select parent row
            const parent = event.relatedTarget.closest('tr');
            // Get group name
            const groupName = parent.querySelectorAll('td')[1].innerText;
            
            modal.find( '.modal-content form' ).trigger('reset');
            validator.resetForm();
            modal.find( '.modal-content form .form-control' ).removeClass('is-valid').removeClass('is-invalid');

            modal.find( '.modal-content form' ).attr( 'action', button.data( 'url' ) );
            modal.find( '.modal-body input[name="name"]' ).val( groupName );
        });

        $( '#kt_modal_create_group' ).on( 'show.bs.modal', function (event) {
            var modal = $( this );
            var validator = modal.find( '.modal-content form' ).validate();
            modal.find( '.modal-content form' ).trigger('reset');
            validator.resetForm();
            modal.find( '.modal-content form .form-control' ).removeClass('is-valid').removeClass('is-invalid');
        });
    }

    var handleGroupForm = () => {
        const groupForms = document.querySelectorAll('form.kt_group_form');

        groupForms.forEach(form => {
            const submitButton = form.querySelector('button[type="submit"]');

            var validator = $( form ).validate({
                rules: {
                    name: {
                        required: true,
                        minlength: 3
                    }
                },
                submitHandler: function(e) {
                    // Show loading indication
                    submitButton.setAttribute('data-kt-indicator', 'on');

                    // Disable button to avoid multiple click 
                    submitButton.disabled = true;

                    loadingBlockUI.block();

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
                            datatable.draw();
                            form.reset();
                            validator.resetForm();
                            $('.modal').modal('hide');
                        });
                    })
                    .catch((error) => {})
                    .then(function () {
                        // always executed
                        // Hide loading indication
                        submitButton.removeAttribute('data-kt-indicator');

                        // Enable button
                        submitButton.disabled = false;

                        if (loadingBlockUI.isBlocked()) {
                            loadingBlockUI.release();
                        }

                        KTRolesList.reDraw();
                        KTPermissionsList.reDraw();
                    });

                    return false;
                }
            });
        });
    }

    return {
        // Public functions
        init: function () {
            if (!table) {
                return;
            }

            initGroupTable();
            initToggleToolbar();
            handleSearchDatatable();
            handleDeleteRows();

            handleModals();
            handleGroupForm();
        },
        reDraw: function () {
            datatable.draw();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTGroupsList.init();
});
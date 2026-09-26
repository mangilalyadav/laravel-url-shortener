"use strict";

// Class definition
var KTRolesList = function() {
    // Shared variables
    var table = document.getElementById('kt_table_roles');
    var datatable;
    var toolbarBase;
    var toolbarSelected;
    var selectedCount;
    
    // Private functions
    // Init datatable
    var initRoleTable = () => {
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
                'emptyTable': 'No roles available'
            },
            'ajax': $(table).data('url'),
            'columns': [
                { data: 'id' },
                { data: 'name' },
                { data: 'display_name' },
                { data: 'actions' },
            ],
            'columnDefs': [
                { targets: 0, orderable: false }, // Disable ordering on column 0 (checkbox)
                { targets: 3, orderable: false, searchable:false, sClass: 'text-end' }, // Disable ordering on column 6 (actions)
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
        const filterSearch = document.querySelector('[data-kt-role-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            datatable.search(e.target.value).draw();
        });
    }

    // Delete role
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-roles-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get role name
                const roleName = parent.querySelectorAll('td')[1].innerText;

                Swal.fire({
                    text: "Are you sure you want to delete " + roleName + "?",
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

                                KTGroupsList.reDraw();
                                KTPermissionsList.reDraw();
                            });
                        })
                        .catch((error) => {})
                        .then(function () {
                            if (loadingBlockUI.isBlocked()) {
                                loadingBlockUI.release();
                            }
                        });


                        /*$.ajax({
                            url: $(d).data('url'),
                            type: 'DELETE',
                            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                            data: {},
                            success: function (data) {
                                if (loadingBlockUI.isBlocked()) {
                                    loadingBlockUI.release();
                                }
                                Swal.fire({
                                    text: data['success'],
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

                                    KTGroupsList.reDraw();
                                    KTPermissionsList.reDraw();
                                });
                            },
                            error: function (data) {
                                if (loadingBlockUI.isBlocked()) {
                                    loadingBlockUI.release();
                                }
                                Swal.fire({
                                    title: 'Something went wrong!',
                                    html: 'There is an error.',
                                    icon: "error",
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok",
                                    customClass: {
                                        confirmButton: "btn btn-primary"
                                    }
                                });
                            }
                        });*/
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
        toolbarBase = document.querySelector('[data-kt-role-table-toolbar="base"]');
        toolbarSelected = document.querySelector('[data-kt-role-table-toolbar="selected"]');
        selectedCount = document.querySelector('[data-kt-role-table-select="selected_count"]');
        const deleteSelected = document.querySelector('[data-kt-role-table-select="delete_selected"]');

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
                    text: "Are you sure you want to delete selected role(s)?",
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

                                KTGroupsList.reDraw();
                                KTPermissionsList.reDraw();
                            });
                        })
                        .catch((error) => {})
                        .then(function () {
                            if (loadingBlockUI.isBlocked()) {
                                loadingBlockUI.release();
                            }
                        });

                        // $.ajax({
                        //     type: 'DELETE',
                        //     url: $(deleteSelected).data('url'),
                        //     headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                        //     data: { 'ids': selected },
                        //     success: function (data) {
                        //         if (loadingBlockUI.isBlocked()) {
                        //             loadingBlockUI.release();
                        //         }
                        //         Swal.fire({
                        //             text: data['success'],
                        //             icon: "success",
                        //             allowOutsideClick: false,
                        //             allowEscapeKey: false,
                        //             buttonsStyling: false,
                        //             confirmButtonText: "Ok",
                        //             customClass: {
                        //                 confirmButton: "btn btn-primary"
                        //             }
                        //         }).then(function (result) {
                        //             // Remove all selected customers
                        //             checkboxes.forEach(c => {
                        //                 if (c.checked) {
                        //                     datatable.row($(c.closest('tbody tr'))).remove().draw();
                        //                 }
                        //             });

                        //             // Remove header checked box
                        //             const headerCheckbox = table.querySelectorAll('[type="checkbox"]')[0];
                        //             headerCheckbox.checked = false;
                        //         }).then(function(){
                        //             toggleToolbars(); // Detect checked checkboxes
                        //             initToggleToolbar(); // Re-init toolbar to recalculate checkboxes

                        //             KTGroupsList.reDraw();
                        //             KTPermissionsList.reDraw();
                        //         });
                        //     },
                        //     error: function (data) {
                        //         if (loadingBlockUI.isBlocked()) {
                        //             loadingBlockUI.release();
                        //         }
                        //         Swal.fire({
                        //             title: 'Something went wrong!',
                        //             text: 'There is an error.',
                        //             icon: "error",
                        //             allowOutsideClick: false,
                        //             allowEscapeKey: false,
                        //             buttonsStyling: false,
                        //             confirmButtonText: "Ok",
                        //             customClass: {
                        //                 confirmButton: "btn btn-primary"
                        //             }
                        //         });
                        //     }
                        // });
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

    return {
        // Public functions
        init: function () {
            if (!table) {
                return;
            }

            initRoleTable();
            initToggleToolbar();
            handleSearchDatatable();
            handleDeleteRows();
        },
        reDraw: function () {
            datatable.draw();
        }
    }
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTRolesList.init();
});
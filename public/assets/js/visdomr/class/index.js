"use strict";
// Class definition
var KTClassesList = function() {
    // Shared variables
    var table = document.getElementById('kt_table_class');
     //console.log(table);
    // if (!table) {
    //     console.error("Table element not found!");
    //     return;
    // }
    var datatable;
    var toolbarBase;
    var toolbarSelected;
    var selectedCount;
    var filterApplied = false;

    // Private functions
    // Init datatable
    var initUserTable = () => {
      //  console.log(table);
        datatable = $(table).DataTable({
            'searchDelay': 500,
            'processing': true,
            'serverSide': true,
            'pageLength': 10,
            'order': [[1, 'desc']],
            'select': {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            'language': {
                'emptyTable': 'No classes available'
            },
            'ajax': $(table).data('url'), 
            'columns': [
                { data: 'ulid', orderable: false },
                { data: 'name', render: function(data, type, row) {
                    return getClassWithSuffix(data);  // Add the suffix logic here
                }},
                { data: 'school.name', name: 'school.name' },
                { data: 'actions', orderable: false, searchable:false, sClass: 'text-end' },
            ],
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
        const filterSearch = document.querySelector('[data-kt-class-table-filter="search"]');
        filterSearch.addEventListener('keyup', debounce(function (e) {
           // console.log("Search input value:", e.target.value);
            datatable.search(e.target.value).draw();
        }, 300));
    }

    // Delete class
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-class-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get class name
                const className = parent.querySelectorAll('td')[1].innerText;
                // const userName = parent.querySelectorAll('td')[1].getElementsByClassName('user_name')[0].innerText;

                Swal.fire({
                    text: "Are you sure you want to delete " + className + "?",
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
        toolbarBase = document.querySelector('[data-kt-class-table-toolbar="base"]');
        toolbarSelected = document.querySelector('[data-kt-class-table-toolbar="selected"]');
        selectedCount = document.querySelector('[data-kt-class-table-select="selected_count"]');
        const deleteSelected = document.querySelector('[data-kt-class-table-select="delete_selected"]');
        // console.log(deleteSelected);

        if (!toolbarBase || !toolbarSelected || !selectedCount) {
           // console.error("Toolbar elements not found!");
            return;
        }
        
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
               // console.log(selected);
                $( table ).find( '.form-check-input:checked' ).each(function() {
                    selected.push( $( this ).val() );
                });
                Swal.fire({
                    text: "Are you sure you want to delete selected class(s)?",
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

                        var actionURL = deleteSelected.getAttribute('data-url');
                       // console.log(actionURL);

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

        // Detect checkboxes class & count
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

    


    var handleRefresh = function () {
        if( $( '#kt_refresh_classes' ).length > 0 ) {
            $( '#kt_refresh_classes' ).click( function() {
                var element = $(this);
                    element.addClass( 'spinner-border' );

                datatable.draw();
                element.removeClass( 'spinner-border' );
            });
        }
    }

    function getClassWithSuffix(classNumber) {
        let suffix = 'th';
        if (classNumber % 100 >= 11 && classNumber % 100 <= 13) {
            suffix = 'th';
        } else {
            switch (classNumber % 10) {
                case 1: suffix = 'st'; break;
                case 2: suffix = 'nd'; break;
                case 3: suffix = 'rd'; break;
            }
        }
        return classNumber + suffix;
    }

  

    return {
        // Public functions
        init: function () {
            if (!table) {
                return;
            }

            initUserTable();
            initToggleToolbar();
            handleSearchDatatable();
            handleDeleteRows();
            
            handleRefresh();

            getClassWithSuffix();




        //    filtereffect();

            var filterInput = document.querySelector("#datatable_filter_tags");
            if (filterInput !== null) {
                new Tagify(filterInput,{userInput: false});     
                  
            }
        },
        reDraw: function () {
            datatable.draw();
        }
    }
}();
KTUtil.onDOMContentLoaded(function () {
    KTClassesList.init();
})












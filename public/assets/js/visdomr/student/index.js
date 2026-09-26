"use strict";
// Class definition
var KTStudentsList = function() {
    // Shared variables
    var table = document.getElementById('kt_table_student');
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
            'order': [[3, 'desc']],
            'select': {
                style: 'multi',
                selector: 'td:first-child input[type="checkbox"]',
                className: 'row-selected'
            },
            'language': {
                'emptyTable': 'No students available'
            },
            'ajax': $(table).data('url'), 
            'columns': [
                { data: 'id', orderable: false },
                { data: 'user', name: 'user' },
                // { data: 'roles', name: 'roles.name' },
                { data: 'status' },
                { data: 'created_at' },
                { data: 'actions', orderable: false, searchable:false, sClass: 'text-end' },
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
        const filterSearch = document.querySelector('[data-kt-student-table-filter="search"]');
        filterSearch.addEventListener('keyup', debounce(function (e) {
           // console.log("Search input value:", e.target.value);
            datatable.search(e.target.value).draw();
        }, 300));
    }

    // Delete student
    var handleDeleteRows = () => {
        // Select all delete buttons
        const deleteButtons = table.querySelectorAll('[data-kt-student-table-filter="delete_row"]');

        deleteButtons.forEach(d => {
            // Delete button on click
            d.addEventListener('click', function (e) {
                e.preventDefault();

                // Select parent row
                const parent = e.target.closest('tr');

                // Get student name
                const userName = parent.querySelectorAll('td')[1].innerText;
                // const userName = parent.querySelectorAll('td')[1].getElementsByClassName('user_name')[0].innerText;

                Swal.fire({
                    text: "Are you sure you want to delete " + userName + "?",
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



    function deleteUserAndRedirect(event, element) {
        event.preventDefault(); // Prevent the default anchor click behavior
    
        // Get the delete URL from the button's data-url attribute
        const deleteUrl = element.getAttribute('data-url');
    
        // Ask for confirmation
        Swal.fire({
            text: "Are you sure you want to delete this student?",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete!",
            cancelButtonText: "No, cancel",
            customClass: {
                confirmButton: "btn fw-bold btn-danger",
                cancelButton: "btn fw-bold btn-light-primary"
            }
        }).then(function(result) {
            if (result.value) {
                // Start blocking UI (if necessary)
                loadingBlockUI.block();
    
                // Send the delete request using Axios (or use any other method you prefer)
                axios.delete(deleteUrl)
                    .then(function(response) {
                        if (loadingBlockUI.isBlocked()) {
                            loadingBlockUI.release();
                        }
    
                        Swal.fire({
                            text: response.data.message,
                            icon: "success",
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        }).then(function() {
                            // Redirect to the index page after successful deletion
                            window.location.href = "{{ route('admin.students.index') }}";
                        });
                    })
                    .catch(function(error) {
                        if (loadingBlockUI.isBlocked()) {
                            loadingBlockUI.release();
                        }
                        Swal.fire({
                            text: "There was an error deleting the student.",
                            icon: "error",
                            confirmButtonText: "Ok",
                            customClass: {
                                confirmButton: "btn btn-primary"
                            }
                        });
                    });
            }
        });
    }
    
    

    // Init toggle toolbar
    var initToggleToolbar = () => {
        // Toggle selected action toolbar
        // Select all checkboxes
        const checkboxes = table.querySelectorAll('[type="checkbox"]');

        // Select elements
        toolbarBase = document.querySelector('[data-kt-student-table-toolbar="base"]');
        toolbarSelected = document.querySelector('[data-kt-student-table-toolbar="selected"]');
        selectedCount = document.querySelector('[data-kt-student-table-select="selected_count"]');
        const deleteSelected = document.querySelector('[data-kt-student-table-select="delete_selected"]');
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
                    text: "Are you sure you want to delete selected students(s)?",
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

    var handleFilterDatatable = () => {
        // Select filter options
        const filterForm    = document.querySelector('[data-kt-student-table-filter="form"]');
        const filterButton  = filterForm.querySelector('[data-kt-student-table-filter="filter"]');
        const filterOptions = filterForm.querySelectorAll('.datatable-input');

        if( filterForm !== null ) {
            // Filter datatable on submit
            filterForm.addEventListener('submit', (e) => {
                e.preventDefault();
                var params = {};
                // Get filter values
                filterOptions.forEach((item, index) => {
                    var i = item.getAttribute('data-index');
                    var type = item.getAttribute('type');
                    if (params[i]) {
                        if( type == 'checkbox' || type == 'radio' ) {
                            if( item.checked ) {
                                params[i] += '|' + item.value;
                            }
                        } else {
                            params[i] += '|' + item.value;
                        }
                    } else {
                        if( type == 'checkbox' || type == 'radio' ) {
                            if( item.checked ) {
                                params[i] = item.value;
                            } else {
                                params[i] = null;
                            }
                        } else {
                            params[i] = item.value;
                        }
                    }
                });
                $.each(params, function(i, val) {
                    datatable.column(i).search(val ? val : '', true, false);
                });
                datatable.draw();
                // showFilterTags();

                $('.modal').modal('hide');
            });
        }
    }


    var handleResetForm = () => {
        // Select reset button
        const filterForm    = document.querySelector('[data-kt-student-table-filter="form"]');
        const resetButton   = document.querySelector('[data-kt-student-table-filter="reset"]');
        const filterOptions = filterForm.querySelectorAll('.datatable-input');

        // Reset datatable
        resetButton.addEventListener('click', function () {
            filterForm.reset();
            filterOptions.forEach((item, index) => {
                var i = item.getAttribute('data-index');
                if( item.type == 'select' ) {
                    $(item).val('').trigger('change');
                }
                datatable.column(i).search('', true, false);
            });
            datatable.draw();
           // datatable.ajax.reload();  // Reload the table without filters
            // showFilterTags();
        });
    }


    var handleStatusUpdate = () => {
        let statusButtons = document.querySelectorAll('[data-kt-student-table-filter="status_update"]');

        statusButtons.forEach(statusButton => {
            statusButton.addEventListener('click', function (e) {
                e.preventDefault();

                var selected = [];
              //  console.log(selected);
                table.querySelectorAll('.form-check-input:checked').forEach(c => {
                    selected.push( c.value );
                });

                const statusChange = this.getAttribute('data-status');
                const action = this.getAttribute('data-action');

                Swal.fire({
                    text             : "Are you sure you want to update status to '" + statusChange + "' for selected student(s)?",
                    icon             : "warning",
                    allowOutsideClick: false,
                    allowEscapeKey   : false,
                    showCancelButton : true,
                    buttonsStyling   : false,
                    confirmButtonText: "Yes, update!",
                    cancelButtonText : "No, cancel",
                    customClass      : {
                        confirmButton: "btn fw-bold btn-danger",
                        cancelButton : "btn fw-bold btn-light-primary"
                    }
                }).then(function (result) {
                    if (result.value) {
                        loadingBlockUI.block();

                        axios.put(action, {
                            'ids': selected,
                            'status': statusChange
                        })
                        .then(function (response) {
                            if (loadingBlockUI.isBlocked()) {
                                loadingBlockUI.release();
                            }
                            Swal.fire({
                                text             : response.data.message,
                                icon             : "success",
                                allowOutsideClick: false,
                                allowEscapeKey   : false,
                                buttonsStyling   : false,
                                confirmButtonText: "Ok",
                                customClass      : {
                                    confirmButton: "btn btn-primary"
                                }
                            }).then(function (result) {
                                datatable.draw();
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
        });
    }
    

    // var showFilterTags = function () {
    //     var currentFilter  = false;
    //     var currentFilters = [];
    //     $.each(datatable.context[0].aoPreSearchCols, function (i, seachCol) {
    //         if (seachCol.sSearch != '') {
    //             var dataInputs = $('.datatable-input[data-index="' + i + '"]');
    //             if (dataInputs.length > 0 ) {
    //                 var filterVars    = [];
    //                 var searchVals    = seachCol.sSearch.split('|');
    //                 var title         = datatable.column(i).header().textContent;
    //                     filterApplied = true;
    //                     currentFilter = true;
    //                 //var btnText = title + ': ' + searchVals.join(' | ');

    //                 $.each(dataInputs, function (i, dataInput) {
    //                     if (dataInput.type == 'checkbox' || dataInput.type == 'radio') {
    //                         if ($(dataInput).prop('checked') == true) {
    //                             if ($.inArray($(dataInput).val(), searchVals) >= 0) {
    //                                 filterVars.push($(dataInput).next().text());
    //                             }
    //                         }
    //                     } else if (dataInput.type == 'select' || dataInput.type == 'select-one') {
    //                         if ($.inArray($(dataInput).val(), searchVals) >= 0) {
    //                             filterVars.push($(dataInput).find(':selected').text());
    //                         }
    //                     }
    //                 });
    //                 var btnText = title + ': ' + filterVars.join(' | ');

    //                 currentFilters.push({
    //                     index  : i,
    //                     title  : title,
    //                     value  : seachCol.sSearch,
    //                     btnText: filterVars.join(' | ')
    //                 });

    //                 if ($('.filter_info button[data-index="' + i + '"]').length == 1) {
    //                     $('.filter_info button[data-index="' + i + '"] span').text(btnText);
    //                 } else {
    //                     var btnHtml = '<button class="btn btn-light-info me-2 mb-2 px-4" data-index="' + i + '"><span>' + btnText + '</span> <i class="filter_remove fa-solid fa-xmark fs-2 px-2"></button>';
    //                     $('.filter_info').append(btnHtml);
    //                 }
    //             }
    //         } else {
    //             if ($('.filter_info button[data-index="' + i + '"]').length == 1) {
    //                 $('.filter_info button[data-index="' + i + '"]').remove();
    //             }
    //         }
    //     });
    //     if (currentFilter == false) {
    //         filterApplied = false;
    //     }
    //     // if (filterApplied == true) {
    //     //     $('.filter_info').removeClass('d-none');
    //     // } else {
    //     //     $('.filter_info').addClass('d-none');
    //     // }

    //     //console.log(currentFilters);
    //     sessionStorage.setItem('ticket.currentFilters', JSON.stringify(currentFilters));
    // }

   
    var handleRefresh = function () {
        if( $( '#kt_refresh_students' ).length > 0 ) {
            $( '#kt_refresh_students' ).click( function() {
                var element = $(this);
                    element.addClass( 'spinner-border' );

                datatable.draw();
                element.removeClass( 'spinner-border' );
            });
        }
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
            
            handleFilterDatatable();
            handleResetForm();
            handleRefresh();
            handleStatusUpdate();





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
    KTStudentsList.init();
})












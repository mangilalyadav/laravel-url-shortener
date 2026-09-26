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
    var initStudentTable = () => {
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
                'emptyTable': 'No students assigned'
            },
            'ajax': $(table).data('url'), 
            'columns': [
                { data: 'id', orderable: false },
                { data: 'user', name: 'user' },
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
                    text: "Are you sure you want to unassign " + userName + "?",
                    icon: "warning",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, unassign!",
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
                    text: "Are you sure you want to unassign selected student(s)?",
                    icon: "warning",
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showCancelButton: true,
                    buttonsStyling: false,
                    confirmButtonText: "Yes, unassign!",
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

    // Format students options
    const studentOptionFormat = (item) => {
        if (!item.id) {
            return item.text;
        }

        var span = document.createElement('span');
        var template = '';

        template += '<div class="d-flex align-items-center">';
        template += '<img src="' + item.avatar + '" class="rounded-circle h-40px me-3" alt="' + item.text + '"/>';
        template += '<div class="d-flex flex-column">'
        template += '<span class="fs-4 fw-bold lh-1" data-counselor="'+ item.counselor_id + '">' + item.text + '</span>';
        template += '<span class="text-muted fs-5">' + item.email + '</span>';
        template += '</div>';
        template += '</div>';

        span.innerHTML = template;

        return $(span);
    }

    // Init unassigned students Select2
    function initStudentSelect2() {
        if ($('#kt_students_select2_rich_content').length > 0) {
            $('#kt_students_select2_rich_content').select2({
                placeholder: "Select a student",
                minimumResultsForSearch: 3,
                language: {
                    noResults: function () {
                      return "No students available for assignment";
                    }
                },
                templateSelection: studentOptionFormat,
                templateResult: studentOptionFormat,
                ajax: {
                    url     : $('.unassigned_student_list').data('url'),
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
                                return { id: item.id, text: item.name, email: item.email, avatar: item.avatar_url, counselor_id: item.counselor_id };
                            }),
                            pagination: {
                                more: data.pagination.more 
                            }
                        };
                    },
                    cache: true 
                }
            });

            $('#kt_students_select2_rich_content').on('select2:select', function (e) {
                const data = e.params.data;
        
                const $option = $(this).find(`option[value="${data.id}"]`);
        
                // Attach any custom data you want to access later
                $option.attr('data-counselor', data.counselor_id);
            });
        }

    }

    var handleAddStudent = () => {
        const addstudent = document.getElementById('kt_modal_users_search_submit');
    
        if (addstudent) {
            addstudent.addEventListener('click', function () {
                const studentId = $('#kt_students_select2_rich_content').val();

                // Show loading indication
                addstudent.setAttribute('data-kt-indicator', 'on');

                // Disable button to avoid multiple click 
                addstudent.disabled = true;
                
                if (studentId != '') {
                    const selectedOption = $('#kt_students_select2_rich_content').find(':selected');

                    const assignedCounselorId = selectedOption.data('counselor');

                    let studentData = {
                        'id': studentId,
                        'name': selectedOption.data('name'),
                        'email': selectedOption.data('email'),
                        'avatar': selectedOption.data('avatar')
                    };

                    if (assignedCounselorId && assignedCounselorId != counselorId) {
                        Swal.fire({
                            title: 'Student Already Assigned!',
                            html: `This student is already assigned to another counsellor. Do you want to override assignment?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonText: 'Yes, override',
                            cancelButtonText: 'No, cancel',
                            customClass: {
                                confirmButton: 'btn btn-danger',
                                cancelButton: 'btn btn-primary'
                            },
                            buttonsStyling: false
                        }).then((result) => {
                            if (result.isConfirmed) {
                                assignStudent(studentData);
                            }
                        });
                    } else {
                        assignStudent(studentData);
                    }
                } else {
                    Swal.fire({
                        title: 'No Student Selected!',
                        html: `Select a student for assignment`,
                        icon: 'warning',
                        showCancelButton: false,
                        confirmButtonText: 'Okay',
                        customClass: {
                            confirmButton: 'btn btn-primary'
                        },
                        buttonsStyling: false
                    }).then((result) => {
                        // Show loading indication
                        addstudent.setAttribute('data-kt-indicator', 'off');

                        // Disable button to avoid multiple click 
                        addstudent.disabled = false;
                    });
                }
            });
        }
    };

    var assignStudent = (studentData) => {
        const addstudent = document.getElementById('kt_modal_users_search_submit');
        
        axios.post(`/admin/schools/${schoolId}/users/${counselorId}/assign-student`, {
            counselor_id: counselorId,
            student_id: studentData.id,
        }).then(function (response) {
            // Show loading indication
            addstudent.setAttribute('data-kt-indicator', 'off');

            // Disable button to avoid multiple click 
            addstudent.disabled = false;

            Swal.fire({
                text: 'Assigned Successfully',
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
                    $('#kt_refresh_students').trigger('click');

                    $('#addstudent').modal('hide');

                    $('#kt_students_select2_rich_content').val(null).trigger('change');
                }
            });
        })
        .catch((error) => { })
    };
  

    return {
        // Public functions
        init: function () {
            if (!table) {
                return;
            }

            initStudentTable();
            initToggleToolbar();
            handleSearchDatatable();
            handleDeleteRows();
            handleRefresh();

            handleAddStudent();

            if ($('#kt_students_select2_rich_content').length > 0) {
                initStudentSelect2();
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












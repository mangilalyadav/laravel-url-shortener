"use strict";
// Class definition
var KTAppointmentsListing = function() {
    // Shared variables
    var table = document.getElementById('kt_table_appointment_listing');
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
                'emptyTable': 'No appointments available'
            },
            'ajax': $(table).data('url'), 
            'columns': [
                { data: 'date' },
                { data: 'counselor' },
                { data: 'start_time' },
                { data: 'end_time' },
                { data: 'created_at' },
            ]
        });

        // Re-init functions on every table re-draw
        datatable.on('draw', function () {
        });
    }

    // Search Datatable
    var handleSearchDatatable = () => {
        const filterSearch = document.querySelector('[data-kt-appointment-table-filter="search"]');
        filterSearch.addEventListener('keyup', debounce(function (e) {
           // console.log("Search input value:", e.target.value);
            datatable.search(e.target.value).draw();
        }, 300));
    }

    


    var handleRefresh = function () {
        if( $( '#kt_refresh_appointments' ).length > 0 ) {
            $( '#kt_refresh_appointments' ).click( function() {
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
            // initToggleToolbar();
            handleSearchDatatable();
            // handleDeleteRows();
            
            handleRefresh();
        },
        reDraw: function () {
            datatable.draw();
        }
    }
}();
KTUtil.onDOMContentLoaded(function () {
    KTAppointmentsListing.init();
})












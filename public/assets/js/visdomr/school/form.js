"use strict";
// Class definition
var KTSchoolForm = function () {
    // Shared variables
    const form = document.querySelector('#kt_school_form');
    const submitButton = form.querySelector('#kt_school_submit');

    var handleForm = () => {
        var validator = $(form).validate({
            rules: {
                "grade[]": {
                    required: true,
                    uniqueGrade: true
                },
                "min[]": {
                    required: true,
                    number: true,
                    minMaxPercentage: true,
                    uniqueRange: true
                },
                "max[]": {
                    required: true,
                    number: true,
                    minMaxPercentage: true,
                    uniqueRange: true
                },
                "grade_point[]": {
                    required: true
                }
            },
            messages: {
                "grade[]": {
                    uniqueGrade: "The grade should be unique for each row." 
                },
                "min[]": {
                    minMaxPercentage: "Minimum value should be less than the maximum value.",
                    uniqueRange: "The min-max range should be unique for each row."
                },
                "max[]": {
                    minMaxPercentage: "Maximum value should be greater than the minimum value.",
                    uniqueRange: "The min-max range should be unique for each row."
                }
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


    var handleStateInput = () => {
        if ($('#state_select').length > 0) {
            $('#state_select').select2({
                placeholder: 'Select States',
                ajax: {
                    url     : getStatesUrl,
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

    var initializeCountrySelect = () => {
        if ($('#country_select').length > 0) {
            $('#country_select').select2({
                placeholder: 'Select countries',
                ajax: {
                    url     : getCountryUrl, 
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
                // $("#state_select").prop("disabled", false);
            })
        } 
    }

    //Student Marks Form
    var initializeStateSelect = () => {
        if($('#state_select').length > 0){
            $('#state_select').select2({
                placeholder: 'Select states',
                ajax: {
                    url     : getStatesUrl, 
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
            // $("#state_select").prop("disabled", true);

           
        }
    }
    

    var handleAddGrade = function () {
        $( document ).on( 'click', '[data-repeater-create]', function( e ) {
            let cloneItem = $('.repeater_template').find('[data-repeater-item]').clone();
                 
            cloneItem.find("input[type='text']").val('');
            cloneItem.find("select").val(null);
            cloneItem.hide().appendTo( '[data-repeater-list="kt_add_grade_scale"]' ).slideDown(300);

            var newItem = $('[data-repeater-list="kt_add_grade_scale"] [data-repeater-item]:last-child');
            newItem.find('input, select').each(function(i, input){
                $(input).valid();
            });
        });

        $(document).on("change", ".grade_min, .grade_max", function() {
            $(this).closest(".row").find(".grade_min, .grade_max").valid();
        });

        $( document ).on( 'click', '[data-repeater-delete]', function( e ) {
            if( $( '[data-repeater-item]' ). length > 1 ) {
                var listItem = $( this ).closest( '[data-repeater-item]' ).get( 0 );
                $( listItem ).slideUp( 'normal', function() { 
                    $( this ).remove(); 
                });
            }
        });

        
        
        
    
    }
    

    
    
    return {
        // Public functions
        init: function () {
            if (!form) {
                return;
            }

            handleForm();
            handleStateInput();
            initializeCountrySelect();
            initializeStateSelect();
            handleAddGrade();

            $.validator.addMethod("minMaxPercentage", function (value, element) {
                console.log(element.value);
                let row = $(element).closest('.grade-row');
                console.log(row);
                let minValue = parseInt(row.find('.grade_min').val(), 10) || 0;
                let maxValue = parseInt(row.find('.grade_max').val(), 10) || 0;
                
                if ($(element).hasClass('grade_min')) {
                    return minValue < maxValue;
                } else if ($(element).hasClass('grade_max')) {
                    return maxValue > minValue;
                }

                return true;
            });

            $.validator.addMethod("uniqueGrade", function(value, element) {
                let allGrades = [];
                $('[name="grade[]"]').each(function() {
                    allGrades.push($(this).val());
                });
            
                let isDuplicate = allGrades.filter(grade => grade === value).length > 1;
            
                return !isDuplicate;
            }, "The grade should be unique for each row.");


            $.validator.addMethod("uniqueRange", function(value, element) {
                let row = $(element).closest('.grade-row');
                let minValue = parseInt(row.find('.grade_min').val(), 10) || 0;
                let maxValue = parseInt(row.find('.grade_max').val(), 10) || 0;
                let ranges = [];
                
                // Collect all selected ranges excluding the current row
                $('[data-repeater-item]').each(function() {
                    if ($(this).get(0) !== row.get(0)) { 
                        let currentMin = parseInt($(this).find('.grade_min').val(), 10) || 0;
                        let currentMax = parseInt($(this).find('.grade_max').val(), 10) || 0;
                        
                     
                        if (currentMin && currentMax) {
                            ranges.push({ min: currentMin, max: currentMax });
                        }
                    }
                });
                
                for (let range of ranges) {
                    
                    if (!(maxValue < range.min || minValue > range.max)) {
                        return false; 
                    }
                }
            
                return true; 
            }, "The min-max range should be unique for each row.");
            
            
            
        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTSchoolForm.init();
});
"use strict";
// Class definition
var KTStudentForm = function () {
    // Shared variables
    const form = document.querySelector('#kt_student_form');
    const submitButton = form.querySelector('#kt_student_submit');

    const inputPhone = document.querySelector('input[name="phone"]');
    var iti;
    let isProgrammaticChange = false;

    //Student Detail Form
    var handleForm = () => {
        var validator = $(form).validate({
            rules: {
                dob: {
                    required: true,
                },
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

    //Student Form Phone Input
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

    const handlePhoneInputChange = (e) => {
        if ( e.target.value ) {
            var inputHiddenPhone = document.querySelector('input[name="intl_phone"]');
            inputHiddenPhone.value = iti.getNumber();
        }
    };

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
                $("#school_select").prop("disabled", false);
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

    // Handle Save & Next button click and AJAX submission for store Country State
    var handleSaveNext = () => {
        const saveNextButton = document.getElementById('saveNextButton');

        if (saveNextButton) {
            saveNextButton.addEventListener('click', function () {
                const countryId = document.getElementById('country_select').value;
                const stateId = document.getElementById('state_select').value;
                const schoolId = document.getElementById('school_select').value;

                if (!countryId || !stateId || !schoolId) {
                    alert('Please select country, state and school.');
                    return;
                }
                //const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


                // AJAX request to store the data
                axios.post(getStoreSchoolUrl, {
                    country_id: countryId,
                    state_id: stateId,
                    school_id: schoolId
                })
                .then(function (response) {
                    Swal.fire({
                        text: 'Saved Successfully',
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

                            location.reload();
                        }
                    });
                })
                .catch((error) => { })
            });
        }
    }


     //Student Marks Form
     var initializeClassSelect = () => {
         if ($('#classDropdown').length > 0) {
            var schooldId = $('#student_grade_section').data('school');
            $('#classDropdown').select2({
                placeholder: 'Select class',
                ajax: {
                    url: getClassUrl, 
                    type: 'get',
                    dataType: 'json',
                    delay: 250,
                    data: function (params) {
                        return {
                            q: params.term,
                            page: params.page || 1,
                            school_id: schooldId
                        };
                    },
                    processResults: function (data, params) {
                        // console.log(data)
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

    //Student Marks Form
    var initializeSubjectSelect = (element) => {
        if (element.length > 0) {
            var schoolId = $('#student_grade_section').data('school');
            element.select2({
                placeholder: 'Select subject',
                ajax: {
                    url     : getSubjectsUrl, 
                    type    : 'get', 
                    dataType: 'json', 
                    delay   : 250, 
                    data    : function (params) {
                        return {
                            q: params.term, 
                            page: params.page || 1,
                            school_id: schoolId
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
        } 
    }

    //Student Marks Form
    var initializeGradeSelect = (element) => {
        if (element.length > 0) {
            var schoolId = $('#student_grade_section').data('school');
            element.select2({
                placeholder: 'Select grade',
                ajax: {
                    url     : getGradesUrl, 
                    type    : 'get', 
                    dataType: 'json', 
                    delay   : 250, 
                    data    : function (params) {
                        return {
                            q: params.term, 
                            page: params.page || 1,
                            school_id: schoolId
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        // console.log(data);
                        return {
                            results: $.map(data.data, function (item) {
                                return { text: item.grade, id: item.id };
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

    //Student Marks Form
    var initializeGradePercentageSelect = (element) => {
        if (element.length > 0) {
            var schoolId = $('#student_grade_section').data('school');
            element.select2({
                placeholder: 'Select range',
                ajax: {
                    url     : getGradesUrl, 
                    type    : 'get', 
                    dataType: 'json', 
                    delay   : 250, 
                    data    : function (params) {
                        return {
                            q: params.term, 
                            page: params.page || 1,
                            school_id: schoolId
                        };
                    },
                    processResults: function (data, params) {
                        params.page = params.page || 1;
                        // console.log(data);
                        return {
                            results: $.map(data.data, function (item) {
                                return { text: item.min + '-' + item.max, id: item.id };
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


   

    var handleAddClassButton = function () {
        $(document).on('click', '[data-repeater-class-create]', function (e) {
            $('#addClassModal').modal('show');
        }); 
    };
    
    var handleSaveClass = () => {
        document.getElementById('saveClass').addEventListener('click', function () {
            const selectedClassId = $('#classDropdown').val(); // Get the selected class ID from select2
            const selectedClassName = $('#classDropdown option:selected').text(); // Get the selected class name
        
            if (!selectedClassId) {
                alert('Please select a class.');
                return;
            }
            // Check if the class ID is already in the list of added classes
            const existingClass = $('[data-repeater-class-item]').find('input.student_class_id').filter(function() {
                return $(this).val() === selectedClassId;
            });

            if (existingClass.length > 0) {
                // If the class is already added, show an error message
                alert('Class already added');
                return;
            }

            let classIndex = $('[data-repeater-class-list]').attr('data-count');

            let cloneItem = $('.class_repeater_template').find('[data-repeater-class-item]').clone();  
            cloneItem.attr('data-index', classIndex);
            cloneItem.find('input.student_class_id').val(selectedClassId);
            cloneItem.find('span.student_class_name').text(selectedClassName);
            cloneItem.find('input.student_class_id').attr('name', 'class[' + classIndex + '][id]');
            cloneItem.hide().appendTo('[data-repeater-class-list]').slideDown(300);

            // Close the modal
            const modal = bootstrap.Modal.getInstance(document.getElementById('addClassModal'));
            modal.hide();

            $('[data-repeater-class-list]').attr('data-count', parseInt(classIndex) + 1);
        });
    }

    // Remove the Class 
    var handleDeleteClass = () => {
        $(document).on('click', '[data-repeater-class-delete]', function (e) {
            var classContainer = $(this).closest('[data-repeater-class-item]'); 
            classContainer.slideUp('normal', function () {
                $(this).remove();
            });
        });
    }

    var handleAddSubjectButton = () => {
        $(document).on('click', '[data-repeater-subject-create]', function (e) {
            e.preventDefault();
            
            const classContainer = $(this).closest('[data-repeater-class-item]');
            var newSubjectRow = $('.subject_repeater_template').find('[data-repeater-subject-item]').clone();
        
            let classIndex = classContainer.attr('data-index');
            let subjectIndex = classContainer.find('[data-repeater-subject-list]').attr('data-count');

            newSubjectRow.attr('data-index', subjectIndex);
            newSubjectRow.find('input.student_class_subject_id').attr('name', 'class[' + classIndex + '][subjects][' + subjectIndex + '][id]');
            newSubjectRow.find('select.student_class_subject').attr('name', 'class[' + classIndex + '][subjects][' + subjectIndex + '][subject_id]');
            newSubjectRow.find('select.student_class_grade').attr('name', 'class[' + classIndex + '][subjects][' + subjectIndex + '][grade]');
            newSubjectRow.find('select.student_class_percentage').attr('name', 'class[' + classIndex + '][subjects]['+ subjectIndex +'][percentage]');

            initializeSubjectSelect(newSubjectRow.find('select.student_class_subject'));
            classContainer.find('[data-repeater-subject-list]').append(newSubjectRow);

            var newItem = classContainer.find('[data-repeater-subject-list] [data-repeater-subject-item]:last-child');
            newItem.find('input, select').each(function(i, input){
                $(input).valid();
            });
            newItem.find('select.student_class_subject').rules("add", {required: true, uniqueSubject: true});
            newItem.find('select.student_class_subject').on('change', function () {
                $(this).valid();
            });
            newItem.find('select.student_class_grade').on('change', function () {
                subjectInput($(this), 'select.student_class_percentage');
                newItem.find('select.student_class_grade').valid();
                newItem.find('select.student_class_percentage').valid();
            });
            newItem.find('select.student_class_percentage').on('change', function () {
                subjectInput($(this), 'select.student_class_grade');
                newItem.find('select.student_class_grade').valid();
                newItem.find('select.student_class_percentage').valid();
            });

            classContainer.find('[data-repeater-subject-list]').attr('data-count', parseInt(subjectIndex) + 1);
        });
    }

    var subjectInput = (element, otherElement) => {
        // Prevent the loop
        if (isProgrammaticChange) return;
    
        const selectedValue = element.val();
        const dropdown = element.closest('[data-repeater-subject-item]').find(otherElement);

        if (selectedValue) { 
            isProgrammaticChange = true;
            dropdown.val(selectedValue).trigger('change');
            isProgrammaticChange = false;
        }
    }

    // Remove the Subject  Row
    var handleDeleteSubject = () => {
        $(document).on('click', '[data-repeater-subject-delete]', function (e) {
            var subjectRow = $(this).closest('[data-repeater-subject-item]');
            subjectRow.slideUp('normal', function () {
                $(this).remove();
            });
        });
    }
    
    // if ($('#kt_calculate_gpa_submit').length > 0) {
    //     document.getElementById('kt_calculate_gpa_submit').addEventListener('click', function (event) {
    //         event.preventDefault();
    //         var myModal = new bootstrap.Modal(document.getElementById('calculatedgpa'));
    //         myModal.show();
    //     });
    // }

    var handleCalculateGpaButton =  () => {
        $('#kt_calculate_gpa_submit').on('click', function() {
            let gpaButton = $(this);
            gpaButton.attr('data-kt-indicator', 'on');
            gpaButton.prop('disabled', true);
     
            axios.get(getGpaUrl, {
               
            }).then(function(response) {
                let responseData = response.data;
                let html = '';

                //Points 
                html += `<div class="row g-3 g-lg-6">`;

                html += `<div class="col-4"><div class="border border-gray-300 border-dashed rounded px-6 py-5">`;
                html += `<div class="d-flex align-items-center justify-content-between mb-4">`;
                html += `<div class="symbol symbol-50px"><span class="symbol-label"><i class="ki-outline ki-award fs-1 text-primary"></i></span></div>`;
                html += `<span class="text-gray-700 fw-bolder fs-2qx lh-1 ls-n1">${responseData.simpleGPA}</span>`;
                html += `</div>`;
                html += `<div class="m-0"><span class="text-gray-500 fw-semibold fs-6">Simple GPA</span></div>`;
                html += `</div></div>`;

                html += `<div class="col-4"><div class="border border-gray-300 border-dashed rounded px-6 py-5">`;
                html += `<div class="d-flex align-items-center justify-content-between mb-4">`;
                html += `<div class="symbol symbol-50px"><span class="symbol-label"><i class="ki-outline ki-award fs-1 text-primary"></i></span></div>`;
                html += `<span class="text-gray-700 fw-bolder fs-2qx lh-1 ls-n1">${responseData.coreGPA}</span>`;
                html += `</div>`;
                html += `<div class="m-0"><span class="text-gray-500 fw-semibold fs-6">Core GPA</span></div>`;
                html += `</div></div>`;

                html += `<div class="col-4"><div class="border border-gray-300 border-dashed rounded px-6 py-5">`;
                html += `<div class="d-flex align-items-center justify-content-between mb-4">`;
                html += `<div class="symbol symbol-50px"><span class="symbol-label"><i class="ki-outline ki-award fs-1 text-primary"></i></span></div>`;
                html += `<span class="text-gray-700 fw-bolder fs-2qx lh-1 ls-n1">${responseData.weightedGPA}</span>`;
                html += `</div>`;
                html += `<div class="m-0"><span class="text-gray-500 fw-semibold fs-6">Weighted GPA</span></div>`;
                html += `</div></div>`;

                html += `</div>`;

                html += `<div class="separator separator-dashed my-6"></div>`;

                html += `<h3 class="fw-bold text-dark fs-3 mb-4">NCAA Eligiblity</h3>`;

                html += `<div class="border border-gray-300 border-dashed rounded px-6 py-5 mb-4">`;
                html += `<div class="d-flex align-items-center justify-content-between">`;
                html += `<span class="text-gray-800 text-hover-primary fs-6 fw-bold">Division 1</span>`;
                if (responseData.divOneEligibility.status == 1) {
                    html += `<span class="badge badge-lg badge-light-success align-self-center p-4">Eligible</span>`;
                } else {
                    html += `<span class="badge badge-lg badge-light-danger align-self-center p-4">Not Eligible</span>`;
                }
                html += `</div>`;
                if (responseData.divOneEligibility.status == 0) {
                    html += `<div class="mt-4"><span class="text-gray-500 fw-semibold fs-6">${responseData.divOneEligibility.message}</span></div>`;
                }
                html += `</div>`;

                html += `<div class="border border-gray-300 border-dashed rounded px-6 py-5">`;
                html += `<div class="d-flex align-items-center justify-content-between">`;
                html += `<span class="text-gray-800 text-hover-primary fs-6 fw-bold">Division 2</span>`;
                if (responseData.divTwoEligibility.status == 1) {
                    html += `<span class="badge badge-lg badge-light-success align-self-center p-4">Eligible</span>`;
                } else {
                    html += `<span class="badge badge-lg badge-light-danger align-self-center p-4">Not Eligible</span>`;
                }
                html += `</div>`;
                if (responseData.divTwoEligibility.status == 0) {
                    html += `<div class="mt-4"><span class="text-gray-500 fw-semibold fs-6">${responseData.divTwoEligibility.message}</span></div>`;
                }
                html += `</div>`;



                $('#calculatedgpa .modal-body').html(html);


                // Update modal content with the response data
                // $('#calculatedgpa .modal-body').html(`
                //     <h5>Simple GPA: ${response.data.simpleGPA || 'N/A'}</h5>
                //     <h5>Core GPA: ${response.data.coreGPA || 'N/A'}</h5>
                //     <h5>Weighted GPA: ${response.data.weightedGPA || 'N/A'}</h5>
                //      <h5>NCAA Eligiblity:${response.data.eligibilityMessageDiv1 || 'N/A'}</h5>
                //       <h5>NCAA Eligiblity:${response.data.eligibilityMessageDiv2 || 'N/A'}</h5>
                    
                // `);

                // Show the modal with the calculated GPA values
                $('#calculatedgpa').modal('show');

                gpaButton.attr('data-kt-indicator', 'off');
                gpaButton.prop('disabled', false);
            })
            .catch(function(error) {
                // Handle error if the request fails
                // alert('Error fetching GPA data');
                // console.error(error);
            });

            //  axios.get(getCalculateGpaUrl)
            //      .then(function(response) {
            //          // Update modal content with the response data
            //          $('#calculatedgpa .modal-body').html(`
            //              <h5>Simple GPA: ${response.data.simpleGPA || 'N/A'}</h5>
            //              <h5>Core GPA: ${response.data.coreGPA || 'N/A'}</h5>
            //              <h5>Weighted GPA: ${response.data.weightedGPA || 'N/A'}</h5>
            //              <h5>Weighted Core GPA: ${response.data.weightedCoreGPA || 'N/A'}</h5>
            //          `);
     
            //          // Show the modal with the calculated GPA values
            //          $('#calculatedgpa').modal('show');
            //      })
            //      .catch(function(error) {
            //          // Handle error if the request fails
            //          alert('Error fetching GPA data');
            //          console.error(error);
            //      });
         });
    };

    
    


    return {
        // Public functions
        init: function () {
            if (!form) {
                return;
            }

            handleForm();
            handlePhoneInput();

            initializeCountrySelect();
            initializeStateSelect();
            initializeSchoolSelect();
            handleSaveNext();

            handleAddClassButton();
            handleSaveClass();
            handleDeleteClass();

            handleAddSubjectButton();
            handleDeleteSubject();

            initializeClassSelect();

            handleCalculateGpaButton();

            $('select.student_class_grade').on('change', function() {
                subjectInput($(this), 'select.student_class_percentage');
            });
            $('select.student_class_percentage').on('change', function () {
                subjectInput($(this), 'select.student_class_grade');
            });

            $('#student_grade_section [data-repeater-subject-item]').each(function () {
                initializeSubjectSelect($(this).find('select.student_class_subject'));    
            });

            // $.validator.addMethod("uniqueSubject", function(value, element) {
            //     let allSubjects = [];
            //     $('select.student_class_subject').each(function () {
            //         allSubjects.push($(this).val());
            //     });
            
            //     let isDuplicate = allSubjects.filter(subject => subject === value).length > 1;
            
            //     return !isDuplicate;
            // }, "This subject has already been selected.");
            
            $.validator.addMethod("uniqueSubject", function(value, element) {
                var parentClass = $(element).closest('[data-repeater-class-item]');  
                var classSubjectSelectors = parentClass.find('select.student_class_subject');
                
                let allSubjects = [];
                classSubjectSelectors.each(function () {
                    allSubjects.push($(this).val());
                });
            
                let isDuplicate = allSubjects.filter(subject => subject === value).length > 1;
            
                return !isDuplicate;  
            }, "This subject has already been selected.");
            

        }
    };
}();

// On document ready
KTUtil.onDOMContentLoaded(function () {
    KTStudentForm.init();
});

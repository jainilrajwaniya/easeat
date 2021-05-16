$(document).ready(function(){
//    setTimeout(function(){
//        initializeAreaDT();
//    }, 500);
    setTimeout(function(){
        initializeCityDT();
    }, 1500);
    setTimeout(function(){
        initializeCountyDT();
        
        getStates();//for ddl in city popup
        getCities();//for ddl in area popup
    }, 2000);
    
    var validator = $("#editCountyForm").validate({
        rules: {
            county_name: {
                required: true,
                minlength: 2
            }
        },
        messages: {
            county_name: {
                required: "Please provide a county or state name",
                minlength: "County or state name must be at least 2 characters long"
            }
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }

        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });
    
    //city form
    var cityValidator = $("#editCityForm").validate({
        rules: {
            city_name: {
                required: true,
                minlength: 2
            }
        },
        messages: {
            city_name: {
                required: "Please provide a city or town name",
                minlength: "City or town name must be at least 2 characters long"
            }
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }

        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });
    
    //area form
    var areaValidator = $("#editAreaForm").validate({
        rules: {
            area_name: {
                required: true,
                minlength: 2
            }
        },
        messages: {
            area_name: {
                required: "Please provide a area name",
                minlength: "Area name must be at least 2 characters long"
            }
        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `help-block` class to the error element
            error.addClass("help-block");

            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.parent("label"));
            } else {
                error.insertAfter(element);
            }

            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!element.next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-remove form-control-feedback'></span>").insertAfter(element);
            }

        },
        success: function (label, element) {
            // Add the span element, if doesn't exists, and apply the icon classes to it.
            if (!$(element).next("span")[ 0 ]) {
                $("<span class='glyphicon glyphicon-ok form-control-feedback'></span>").insertAfter($(element));
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-error").removeClass("has-success");
            $(element).next("span").addClass("glyphicon-remove").removeClass("glyphicon-ok");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).parents(".col-sm-5").addClass("has-success").removeClass("has-error");
            $(element).next("span").addClass("glyphicon-ok").removeClass("glyphicon-remove");
        }
    });
    
    $(".cancel").click(function() {
        validator.resetForm();
        cityValidator.resetForm();
        areaValidator.resetForm();
    });
    
});

/**
 * initialize data table
 * @returns {undefined}
 */
function initializeAreaDT() {
    /**state datatable**/
    $('#areaListing').DataTable({
        destroy: true,
        ajax: baseUrl+'/admin/region/ajax_get_areas',
        lengthChange: true,
        autoWidth   : false,
        columns: [
            {data : 'area'},
            {data : 'city'},
            {data : 'state'},
            {data : 'country'},
            {
                data: null,
                searchable: false,
                sortable: false,
                className: "center",
                mRender: function ( data, type, row ) {
                    return "<a href=\"javascript:void(0);\" onclick=\"openEditAreaModal("+row.id+",'"+row.area+"', "+row.city_id+", 'EDIT');\"><i class=\"fa fa-edit\" style=\"font-size:20px;\"></i></a>&nbsp;<a href=\"javascript:void(0);\" onclick=\"deleteRegion("+row.id+", 'AREA');\"><i class=\"fa fa-times-circle-o text-danger\" style=\"font-size:20px;\"></i></a>";
                }
            }
        ]      
    });
}

/**
 * initialize data table
 * @returns {undefined}
 */
function initializeCountyDT() {
    /**state datatable**/
    $('#stateListing').DataTable({
        destroy: true,
        ajax: baseUrl+'/admin/region/ajax_get_counties',
        lengthChange: true,
        autoWidth   : false,
        columns: [
            {data : 'state'},
            {data : 'country'},
            {
                data: null,
                searchable: false,
                sortable: false,
                className: "center",
                mRender: function ( data, type, row ) {
                    return "<a href=\"javascript:void(0);\" onclick=\"openEditCountyModal("+row.id+",'"+row.state+"', "+row.country_id+", 'EDIT');\"><i class=\"fa fa-edit\" style=\"font-size:20px;\"></i></a>&nbsp;<a href=\"javascript:void(0);\" onclick=\"deleteRegion("+row.id+", 'STATE');\"><i class=\"fa fa-times-circle-o text-danger\" style=\"font-size:20px;\"></i></a>";
                }
            }
        ]      
    });
}

/**
 * initialize data table
 * @returns {undefined}
 */
function initializeCityDT() {
    /**state datatable**/
    $('#cityListing').DataTable({
        destroy: true,
        ajax: baseUrl+'/admin/region/ajax_get_cities',
        lengthChange: true,
        autoWidth   : false,
        columns: [
            {data : 'city'},
            {data : 'state'},
            {data : 'country'},
            {
                data: null,
                searchable: false,
                sortable: false,
                className: "center",
                mRender: function ( data, type, row ) {
                    return "<a href=\"javascript:void(0);\" onclick=\"openEditCityModal("+row.id+",'"+row.city+"', "+row.state_id+", 'EDIT');\"><i class=\"fa fa-edit\" style=\"font-size:20px;\"></i></a>&nbsp;<a href=\"javascript:void(0);\" onclick=\"deleteRegion("+row.id+", 'CITY');\"><i class=\"fa fa-times-circle-o text-danger\" style=\"font-size:20px;\"></i></a>";
                }
            }
        ]      
    });
}

function openEditCountyModal(county_id, county_name, country_id, type) {
    $('#county_id').val(county_id);
    $('#county_name').val(county_name);
    $('#country option[value="'+country_id+'"]').prop('selected', true);
    $('#editCountyModal').modal();
    switch(type) {
        case 'EDIT':
            $('#county_modal_title').text('Edit Governate');
            $('#county_modal_save_button').text('Edit Governate');
        break;
        default:
            $('#county_modal_title').text('Add Governate');
            $('#county_modal_save_button').text('Add Governate');
        break;
    }   
}

function openEditCityModal(city_id, city_name, state_id, type) {
    $('#city_id').val(city_id);
    $('#city_name').val(city_name);
    $('#state option[value="'+state_id+'"]').prop('selected', true);
    $('#editCityModal').modal();
    switch(type) {
        case 'EDIT':
            $('#city_modal_title').text('Edit Area');
            $('#city_modal_save_button').text('Edit Area');
        break;
        default:
            $('#city_modal_title').text('Add Area');
            $('#city_modal_save_button').text('Add Area');
        break;
    }   
}

function openEditAreaModal(area_id, area_name, city_id, type) {
    $('#area_id').val(area_id);
    $('#area_name').val(area_name);
    $('#city option[value="'+city_id+'"]').prop('selected', true);
    $('#editAreaModal').modal();
    switch(type) {
        case 'EDIT':
            $('#area_modal_title').text('Edit Area');
            $('#area_modal_save_button').text('Edit Area');
        break;
        default:
            $('#area_modal_title').text('Add Area');
            $('#area_modal_save_button').text('Add Area');
        break;
    }   
}

/*
 * add / edit county
 */
function editCounty() {
    $('.spinner').show();
    if($('#editCountyForm').valid()) {
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        var data = {
            name : $('#county_name').val(),
            country : $('#country').val()
        };
        $.ajax({
            url: baseUrl+"/admin/region/ajax_update_county/"+$('#county_id').val(),
            method: "POST",
            data: data,
            dataType : 'json',
            success: function(response) {
//                console.log(response);
                $('.spinner').hide();
                if(typeof(response.status) != 'undefined' && response.status == true) {
                    toastr.success(response.message);
                    initializeCountyDT();
                    $('.cancel').click();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(response) {
                $('.spinner').hide();
                if(response.status == 422) {
                    toastr.error(response.responseJSON.meta.message);
                } else {
                    toastr.error('Something went wrong!!!');
                }
            }
        });
    } else {
        $('.spinner').hide();
    }
}
/*
* add / edit city
*/
function editCity() {
    
    $('.spinner').show();
    if($('#editCityForm').valid()) {
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        var data = {
            name : $('#city_name').val(),
            state : $('#state').val()
        };
        $.ajax({
            url: baseUrl+"/admin/region/ajax_update_city/"+$('#city_id').val(),
            method: "POST",
            data: data,
            dataType : 'json',
            success: function(response) {
//                console.log(response);
                $('.spinner').hide();
                if(typeof(response.status) != 'undefined' && response.status == true) {
                    toastr.success(response.message);
                    initializeCityDT();
                    $('.cancel').click();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(response) {
                $('.spinner').hide();
                if(response.status == 422) {
                    toastr.error(response.responseJSON.meta.message);
                } else {
                    toastr.error('Something went wrong!!!');
                }
            }
        });
    } else {
        $('.spinner').hide();
    }
}

/*
* add / edit area
*/
function editArea() {
    
    $('.spinner').show();
    if($('#editAreaForm').valid()) {
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        
        var data = {
            name : $('#area_name').val(),
            city : $('#city').val()
        };
        $.ajax({
            url: baseUrl+"/admin/region/ajax_update_area/"+$('#area_id').val(),
            method: "POST",
            data: data,
            dataType : 'json',
            success: function(response) {
//                console.log(response);
                $('.spinner').hide();
                if(typeof(response.status) != 'undefined' && response.status == true) {
                    toastr.success(response.message);
                    initializeAreaDT();
                    $('.cancel').click();
                } else {
                    toastr.error(response.message);
                }
            },
            error: function(response) {
                $('.spinner').hide();
                if(response.status == 422) {
                    toastr.error(response.responseJSON.meta.message);
                } else {
                    toastr.error('Something went wrong!!!');
                }
            }
        });
    } else {
        $('.spinner').hide();
    }
}

/**
 * delete region
 * @param {type} id
 * @param {type} type
 * @returns {undefined}
 */
function deleteRegion(id, type) {
    if(confirm('Are you sure want to delete this record?')) {
        $('.spinner').show();
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        var data = {type: type};
        $.ajax({
            url: baseUrl+"/admin/region/ajax_delete/"+id,
            method: "POST",
            dataType : 'json',
            data : data,
            success: function(response) {
                if(typeof(response.status) != 'undefined' && response.status == true) {
                    if(type == 'STATE') {
                        initializeCountyDT();
                    }
                    if(type == 'CITY') {
                        initializeCityDT();
                    }
                    if(type == 'AREA') {
                        initializeAreaDT();
                    }
                    
                    toastr.success(response.message);
                    $('.spinner').hide();
                } else {
                    $('.spinner').hide();
                    toastr.error('Something went wrong!!!');
                }
            },
            error: function(response) {
                $('.spinner').hide();
                toastr.error('Something went wrong!!!');
            }
        });
    }
}

/**
 * get cities from ajax
 * @returns {undefined}
 */
function getCities() {
    $('#city').html('');
    $('.spinner').show();
    $.ajax({
        url: baseUrl+"/admin/region/ajax_get_cities/",
        method: "GET",
        dataType : 'json',
        success: function(response) {
            if(typeof(response.data) != 'undefined') {
                $(response.data).each(function(ind, ele){
                    $('#city').append($('<option></option>').val(ele.id).html(ele.city));
                });
                $('.spinner').hide();
            } else {
                $('.spinner').hide();
            }
        },
        error: function(response) {
            $('.spinner').hide();
        }
    });
}

/**
 * gt states from ajax
 * @returns {undefined}
 */
function getStates() {
    $('#state').html('');
    $('.spinner').show();
    $.ajax({
        url: baseUrl+"/admin/region/ajax_get_counties/",
        method: "GET",
        dataType : 'json',
        success: function(response) {
            if(typeof(response.data) != 'undefined') {
                $(response.data).each(function(ind, ele){
                    $('#state').append($('<option></option>').val(ele.id).html(ele.state));
                });
                $('.spinner').hide();
            } else {
                $('.spinner').hide();
            }
        },
        error: function(response) {
            $('.spinner').hide();
        }
    });
}
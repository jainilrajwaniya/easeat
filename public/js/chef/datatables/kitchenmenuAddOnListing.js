$(document).ready(function() {
//    setTimeout(function(){
//        initializeAddonDT(); 
//    }, 500);
    $( function() {
        $("#addon_sortable").sortable({
            items: ".addon_item"
        });
        $("#addon_sortable").disableSelection();
        $("#addon_sortable").on("sortstop", function(event, ui) {
            editAddonOrder();
        });
    });
});

function initializeAddonDT() {
    $('#menuAddonListing').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        paging      : true,
        lengthChange: true,
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : false,
        ajax: baseUrl+'/chef/kitchenmenu/addon/ajax_listing/'+kitchenItemId, 
        columns: [
            {data : 'category_name_en', name: 'category_name_en'},
//            {data : 'choices', name: 'kitchen_add_on_category.choices'},
            {data : 'created_date', name: 'created_at'},
            {
                 data: null,
                 searchable: false,
                 sortable: false,
                 className: "center",
                 mRender: function ( data, type, row ) {
                     return '<a href="javascript:void(0)" onclick="deleteAddon('+row.id+')">Delete</a>&nbsp;&nbsp;<a href="'+baseUrl+'/chef/kitchenmenu/addon/show_edit_form/'+kitchenItemId+'/'+row.id+'"><i class="fa fa-edit" style="font-size:20px;"></i></a>';
                 }
             }
        ],
        "aaSorting": [[1, "desc" ]]        
    });
}

/**
 * delete item add on
 * @param {type} id
 * @returns {undefined}
 */
function deleteAddon(id) {
    if(confirm('There may be addon items associated with this category, Are you sure want to delete this Add on?')) {
        $('.spinner').show();
        $.ajaxSetup({
            headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $.ajax({
            url: baseUrl+"/chef/kitchenmenu/ajax_delete_addon_cat/"+id,
            type: "GET",
            dataType : 'json',
            success: function(response) {
                if(response.status == true) {
                    toastr.success(response.message);
                    initializeAddonDT();
                    $('.spinner').hide();
                } else {
                    $('.spinner').hide();
                    toastr.success(response.message);
                }
            },
            error: function(response) {
                $('.spinner').hide();
                toastr.success('Something went wrong!!!');
            }
        });
    }
}

/**
 * Edit addon order
 * @returns {undefined}
 */
function editAddonOrder() {
    var cat_order = '';
    var sep = '';
    $('.addon_item').each(function(){
        cat_order += sep+$(this).attr('addon_id');
        sep = '||';
    });
    
    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.ajax({
        url: baseUrl+"/chef/kitchenmenu/ajax_edit_addon_cat_order/?kitchen_item_id="+kitchenItemId+"&cat_order="+cat_order,
        method: "GET",
        dataType : 'json',
        success: function(response) {
            if(typeof(response.status) != 'undefined' && response.status == true) {
                toastr.success(response.message);
                $('.spinner').hide();
            } else {
                $('.spinner').hide();
                toastr.success('Something went wrong!!!');
            }
        },
        error: function(response) {
            $('.spinner').hide();
            toastr.success('Something went wrong!!!');
        }
    });
}

/*
 * change add on category status
 * @param {type} id
 * @returns {undefined}
 */
function changeaddonstatus(id) {
    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: baseUrl+"/chef/kitchenmenu/addon/ajax_change_addon_cat_status/"+id,
        method: "GET",
        dataType : 'json',
        success: function(response) {
            if(response.status == true) {
                if($("#status_"+id).text() == 'Active') {
                    toastr.success(response.message);
//                    $("#status_"+id).text('Inactive');
//                    $("#status_"+id).removeClass('label-success');
//                    $("#status_"+id).addClass('label-danger');
                } else {
                    toastr.success(response.message);
//                    $("#status_"+id).text('Active');
//                    $("#status_"+id).removeClass('badge label-danger');
//                    $("#status_"+id).addClass('badge label-success');
                }
                $('.spinner').hide();
            } else {
                $('.spinner').hide();
                toastr.success('Something went wrong!!!');
            }
        },
        error: function(response) {
            $('.spinner').hide();
            toastr.success('Something went wrong!!!');
        }
    });
}
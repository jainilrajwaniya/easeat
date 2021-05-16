$(document).ready(function() {
//    setTimeout(function(){
//        $('#menuListing').DataTable({
//           destroy: true,
//           processing: true,
//           serverSide: true,
//           paging      : true,
//           lengthChange: true,
//           searching   : true,
//           ordering    : true,
//           info        : true,
//           autoWidth   : false,
//           ajax: baseUrl+'/chef/kitchenmenu/ajax_listing', 
//           columns: [
//               {data : 'item_name', name: 'kitchen_items.item_name'},
//               {data : 'category_name', name: 'categories.category_name'},
//               {data : 'cuisine_type_name', name: 'cuisine_types.cuisine_type_name'},
////               {data : 'group_name', name: 'kitchen_items.group_name'},
//               {data : 'average_prep_time', name: 'kitchen_items.average_prep_time'},
//               {data : 'price', name: 'kitchen_items.price'},
//               {data : 'created_date', name: 'kitchen_items.created_at'},
//               {
//                    data: null,
//                    searchable: false,
//                    sortable: false,
//                    className: "center",
//                    mRender: function ( data, type, row ) {
//                        return '<label title="Change Status" class="switch"><input type="checkbox"'+(row.status == "Active" ? "checked" : "")+' onchange="changepromo_codestatus('+row.id+')" /><span class="slider round"></span></label>&nbsp;&nbsp;<a href="'+baseUrl+'/chef/kitchenmenu/show_edit_form/'+row.id+'"><i class="fa fa-edit" style="font-size:20px;"></i></a>';
//                    }
//                }
//           ],
//           "aaSorting": [[5, "desc" ]]        
//       });   
//    }, 500);
    
});

/*
 * change rating status
 * @param {type} id
 * @returns {undefined}
 */
function changepromo_codestatus(id) {
    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: baseUrl+"/chef/kitchenmenu/ajax_change_status/"+id,
        method: "GET",
        dataType : 'json',
        success: function(response) {
            if(response.status == true) {
                if($("#status_"+id).text() == 'Active') {
                    toastr.success(response.message);
                    $("#status_"+id).text('Inactive');
                    $("#status_"+id).removeClass('label-success');
                    $("#status_"+id).addClass('label-danger');
                } else {
                    toastr.success(response.message);
                    $("#status_"+id).text('Active');
                    $("#status_"+id).removeClass('badge label-danger');
                    $("#status_"+id).addClass('badge label-success');
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

/**
 * Edit group order
 * @returns {undefined}
 */
function editGroupOrder() {
    var group_order = '';
    var sep = '';
    $('.group_item').each(function(){
        group_order += sep+$(this).attr('group_id');
        sep = '||';
    });
    
    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.ajax({
        url: baseUrl+"/chef/group/ajax_edit_group_order/?group_order="+group_order,
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

/**
 * edit Kitchen Item order
 * @param {type} group_id
 * @returns {undefined}
 */
function editGroupItemOrder(group_id) {
    var item_order = '';
    var sep = '';
    $('#'+group_id).find('.kitchen_item').each(function(){
        item_order += sep+$(this).attr('item_id');
        sep = '||';
    });
    
    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    group_id = group_id.replace("item_sortable_", "");
    $.ajax({
        url: baseUrl+"/chef/kitchenmenu/ajax_edit_item_order/?group_id="+group_id+"&item_order="+item_order,
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
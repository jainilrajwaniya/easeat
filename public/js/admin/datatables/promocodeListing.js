$(document).ready(function() {
    setTimeout(function(){
        $('#promocodeListing').DataTable({
           destroy: true,
           processing: true,
           serverSide: true,
           paging      : true,
           lengthChange: true,
           searching   : true,
           ordering    : true,
           info        : true,
           autoWidth   : false,
           ajax: baseUrl+'/admin/promocode/ajax_listing', 
           columns: [
               {data : 'promo_code', name: 'promo_codes.promo_code'},
               {
                    data : 'image',
                    searchable: false,
                    sortable: false
                },
               {data : 'discount_percentage', name: 'promo_codes.discount_percentage'},
               {data : 'no_of_usage', name: 'promo_codes.no_of_usage'},
               {data : 'min_order_value', name: 'promo_codes.min_order_value'},
               {data : 'max_dis_amt', name: 'promo_codes.max_dis_amt'},
               {data : 'limitation', name: 'promo_codes.limitation'},
               {data : 'publish_at', name: 'promo_codes.publish_at'},
               {data : 'expire_at', name: 'promo_codes.expire_at'},
               {data : 'created_at', name: 'promo_codes.created_at'},
               {
                    data: null,
                    searchable: false,
                    sortable: false,
                    className: "center",
                    mRender: function ( data, type, row ) {
                        return '<label title="Change Status" class="switch"><input type="checkbox"'+(row.status == "Active" ? "checked" : "")+' onchange="changepromo_codestatus('+row.id+')" /><span class="slider round"></span></label>&nbsp;&nbsp;<a href="'+baseUrl+'/admin/promocode/show_edit_form/'+row.id+'"><i class="fa fa-edit" style="font-size:20px;"></i></a>';
                    }
                }
           ],
           "aaSorting": [[7, "desc" ]]
       });   
    }, 500);
    
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
        url: baseUrl+"/admin/promocode/ajax_change_status/"+id,
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
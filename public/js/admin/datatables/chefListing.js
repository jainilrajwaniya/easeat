$(document).ready(function() {
    setTimeout(function(){
        $('#chefListing').DataTable({
           destroy: true,
           processing: true,
           serverSide: true,
           paging      : true,
           lengthChange: true,
           searching   : true,
           ordering    : true,
           info        : true,
           autoWidth   : false,
           ajax: baseUrl+'/admin/chef/ajax_listing', 
           columns: [
               {data : 'name', name: 'chefs.name'},
               {data : 'email', name: 'chefs.email'},
               {
                    data : 'profile_pic',
                    searchable: false,
                    sortable: false
                },
                {data : 'role', name: 'chefs.role'},
                {
                    data : 'status',
                    mRender: function ( data, type, row ) {
                        return '<span id="status_'+row.id+'" class="badge label-'+(row.status == 'Active' ? "success" : "danger")+'">'+row.status+'</span>';
                    }
                },
               {data : 'created_date', name: 'chefs.created_at'},
               {
                    data: null,
                    searchable: false,
                    sortable: false,
                    className: "center",
                    mRender: function ( data, type, row ) {
                        return '<label title="Change Status" class="switch"><input type="checkbox"'+(row.status == "Active" ? "checked" : "")+' onchange="changeChefStatus('+row.id+')" /><span class="slider round"></span></label>&nbsp;&nbsp;<a href="'+baseUrl+'/admin/chef/show_edit_form/'+row.id+'"><i class="fa fa-edit" style="font-size:20px;"></i></a>';
                    }
                }
           ],
           "aaSorting": [[5, "desc" ]]          
       });   
    }, 500);
    
});

/*
 * change chef status
 * @param {type} id
 * @returns {undefined}
 */
function changeChefStatus(id) {
    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: baseUrl+"/admin/chef/ajax_change_status/"+id,
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
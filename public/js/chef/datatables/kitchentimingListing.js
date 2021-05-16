$(document).ready(function() {
    setTimeout(function(){
        $('#timingListing').DataTable({
           destroy: true,
           processing: true,
           serverSide: true,
           paging      : true,
           lengthChange: true,
           searching   : true,
           ordering    : true,
           info        : true,
           autoWidth   : false,
           ajax: baseUrl+'/chef/kitchentiming/ajax_listing', 
           columns: [
               {data : 'day', name: 'kitchen_timings.day'},
               {data : 'from_time1', name: 'kitchen_timings.from_time1'},
               {data : 'to_time1', name: 'kitchen_timings.to_time1'},
               {data : 'from_time2', name: 'kitchen_timings.from_time2'},
               {data : 'to_time2', name: 'kitchen_timings.to_time2'},
               {data : 'created_date', name: 'kitchen_timings.created_at'},
               {
                    data: null,
                    searchable: false,
                    sortable: false,
                    className: "center",
                    mRender: function ( data, type, row ) {
                        return '<a href="'+baseUrl+'/chef/kitchentiming/show_edit_form/'+row.id+'"><i class="fa fa-edit" style="font-size:20px;"></i></a>';
                    }
                }
           ]        
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
        url: baseUrl+"/chef/kitchentiming/ajax_change_status/"+id,
        method: "GET",
        dataType : 'json',
        success: function(response) {
            if(typeof(response.meta) != 'undefined' && typeof(response.meta.status_code) != 'undefined' && response.meta.status_code == 200) {
                if($("#status_"+id).text() == 'Active') {
                    toastr.success(response.meta.message);
                    $("#status_"+id).text('Inactive');
                    $("#status_"+id).removeClass('label-success');
                    $("#status_"+id).addClass('label-danger');
                } else {
                    toastr.success(response.meta.message);
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
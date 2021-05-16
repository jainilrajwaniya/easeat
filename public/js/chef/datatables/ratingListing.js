$(document).ready(function() {
    setTimeout(function(){
        initializeDT();   
    }, 500);    
});

/**
 * initialize data table
 * @returns {undefined}
 */
function initializeDT() {
    $('#ratingListing').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        paging      : true,
        lengthChange: true,
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : true,
        // scrollX     : true,
        order: [[ 5, "desc" ]],
//        sProcessing: "<img src='/loading.gif'>",
        ajax: baseUrl+'/chef/rating/ajax_listing', 
        columns: [
            {data : 'user_name'},
            {data : 'user_email'},
            {data : 'user_phno'},
            {
                data : 'rating',
                mRender: function ( data, type, row ) {
                    return getRatingInStars(row.rating);
                }
                 
            },
            {
                data : 'status',
                mRender: function ( data, type, row ) {
                    return '<span id="status_'+row.id+'" class="badge label-'+(row.status == 'Active' ? "success" : "danger")+'">'+row.status+'</span>';
                }
            },
            {data : 'created_at'},
            {
                data: null,
                searchable: false,
                sortable: false,
                className: "center",
                mRender: function ( data, type, row ) {
                    return '<a onclick="openRatingAndReviewModal('+row.id+')" href="javascript:void(0);"><i title="View Rating & Review" class="datatable-fa_icons fa fa-eye"></i></a>';
                }
             }
        ],
        "aaSorting": [[5, "desc" ]]        
    });
}

/**
 * get rating data and show in popup
 * @param {type} id
 * @returns {undefined}
 */
function openRatingAndReviewModal(id) {
    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: baseUrl+"/chef/rating/ajax_get_rating_data/"+id,
        method: "GET",
        dataType : 'json',
        success: function(response) {
            if(typeof(response.meta) != 'undefined' && typeof(response.meta.status_code) != 'undefined' && response.meta.status_code == 200) {
                $('#reviewHeading').html('<b>'+response.data.heading+'</b>');
                $('#reviewdes').text(response.data.description);
                $('#ratingAndReviewModal').modal();
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


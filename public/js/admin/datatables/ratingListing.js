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
        scrollX     : true,
        order: [[ 8, "desc" ]],
//        sProcessing: "<img src='/loading.gif'>",
        ajax: baseUrl+'/admin/rating/ajax_listing', 
        columns: [
            {data : 'user_name'},
            {data : 'user_email'},
            {data : 'user_phno'},
            {data : 'chef_name'},
            {data : 'chef_email'},
            {data : 'chef_phno'},
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
                    return '<label title="Change Status" class="switch"><input type="checkbox"'+(row.status == "Active" ? "checked" : "")+' onchange="changeRatingStatus('+row.id+')" /><span class="slider round"></span></label>&nbsp;&nbsp;<a onclick="openRatingAndReviewModal('+row.id+')" href="javascript:void(0);"><i title="View Rating & Review" class="datatable-fa_icons fa fa-eye"></i></a>';
                }
             }
        ],
        "aaSorting": [[5, "desc" ]]         
    });
}

/*
 * change rating status
 * @param {type} id
 * @returns {undefined}
 */
function changeRatingStatus(id) {
    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $.ajax({
        url: baseUrl+"/admin/rating/ajax_change_status/"+id,
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
        url: baseUrl+"/admin/rating/ajax_get_rating_data/"+id,
        method: "GET",
        dataType : 'json',
        success: function(response) {
            if(response.status == true) {
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


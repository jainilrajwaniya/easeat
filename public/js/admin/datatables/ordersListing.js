$(document).ready(function() {
    setTimeout(function(){
        $('#orderListing').DataTable({
           destroy: true,
           processing: true,
           serverSide: true,
           paging      : true,
           lengthChange: true,
           searching   : true,
           ordering    : true,
           info        : true,
           autoWidth   : false,
           ajax: baseUrl+'/admin/orders/ajax_listing', 
           columns: [
               {data : 'id'},
               {data : 'delivery_type'},
               {
                data : 'status',
                mRender: function ( data, type, row ) {
                var status = {
                                'Pending': {'title': 'Pending', 'class': 'label-light'},
                                'Placed': {'title': 'Placed', 'class': 'label-primary'},
                                'Cooking': {'title': 'Cooking', 'class': 'label-info'},
                                'Ready': {'title': 'Ready', 'class': 'label-warning'},
                                'OnTheWay': {'title': 'OnTheWay', 'class': 'label-default'},
                                'Completed': {'title': 'Complete', 'class': 'label-success'},
                                'PaymentIssue': {'title': 'Payment Issue', 'class': 'label-danger'},
                            };
                 return '<span id="status_'+row.id+'" class="label '+status[row.status].class+'">'+row.status+'</span>';
             }
            },
            {data : 'chef_id'},
            {data : 'created_date', name : 'created_at'},
            {
             data: null,
             searchable: false,
             sortable: false,
             className: "center",
             mRender: function ( data, type, row ) {
                 return '<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span></button>\n\
                        <ul class="dropdown-menu" role="menu">\n\
                        <li><a target="_blank" href="'+baseUrl+'/admin/orders/detail/'+row.id+'">Detail</a></li></ul></div>';
             }
            }
           ],
           "aaSorting": [[4, "desc" ]]         
       });   
    }, 500);
    
});
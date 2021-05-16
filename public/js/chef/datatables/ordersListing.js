$(document).ready(function() {
    setTimeout(function(){
        initializeDT();   
    }, 500);
});

function initializeDT() {
    $('#ordersListing').DataTable({
        destroy: true,
        processing: true,
        serverSide: true,
        paging      : true,
        lengthChange: true,
        searching   : true,
        ordering    : true,
        info        : true,
        autoWidth   : false,
        ajax: baseUrl+'/chef/orders/ajax_listing', 
        columns: [
            {data : 'id'},
            {data : 'delivery_type'},
            {
                data : 'status',
                mRender: function ( data, type, row ) {
                var status = {
                                'Pending': {'title': 'Pending', 'class': 'label-info'},
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
            {data : 'created_date', name : 'created_at'},
            {
             data: null,
             searchable: false,
             sortable: false,
             className: "center",
             mRender: function ( data, type, row ) {
                 return '<div class="btn-group"><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">Actions <span class="caret"></span></button>\n\
                        <ul class="dropdown-menu" role="menu">\n\
                         <li style="'+(row.status == "Completed" ? "display:none;" : "")+'"><a href="javascript:void(0);" onclick="openOrderMenuModal('+row.id+')">Change status</a></li>\n\
                         <li><a target="_blank" href="'+baseUrl+'/chef/orders/detail/'+row.id+'">Detail</a></li></ul></div>';
                        
//                        <li><a style="'+(row.status == 'Placed' ? '' : 'display:none;')+'" href="javascript:void(0);" onclick="changeOrderStatus('+row.id+',\'Cooking\')">Accept</a></li> \n\
//                         <li><a style="'+(row.status == 'Cooking' ? '' : 'display:none;')+'" href="javascript:void(0);" onclick="changeOrderStatus('+row.id+',\'Ready\')">Ready</a></li> \n\
//                         <li><a style="'+(row.status == 'Ready' ? '' : 'display:none;')+'" href="javascript:void(0);" onclick="changeOrderStatus('+row.id+',\'OnTheWay\')">OnTheWay</a></li> \n\
//                         <li><a style="'+(row.status == 'OnTheWay' ? '' : 'display:none;')+'" href="javascript:void(0);" onclick="changeOrderStatus('+row.id+',\'Completed\')">Completed</a></li> \n\
             }
          }
        ],
        "aaSorting": [[3, "desc" ]]         
    });   
}

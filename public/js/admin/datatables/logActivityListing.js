$(document).ready(function() {
    console.log('aaaaa');
    setTimeout(function(){
        $('#logActivityListing').DataTable({
           destroy: true,
           processing: true,
           serverSide: true,
           paging      : true,
           lengthChange: true,
           searching   : true,
           ordering    : true,
           info        : true,
           autoWidth   : false,
           ajax: baseUrl+'/admin/log-activity/ajax_listing', 
           columns: [
               {data : 'name', name: 'admins.name'},
               {data : 'action', name: 'log_activities.action'},
               {data : 'type', name: 'log_activities.type'},
               {data : 'ip_address', name: 'log_activities.ip_address'},
               {data : 'created_date', name: 'log_activities.created_at'}
           ],
           "aaSorting": [[4, "desc" ]]         
       });   
    }, 500);
    
});
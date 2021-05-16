$(document).ready(function() {
    setTimeout(function(){
        $('#userListing').DataTable({
           destroy: true,
           processing: true,
           serverSide: true,
           paging      : true,
           lengthChange: true,
           searching   : true,
           ordering    : true,
           info        : true,
           autoWidth   : false,
           ajax: baseUrl+'/admin/user/ajax_listing', 
           columns: [
               {data : 'name'},
               {data : 'email'},
               {data : 'phone_number'},
               {data : 'status'},
               {data : 'created_at'}
//               {
//                    data: null,
//                    searchable: false,
//                    sortable: false,
//                    className: "center",
//                    mRender: function ( data, type, row ) {
//                        return '<a href="#"><i class="fa fa-edit" style="font-size:12px;color:blue"></i></a>';}
//                }
           ]        
       });   
    }, 500);
    
});


 
   // $('#example1').DataTable()
    
 
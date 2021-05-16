$(document).ready(function() {
    setTimeout(function(){
        $('#adminListing').DataTable({
            destroy: true,
            ajax: baseUrl+'/admin/subadmin/ajax_listing',
            columns: [
                {data : 'name'},
                {data : 'email'},
                {data : 'role'},
                {
                    data : 'profile_pic',
                    searchable: false,
                    sortable: false
                },
    //            {'data' : 'status'},
                {data : 'created_at'},
                {
                    data: null,
                    searchable: false,
                    sortable: false,
                    className: "center",
                    mRender: function ( data, type, row ) {
                        return '<a href="'+baseUrl+'/admin/subadmin/show_edit_form/'+row.id+'"><i class="fa fa-edit" style="font-size:20px;"></i></a>';}
                }
            ]        
        });
    }, 500);
});

 
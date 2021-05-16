$(document).ready(function() {
    setTimeout(function() {
        initializeGroupDT();
    }, 500);

});

function initializeGroupDT() {
    $('#groupListing').DataTable({
        destroy: true,
        ajax: baseUrl+'/chef/group/ajax_listing',
        order: [[ 2, "asc" ]],
        columns: [
            {data:'group_name'},
            {
                data : 'status',
                mRender: function ( data, type, row ) {
                    return '<span id="status_'+row.id+'" class="badge label-'+(row.status == 'Active' ? "success" : "danger")+'">'+row.status+'</span>';
                }
            },
            {data : 'seq_no'},
            {
                data: null,
                searchable: false,
                sortable: false,
                className: "center",
                mRender: function ( data, type, row ) {
                    return '<a onclick="openEditModel(\''+row.group_name+'\',\''+row.id+'\',\''+row.seq_no+'\')" href="javascript:void(0);"><i title="Edit" class="datatable-fa_icons fa fa-edit"></i></a>';
                }
            }
        ]        
    });
}

function openEditModel(name, id, seq_no) {
    $('#group_id').val(id);
    $('#group_name').val(name);
    $('#seq_no').val(seq_no);
    $('#addEditGroupStatus').modal();
}

function editGroup() {
    var group_name = $('#group_name').val();
    if(group_name.trim() == "") {
        toastr.error('Group name is mandatory');
        return;
    }
    
    var seq_no = $('#seq_no').val();
    if(seq_no.trim() == "" || seq_no < 0) {
        toastr.error('Sequence number must be numeric');
        return;
    }

    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    
    $.ajax({
        url: baseUrl+"/chef/group/ajax_edit_group/?id="+$('#group_id').val()+"&group_name="+group_name+"&seq_no="+seq_no,
        method: "GET",
        dataType : 'json',
        success: function(response) {
            if(typeof(response.status) != 'undefined' && response.status == true) {
                toastr.success(response.message);
                initializeGroupDT();
                $('#addEditGroupStatus').modal('hide');
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
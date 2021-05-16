$(document).ready(function() {
    setTimeout(function() {
        $('#cuisineListing').DataTable({
            destroy: true,
            columns: [
                {
                    searchable: true,
                    sortable: true,
                },
                {
                    searchable: true,
                    sortable: true,
                },
                {
                    searchable: true,
                    sortable: true,
                },
                {
                    searchable: false,
                    sortable: false,
                }
            ]        
        });
    }, 500);

});
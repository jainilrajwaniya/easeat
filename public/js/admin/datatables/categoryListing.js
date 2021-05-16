$(document).ready(function() {
    setTimeout(function() {
        $('#categoryListing').DataTable({
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
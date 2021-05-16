$(document).ready(function() {
    setTimeout(function(){
        $('#walletListing').DataTable({
           destroy: true,
           processing: true,
           serverSide: true,
           paging      : true,
           lengthChange: true,
           searching   : true,
           ordering    : true,
           info        : true,
           autoWidth   : false,
           ajax: baseUrl+'/admin/wallet/ajax_listing', 
           columns: [
               {data : 'id', name: 'wallet_transactions.id'},
               {data : 'order_id', name: 'wallet_transactions.order_id'},
               {data : 'user_id', name: 'wallet_transactions.user_id'},
               {data : 'guest_user_id', name: 'wallet_transactions.guest_user_id'},
               {data : 'amount', name: 'wallet_transactions.amount'},
               {data : 'description', name: 'wallet_transactions.description'},
               {data : 'transaction_type', name: 'wallet_transactions.transaction_type'},
               {data : 'type', name: 'wallet_transactions.type'},
               {data : 'created_date', name: 'wallet_transactions.created_at'}
           ],
           "aaSorting": [[ 7, "desc" ]]        
       });   
    }, 500);
    
});
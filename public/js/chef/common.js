
/**
 * open order modal with items
 * @param {type} order_id
 * @returns {undefined}
 */
function openOrderMenuModal(order_id) {
    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var data = {id : order_id};
    $.ajax({
        url: baseUrl+"/chef/orders/ajax_get_order_detail",
        type: "POST",
        dataType : 'json',
        data: data,
        
        success: function(response) {
            if(response.status == true) {
                $('#orderMenuTitle').text('Menu items of order id : '+order_id);
                var docResTableBodyHTML = '';
                var orderStatus = '';
                $(response.data).each(function (ind, ele) {
                    var add_ons = '';
                    var comma = '';
                    $(ele.add_ons).each(function (add_ons_ind, add_ons_ele) {
                        add_ons += comma+add_ons_ele.add_on_name;
                        comma = ", "
                    });
                    docResTableBodyHTML += "<tr>";
                    docResTableBodyHTML += "<td>" + (ind + 1) + "</td>";
                    docResTableBodyHTML += "<td>" + ele.item_name + "</td>";
                    docResTableBodyHTML += "<td>" + add_ons + "</td>";
                    docResTableBodyHTML += "<td>" + ele.quantity + "</td>";
                    docResTableBodyHTML += "</tr>";
                    
                    orderStatus = ele.status;//set current order status
                });
                //set menu item table
                $('#orderMenuBody').html(docResTableBodyHTML);
                //set status button
                var changeStatusTo = '';
                var btnTitle = '';
                switch(orderStatus) {
                    case 'Placed':
                        changeStatusTo = 'Cooking';
                        btnTitle = 'Accept Order';
                    break;
                    case 'Cooking':
                        changeStatusTo = 'Ready';
                        btnTitle = 'Mark As Ready';
                    break;
                    case 'Ready':
                        changeStatusTo = 'OnTheWay';
                        btnTitle = 'Mark As On The Way';
                    break;
                    case 'OnTheWay':
                        changeStatusTo = 'Completed';
                        btnTitle = 'Mark As Completed';
                    break;
                    default:
                        changeStatusTo = '';
                    break;
                }

                if(changeStatusTo != '') {
                    $('#orderMenuFooter').html('<button onclick="changeOrderStatus('+order_id+',\''+changeStatusTo+'\')" class="btn btn-warning" type="button">'+btnTitle+'</button>');
                } else {
                    $('#orderMenuFooter').html('');
                }
                
                $('.spinner').hide();
                $('#openOrderMenuModal').modal();
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
 * update order status
 * @param {type} id
 * @returns {undefined}
 */
function changeOrderStatus(id, status) {
    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var data = {
        id : id,
        status : status
    };
    $.ajax({
        url: baseUrl+"/chef/orders/ajax_update_order_status",
        type: "POST",
        dataType : 'json',
        data: data,
        
        success: function(response) {
            if(response.status == true) {
                toastr.success(response.message);
                $('#openOrderMenuModal').modal('hide');
                $('.spinner').hide();
                initializeDT();
                getChefPlacedOrders();
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
 * Mark Kitchen as open/close
 * @param {type} id
 * @returns {undefined}
 */
function markKitchenAsOpenClose(id, status) {
    $('.spinner').show();
    $.ajaxSetup({
        headers: {
          'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var status = 0;
    if($('#kitchenStatus').is(":checked")) {
        status = 1;
    }
    var data = {
        id : id,
        status : status
    };
    $.ajax({
        url: baseUrl+"/chef/settings/ajax_mark_kitchen_open_close",
        type: "POST",
        dataType : 'json',
        data: data,
        success: function(response) {
            if(response.status == true) {
                toastr.success(response.message);
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
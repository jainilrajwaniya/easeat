// Initialize Firebase
//firebase.initializeApp(firebaseConfig);
// Get a reference to the database service
//var database = firebase.database();
// Get 
//var orders = firebase.database().ref('/orders/chefs/'+LOGGED_IN_USER_ID+'/');
//orders.on('value', function(snapshot) {
////  console.log(snapshot.val());
//}); 

//gt new orders and show notifications to logged in chef
//var NewOrders = 0;
//var newOrderHtml = '';

//orders.on('child_added', function(data) {
//    if(NewOrders > 0) {
////        console.log(data);
////        console.log(data.key);
////        console.log(data.val().status);
////        console.log(data.val().payment_status);
////        console.log(data.val());
//        var newOrderCnt = $('#newOrderNotificationCount').html();
//        newOrderCnt = parseInt(newOrderCnt) + 1;
//        $('#newOrderNotificationCount').html(newOrderCnt);//set count
//        
//        //set notifications headings
//        $('#newOrderNotificationHeading').html("You have "+newOrderCnt+" new orders");
//        
//        //set order rows in notifications section in header
//        newOrderHtml += "<li>";
//        newOrderHtml += "<a href='javascript:void(0);' onclick='openOrderMenuModal("+data.key+");'>";
//        newOrderHtml += "<i class='fa fa-cutlery text-aqua'></i>New order arrived : "+data.key+"</a>";
//        newOrderHtml += "</li>";
//        newOrderHtml += "</a>";
//        newOrderHtml += "</li>";
//        $('#newOrderNotificationListing').html(newOrderHtml);
//        playSound(); // play notfication sound
//        toastr.success('New orders arrived');
//    }
//    
//});
//setTimeout(function(){ NewOrders++; }, 3000);``
//
//function log() {
//    firebase.database().ref('/orders/chefs/'+LOGGED_IN_USER_ID+'/').once('value').then(function(snapshot) {
//        console.log(snapshot.val());
//    });
//}
//
//
//
//function writeUserData(orderId, chefId, kitchenId, status) {
//  firebase.database().ref('orders/' + orderId).set({
//    kitchen_id: kitchenId,
//    chef_id : chefId,
//    status : status
//  });
//}
////
//function updateOrderStatus(orderId, status) {
//    var postData = {'status' : status, 'chef_id': 5};
//    
//    var updates = {};
//    updates['/orders/' + orderId + '/' ] = postData;
//
//    return firebase.database().ref().update(updates);
//}
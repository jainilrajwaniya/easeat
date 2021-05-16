//if ('serviceWorker' in navigator) {
//    window.addEventListener('load', function() {
//        navigator.serviceWorker.register('firebase-messaging-sw.js').then(function(registration) {
//            // Registration was successful
//            //console.log('ServiceWorker registration successful with scope: ', registration.scope);
//        }, function(err) {
//            // registration failed :(
//            //console.log('ServiceWorker registration failed: ', err);
//        });
//    });
//}
/* FCM code*/
//const messaging = firebase.messaging();
////
//messaging.requestPermission()
//    .then(function() {
//        console.log('Notification permission granted.');
//        return messaging.getToken();
//    })
//    .then(function(token) {
//        console.log(token);
//////        //localStorage.setItem("token", token);
//////        //sendTokenToServer(token);
//////        // alert("userID: " + userid);
//////        // alert("token: " + token);
//    })
//    .catch(function(err) {
//        console.log('Unable to get permission to notify.', err);
//    });
/* FCM code ENDS*/

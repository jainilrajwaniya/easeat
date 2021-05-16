importScripts('https://www.gstatic.com/firebasejs/5.8.6/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/5.8.6/firebase-messaging.js');

var config = {
    apiKey: "AIzaSyDKZ2RpYbkQX4Z_39KOHt3hz4p5qeJd98s",
    authDomain: "practice-ce1c6.firebaseapp.com",
    databaseURL: "https://practice-ce1c6.firebaseio.com",
    projectId: "practice-ce1c6",
    storageBucket: "practice-ce1c6.appspot.com",
    messagingSenderId: "209338215925"
  };
firebase.initializeApp(config);

const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    const notificationTitle = 'Background Message Title';
    const notificationOptions = {
        //body: payload.data.status,
        body: 'Background Message body.',
        icon: '/firebase-logo.png'
    };

    return self.registration.showNotification(notificationTitle, notificationOptions);
});
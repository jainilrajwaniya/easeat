if (APP_ENV == 'local') {
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyDZVaO8Ye1-Dq3SSUSTFNTdKzo82yc1jK4",
        authDomain: "homekitchen-119ba.firebaseapp.com",
        databaseURL: "https://homekitchen-119ba.firebaseio.com",
        projectId: "homekitchen-119ba",
        storageBucket: "homekitchen-119ba.appspot.com",
        messagingSenderId: "970803956067",
        appId: "1:970803956067:web:6919525a6afbdb3b"
    };
}

if (APP_ENV == 'development') {
    // Your web app's Firebase configuration
    var firebaseConfig = {
        apiKey: "AIzaSyA0iWFOhgByfKHhiIFFCAKA206AhwRsxYU",
        authDomain: "easeat-test.firebaseapp.com",
        databaseURL: "https://easeat-test.firebaseio.com",
        projectId: "easeat-test",
        storageBucket: "",
        messagingSenderId: "605065351760",
        appId: "1:605065351760:web:70fe5b92a9da4312"
    };
}

if (APP_ENV == 'production') {
    // Your web app's Firebase configuration
    var firebaseConfig = {};
}
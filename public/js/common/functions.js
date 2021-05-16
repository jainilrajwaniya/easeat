/**
 * fill rating stars
 * @param {type} rating
 * @returns {String}
 */
function getRatingInStars(rating) {
    var ratHtml = "<div class='rating'>";
    rating = Math.floor(rating);
    
    for(var i = 0; i < rating ; i++) {
        ratHtml += "<i class='fa fa-star checked'></i>";
    }
    for(var j = i; j < 5 ; j++) {
        ratHtml += "<i class='fa fa-star-o'></i>";
    }
    
    ratHtml += "</div>";
    return ratHtml;
}

/**
 * for notification audio
 * @returns {undefined}
 */
function playSound() {
    var sound = document.getElementById("audio");
    sound.play();
}

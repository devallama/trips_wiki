// Array of ratingstar elements
var elements = document.getElementsByClassName('ratingstar');
// Loop to addEventlistener to each ratingstar element, with the even being mouseleave
for(var j = 0; j < elements.length; j++){
    elements[j].addEventListener("mouseleave", resetStars);
}

// Reset stars restores the rating stars back to normal after a user has hovered over them
function resetStars() {
    console.log("called");
    for(var i = 0; i < elements.length; i++) {
        elements[i].src = './resources/imgs/star.png';
    }
}

// When a user hovers of one of the ratingstars, it, along with all of the stars to the left, will change colour.
function rateHover(id) {
    for(var i = 1; i <= id; i++) {
        document.getElementById("ratingstar_" + i).src = './resources/imgs/star_selected.png';
    }
}

// Ajax function to rate a place, parameters being the placeID and the rating the user gave the place
function ratePlace(placeid, rating) {
    var xhr2 = new XMLHttpRequest();
    // Add get data to url for AJAX
    var url = './php/process_rating.php?placeid=' + placeid + '&rating=' + rating;

    xhr2.addEventListener('load', ratingResponse);

    // Send ajax
    xhr2.open('GET', url, true)
    xhr2.send();
}

// Rating response from AJAX
function ratingResponse(e) {
    // decode json response from php script
    var data = JSON.parse(e.target.responseText);
    // check if AJAX request was sucessfull
    if(data['status'] == 1) {
        // variable to store html that will change the place_rating element
        var html = '';
        // variable to store html that will change the user_rating element
        var html2 = '';
        for(var i = 1; i <= 10; i++) {
            var src = './resources/imgs/star.png';
            if(i <= Math.round(data['avg_rating'])) {
                src = './resources/imgs/star_filled.png';
            }
            html += '<img src="' + src + '" class="ratingstar" alt="Rating star" />';
        }
        for(var i = 1; i <= 10; i++) {
            var src = './resources/imgs/star.png';
            if(i <= Math.round(data['user_rating'])) {
                src = './resources/imgs/star_selected.png';
            }
            html2 += '<img src="' + src + '" class="ratingstar" alt="Rating star" />';
        }
        // Insert the html into the elements
        document.getElementById("place_rating").innerHTML = html;
        document.getElementById("user_rating").innerHTML = html2;
        // Add the users rating to the page
        document.getElementById("user_rating_info").innerHTML = 'Your rating: ' + data['user_rating'];
        // Update the average rating
        document.getElementById("average_rating").innerHTML = data['avg_rating'];
        // Update the number of raters
        document.getElementById("num_raters").innerHTML = data['num_raters'];
    }
}

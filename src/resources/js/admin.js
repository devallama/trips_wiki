var fadeoutTimer;

// Ajax function to create review
function processReview(id, type) {
    var xhr2 = new XMLHttpRequest();
    // url of php script
    var url = './php/process_review.php';
    // Post data
    var params = 'id=' + id + '&type=' + type;

    xhr2.addEventListener('load', response);

    xhr2.open('POST', url, true)
    xhr2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr2.send(params);
}

// Response from AJAX
function response(e) {
    // decode the json response
    var data = JSON.parse(e.target.responseText);
    // Get the response message
    document.getElementById('response').innerHTML = data['msg'];
    // call timer to fade out message after 3 seconds
    fadeMessage();
    // If sucessfully, hide the now processes review.
    if(data['status'] == 1) {
        document.getElementById('review_' + data['reviewID']).style.display = 'none';
    }
}

// Hides the response message after 3 seconds.
function fadeMessage() {
    clearTimeout(fadeoutTimer);
    fadeoutTimer = setTimeout(function() { document.getElementById('response').innerHTML = '';}, 3000);
}

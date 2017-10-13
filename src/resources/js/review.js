// Event to call ajaxRequest on form submit
document.getElementById('review_form').onsubmit = function(e) {
    // Prevent the default action of form submit
    e.preventDefault();
    ajaxRequest();
}

// Ajax request to enter a review
function ajaxRequest() {
    var xhr2 = new XMLHttpRequest();
    // review the user has entered
    var review = document.getElementById('form_review').value;
    // the placeid the review is for
    var placeid = document.getElementById('form_placeid').value;

    // URL of the php script
    var url = './php/process_createreview.php';
    // Post data for the request
    var params = 'review=' + review + '&placeid=' + placeid;

    xhr2.addEventListener('load', response);

    // Sends the AJAx request
    xhr2.open('POST', url, true)
    xhr2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

    xhr2.send(params);
}

// Response from the ajaxrequest
function response(e) {
    // decode json response from php script
    var data = JSON.parse(e.target.responseText);
    // if successful then
    if(data['status'] == 1) {
        // Update review_reponse element to show sucess message
        document.getElementById('review_response').innerHTML = data['msg'];
        // Hide any error message from previous
        document.getElementsByClassName('error')[0].style.display = "none";
        // Hide the review form
        document.getElementById('review_form').style.display = "none";

    } else if(data['status'] == 0) {
        // If fails then show error message in element
        document.getElementsByClassName('error')[0].innerHTML = data['msg'];
        document.getElementsByClassName('error')[0].style.display = "block";
    } else if(data['status'] == 2) {
        // if fails with status 2, show the error message in the error element
        document.getElementsByClassName('error')[0].innerHTML = data['data']['failed_fields'][0][1];
        // add the users previously submitted review back into the textarea element so they do not lose their work
        document.getElementById('form_review').value = data['data']['data']['review']['data'];
        document.getElementsByClassName('error')[0].style.display = "block";
    }
}

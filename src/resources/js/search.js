// Event to call ajax request on form submit
document.getElementById('search_form').onsubmit = function(e) {
    // Prevent default form submit action
    e.preventDefault();
    ajaxRequest();
}

// Ajax request to search places by name, type, region and country
function ajaxRequest() {
    var xhr2 = new XMLHttpRequest();
    // Users submitted search term
    var search_term = document.getElementById('search_field').value;

    xhr2.addEventListener('load', response);

    // Submit AjaxRequest
    xhr2.open('GET', './php/searchAjax.php?search_term=' + search_term, true)
    xhr2.send();
}

// Response from AjaxRequest
function response(e) {
    // Decode json response
    var data = JSON.parse(e.target.responseText);
    // if success
    if(data['status'] == 1){
        // variable to hold the the results of the search in a html format
        var results = '';
        // loop through the results
        for(var i = 0; i < data['data'].length; i++) {
            var r = data['data'][i];
            results += '<a target="_blank" class="search_result" href="./place.php?id=' + r['ID'] + '">\
                <div class="info">\
                    <div class="left">\
                        <div class="name">' + r['name'] + '</div>\
                        <div class="location">' + r['region'] + ', ' + r['country'] + '</div>\
                    </div>\
                    <div class="right">\
                        <div class="type">Type: <span class="capitalize">' + r['type'] + '</span></div>\
                        <div class="rating">Average Rating: ' + r['avg_rating'] + '</div>\
                    </div>\
                </div>\
            </a>';
        }
        // Once loop has completed set results element equals to the results variable
        document.getElementById('results').innerHTML = results;
    } else {
        // if fails or no search results, show error messsage
        document.getElementById('results').innerHTML = data['msg'];
    }

}

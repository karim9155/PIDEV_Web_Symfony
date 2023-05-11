// public/js/search.js

$(document).ready(function() {
    $('#search-form').submit(function(e) {
        e.preventDefault();

        var query = $('#search-input').val();

        $.ajax({
            url: '/search',
            type: 'GET',
            data: { q: query },
            success: function(data) {
                $('#search-results').html(data);
            }
        });
    });
});

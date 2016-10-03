$(document).ready(function() {
    var currentDate = '';
    $('.mod_eventlist').each(function(index) {
        $(this).find('.event').each(function(index) {
            var dateElement = $(this).find('time');
            var dateText = dateElement.find('.time_day').text();
            if (dateText != currentDate) {
                currentDate = dateText;
                var header = $("<h2></h2>").text(dateText);
                if ($(this).hasClass('bygone')) {
                    header.addClass('bygone');
                }
                $(this).before(header);
            }
            dateElement.find('.time_day').remove();
        });
    });
});
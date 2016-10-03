$(document).ready(function() {
    $('.mod_eventlist').each(function() {
        var currentDate = '';
        $(this).find('.event').each(function() {
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
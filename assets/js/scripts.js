jQuery(document).ready(function($) {
    setInterval(function() {
        var d = new Date(),
            h = d.getHours(),
            m = d.getMinutes(),
            s = d.getSeconds();

        var $ele = $('.hijri-calendar'),
            morning = $ele.data('morning'),
            noon = $ele.data('noon'),
            afternoon = $ele.data('afternoon'),
            evening = $ele.data('evening');

        // morning
        if (h >= morning && h < noon) {
            $ele.removeClass('malam').addClass('pagi');
        }

        // noon
        else if (h >= noon && h < afternoon) {
            $ele.removeClass('pagi').addClass('siang');
        }

        // afternoon
        else if (h >= afternoon && h < evening) {
            $ele.removeClass('siang').addClass('sore');
        }

        // evening
        else {
            $ele.removeClass('sore').addClass('malam');
        }
    }, 1000);
});

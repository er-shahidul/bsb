(function ($) {
    function initTimeElapsed() {
        $('.time-elapsed').each(function () {
            var $el = $(this);
            $el.html(moment($el.data('time')).fromNow());
        })
    }

    var viewMessageDetails = function (id) {
        var option = {onEscape: true, backdrop: true};

        $.get(Routing.generate('notification_view', {id: id}), function (data) {
            if (data.link && data.link !== "") {
                option['buttons'] = {
                    "GO": function () {
                        window.location.href = data['link'];
                    }
                }
            } else {
                option['buttons'] = {
                    "Close": function () {
                    }
                }
            }

            option['className'] = 'notification-dialog';
            option['message'] = data['message'];
            option['title'] = data['subject'];
            bootbox.dialog(option);
        });
    };

    function initClickHandler() {
        $('body').on('click', '.notificationrecipient_datatable_action', function (e) {
            var $tr = $(this).closest('tr');
            var id = $tr.attr('id');
            console.log($tr);
            $tr.css({"font-weight": "normal"});
            viewMessageDetails(id);
        });

        $('body').on('click', '.notification-item', function (e, a) {
            var $el = $(this);
            var id = $el.data('id');

            if (id === null || id === "") {
                return;
            }
            e.preventDefault();
            viewMessageDetails(id);
        })
    }

    $(document).ready(function () {
        initTimeElapsed();
        initClickHandler();
        setInterval(initTimeElapsed, 60000)
    });

})(window.jQuery);
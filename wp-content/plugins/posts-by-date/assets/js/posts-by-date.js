(function ($) {
    "use strict";
    $(document).ready(function () {
        $("#load-more").on('click', function (e) {
            // var $(this) = $(this);
            $(this).attr('disabled', true);
            $.ajax({
                type : "post",
                url: myAjax.ajaxurl,
                dataType: 'json',
                data: {
                    action : 'load_more',
                    args: {
                        category: $("#load-more").data('category'), 
                        date: $("#load-more").data('date'),
                        limit: $("#load-more").data('limit'),
                        orderby: $("#load-more").data('orderby'),
                        order: $("#load-more").data('order'),
                        current: $("#load-more").attr('data-paged'),
                        output: ''
                    }
                },
                success: function (response) {
                    if(response.status == 'success'){
                        $("#load-more").removeAttr('disabled').attr('data-paged', response.current).before(response.output);
                    }else{
                        $("#load-more").before(response.output);
                        $("#load-more").slideUp(600, function () { 
                            $(this).remove();
                            $("body").find("#no-more-post").slideDown().delay(4000).slideUp();
                         });
                    }
                }
            });
        });
    });
})(jQuery)
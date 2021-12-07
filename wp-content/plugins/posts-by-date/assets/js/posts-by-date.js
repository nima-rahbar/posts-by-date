(function ($) {
    $(document).ready(function () {
        $('body').removeClass('wp-core-ui wp-admin');

        if($("#category_0").val().length > 0){
            $("#category_0_value").children('span').text($("#category_0").children("option:selected").text()).removeClass("placeholder bg-light").addClass("text-light lead");
        }
        $("#category_0").on('change', function(){
            if($(this).val().length > 0){
                $("#category_0_value").children('span').text($(this).children("option:selected").text()).removeClass("placeholder bg-light").addClass("text-light lead");
            }else{
                $("#category_0_value").children('span').empty().addClass("placeholder bg-light").removeClass("text-light lead");
            }
        });

        if($("#date_1").val().length > 0){
            $("#date_1_value").children('span').text($("#date_1").val()).removeClass("placeholder bg-light").addClass("text-light lead");
        }
        $("#date_1").on('change', function(){
            if($(this).val().length > 0){
                $("#date_1_value").children('span').text($(this).val()).removeClass("placeholder bg-light").addClass("text-light lead");
            }else{
                $("#date_1_value").children('span').empty().addClass("placeholder bg-light").removeClass("text-light lead");
            }
        });

        if($("#limit_2").val().length > 0){
            $("#limit_2_value").children('span').text($("#limit_2").val()).removeClass("placeholder bg-light").addClass("text-light lead");
        }
        $("#limit_2").on('change', function(){
            if($(this).val().length > 0){
                $("#limit_2_value").children('span').text($(this).val()).removeClass("placeholder bg-light").addClass("text-light lead");
            }else{
                $("#limit_2_value").children('span').empty().addClass("placeholder bg-light").removeClass("text-light lead");
            }
        });

        if($("#orderby_3").val().length > 0){
            $("#orderby_3_value").children('span').text($("#orderby_3").children("option:selected").text()).removeClass("placeholder bg-light").addClass("text-light lead");
        }
        $("#orderby_3").on('change', function(){
            if($(this).val().length > 0){
                $("#orderby_3_value").children('span').text($(this).children("option:selected").text()).removeClass("placeholder bg-light").addClass("text-light lead");
            }else{
                $("#orderby_3_value").children('span').empty().addClass("placeholder bg-light").removeClass("text-light lead");
            }
        });

        if($("#order_4").val().length > 0){
            $("#order_4_value").children('span').text($("#order_4").children("option:selected").text()).removeClass("placeholder bg-light").addClass("text-light lead");
        }
        $("#order_4").on('change', function(){
            if($(this).val().length > 0){
                $("#order_4_value").children('span').text($(this).children("option:selected").text()).removeClass("placeholder bg-light").addClass("text-light lead");
            }else{
                $("#order_4_value").children('span').empty().addClass("placeholder bg-light").removeClass("text-light lead");
            }
        });

    });
})(jQuery);
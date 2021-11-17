require(["jquery"], function ($) {
    $(document).ready(function () {

        $(".format-flexsections #select_skill .modal-body").html( '<div class="course-content">'+$(".format-flexsections #activities_skill .modal-body .course-content").html()+'</div>' );

        $("#select_skill .modal-body .flexsections .section .activity .activityincomplete .activityinstance > a").each(function(){
            var link = $(this).attr('href');

            var parentsection = $(this).closest('li.section');
            var text = $(parentsection).find('.sectionname').html();
            if(!$(text).attr("href")){
                $(parentsection).find('.sectionname').html('<a href="'+link+'">'+text+'</a>');
            }
            
        });

        $("#page-course-view-topics ul.topics li.section").each(function(){
            var count = $(this).find('ul.section > li').length;
            if( count == 0 ){
                $(this).hide();
            }
        });

        $('.lang_switcher_div').html($('#lang_hide').html());
        $('#lang_hide').remove();

        $(".section_availability").click(function () {
            $(this).parent(".content").find("ul.section").slideToggle();

            $(this).toggleClass("section_availability_close");
        });
        $(".copyquizattempturl").click(function (e) {
            e.preventDefault();
            var $temp = $("<input>");
            $("body").append($temp);
            $temp.val($(this).attr("href")).select();
            document.execCommand("copy");
            $temp.remove();
            alert('Copied');
        });

    });

    $(".leeloobuytheme").on("click", function (e) {
        e.preventDefault();

        var modal = $(this).attr("data-target");

        $(modal + " .modal-body").html(
            '<iframe class="leeloo_themeframe" src="' +
                $(this).attr("href") +
                '"></iframe>'
        );
    });

    $(".leelooProdcutSingModal").on("hidden.bs.modal", function () {
        location.reload();
    });

    $(window).scroll(function() {

        if ($(document).scrollTop() > 220) {
            $('#back-to-top').fadeIn(300);
        } else {
            $('#back-to-top').fadeOut(100);
        }

    });

    $('#back-to-top').click(function(event) {
        event.preventDefault();
        $('html, body').animate({scrollTop: 0}, 500);
    });

});

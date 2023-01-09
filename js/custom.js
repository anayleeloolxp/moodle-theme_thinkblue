require(["jquery"], function ($) {
    $(document).ready(function () {

        function getCookie(cname) {
            let name = cname + "=";
            let ca = document.cookie.split(";");
            for(let i = 0; i < ca.length; i++) {
                let c = ca[i];
                while (c.charAt(0) == " ") {
                    c = c.substring(1);
                }
                if (c.indexOf(name) == 0) {
                    return c.substring(name.length, c.length);
                }
            }
            return "";
        }
        function setCookie(cname, cvalue, exdays) {
            const d = new Date();
            d.setTime(d.getTime() + (exdays*24*60*60*1000));
            let expires = "expires="+ d.toUTCString();
            document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
        }

        $("#rewardspop .close").click(function (e) {
            e.preventDefault();
            $('#rewardspop').hide();
            $('#rewardspop').removeClass('show');
        });

        $("#rewardsnoti .close").click(function (e) {
            e.preventDefault();
            $('#rewardsnoti').hide();
            $('#rewardsnoti').removeClass('show');
        });

        if ($('a[data-key="localboostnavigationcustomrootusersleeloossourl"]').length ) {
            var href = $('a[data-key="localboostnavigationcustomrootusersleeloossourl"]').attr('href');
            $('.l_dashlinks').attr('href', href);

            let result = href.includes("?");
            if( result ){
                var herohref = href+'&view=hero';
                var dashhref = href+'&view=dashboard';
            } else {
                var herohref = href+'?view=hero';
                var dashhref = href+'?view=dashboard';
            }

            $('.l_hero_link').attr('href', herohref);
            $('.l_dashboard_link').attr('href', dashhref);

        }else{
            $('.l_dashlinks').hide();
        }

        $(".format-flexsections #select_skill .modal-body").html( '<div class="course-content">'+$(".format-flexsections #activities_skill .modal-body .course-content").html()+'</div>' );

        $("#select_skill .modal-body .flexsections .section .activity .activityincomplete .activityinstance > a").each(function(){
            var link = $(this).attr('href');

            var parentsection = $(this).closest('li.section');
            var mainid = parentsection.attr('id');

            if(mainid != 'section-0'){
                var text = $(parentsection).find('.sectionname').html();
                var textdiv = $(parentsection).find('.sectionname');
                if(!$(textdiv).attr("href")){
                    $(parentsection).find('.sectionname').html('<a href="'+link+'">'+text+'</a>');
                }
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

        $("body.format-flexsections #fitem_id_format #id_format").after("<span class='warning_flex'>Warning: switching from Flexible section format to another course format will break the hierarchy of the course.<br />Make sure you have a backup before switching!</span>");

        var id_format = $("body.format-flexsections #fitem_id_format #id_format");
        var id_formatval = id_format.val();

        $('#id_updatecourseformat').attr('disabled' , true);
        $(id_format).on('change', function() {

            var thisval = $(this).val();
            if( id_formatval == 'flexsections' && thisval != 'flexsections' ){
                var success = confirm('Warning: switching from Flexible section format to another course format will break the hierarchy of the course. Are you sure you want to do this?');
                if(!success)
                {
                    $(this).val(id_formatval);
                }else{
                    $('#id_updatecourseformat').attr('disabled' , false);
                    $('#id_updatecourseformat').trigger('click');
                }
            }else{
                $('#id_updatecourseformat').attr('disabled' , false);
                $('#id_updatecourseformat').trigger('click');
            }
        });


        if ($(".currentactivear")[0]){
            var $container = $(".bottom_activity-right-main");
            var $scrollTo = $('.currentactivear');

            $container.animate({scrollLeft: $scrollTo.position().left - $container.offset().left + $container.scrollLeft(), scrollTop: 0},300);
        }

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

    $(".head-mn-btn .btn").on("click", function (e) {
        e.preventDefault();
        $('body').toggleClass('up-head');
    });

    $(".bottom-mn-btn .btn").on("click", function (e) {
        e.preventDefault();
        $('body').toggleClass('down-bottom');
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

    window.addEventListener("message", function(event) {
        if (event.data == 'showletsgo') {
            $('#letsgo').modal('show');
        }

        if (event.data == 'showleftmeu') {
            $('.logo_side_container button').addClass('click');
            $('.logo_side_container button').trigger('click');
        }

        if (event.data == 'arenamodelshow') {
            $('.boardheader, div#nav-drawer').fadeOut(500);
        }

        if (event.data == 'arenamodelclose') {
            console.log('arenamodelclose');
            $('.boardheader, div#nav-drawer').fadeIn(500);
        }

        var text = event.data;
        if( text.includes("leeloo_chatcount-") ){
            var count = text.replace("leeloo_chatcount-", "");
            if( count != 0 ){
                $('.l_chatcount').removeClass('l_hidden');
                $('.l_chatcount').text(count);
            }else{
                $('.l_chatcount').addClass('l_hidden');
                $('.l_chatcount').text(count);
            }
        }

    });


    $(function(){
        $(".progress-pie-chart").each(function() {
            percent = parseInt($(this).data('percent')),
            deg = 360*percent/100;
            if (percent > 50) {
                $(this).addClass('gt-50');
            }
            $(this).find('.ppc-progress-fill').css('transform','rotate('+ deg +'deg)');
            $(this).find('.ppc-progress-fill2').css('transform','rotate('+ deg +'deg)');
        });
        /*var $ppc = $('.progress-pie-chart'),
        percent = parseInt($ppc.data('percent')),
        deg = 360*percent/100;
        if (percent > 50) {
            $ppc.addClass('gt-50');
        }
        $('.ppc-progress-fill').css('transform','rotate('+ deg +'deg)');
        $('.ppc-percents span').html(percent+'%');*/
    });

});

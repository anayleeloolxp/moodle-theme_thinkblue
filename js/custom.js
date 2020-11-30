require(["jquery"],function($) {

    $(document).ready(function(){

        $(".section_availability").click(function(){

            $(this).parent('.content').find('ul.section').slideToggle();

            $(this).toggleClass('section_availability_close');

        });

    });



    $('.leeloobuytheme').on('click', function(e){

        e.preventDefault();

        var modal = $(this).attr('data-target');

        $(modal+' .modal-body').html('<iframe class="leeloo_themeframe" src="'+$(this).attr('href')+'"></iframe>');

    });

    

    $('.leelooProdcutSingModal').on('hidden.bs.modal', function () {

        location.reload();

    });



});


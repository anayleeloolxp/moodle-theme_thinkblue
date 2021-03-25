require(["jquery"], function ($) {
    $(document).ready(function () {
        $(".section_availability").click(function () {
            $(this).parent(".content").find("ul.section").slideToggle();

            $(this).toggleClass("section_availability_close");
        });

        $(".fa-leeloo").hide();

        $(".fa-leeloo")
            .parent()
            .append(
                '<svg version="1.0" xmlns="http://www.w3.org/2000/svg"         width="35.000000pt" height="45.000000pt" viewBox="0 0 50.000000 45.000000"         preserveAspectRatio="xMidYMid meet"> <g transform="translate(0.000000,45.000000) scale(0.100000,-0.100000)" fill="#000000" stroke="none"> <path d="M272 434 c43 -88 43 -88 43 -214 0 -107 -3 -131 -21 -167 -16 -31 -18 -43 -8 -43 25 0 62 19 99 52 111 97 96 270 -28 342 -47 27 -91 42 -85 30z"/> <path d="M150 391 c-54 -29 -93 -83 -94 -132 -1 -25 4 -44 12 -46 8 -3 12 6 12 27 0 67 57 139 127 160 28 8 28 8 3 9 -14 1 -41 -7 -60 -18z"/> <path d="M169 343 c-17 -15 -38 -42 -45 -60 -16 -38 -18 -83 -4 -83 6 0 10 12 10 28 0 40 40 102 80 123 28 15 30 19 13 19 -12 0 -36 -12 -54 -27z"/> <path d="M35 189 c26 -87 92 -148 171 -157 44 -5 44 -4 64 39 11 24 20 56 20 72 0 27 0 27 -68 27 -61 0 -118 11 -176 35 -16 7 -17 5 -11 -16z"/> </g>  </svg>'
            );
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
});

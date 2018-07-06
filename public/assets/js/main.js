$(document).ready(function () {
    M.updateTextFields();

    $('.dropdown-trigger').dropdown();
    $('.sidenav').sidenav();
    $('.modal').modal();
    $('.fixed-action-btn').floatingActionButton();

    // Кнопка вверх
    $("#back-top").click(function () {
        $("html").animate({
            scrollTop: 0
        }, 800);
        return false;
    });

    //создание новой сущности
    $('#create_user').submit(function (e) {
        e.preventDefault();
        $.ajax({
            type: "POST",
            url: e.target.action,
            data: {},
            success: function(response) {}
        });
    });

});

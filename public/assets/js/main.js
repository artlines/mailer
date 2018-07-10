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
    $('#create_user').click(function (e) {
        $.ajax({
            type: "POST",
            url: $(this).data('action'),
            data: {},
            success: function(data) {
                $('#user_modal_content').html(data);
            }
        });
    });

    //создание и редактирование сущности
    $('.edit_user').click(function (e) {
        $('.modal').modal('open');
        $.ajax({
            type: "POST",
            url: $(this).data('action'),
            data: {},
            success: function(data) {
                $('#user_modal_content').html(data);
            }
        });
    });


});

$(document).ready(function () {
    M.updateTextFields();

    $('.dropdown-trigger').dropdown();
    $('.sidenav').sidenav();
    $('.modal').modal();
    $('.fixed-action-btn').floatingActionButton();

    //создание новой сущности
    $('#create_entity').click(function (e) {
        $.ajax({
            type: "POST",
            url: $(this).data('action'),
            data: {},
            success: function(data) {
                $('#modal_content').html(data);
            }
        });
    });

    //вызов модалочки и подгрузка в него данных
    $('.get_modal').click(function (e) {
        $('.modal').modal('open');
        $.ajax({
            type: "POST",
            url: $(this).data('action'),
            data: {},
            success: function(data) {
                $('#modal_content').html(data);
            }
        });
    });


});

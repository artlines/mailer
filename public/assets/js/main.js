$(document).ready(function () {
    M.updateTextFields();

    $('.dropdown-trigger').dropdown();
    $('.sidenav').sidenav();
    $('.modal').modal();
    $('.fixed-action-btn').floatingActionButton();

    //создание/редактирование сущности
    $('#create_entity, .get_modal').click(function (e) {
        $('.modal').modal('open');
        $.ajax({
            type: "POST",
            url: $(this).data('action'),
            data: {},
            beforeSend: function( xhr ) {
                $('.progress').fadeTo(0, 1);
            },
            success: function(data) {
                $('#modal_content').html(data);
            },
            complete: function( xhr ) {
                $('.progress').fadeTo(0, 0);
            },
        });
    });

});

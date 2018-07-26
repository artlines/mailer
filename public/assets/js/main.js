$(document).ready(function () {
    // localStorage.clear();
    M.updateTextFields();

    $('.dropdown-trigger').dropdown();
    $('.sidenav').sidenav();
    $('.modal').modal();
    $('.fixed-action-btn').floatingActionButton();
    $('.datepicker').datepicker({
        'firstDay': 1,
        'format': 'dd.mm.yyyy',
        'autoClose': true,
    });
    $('select').formSelect();

    //создание/редактирование сущности
    $('#create_entity, .get_modal').click(function (e) {
        $('.modal--data').modal('open');
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
            error: function(data, textStatus, xhr) {
                $('#feedback').addClass('red').text(invalidMessage).fadeTo(0, 1);
            },
            complete: function( xhr ) {
                $('.progress').fadeTo(0, 0);
            },
        });
    });

    //Логи-фильтр
    $('[name="filter"]').change(function (e) {
        e.preventDefault();
        $.ajax({
            type: "GET",
            url: $(this).attr('action'),
            data: $(this).serialize(),
            beforeSend: function( xhr ) {
                $('.progress').fadeTo(0, 1);
            },
            success: function(data) {
                $('#list').html(data);
            },
            complete: function( xhr ) {
                $('.progress').fadeTo(0, 0);
            },
        });
    });

    //Получить модалочку, данные из локального хранилища
    $('.get_modal_local').click(function (e) {
        if (!$(this).hasClass('link--disabled')){
            $('.modal--local').modal('open');
            var data = localStorage.getItem($(this).data('key'));
            var transform = {"<>":"li","html":[
                    {"<>":"span","html":"Поле: ${field}"},
                    {"<>":"div","text":"Прежнее значение: "},
                    {"<>":"div",'id':'editor',"text":"${old_value}"}
                ]};
            $('#diff_content').html(json2html.transform(data, transform));

            var editor = ace.edit("editor");
            editor.setTheme("ace/theme/github");
            editor.getSession().setMode("ace/mode/twig");
            editor.setShowPrintMargin(true);
        }
    });

    $('#filters_clear').click(function (e) {
       e.preventDefault();
        $('[name="filter"]').find("input").val("");
        $('[name="filter"]').submit();
    });

});

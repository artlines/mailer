$(document).ready(function() {
  M.updateTextFields();

  $('.dropdown-trigger').dropdown();
  $('.sidenav').sidenav();
  $('.modal').modal();
  
  // Кнопка вверх
  $(document).on('click', "#back-top", function () {
    $("body,html").animate({
      scrollTop:0
    }, 800);
    return false;
  });

  $(window).scroll(function(){
    var menu = $('[id^="box-sticky"]');

    if($(this).scrollTop() > 25) {
      $('nav.top-navbar').css({'box-shadow': 'none'});
      $(menu).css({'border-radius': 0});
    }
    else {
      $('nav.top-navbar').css({'box-shadow': '0 2px 2px 0 rgba(0,0,0,0.14),0 3px 1px -2px rgba(0,0,0,0.12),0 1px 5px 0 rgba(0,0,0,0.2)'});
      $(menu).css({'border-radius': 2});
    }
  });

  //##########################################
  // Работа с таблицей
  //##########################################

  /* Обработка нажатия на главный флажок (выбор всех) */
  $(document).on('change', '#main table input.check-all', function()
  {
    if(!$(this).is(":checked"))
    {
      $(this).parents('table').find('td:nth-child(2) input:checkbox').prop('checked', false);
      $(this).parents('table').find('td:nth-child(2) input:checkbox').parent().removeClass('checked');
      $(this).parents('table').find("tr").removeClass("over");
    }
    else
    {
      $(this).parents('table').find('td:nth-child(2) input:checkbox').attr("checked", "checked").prop('checked', true);
      $(this).parents('table').find('td:nth-child(2) input:checkbox').parent().addClass('checked');
      $(this).parents('table').find("tbody tr").addClass("over");
    }
  });

  /* Обработка остальных флажков, клик по строке таблице, кроме самого чекбокса */
    $(document).on('click change', '#main table tbody tr td:not(:nth-child(2))', function()
    {
      var check = $(this).parents('tr').find('td:nth-child(2) input');

      if(!$($(this).parents('tr').find('td:nth-child(2) input')).is(':checked'))
      {
        $(check).attr("checked", "checked").prop('checked', true).parent().addClass('checked').parents('tr').addClass("over");
      }
      else
      {
        $(check).removeAttr("checked").prop('checked', false).parent().removeClass('checked').parents('tr').removeClass('over');
      }
    });

    /* Обработка checkbox в таблице */
      $(document).on('click', '#main table', function() {
        var checkbox = $(this).find('td:nth-child(2) input:checkbox');
        var checkCount = $("td:nth-child(2)  input:checkbox").length;
        var count = 0;

        // Обрабатываем флажки, если выделины не все
        $(this).find('tbody td:nth-child(2) input:checkbox').each(function(i,el){
          if($(el).is(':checked'))
          {
            count = $('input[type=checkbox]:checked').length;
          }
        });

        if(count < checkCount)
        {
          $(this).find('input.check-all').removeAttr("checked").parent().removeClass('checked');
        }
        else
        {
          $(this).find('input.check-all').attr("checked","checked").parent().addClass('checked');
        }

         /** TODO: Испарвить выбор всех флажков */
        // Получаем значения флажков
        var values = $(this).find('td:nth-child(2) input:checkbox').map(function(i,el){
          if($(el).prop('checked')){
            return $(el).val();
          }
        }).get();

        // Если выбран 1 флажок, то активируем (Удалить, Редактировать)
        if(values.length === 1)
        {
          if(values > 0)
          {
            $(document).find('[id^="box-sticky"] span#trash, [id^="box-sticky"] span#edit').removeClass('hide').attr('data-value', values);
          }
          else
          {
            $(document).find('[id^="box-sticky"] span#edit').removeClass('hide').attr('data-value', values);
            $(document).find('[id^="box-sticky"] span#trash').addClass('hide').removeAttr('data-value');
          }
        }
        else if(values.length > 1) // Если больше, то деактивируем (Редактировать)
        {
          $(document).find('[id^="box-sticky"] span#trash').removeClass('hide').attr('data-value', values);
          $(document).find('[id^="box-sticky"] span#edit').addClass('hide').removeAttr('data-value');
        }
        else if(values.length === 0)
        {
          $(document).find('[id^="box-sticky"] span#trash, [id^="box-sticky"] span#edit').addClass('hide').removeAttr('data-value');
        }
      });

      /* Создании нового элемента */
      $(document).on('click', '[id^="box-sticky"] span#add', function() {
        var response = $(this).data('response');
        var target = $(this).data('target');

        // Снимаем флажок
        $(response).find('td:nth-child(2) input:checkbox').prop('checked', false).parent().removeClass('checked');
        // Очищаем в меню атрибуты "data-value"
        $(document).find('[id^="box-sticky"] span#trash, [id^="box-sticky"] span#edit').removeAttr('data-value');
      });

      //##########################################
      // Отправка Ajax
      //##########################################

      // Отправка по клику
      $(document).on('click', '.clickAjax', function(event) {
        event.preventDefault();

        var method = $(this).data('method');
        var action = $(this).data('action');
        var update = $(this).data('update');
        var value  = $(this).attr('data-value') ? $(this).data('value') : '';

        var response = $(this).data('response');
        var target = $(this).data('target');
        var wrap = $(this).data('wrap');
        var location =  $(this).data('location');

        var status = false;
        
        var send = function(status) {
          if(status === true)
          {
            $.ajax({
              url: action + value,
              type: method,
              success: function(data){

              },
              complete: function(data){
                method = null;
                action = null;
              }
            });

          }
        };

         send(true);

      });
});

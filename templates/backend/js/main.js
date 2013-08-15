$(function(){
	
	// Host
	var host = window.location.protocol + '//' + window.location.hostname + '/';   // http://www.mysite.ru/
	
	// Popup
	$('[rel=popover]').popover({
		placement: 'top',
		trigger: 'hover'
	})
	
	// Tooltip
	$('[rel=tooltip]').tooltip();
	
	// Radio
	$('div[data-toggle=buttons-radio] button').click(function(){
		var self  = $(this),
			value = self.attr('data-value');
		
		self.parent().parent().find(':hidden').val(value);
	});
	
	// Delete
	//$('a.delete').click(function(){
		
		//modal_box('Предупреждение', 'Точно удалить?', 'Удалить', $(this).attr('href'));
		
		return false;
	//});
	
	// Закрытие модального окна
	$('.modal a.close-button').on('click', function(){
		$('.modal').modal('hide');
	});
	
	// Бан пользователя
	$('a.users-ban').click(function(){
		var self        = $(this),
			login       = self.attr('login'),
			href        = self.attr('href'),
			type        = href.split('/')[5],
			modal_block = $('#modal-box-user-ban');
		
		if(type == 'off')
		{
			return true;
		}
		
		$('.modal-header h3 span', modal_block).html(': ' + login);
		
		$('form', modal_block).attr('action', href);
		
		// Очистка полей
		$('textarea').val('');

		// Удаляю инфу об ошибках
		$('div.alert', modal_block).remove();
		
		modal_block.modal('show');
		
		return false;
	});

    $('.clear-cache').click(function(){
        var self = $(this);
        self.hide().after('<img src="/resources/images/ajax-loader1.gif" width="16" height="11" class="ajl" />');
        $.post('/backend/main/ajax_clear_cache',{},function(){
            self.parent().find('.ajl').remove();
            self.parent().next().html('0 Bytes');
            self.show();
        });
    });
    
    $('select').addClass('chzn-select');
    
    // Chosen
    $('.chzn-select').chosen({
    	disable_search_threshold: 10
    });

   /* $('.chzn-select-deselect').chosen({
    	allow_single_deselect: true,
    	disable_search_threshold: 10
    });*/
    
    // Tabs
    $('.nav-tabs a').on('click',function (e) {
	    e.preventDefault();
	    $(this).tab('show');
    })
})

function modal_box(title, text, button_name, url)
{
	$('.modal').remove();
	
	var html = 
	'<div class="modal" id="modal-box-change-password" style="display: none">' +
		'<div class="modal-header">' +
			'<button class="close" data-dismiss="modal">×</button>' +
			'<h3>' + title + ' <span></span></h3>' +
		'</div>' +
		'<div class="modal-body">' + text + '</div>' +
		'<div class="modal-footer">' +
			'<a class="btn btn-primary" style="margin: 0 0 0 165px;" ' + (url != '' ? 'href="' + url + '"' : '') + '>' + button_name + '</a>' +
			'<a class="btn close-button">Закрыть</a>' +
		'</div>' +
	'</div>';
	
	$('body').prepend(html);
	$('.modal').modal('show');
}
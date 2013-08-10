$(function(){

    // Обновление капчи 
    var xhr_captcha = null;

    $('div.captcha').click(function(){ 
        var self = $(this);
        
        self.html('<img class="img-loader-captcha" src="/resources/images/ajax-loader.gif" alt="" />');
        
        if(xhr_captcha != null)
        {
            xhr_captcha.abort();
        }
        
        xhr_captcha = $.get('/ajax/captcha_reload/',{},function(data){
            $(data.image).load(function(){
                self.html(data.image);
            });
        },'json');
    });

})
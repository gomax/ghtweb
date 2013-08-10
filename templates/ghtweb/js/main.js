$(function(){
	
	// Tabs
	$('.tabs-nav li').click(function(){
		$(this).addClass('active').siblings().removeClass('active');
		var i = $(this).index(),
			b = $(this).parents('.tops').find('.tabs-content');
		b.addClass('dn');
		b.filter(':eq(' + i + ')').removeClass('dn');
	});
	
	// Fancybox
	if(typeof $.fancybox == 'function')
	{
		$('.fancybox-button').fancybox({
			prevEffect : 'none',
			nextEffect : 'none',
			closeBtn : false,
			padding : 0,
			helpers : {
				title : {
					type : 'inside'
				},
				buttons : {}
			}
		});
	}
	
})
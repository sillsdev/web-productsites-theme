jQuery(document).ready(function($) {
	
  //	### OPEN .external LINKS IN NEW TAB ###

	$('a.external').attr('target','_blank');

	//	### OPEN / CLOSE ALL BUTTONS FOR ACCORDIONS ###

	$('.open-all').click(function() {
		var rl = $(this).attr('rel'),
				acc = (rl === undefined) ? '.accordion-title' : '#accordion-' + rl + ' > .accordion-title';
		$.each($(acc), function(index, value) {
			if (!$(this).hasClass('open')) {
				$(this).trigger('click');
			}
		});
	});

	$('.close-all').click(function() {
		var rl = $(this).attr('rel'),
				acc = (rl === undefined) ? '.accordion-title' : '#accordion-' + rl + ' > .accordion-title';
		$.each($(acc), function(index, value) {
			if ($(this).hasClass('open')) {
				$(this).trigger('click');
			}
		});
	});
});

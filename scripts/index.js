var setQuestion = function(el) {
	switch (el.attr('data-type')) {
		case 'select':
			if ( el.val() != '0' && $.trim(el.val()) != '') {
				$('#img' + el.attr('data-id')).show();
			} else {
				$('#img' + el.attr('data-id')).hide();
			}
			break;

		case 'text':
			if ( el.val() != '') {
				$('#img' + el.attr('data-id')).show();
			} else {
				$('#img' + el.attr('data-id')).hide();
			}
			break;

		case 'date':
			if ( el.val() != '' && el.val().match(/^\d\d\.\d\d\.\d\d\d\d$/g)) {
				$('#img' + el.attr('data-id')).show();
			} else {
				$('#img' + el.attr('data-id')).hide();
			}
			break;

		case 'textarea':
			break;
	}

}

var resetForm = function() {
	$('select[name^="question"]').each(function(){
		$(this).children('option').removeAttr('selected');
		$(this).children('option:first-child').attr('selected', 'selected');
		setQuestion($(this))
	});
	$('.dataTables_length input, select').not("select.multiple").selectmenu("destroy").selectmenu({
		style: 'dropdown',
		transferClasses: true,
		width: null
	});
}

var changeFormArea = function() {
	$(".group_star, .group_text").hide();
	areas = $("input.form_area:checked").each(function(){
		var i;
		for (i = 0; i < formArea[$(this).data('id')].length; i++) {
			$(".group_star_" + formArea[$(this).data('id')][i]).show();
			$(".group_text_" + formArea[$(this).data('id')][i]).show();
		}
	})
}

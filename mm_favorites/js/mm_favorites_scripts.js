jQuery(document).ready(function($){
	$('.mm_favorites_button').click(function(){
		const action = $(this).attr('data-action')
		$.ajax({
			type:'POST',
			url: mmFavorites.url,
			data: {
				security: mmFavorites.nonce,
				postId: mmFavorites.postId,
				action: action
			},
			beforeSend: function() {
				$('.mm_favorites_add_favorites').fadeOut(300, function(){
					$('.mm_favorites_loader').fadeIn(300)
				})
			},
			success: function(res){
				$('.mm_favorites_wrapper_loader').text(res)
			},
			error: function(){
				alert('Ошибка!!!');
			}
		})
	})
});
jQuery(document).ready(function($){
	$('.mm_favorites_post_delete').click(function(){
		const postId = $(this).attr('data-post')
		const currentButton = $(this)
		$.ajax({
			type:'POST',
			url: ajaxurl,
			data: {
				security: mmFavorites.nonce,
				postId: postId,
				action: 'mm_favorites_del'
			},
			success: function(){
				currentButton.parent().remove()
			},
			error: function(){
				alert('Ошибка!!!');
			}
		})
	})
	$('.mm_favorites_delete_all').click(function(){
		let arrPostsId = []
		for(let i=0; i<$('.mm_favorites_post_delete').length; i++){
			arrPostsId.push($('.mm_favorites_post_delete').eq(i).attr('data-post'))
		}

		$.ajax({
			type:'POST',
			url: ajaxurl,
			data: {
				security: mmFavorites.nonce,
				postArr: arrPostsId.join(','),
				action: 'mm_favorites_del_all'
			},
			success: function(data){
				$('.mm_favorites_wrapper_widget').text(data)
			},
			error: function(){
				alert('Ошибка!!!');
			}
		})
	})
});
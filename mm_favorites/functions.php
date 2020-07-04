<?php
include 'classes/MM_Favorites_Widget.php';

function mm_favorites_add_link_favorites( $content ){
	if(!is_user_logged_in()) return $content;
	global $post;
	$img_src = plugins_url('/img/loader.gif', __FILE__);
	if(!mm_is_favorites($post->ID)) {
		$content .= "<div class='mm_favorites_wrapper_loader'>
					<button 
					  data-action='mm_favorites_add'
					  class='mm_favorites_add_favorites mm_favorites_button'
					  >Добавить в избранное
					</button>
					  <img 
					  	src='{$img_src}' 
					  	alt='' 
					  	class='mm_favorites_loader mm_favorites_hidden'>
                    </div>";
		return $content;
	}
	else {
		$content .= "<div class='mm_favorites_wrapper_loader'>
					<button 
					  data-action='mm_favorites_del'
					  class='mm_favorites_delete_favorites mm_favorites_button'
					  >Удалить из избраного
					</button>
                    </div>";
		return $content;
	}
}

function mm_favorites_scripts() {
	if(!is_user_logged_in()) return;
	global $post;
	wp_enqueue_script(
		'mm_favorites_scripts',
		plugins_url('/js/mm_favorites_scripts.js',  __FILE__), array('jquery'), null, true);

	wp_enqueue_style(
		'mm_favorites_style',
		plugins_url('/css/mm_favorites_style.css',  __FILE__), null, true);

	wp_localize_script(
		'mm_favorites_scripts',
		'mmFavorites',
		[
			'url' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('mmFavorites'),
			'postId' =>  $post->ID
		]
	);
}

function mm_favorites_add()
{
	if(!wp_verify_nonce($_POST['security'], 'mmFavorites')){
		wp_die('Ошибка безопасности!!!');
	}
	$post_id = (int)$_POST['postId'];
	$user = wp_get_current_user();

	if(mm_is_favorites($post_id)) wp_die();

	if(add_user_meta($user->ID, 'mm_favorites', $post_id)) {
		wp_die('Добавлено');
	}
	wp_die('Ошибка добавления');
}

function mm_favorites_del(){
	if(!wp_verify_nonce($_POST['security'], 'mmFavorites')){
		wp_die('Ошибка безопасности!!!');
	}
	$post_id = (int)$_POST['postId'];
	$user = wp_get_current_user();

	if(!mm_is_favorites($post_id)) wp_die();

	if(delete_user_meta($user->ID, 'mm_favorites', $post_id)) {
		wp_die('Удалено');
	}
	wp_die('Ошибка удаления');
}

function mm_favorites_del_all() {
	if(!wp_verify_nonce($_POST['security'], 'mmFavorites')){
		wp_die('Ошибка безопасности!!!');
	}

	$user = wp_get_current_user();
	$postArr = explode(',', $_POST['postArr']);

	$error = false;
	foreach ($postArr as $post_id) {
		if(!mm_is_favorites($post_id)) wp_die();
		if(delete_user_meta($user->ID, 'mm_favorites', $post_id)) $error = false;
		else $error = true;
	}

	if($error) wp_die('Ошибка удаления');
	else wp_die('Посты удалены');
}

function mm_is_favorites($post_id){
	$user = wp_get_current_user();
	$favorites_post_id = get_user_meta($user->ID, 'mm_favorites');

	if(in_array($post_id, $favorites_post_id)) return true;
	else return false;
}

function mm_favorites_dashboard_widget(){
	wp_add_dashboard_widget(
		'mm_favorites_dashboard',
		'Список избранных постов',
		'mm_favorites_show_widget'
	);
}

function mm_favorites_show_widget() {
	$user = wp_get_current_user();
	$favorites_post_id = get_user_meta($user->ID, 'mm_favorites');
	if(count($favorites_post_id) === 0) {
		echo 'Список пуст';
		return;
	} else {
		$str_html = '<div class="mm_favorites_wrapper_widget"><ul>';
		foreach ($favorites_post_id as $id) {
			$permalink = get_permalink($id);
			if(!empty($permalink)) {
				$post_title = get_the_title($id);
				$str_html.="<li>
                            <a href='{$permalink}'>{$post_title}</a>
                            <span 
                            	class='mm_favorites_post_delete' 
                            	data-post='{$id}'
                            >x</span>
                        </li>";
			}
		}
		$str_html .= '</ul>';
		$str_html .= '<button 
                        class="btn mm_favorites_delete_all">
                        Удалить все записи
                      </button></div>';
		echo $str_html;
	}
}

function mm_favorites_admin_scripts($hook) {
	if($hook === 'index.php') {
		wp_enqueue_script(
			'mm_favorites_scripts',
			plugins_url('/js/mm_favorites_admin_scripts.js',  __FILE__), array('jquery'), null, true);

		wp_enqueue_style(
			'mm_favorites_style',
			plugins_url('/css/mm_favorites_admin_style.css',  __FILE__), null, true);

		wp_localize_script(
			'mm_favorites_scripts',
			'mmFavorites',
			[
				'nonce' => wp_create_nonce('mmFavorites')
			]
		);
	}
}

function mm_favorites_widget(){
	register_widget( 'MM_Favorites_Widget');
}
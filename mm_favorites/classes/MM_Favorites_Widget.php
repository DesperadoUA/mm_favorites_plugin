<?php

class MM_Favorites_Widget extends WP_Widget {
    function __construct(){
    	$args = [
    		'name' => 'Избранные записи',
			'description' => 'Выводит блок избранных записей пользоваля'
		];
		parent::__construct('mm_favorites_widget', 'Избранные записи', $args);
	}

	public function form($instance) {
    	extract($instance);
    	$title = !empty($title) ? esc_attr($title) : '';
		?>
		<p>
			<label for="<?= $this->get_field_id('title'); ?>">Заголовки</label>
			<input
				type="text"
				name="<?= $this->get_field_name('title'); ?>"
				value="<?= $title; ?>"
				id="<?= $this->get_field_id('title'); ?>"
				class="widefat" />
		</p>
		<?php
	}

	public function widget($args, $instance) {
		if(!is_user_logged_in()) return;
		echo $args['before_widget'];
			echo $args['before_title'];
				echo $instance['title'];
			echo $args['after_title'];
			$this->get_front_html();
		echo $args['after_widget'];
	}

	private function get_front_html() {
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
                        </li>";
				}
			}
			$str_html .= '</ul>';
			echo $str_html;
		}
	}
}
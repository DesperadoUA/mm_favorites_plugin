<?php
/*
Plugin Name: mm_favorites
Description: Тестовый плагин создания записей.
Version: Номер версии плагина, например: 1.0
Author: Lazarev Konstantin Aleksandrovich
*/
include 'functions.php';

add_filter( 'the_content', 'mm_favorites_add_link_favorites' );
add_action( 'wp_enqueue_scripts', 'mm_favorites_scripts' );
add_action( 'admin_enqueue_scripts', 'mm_favorites_admin_scripts' );
add_action( 'wp_ajax_mm_favorites_add', 'mm_favorites_add' );
add_action( 'wp_ajax_mm_favorites_del', 'mm_favorites_del' );
add_action( 'wp_ajax_mm_favorites_del_all', 'mm_favorites_del_all' );
add_action( 'wp_dashboard_setup', 'mm_favorites_dashboard_widget' );
add_action( 'widgets_init', 'mm_favorites_widget');

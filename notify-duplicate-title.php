<?php
/*
Plugin Name: Notify Duplicate Title
Plugin URI: http://smkn.xsrv.jp/blog/2016/11/wordpress-plugin-called-notify-duplicate-title/
Description: Notify Duplicate Title.
Version: 1.0.0
Author: smkn
Author URI: http://smkn.xsrv.jp/blog/
Text Domain: notify-duplicate-title
Domain Path: /languages/
License: GPLv2 or later
*/

load_plugin_textdomain('notify-duplicate-title', false, dirname(plugin_basename(__FILE__)).'/languages/');

function ndt_enqueue($hook_suffix){
	if($hook_suffix === 'post.php' || $hook_suffix === 'post-new.php'){
		$ajax_nonce = wp_create_nonce("NDT_nonce");
		wp_enqueue_script('notify_duplicate_title', plugins_url('js/notify-duplicate-title.js', __FILE__), array('jquery'));
		wp_localize_script('notify_duplicate_title', 'NDT', array('endpoint' => admin_url('admin-ajax.php'), 'nonce' => $ajax_nonce));
	}
}
add_action('admin_enqueue_scripts', 'ndt_enqueue');

function duplicate_title_checker(){
	check_ajax_referer('NDT_nonce', 'security');

	$post_id = intval($_POST['post_id']);
	$post_title = wp_unslash(sanitize_post_field('post_title', $_POST['post_title'], $post_id, 'db'));
	$post_type = sanitize_text_field($_POST['post_type']);

	$get_page_by_title_obj = get_page_by_title($post_title, OBJECT, $post_type);
	if($get_page_by_title_obj !== NULL && $get_page_by_title_obj->ID !== $post_id && $get_page_by_title_obj->post_title === $post_title) {
		echo '<div id="message" class="notice notice-error"><p>'.__('This title is duplicated.', 'notify-duplicate-title').'</p></div>';
	} else {
		echo '<div id="message" class="notice notice-success"><p>'.__('This title is unique!', 'notify-duplicate-title').'</p></div>';
	}
	exit;
}
add_action('wp_ajax_duplicate_title_checker', 'duplicate_title_checker');

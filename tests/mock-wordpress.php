<?php

global $current_user;
$current_user = new stdClass();
$current_user->user_url = 'user_url';
$current_user->display_name = 'display_name';
$current_user->user_email = 'user_email';
$current_user->user_url = 'user_url';

function __($str) {
	return $str;
}
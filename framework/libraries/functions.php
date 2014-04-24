<?php

function get_user_uid() {
	return (isset($_SESSION['user_id']))? $_SESSION['user_id'] : 0;
}

<?php
	/**
	 * Just a simple test to show how the API works.
	 * 
	 * @author Benjamin Jay Young <blaher@blahertech.org>
	 * @version 1.0
	 */
	include('../IRCphpAPI.php');

	$objIRC=new IRCphpAPI('irc.chatspike.net', 6667, 'PHPbot', '#blahertech');
	unset($objIRC);
?>
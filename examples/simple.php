<?php
	/**
	 * Just a simple test to show how the API works.
	 * 
	 * @author Benjamin Jay Young <blaher@blahertech.org>
	 * @version 1.0
	 */
	include('../IRCphpAPI.php');

	class IRCtest extends IRCphpAPI
	{
		public function process($strBuffer)
		{
			if (strpos($strBuffer, ' :!kill'))
			{
				return false;
			}
			return true;
		}
		
		public function report($strMessage)
		{
			echo $strMessage,"<br />\n";
		}
	}

	$objIRC=new IRCtest('irc.chatspike.net', 6667, 'PHPbot', '#blahertech');
	unset($objIRC);

	die();
?>
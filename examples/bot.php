<?php
	/**
	 * A simple bot that uses the API.
	 * 
	 * @author Benjamin Jay Young <blaher@blahertech.org>
	 * @version 1.0
	 */
	include('../IRCphpAPI.php');

	class IRCbot extends IRCphpAPI
	{
		public function process($strBuffer)
		{
			$strCommand=substr($strBuffer, strpos($strCommand, ' PRIVMSG ')+9);
			$strCommand=substr($strCommand, strpos($strCommand, ' ')+1);

			if (substr($strCommand, 0, 1)=='!')
			{
				switch (substr($strCommand, 1, strpos($strCommand, ' ')))
				{
					case 'kill':
						return false;
				}
			}

			return true;
		}
		
		public function report($strMessage)
		{
			echo $strMessage,"<br />\n";
		}
	}

	$objIRC=new IRCbot('irc.chatspike.net', 6667, 'PHPbot', '#blahertech');
	unset($objIRC);

	die();
?>
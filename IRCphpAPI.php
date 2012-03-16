<?php
	/**
	 * A object to connect and communicate with IRC.
	 * 
	 * @author Benjamin Jay Young <blaher@blahertech.org>
	 * @version 1.0
	 */
	class IRCphpAPI
	{
		private $objConnection, $strServer, $intPort, $aryChannels, $strNick;
		
		public function __construct
		(
			$strServer, $intPort=6667, $strChannel=false, $strNick=false
		)
		{
			$this->connect($strServer, $intPort);
			
			if ($strChannel)
			{
				$this->join($strChannel);
			}
			
			if ($strNick)
			{
				$this->nick($strNick);
			}
		}
		
		private function connect($strServer, $intPort=6667)
		{
			
		}
		
		private function disconnect()
		{
			
		}
		
		public function join($strChannel)
		{
			
		}
		
		public function quit($strChannel)
		{
			
		}
		
		private function changeNick($strNick)
		{
			$this->strNick=$strNick;
		}
		
		public function nick($strNick=false)
		{
			if ($strNick)
			{
				// TODO: Send change Nick command.
				$this->changeNick($strNick);
			}
			
			return $this->strNick;
		}

		public function __deconstruct()
		{
			$this->disconnect();
		}
	}
?>
<?php
	/**
	 * A object to connect and communicate with IRC.
	 * 
	 * @author Benjamin Jay Young <blaher@blahertech.org>
	 * @version 1.0
	 */
	class IRCphpAPI
	{
		private $rscConnection, $strServer, $intPort, $aryChannels, $strNick;
		
		public function __construct
		(
			$strServer, $intPort=6667, $strChannel=false, $strNick=false
		)
		{
			if ($this->connect($strServer, $intPort))
			{
				if ($strNick)
				{
					$this->nick($strNick);
				}

				while (!feof($rscConnection))
				{
					$strBuffer=fgets($rscConnection, 1024);
					if ($strBuffer && $strBuffer!='')
					{
						echo '[RECIVE] ',$strBuffer,"<br />\n";
					}

					if ($strChannel && strpos($strBuffer, 422))
					{
						$this->join($strChannel);
					}
					if (substr($strBuffer, 0, 6)=='PING :')
					{
						$this->send('PONG :'.substr($strBuffer, 6));
					}
					flush();
				}
			}
		}
		
		private function send($strMessage)
		{
			echo '[SEND] ',$strMessage,"<br />\n";
			@fwrite
			(
				$this->rscConnection, $strMessage."\n\r", strlen($strMessage)
			);
		}
		
		private function connect($strServer, $intPort=6667)
		{
			$this->rscConnection=@fsockopen($strServer, $intPort);
			var_dump($strServer);
			var_dump($intPort);
			if ($this->rscConnection)
			{
				$this->send('PASS NOPASS');
			}
			return $this->rscConnection;
		}
		
		private function disconnect()
		{
			
		}
		
		public function join($strChannel)
		{
			$strChannel=strtolower($strChannel);
			if (substr($strChannel, 0, 1)!='#')
			{
				$strChannel='#'.$strChannel;
			}
			
			$this->send('JOIN '.$strChannel);
			$this->aryChannels[$strChannel]=array();
		}
		
		public function quit($strChannel)
		{
			// TODO: send quit command
			unset($this->aryChannels[$strChannel]);
		}
		
		private function changeNick($strNick)
		{
			$this->strNick=$strNick;
		}
		
		public function nick($strNick=false)
		{
			if ($strNick)
			{
				$this->send('NICK '.$strNick);
				$this->send('USER '.$strNick.' USING PHP IRC');
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
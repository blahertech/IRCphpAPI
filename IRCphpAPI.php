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
			$strServer, $intPort=6667, $strNick='IRCphpAPI', $strChannel=false
		)
		{
			$intMsgSize=256;
			
			if ($this->connect($strServer, $intPort))
			{
				$this->nick($strNick);

				$strBuffer=fgets($this->rscConnection, $intMsgSize);
				$this->strServer=substr
				(
					$strBuffer, 1, strpos($strBuffer, ' ')-1
				);

				$bolJoin=false;
				while (!feof($this->rscConnection))
				{
					$strBuffer=$this->get($intMsgSize);

					if ($this->error(433, $strBuffer))
					{
						if (!isset($i))
						{
							$i=2;
						}
						else
						{
							$i++;
						}
						$this->nick($strNick.$i);
					}

					if ($strChannel && $this->error(422, $strBuffer))
					{
						$bolJoin=true;
						$this->join($strChannel);
					}
					
					$this->refresh($strBuffer);
					if ($bolJoin || !$strChannel)
					{
						break;
					}
				}
				
				while (!feof($this->rscConnection))
				{
					$strBuffer=$this->get($intMsgSize);
					$this->refresh($strBuffer);
					if (!$this->process($strBuffer))
					{
						break 2;
					}
				}
			}
		}
		
		private function refresh($strBuffer)
		{
			if (substr($strBuffer, 0, 6)=='PING :')
			{
				$this->send('PONG :'.substr($strBuffer, 6));
			}
			flush();
		}
		
		public function process($strBuffer)
		{
			if (strpos($strBuffer, ' :!kill'))
			{
				return false;
			}
			return true;
		}

		private function send($strMessage)
		{
			echo '[SEND] ',$strMessage,"<br />\n";
			$strMessage.="\n\r";
			@fwrite
			(
				$this->rscConnection, $strMessage, strlen($strMessage)
			);
		}
		
		private function get($intMsgSize)
		{
			$strBuffer=fgets($this->rscConnection, $intMsgSize);
			if ($strBuffer && $strBuffer!='')
			{
				echo '[RECIVE] ',$strBuffer,"<br />\n";
			}
			return $strBuffer;
		}

		private function connect($strServer, $intPort=6667)
		{
			$this->rscConnection=@fsockopen
			(
				$strServer, $intPort, $intError, $strError, 2
			);
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
				$this->send('USER '.$strNick.' USING IRCphpAPI IRC');
				$this->changeNick($strNick);
			}

			return $this->strNick;
		}

		private function error($intError, &$strBuffer)
		{
			$strError=':'.$this->strServer.' '.$intError;

			if (substr($strBuffer, 0, strlen($strError))==$strError)
			{
				return true;
			}
			else
			{
				return false;
			}
		}

		public function __destruct()
		{
			$this->disconnect();
		}
	}
?>
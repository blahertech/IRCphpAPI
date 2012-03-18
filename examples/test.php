<?php
	$strVar=':Blaher!blaher@ChatSpike-fbdc21e8.akrnoh.sbcglobal.net PRIVMSG #blahertech :!kills';
	$strVar=substr($strVar, strpos($strVar, ' PRIVMSG ')+9);
	$strVar=substr($strVar, strpos($strVar, ' ')+1);
	
	echo $strVar;
?>
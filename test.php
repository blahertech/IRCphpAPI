<?php
//First lets set the timeout limit to 0 so the page wont time out.
//set_time_limit(0);
//Also inclue our config file
//The server host is the IP or DNS of the IRC server.
$server_host = "irc.chatspike.net";
//Server Port, this is the port that the irc server is running on. Deafult: 6667
$server_port = 6667;
//Server Chanel, After connecting to the IRC server this is the channel it will join.
$server_chan = "#blahertech"; 
//Second lets grab our data from our form.
$nickname = $_POST['nick'];
//Now lets check to see if there is a nickname set.
if(empty($nickname))
{
    //Whoops we dont have a nickname set. 
    echo "<form name=\"form1\" method=\"post\" action=\"./test.php\">\n\r";
    echo "<p align=\"center\">Please Insert a Nickname.\n\r";
    echo "<input type=\"text\" name=\"nick\"> \n\r";
    echo "</p>\n\r";
    echo "<p align=\"center\">\n\r";
    echo "<input type=\"submit\" name=\"Submit\" value=\"Join IRC\">\n\r";
    echo "</p>\n\r";
    echo "</form>\n\r";
}
else
{
    //Ok, We have a nickname, now lets connect.
    $server = array(); //we will use an array to store all the server data.
    //Open the socket connection to the IRC server
    $server['SOCKET'] = @fsockopen($server_host, $server_port, $errno, $errstr, 2);
    if($server['SOCKET'])
    {
        //Ok, we have connected to the server, now we have to send the login commands.
        SendCommand("PASS NOPASS\n\r"); //Sends the password not needed for most servers
          SendCommand("NICK $nickname\n\r"); //sends the nickname
          SendCommand("USER $nickname USING PHP IRC\n\r"); //sends the user must have 4 paramters
        while(!feof($server['SOCKET'])) //while we are connected to the server
        {
            $server['READ_BUFFER'] = fgets($server['SOCKET'], 1024); //get a line of data from the server
            echo "[RECIVE] ".$server['READ_BUFFER']."<br>\n\r"; //display the recived data from the server
            
            /*
            IRC Sends a "PING" command to the client which must be anwsered with a "PONG"
            Or the client gets Disconnected
            */
            //Now lets check to see if we have joined the server
            if(strpos($server['READ_BUFFER'], "422")) //422 is the message number of the MOTD for the server (The last thing displayed after a successful connection)
            {
                //If we have joined the server
                
                SendCommand("JOIN $server_chan\n\r"); //Join the chanel
            }
            if(substr($server['READ_BUFFER'], 0, 6) == "PING :") //If the server has sent the ping command
            {
                SendCommand("PONG :".substr($server['READ_BUFFER'], 6)."\n\r"); //Reply with pong
                //As you can see i dont have it reply with just "PONG"
                //It sends PONG and the data recived after the "PING" text on that recived line
                //Reason being is some irc servers have a "No Spoof" feature that sends a key after the PING
                //Command that must be replied with PONG and the same key sent.
            }
            flush(); //This flushes the output buffer forcing the text in the while loop to be displayed "On demand"
        }
    }
}
function SendCommand ($cmd)
{
    global $server; //Extends our $server array to this function
    @fwrite($server['SOCKET'], $cmd, strlen($cmd)); //sends the command to the server
    echo "[SEND] $cmd <br>"; //displays it on the screen
}
?> 
<?php
class xmlClass {
    //Database settings
    private $db_host = "localhost";
    private $db_name = "xmltest";
    private $db_user = "root";
    private $db_password = "root";


    private function dbConnect() {
        mysql_connect($this->db_host, $this->db_user, $this->db_password) or die('Failed to connect to database.<br>'.mysql_error());
        mysql_select_db($this->db_name) or die('Failed to select database.<br>'.mysql_error());
    }
    
    public function addFeed($location, $name) {
        //Check data
        if((!isset($location) && (strpos($location, "http") != 0)) && (!isset($name) && $name == "")){
            echo 'incorrect parameter';
            break;
        }
        
        //DB connect
        $this->dbConnect();
        
        $query = "SELECT location, name FROM feeds WHERE location = '$location' or name = '$name'";
        $result = mysql_query($query);
        if (mysql_num_rows($result) > 0) {
            echo "Location or Name already exists.";
            break;
        }
        
        $query = "SELECT MAX(position) FROM feeds";
        $result = mysql_query($query) or die("Error in checking position");
        $positon = (mysql_result($result, 0)) + 1;
        mysql_query("INSERT INTO feeds (location, name, position) VALUES ('$location', '$name', '$positon')") or die('Failed to insert new item in database.<br>'.mysql_error());
        
    }
    
    public function changeFeedPosition($id, $newPosition) {
        //DB connect
        $this->dbConnect();
        
        $query = "SELECT position FROM feeds WHERE id = $id";
        $result = mysql_query($query) or die("Error in checking position");
        $currentPosition = mysql_result($result, 0);
 
	if($newPosition < $currentPosition) {
                $update = mysql_query("UPDATE feeds SET position = 999999 WHERE id = $id") or die(mysql_error());
		$update1 = mysql_query("UPDATE feeds SET position = position+1 WHERE position >= $newPosition AND position < $currentPosition ORDER BY position DESC") or die(mysql_error());
                $update2 = mysql_query("UPDATE feeds SET position = $newPosition WHERE id = $id") or die(mysql_error());
                
                
	} else {
                $update = mysql_query("UPDATE feeds SET position = 999999 WHERE id = $id") or die(mysql_error());
		$update1 = mysql_query("UPDATE feeds SET position = position-1 WHERE position <= $newPosition AND position > $currentPosition ORDER BY position ASC") or die(mysql_error());
                $update2 = mysql_query("UPDATE feeds SET position = $newPosition WHERE id = $id") or die(mysql_error());
	}
    }
    
    
}

?>

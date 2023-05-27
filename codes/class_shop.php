<?php
class shop{
    function __construct($uid){
        $this->ifshopexist = false;
        $this->uid = $uid;
        $this->sid = -1; // means the user is not shop keeper
        $conn = OpenCon('shop');
        $stmt=$conn->prepare("select sid,name,category,ST_AsText(Location) as Location from shop where uid=:uid");
        $stmt->execute(array('uid' => $uid));
        if ($stmt->rowCount()==1){
            $row=$stmt->fetch();
            $this->ifshopexist = true;
            $this->sid=$row['sid']; 
            $this->name=addslashes($row['name']);
            $this->category=addslashes($row['category']);
            $Location=$row['Location'];
            $Location1=str_replace("POINT(","",$Location);
            $Location1=str_replace(")","",$Location1);
            $NewStringn = preg_split("[\s]", $Location1);
            $this->longitude = $NewStringn[0];
            $this->latitude = $NewStringn[1];
        }
        CloseCon($conn);
    }
}
?>
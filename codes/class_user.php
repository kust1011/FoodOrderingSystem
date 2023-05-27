<?php
class user{
    function __construct($val1){
        $this->uid = $val1;
        $conn = OpenCon('sign_up');
        $stmt=$conn->prepare("select name,Account,PhoneNumber,ST_AsText(Location) as Location,role,walletbalance from user where uid=:uid");
        $stmt->execute(array('uid' => $this->uid));
        $row=$stmt->fetch();
        $this->name = $row['name']; 
        $this->Account = $row['Account'];
        $this->PhoneNumber = $row['PhoneNumber'];
        $Location=$row['Location'];
        $Location1=str_replace("POINT(","",$Location);
        $Location1=str_replace(")","",$Location1);
        $NewStringn = preg_split("[\s]", $Location1);
        $this->longitude = $NewStringn[0];
        $this->latitude = $NewStringn[1]; 
        $this->role = $row['role'];
        $this->walletbalance = $row['walletbalance'];
        CloseCon($conn);
    }
}
?>
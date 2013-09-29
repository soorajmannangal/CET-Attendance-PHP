<?php
class cetat {
private $cetat_handle;
private function post($url, $data, $r=false) {
$this->cetat_handle=curl_init($url);
if( ! file_exists("cookie.txt")) {
$file=fopen("cookie.txt", 'w'); fclose($file);
}
curl_setopt($this->cetat_handle, CURLOPT_COOKIEFILE, "cookie.txt");
curl_setopt($this->cetat_handle, CURLOPT_COOKIEJAR, "cookie.txt");
curl_setopt($this->cetat_handle, CURLOPT_ENCODING, "gzip");
curl_setopt($this->cetat_handle, CURLOPT_FOLLOWLOCATION, true);
curl_setopt($this->cetat_handle, CURLOPT_HEADER, true);
curl_setopt($this->cetat_handle, CURLOPT_HTTPHEADER, array("Connection: Keep-Alive", "Content-type: application/x-www-form-urlencoded", "Keep-Alive: 300"));
curl_setopt($this->cetat_handle, CURLOPT_POST, true);
curl_setopt($this->cetat_handle, CURLOPT_POSTFIELDS, $data);
if($r) curl_setopt($this->cetat_handle, CURLOPT_REFERER, $r);
curl_setopt($this->cetat_handle, CURLOPT_RETURNTRANSFER, true);
curl_setopt($this->cetat_handle, CURLOPT_SSL_VERIFYHOST, 2);
curl_setopt($this->cetat_handle, CURLOPT_USERAGENT, "iPhone 4.0");
$return=curl_exec($this->cetat_handle);
curl_close($this->cetat_handle);
return $return;
}
public function attandance($userid, $password) {
$page=$this->post("http://117.211.100.44:8080/index.php", "userid=".$userid."&password=".$password."&Submit=Submit");
$page=$this->post("http://117.211.100.44:8080/index.php", "module=com_views&task=student_attendance_view");
$line = preg_split("/(\r\n|\n|\r)/", $page);
$flag = 0;
echo "<html><body>College of Engineering Trivandrum<br>----<br>";
for( $i=0; $i < count($line);$i++){
	if($flag == 0)
		{
			if(preg_match('/Logged in as./',$line[$i]))
			{
				$i=$i+2;	
				if( preg_match("/<td>(.+?)</",$line[$i],$name))
				{
					echo "Student Name : ".$name[1]."<br>";
					$flag = 1;
				}
			}
		}
		else
		{
			if(preg_match("/Attendance till date :(.+?)%/",$line[$i],$attandance))
				{
					echo 'Current  Attendance : '.$attandance[1].'%';				
					break;
				}
		}				
}
//echo "<br><br><br><br>--vCompile Innovations--</body></html>";
if(file_exists('cookie.txt')) unlink('cookie.txt');
}
}
$att=new cetat();
$msg = $_GET['message'];
$msg = urldecode($msg);
$content=explode(" ",$msg);
$uid=$content[0];
$pswd=$content[1];
$att->attandance($uid,$pswd);
sleep(7);

?>
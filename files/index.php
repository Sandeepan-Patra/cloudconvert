<?php
//create a page for file convert. using (cloudconvert.com).
error_reporting(0);
 require __DIR__ . '/../vendor/autoload.php';
    use \CloudConvert\Api;
     
    $api = new Api("X7subgZxJTswC8q4UGRx6JgbvlDoswV26xFvIEdgLqk1HXAAfF7WmgHHHRV37St5");
     
	if(isset($_POST['submit'])){
      //Upload the file to the server and send it to cloudconvert apis for conversion.
      $file_name = "doc".rand(100,1000).date("YmdHis");
      
      $file_tmp =$_FILES['doc']['tmp_name'];
      $file_type=$_FILES['doc']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['doc']['name'])));
      
      $extensions= array("docx","doc");
      
      if(in_array($file_ext,$extensions)=== false){
        echo "extension not allowed, please choose a docx file.";
		exit;
      }
		$fil_name = $file_name.".".$file_ext;
		if(move_uploaded_file($file_tmp,"doc/".$fil_name)){  //1.1. Upload the file to the server
		 
			 $process = $api->convert([
				"inputformat" => "docx",
				"outputformat" => "pdf",
				"input" => "upload",
				//"callback" => "test.php", 
				"save" => true,
				"file" => fopen("doc/".$fil_name, 'r'), 
			]);
	
			$process->download('inline');
		
			$process->refresh(); // fetch status from API
		
			$process->wait(); // wait until conversion completed

			$statu = $process->message; // access process data 1.2. send it to cloudconvert apis for conversion.
			$date_m = date("Y-m-d H:i:s");
			if( $statu == "Conversion finished!"){
				class MyDB extends SQLite3 {
				    function __construct() {
						$this->open('sqllite.db');
					}
			   }
   
			   $db = new MyDB();
			   if(!$db){
				  echo $db->lastErrorMsg();
			   } else {
				 // echo "Opened database successfully\n";
			   }

   $sql =<<<EOF
      INSERT INTO files (file_name,status,date_time)
      VALUES ('$fil_name','$statu', '$date_m' );
EOF;

   $ret = $db->exec($sql); // 4. Store the details of the file converted in a sqlite db.
   if(!$ret) {
      echo $db->lastErrorMsg();
   } else {
      echo "Converted Succssfully\n";
	  ?>
	 <script> 
       
            window.open( 
              "view_last.php", "_blank");  //2. Once converted return the file to the front end.
        
    </script> 
	<?php
   }
   $db->close();
			}
		}	
     
	 
   }
?>
<html>
   <body>
      
      <form action="" method="POST" enctype="multipart/form-data">
         <input type="file" name="doc" />
         <input type="submit" name="submit" value="Submit"/>
      </form>
      
   </body>
</html>
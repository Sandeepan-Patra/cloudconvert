<style>
table, td, th {
  border: 1px solid black;
}

table {
  width: 100%;
  border-collapse: collapse;
}
</style>
<?php
//Create a page to show all the converted files and its status.
/*
   class MyDB extends SQLite3 {
      function __construct() {
         $this->open('sqllite.db');
      }
   }
   $db = new MyDB();
   if(!$db) {
      echo $db->lastErrorMsg();
   } else {
      echo "Opened database successfully\n";
   }

   $sql =<<<EOF
      CREATE TABLE files
      (
      file_name           TEXT    NOT NULL,
	  status           TEXT    NOT NULL,
      date_time           TEXT    NOT NULL);
EOF;

   $ret = $db->exec($sql);
   if(!$ret){
      echo $db->lastErrorMsg();
   } else {
      echo "Table created successfully\n";
   }
   $db->close();
   */
   
   class MyDB extends SQLite3 {
				    function __construct() {
						$this->open('sqllite.db');
					}
			   }
   
			   $db = new MyDB();
			   if(!$db){
				  echo $db->lastErrorMsg();
			   } else {
				  //echo "Opened database successfully\n";
			   }
   
   $sql =<<<EOF
      SELECT * from files;
EOF;

   $ret = $db->query($sql);
   echo "<table>";
   echo "<th>File Name</th><th>Status</th><th>Date Time</th>";
   while($row = $ret->fetchArray(SQLITE3_ASSOC) ) {
	  echo "<tr>";
      
      echo "<td>". $row['file_name'] ."</td>";
      echo "<td>". $row['status'] ."</td>";
      echo "<td>". $row['date_time'] ."</td>";
	  echo "</tr>";
     
   }
    echo "</table>";
	
 
   $db->close();
			
?>
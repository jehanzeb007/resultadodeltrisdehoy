<?php

    include "db.php";
    
    $result = array();
    $data = array();
    $query = '';
    
    
    

 if(isset($_GET["date"]) && $_GET["date"] !="") {
    
    $query = "SELECT * FROM `tbl_loterianacional` WHERE result_date='" . $_GET["date"] . "' ORDER BY result_date DESC LIMIT 30" ;
    $allresult=mysqli_query($con, $query);
    while($row=mysqli_fetch_array($allresult))
        {
            $row_data =array(
                'result_date' => $row['result_date'],
                'result_time' => $row['result_time'],
      		    'result_numbers' => $row['result_numbers'],
                'result_code' => $row['result_code'],
                'result_title' => $row['result_title'],
                'result_multiplier' => $row['result_multiplier']
            );
            array_push($data, $row_data);
        }
        
        if($data != null){
                $result['status'] = 200;
                $result['message'] =  'success';            
            }else{
                $result['status'] = 200;
                $result['message'] =  'Not Data Found';            
            }
            

        $result['data'] = $data;
        echo json_encode($result);
        exit();

     
 }else if (isset($_GET["result_title"]) && $_GET["result_title"] !="") {
     

    $query = "SELECT * FROM `tbl_loterianacional` WHERE result_title='" . $_GET["result_title"] . "' ORDER BY result_date DESC LIMIT 30" ;


    $allresult=mysqli_query($con, $query);
    while($row=mysqli_fetch_array($allresult))
        {
            $row_data =array(
                'result_date' => $row['result_date'],
                'result_time' => $row['result_time'],
      		    'result_numbers' => $row['result_numbers'],
                'result_code' => $row['result_code'],
                'result_title' => $row['result_title'],
                'result_multiplier' => $row['result_multiplier']
            );
            array_push($data, $row_data);
        }
        
        
        if($data != null){
                $result['status'] = 200;
                $result['message'] =  'success';            
            }else{
                $result['status'] = 200;
                $result['message'] =  'Not Data Found';            
            }
            

        $result['data'] = $data;
        echo json_encode($result);
        exit();
     
 } else{
    
    
     $query = "SELECT * FROM `tbl_loterianacional` ORDER BY result_date DESC  LIMIT 30" ;
    $allresult=mysqli_query($con, $query);
    while($row=mysqli_fetch_array($allresult))
        {
            $row_data =array(
                'result_date' => $row['result_date'],
                'result_time' => $row['result_time'],
      		    'result_numbers' => $row['result_numbers'],
                'result_code' => $row['result_code'],
                'result_title' => $row['result_title'],
                'result_multiplier' => $row['result_multiplier']
            );
            array_push($data, $row_data);
        }
    
    
    
      if($data != null){
                $result['status'] = 200;
                $result['message'] =  'success';            
            }else{
                $result['status'] = 200;
                $result['message'] =  'Not Data Found';            
            }
            

        $result['data'] = $data;
        echo json_encode($result);
        exit();
}

?>
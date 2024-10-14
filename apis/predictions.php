<?php

    include "db.php";
    
    $result = array();
    $data = array();
    $query = '';
    
    
    

 if(isset($_GET["date"]) && $_GET["date"] !="") {

    $query = " SELECT predictions.*, categories.loterianacional_map FROM predictions LEFT JOIN categories ON predictions.cat_id = categories.id WHERE predictions.date='" .  $_GET["date"] . "' ORDER BY time DESC LIMIT 30";

    $allresult=mysqli_query($con, $query);
    while($row=mysqli_fetch_array($allresult))
        {
            
            $row_data =array(
                'date' => $row['date'],
                'time' => $row['time'],
      		    'draw_numbers' => $row['draw_numbers'],
                'score' => $row['score'],
                'predic_numbers' => $row['predic_numbers'],
                'category_name' => $row['loterianacional_map']
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

     
 }else if (isset($_GET["title"]) && $_GET["title"] !="") {
     

    
    $query = " SELECT predictions.*, categories.loterianacional_map FROM predictions JOIN categories ON predictions.cat_id = categories.id WHERE categories.loterianacional_map='" .  $_GET["title"] . "' ORDER BY predictions.date DESC LIMIT 30";


    $allresult=mysqli_query($con, $query);
    while($row=mysqli_fetch_array($allresult))
        {
            
            $row_data =array(
                'date' => $row['date'],
                'time' => $row['time'],
      		    'draw_numbers' => $row['draw_numbers'],
                'score' => $row['score'],
                'predic_numbers' => $row['predic_numbers'],
                'category_name' => $row['loterianacional_map']
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
    
    
    $query = " SELECT predictions.*, categories.loterianacional_map FROM predictions LEFT JOIN categories ON predictions.cat_id = categories.id ORDER BY date DESC  LIMIT 30";

  
    $allresult=mysqli_query($con, $query);
    while($row=mysqli_fetch_array($allresult))
        {
            
            $row_data =array(
                'date' => $row['date'],
                'time' => $row['time'],
      		    'draw_numbers' => $row['draw_numbers'],
                'score' => $row['score'],
                'predic_numbers' => $row['predic_numbers'],
                'category_name' => $row['loterianacional_map']
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
<?php
        
    $data = file_get_contents('php://input');
    $obj =  json_decode($data);
    include("functions.php");
    
    $resultNumbers = array();
    
    if($obj != null){
        
        
        $operations = new Operations();
        
        foreach($obj as $row) {
            
            $result_array = explode('/', $row->result_date);
            $result_date = $result_array[2] ."-". $result_array[1] ."-". $result_array[0];
            $result_time = $row->result_time ; 
            
            $result_numbers = '';
            $result_code = '';
            $result_title = '';
            $result_multiplier = '';
            
            foreach($row->results as $num) {   
                
                $digitsArray = str_split((string)$num->result_numbers);
                $result = implode(' ', $digitsArray);
                $result_numbers = explode(' ', $result);
                
                $result_code = $num->result_code;
                $result_title = $num->result_title;
                $result_multiplier = $num->result_multiplier;
                
                $cat_id = $operations->getCategoryId("loterianacional_map",$result_title);
            
                $tableData = array(
                    'result_date' => $result_date,
                    'result_time' => $result_time,
                    'result_numbers' => json_encode($result_numbers),
                    'result_code' => $result_code,
                    'result_title' => $result_title,
                    'cat_id' => $cat_id,
                    'result_multiplier' => $result_multiplier
                );
                
                array_push($resultNumbers, $tableData);     
    
                $result = $operations->insert('tbl_loterianacional', $tableData);

            }

        }
    
        echo json_encode($resultNumbers);
        exit();
        
    }
    
    
    
?>    
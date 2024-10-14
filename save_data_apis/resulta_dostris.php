<?php
        
    $data = file_get_contents('php://input');
    $obj =  json_decode($data);
    include("functions.php");
    
    $resultNumbers = array();
    
    if($obj != null){
        
        
        $operations = new Operations();
        
        foreach($obj as $row) {
            
            $result_date = $row->div_date;
            $result_title = $row->div_title ; 
            
            $result_numbers = '';
            $result_code = '';
            $result_type = '';
            foreach($row->results as $num) {   
                
                $result_numbers = explode(' ', $num->result_numbers);
                $result_code = $num->result_code;
//                $result_type = $num->result_type;
                $cat_id = $operations->getCategoryId("resultadostris_map",$num->result_type);
            
                $tableData = array(
                    'result_date' => $result_date,
                    'result_title' => $result_title,
                    'result_numbers' => json_encode($result_numbers),
                    'result_code' => $result_code,
                    'cat_id' => $cat_id
                );
                
                array_push($resultNumbers, $tableData);     
    
                $result = $operations->insert('tbl_resulta_dostris', $tableData);

            }

        }
    
        echo json_encode($resultNumbers);
        exit();
        
    }
    
    
    
?>    
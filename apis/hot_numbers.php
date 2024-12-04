<?php

    include "db.php";
    
    
    $date_30_days_ago = date('Y-m-d',strtotime('-30 days'));
    
    
    function array_sort_by_column(&$arr, $col, $dir = SORT_ASC) {
        $sort_col = array();
        foreach ($arr as $key => $row) {
            $sort_col[$key] = $row[$col];
        }
        array_multisort($sort_col, $dir, $arr);
    }
    
    
   function time_elapsed_string($datetime) {
        $now = new DateTime;
        $ago = new DateTime($datetime);
        $diff = $now->diff($ago);
    
        $diff->w = floor($diff->d / 7);
        $diff->d -= $diff->w * 7;
    
        $string = array(
            'd' => 'day',
            'h' => 'hour',
        );
    
        $elapsed = '';
        foreach ($string as $k => &$v) {
            if ($diff->$k) {
                $elapsed = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '') . ' ago';
                break; // Stop further breakdown
            }
        }
    
        return $elapsed ? $elapsed : 'just now';
    }
    
    if (isset($_GET["title"]) && $_GET["title"] !="") {
        
        
        $query = "SELECT id FROM categories WHERE loterianacional_map='" . $_GET["title"] . "'";
        $category_id = '';
        $allresult = mysqli_query($con, $query);
        while ($row = mysqli_fetch_array($allresult)) {
            $category_id = $row['id'];
        }
        

        $query_results = "Select * From tbl_loterianacional where cat_id = '". $category_id ."' and result_date >= '".$date_30_days_ago."' order by result_date asc";
        $result_results = mysqli_query($con, $query_results);
        
        $data_numbers = [];
        while ($row=mysqli_fetch_array($result_results)){
            $result_numbers = json_decode($row['result_numbers'],1);
            foreach ($result_numbers as $number){
        
                $data_numbers[$number]['number'] = $number;
                $data_numbers[$number]['drawn'] = $data_numbers[$number]['drawn']+1;
                $data_numbers[$number]['last_come'] = strtotime($row['result_date']);
            }
        }
        array_sort_by_column($data_numbers, 'drawn',SORT_DESC);
        $data_numbers_by_number = [];
        foreach ($data_numbers as $data_number_row){
            $data_numbers_by_number[$data_number_row['drawn']][] = $data_number_row;
        }
        $data_numbers_by_number_sort = [];
        foreach ($data_numbers_by_number as $draw=>$data_numbers_by_number_arr){
            array_sort_by_column($data_numbers_by_number_arr, 'last_come',SORT_DESC);
            $data_numbers_by_number_sort[] = $data_numbers_by_number_arr;
        }
        
        $data_numbers = [];
        foreach ($data_numbers_by_number_sort as $data_arr){
            foreach ($data_arr as $numbers_arr){
                $data_numbers[] = $numbers_arr;
            }
        }
        $pieces_data_numbers = array_chunk($data_numbers, ceil(count($data_numbers) / 2));
    
    
        $data = [];
        
        foreach ($pieces_data_numbers[0] as $number_data){
            $row = array(
                "number" =>   $number_data['number'],
                "drawn" =>   $number_data['drawn'],
                "last_drawn" =>  time_elapsed_string(date('Y-m-d',$number_data['last_come']))
            );
            
            array_push($data, $row);
        }
    
    
        if($data_numbers != null){
            $result['status'] = 200;
            $result['message'] =  'success';            
        }else{
            $result['status'] = 200;
            $result['message'] =  'Not Data Found';            
        }
            
        $result['data'] = $data;
        echo json_encode($result);
        exit();
    

    }else{
        
        $result['status'] = 200;
        $result['message'] =  'No parameter passed.';            
        $result['data'] = null;
        echo json_encode($result);
        exit();
    }

        


?>
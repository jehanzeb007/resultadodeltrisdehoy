<?php

    include "db.php";
    
    $result = array();
    $data = array();
    
    
    if (isset($_GET["result_title"]) && $_GET["result_title"] !="") {
        
        
        $query = "SELECT id FROM categories WHERE loterianacional_map='" . $_GET["result_title"] . "'";
        $category_id = '';
        $allresult = mysqli_query($con, $query);
        while ($row = mysqli_fetch_array($allresult)) {
            $category_id = $row['id'];
        }
        
        $limit = 20;
        $getQuery = "Select result_date From tbl_loterianacional where cat_id = '". $category_id ."'";
        $total_rows = mysqli_num_rows(mysqli_query($con, $getQuery));
        
        $total_pages = ceil ($total_rows / $limit);
        
        $page_number = 1;
        
        if (isset($_GET['page'])) {
            $page_number = $_GET['page'];
        }

        $initial_page = ($page_number-1) * $limit;
        
        $query_results = "Select * From tbl_loterianacional where cat_id = '". $category_id ."' order by result_date desc LIMIT $initial_page, $limit";
        $result_results = mysqli_query($con, $query_results);

        while($row=mysqli_fetch_array($result_results))
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
            $result['total_pages'] =  $total_pages;    
            $result['current_page'] =  $page_number;    
            $result['limit'] =  $limit;    
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
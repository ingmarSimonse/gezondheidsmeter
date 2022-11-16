


<?php

 

    function TableData($SearchTable, $searchon){

        global $link;
    if (isset($_GET['page']))
    {
        $page = $_GET['page'];
    }
    else
    {
        $page = 1;
    }

    
            $LimitPerPage = 7;
        $offset = ($page - 1) * $LimitPerPage;
        // haal de user_list op zet hem in een array
        $UserId = $_SESSION["id"];
        $userLists = array();
        if($SearchTable == false){
            $ResultUserlist = $link->query("SELECT user_list.id, DATE(user_list.created_at)   FROM user_list WHERE user_list.user_id = $UserId ORDER BY user_list.created_at DESC  LIMIT $LimitPerPage OFFSET  $offset");

        }else{
            $ResultUserlist = $link->query("SELECT user_list.id, DATE(user_list.created_at)   FROM user_list WHERE user_list.user_id = $UserId AND user_list.created_at LIKE '$searchon%' ORDER BY user_list.created_at DESC LIMIT $LimitPerPage OFFSET  $offset");

        }
        if ($ResultUserlist->num_rows > 0) {
            while ($row = $ResultUserlist->fetch_assoc()) {

                    array_push($userLists, $row);
            }
        }


        
        $res = $link->query("SELECT count(*) FROM user_list WHERE user_list.user_id = $UserId");
        $records = $res->fetch_array();
        $totalpages = ceil($records[0] / $LimitPerPage);


        for ($i = 0; $i < count($userLists); $i++) {
            $ArbeidsomstandighedenPoints = null;
            $SportenBewegenPoints  = null;
            $voedingPoints  = null;
            $DrugsPoints  = null;
            $SlaapPoints = null;
            $UserListId = $userLists[$i]['id'];



            $result = $link->query("SELECT  option.points, category.name FROM  user_answer  INNER JOIN `option` on user_answer.option_id = option.id INNER JOIN question ON option.question_id = question.id INNER JOIN category ON question.category_id = category.id WHERE user_answer.user_list_id = $UserListId");

            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    if($row["name"] == "Arbeidsomstandigheden"){
                        $ArbeidsomstandighedenPoints  = $ArbeidsomstandighedenPoints + $row["points"];
                    }
                    elseif($row["name"] == "Sport en bewegen"){
                        $SportenBewegenPoints  = $SportenBewegenPoints + $row["points"];
                    }
                    elseif($row["name"] == "Voeding"){
                        $voedingPoints  = $voedingPoints + $row["points"];
                    }
                    elseif($row["name"] == "Drugs"){
                        $DrugsPoints  = $DrugsPoints + $row["points"];
                        
                    }
                    elseif($row["name"] == "Slaap"){
                        $SlaapPoints  = $SlaapPoints + $row["points"];
                    }

                    
                } 
            }
   
            $userLists[$i] += array("Arbeidsomstandigheden" =>  $ArbeidsomstandighedenPoints) + array("SportenBeweging" =>  $SportenBewegenPoints) + array("Drugs" =>  $DrugsPoints) 
            + array("Voeding" =>  $voedingPoints) + array("Slaap" =>  $SlaapPoints);


        }

        $userLists += array("totalpages" =>  $totalpages);


        return $userLists;
    }



    






 



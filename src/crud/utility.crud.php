<?php
function rs_to_tab($rs){
    $tab=[];
    while($row=mysqli_fetch_assoc($rs)){
        $tab[]=$row;
    }
    return $tab;
}


?>
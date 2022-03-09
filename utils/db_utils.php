<?php
    function delete_table(mysqli $conn, string $table_name): void{
        $query = "drop table `".$table_name."`;";
        if($conn->query($query)){
            // echo "[*] Table deleted succesessfully!<br>";
        }
        else{
            die("[!] Error deleting table! " . $conn->error);
        }
    }



    function insert_into_table(mysqli $conn, string $table_name, array $map): int{
        $fields = array_keys($map);
        $values = array_values($map);
        
        $query = "insert into `".$table_name."` ("
        .implode(",", $fields)
        .") values (";
        
        for($i = 0; $i < count($fields); ++$i){
            $val = $values[$i];
            if(is_string($val)){
                $val = "'".$val."'";
            }else if(is_bool($val)){
                $val = $val ? "true" : "false";
            }

            if($i == count($fields)-1){
                $query .= $val;
            }else{
                $query .= $val.",";
            }
        }
        
        $query .= ");";

        if($conn->query($query)){
            // echo "[*] Inserted succesessfully!<br>";
            return $conn->insert_id;
        }
        else{
            throw new Exception("[!] Error inserting into table! " . $conn->error);
        }
    }

    function select_from_table(mysqli $conn, string $table_name, array $fields = null, string $where = null): mysqli_result{
        $query = "select ";
        
        if($fields){
            for($i = 0; $i < count($fields); ++$i){
                $field = $fields[$i];
                
                if($i == count($fields)-1){
                    $query .= $field;
                }else{
                    $query .= $field.",";
                }
            }
        }else{
            $query .= "*";
        }
        
        
        $query .= " from `".$table_name."`";

        if($where != null){
            $query .= " where ".$where;
        }

        $query .= ";";

        $res = $conn->query($query);
        if($res){
            // echo "[*] Selected succesessfully!<br>";
            return $res;
        }
        else{
            throw new Exception("[!] Error selecting from table! " . $conn->error);
        }
    }

    function update_table(mysqli $conn, string $tablename, array $map, string $where): void{
        $fields = array_keys($map);
        $values = array_values($map);
        
        $query = "update `".$tablename."` set ";
        
        for($i = 0; $i < count($fields); ++$i){
            $val = $values[$i];
            if(is_string($val)){
                $val = "'".$val."'";
            }

            if($i == count($fields)-1){
                $query .= $fields[$i]."=".$val;
            }else{
                $query .= $fields[$i]."=".$val.",";
            }
        }
        
        $query .= " where ".$where.";";

        if($conn->query($query)){
            // echo "[*] Updated succesessfully!<br>";
        }
        else{
            throw new Exception("[!] Error updating table! " . $conn->error);
        }
    }

    function delete_from_table(mysqli $conn, string $table_name, string $where): void{
        $query = "delete from `".$table_name."` where ".$where.";";

        if($conn->query($query)){
            // echo "[*] Deleted succesessfully!<br>";
        }
        else{
            throw new Exception("[!] Error deleting from table! " . $conn->error);
        }
    }
?>

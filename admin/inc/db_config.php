<?php

    $hostName = 'localhost';
    $userName = 'root';
    $password = '';
    $db = 'homes_db';

    $con = mysqli_connect($hostName, $userName, $password, $db);

    if(!$con){
        die("Not Connected to Database " . mysqli_connect_error());
    }

    function filteration($data)
    {
        foreach($data as $key => $value){
            $value = trim($value);             // removes whitespace (spaces, tabs, newlines)
            $value = stripslashes($value);    // removes backslashes (\) 
            $value = htmlspecialchars($value); // converts special HTML characters         Ex: stripcslashes("john\'s") to "john's".
            $value = strip_tags($value);       // removes HTML and PHP tags from a string. Ex: strip_tags("<b>Bold</b>") to "Bold".
            
            $data[$key] = $value;    
        }
        return $data;
    }

    function selectAll($table){
        $con = $GLOBALS['con'];
        $res = mysqli_query($con, "SELECT * FROM $table");
        return $res;
    }

    function select($sql, $values, $datatypes)
    {
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con, $sql))
        {
            mysqli_stmt_bind_param($stmt, $datatypes,...$values);
            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_get_result($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            }
            else{
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Select");
            }
        }
        else{
            die("Query cannot be prepared - Select");
        }
    }

    function update($sql, $values, $datatypes)
    {
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con, $sql))
        {
            mysqli_stmt_bind_param($stmt, $datatypes,...$values);
            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            }
            else{
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Update");
            }
        }
        else{
            die("Query cannot be prepared - Update");
        }
    }

    function insert($sql, $values, $datatypes)
    {
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con, $sql))
        {
            mysqli_stmt_bind_param($stmt, $datatypes,...$values);
            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            }
            else{
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Insert");
            }
        }
        else{
            die("Query cannot be prepared - Insert");
        }
    }

    function delete($sql, $values, $datatypes)
    {
        $con = $GLOBALS['con'];
        if($stmt = mysqli_prepare($con, $sql))
        {
            mysqli_stmt_bind_param($stmt, $datatypes,...$values);
            if(mysqli_stmt_execute($stmt)){
                $res = mysqli_stmt_affected_rows($stmt);
                mysqli_stmt_close($stmt);
                return $res;
            }
            else{
                mysqli_stmt_close($stmt);
                die("Query cannot be executed - Delete");
            }
        }
        else{
            die("Query cannot be prepared - Delete");
        }
    }


?>

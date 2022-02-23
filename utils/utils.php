<?php

function get_default(array $array, $key, $default = null){
    // a default value is returned if the key is not found
    // if $deufault is not provided and key is not found, null is returned

    if(isset($array[$key]))
        return $array[$key];
    
    else
        return $default;
}

function get_config(bool $force = false){
    if(!isset($GLOBALS["config"]) || $force){
        require_once "config.php";
        $GLOBALS["config"] = read_config();
    }
    return $GLOBALS["config"];
}

?>
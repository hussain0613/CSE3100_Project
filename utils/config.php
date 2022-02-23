<?php
    $script_dir = dirname(__FILE__);
    
    function read_config(): array{
        // read the config file
        // return the config array

        $script_dir = dirname(__FILE__);

        $fn = $script_dir."/../server_settings.json";
        $config_file = fopen($fn, "r") or throw new Exception("[!] Error opening config file: $fn");

        $config_json = fread($config_file, filesize($fn));
        fclose($config_file);

        return json_decode($config_json, true);
    }
?>

<?php

    // Real simple registry class
    Class RpcConfig
    {
        var $handlers = array();

        function RpcConfig()
        {
            $this->handlers["mapping"] = array();
            $this->handlers["library"] = array();
        }

        function addMapping($mapping)
        {
            if(is_array($mapping))
            {
                $this->handlers["mapping"] = $this->handlers["mapping"] + $mapping;
            }
        }

        function addLibrary($library)
        {
            $this->handlers["library"][] = $library;
        }
    }

?>

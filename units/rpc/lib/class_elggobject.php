<?php
    Class ElggObject
    {
        var $ident;

        function ElggObject()
        {
        }

        /**
         * Get the object's id
         *
         * @return int The object id.
         */
        function getIdent()
        {
            return $this->ident;
        }
    }
?>

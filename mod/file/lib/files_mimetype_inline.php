<?php

    // Determines whether or not a file should be displayed inline (false if not, the mime-type if true)
    // $parameter = the file location
    
    /*    $mimetype = mime_content_type($parameter);
        
        if (in_array($mimetype,$data['mimetype:inline'])) {
            $run_result = $mimetype;
        } else {
            $run_result = false;
        } */
        $result = @getimagesize($parameter);
        if ($result != false) {
            $run_result = image_type_to_mime_type($result[2]);
        } else {
            $run_result = false;
        }

?>
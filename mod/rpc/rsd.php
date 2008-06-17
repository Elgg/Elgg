<?php

    // Really Simple Discovery (RSD)
    // see: <http://archipelago.phrasewise.com/rsd>
    //
    // (this is old stuff, is it being used anymore?!?)

    include "../../includes.php";

    header("Content-type: text/xml");

    $blog_id =  optional_param('user_id',0,PARAM_INT);
    if (!empty($blog_id)) {
        $username = user_info('username', $blog_id);
        $service_url = url . "mod/rpc/RPC2.php";
        $user_homepage = url . $username . "/weblog/";

        $xml = <<< END
<?xml version="1.0" ?>
<rsd version="1.0" xmlns="http://archipelago.phrasewise.com/rsd" >
    <service>
        <engineName>Elgg Learning Landscape</engineName> 
        <engineLink>http://elgg.org</engineLink>
        <homePageLink>$user_homepage</homePageLink>
        <apis>
            <api name="MetaWeblog" 
                    preferred="false" 
                    apiLink="$service_url" 
                    blogID="$blog_id" />
            <api name="Blogger" 
                    preferred="false" 
                    apiLink="$service_url" 
                    blogID="$blog_id" />
            <api name="MoveableType" 
                    preferred="true" 
                    apiLink="$service_url" 
                    blogID="$blog_id" />
        </apis>
    </service>
</rsd>

END;

        echo $xml;
    }

?>

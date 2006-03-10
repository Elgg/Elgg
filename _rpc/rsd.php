<?

    // Really Simple Discovery (RSD)
    // see: <http://archipelago.phrasewise.com/rsd>
    //
    // (this is old stuff, is it being used anymore?!?)

    include "../includes.php";

    header("Content-type: text/xml");

    if ($_GET["user_id"]) {
        $blog_id  = $_GET["user_id"];
        $username = run("users:id_to_name", $blog_id);
        $service_url = url . "_rpc/RPC2.php";
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

<?php

    // Parse and save language code and regions database
    // Uses Firefox language property files - thank
    // you Mozilla Foundation!

    $propFile   = 'language.properties';
    $langFile   = 'languageNames.properties';
    $regionFile = 'regionNames.properties';

    function parse_languages()
    {
        global $propFile;

        $languages = parse_language_names();
        $regions = parse_region_names();

        $parsed = array();

        $lines = file($propFile);

        foreach ($lines as $line)
        {
            $lang = explode('.', $line);

            $elements = explode('-', $lang[0]);

            $code = $elements[0];
            $name = $languages[$code];

            if (!empty($elements[1]))
            {
                $name .= '/'.$regions[$elements[1]];
                $code .= '_'.strtoupper($elements[1]);
            }
            $parsed[$code] = $name; 
        }

        return $parsed;
    }


    function parse_language_names()
    {
        global $langFile;

        $language_names = array();

        $lines = file($langFile);
        
        foreach($lines as $line)
        {
            $lang = explode('=', $line);
            $language_names[trim($lang[0])] = trim($lang[1]);
        }

        return $language_names;
    }

    function parse_region_names()
    {
        global $regionFile;

        $region_names = array();

        $lines = file($regionFile);
        
        foreach($lines as $line)
        {
            $lang = explode('=', $line);
            $region_names[trim($lang[0])] = trim($lang[1]);
        }

        return $region_names;
    }

    $data = serialize(parse_languages());
    $file = fopen('../languages.cache', 'a');
    fwrite($file, $data);
    fclose($file);
?>

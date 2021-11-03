<?php 
namespace App\Libraries;

class Utilities {
    public static function clearXSS($string)
    {
        $string = nl2br($string);
        $string = trim(strip_tags($string));
        $string = self::removeScripts($string);

        return $string;
    }

    public static function removeScripts($str)
    {
        $regex =
            '/(<link[^>]+rel="[^"]*stylesheet"[^>]*>)|' .
            '<script[^>]*>.*?<\/script>|' .
            '<style[^>]*>.*?<\/style>|' .
            '<!--.*?-->/is';

        return preg_replace($regex, '', $str);
    }

    public static function uploadFile($file)
    {
        if ($file) {
            $name = uniqid() . $file->getClientOriginalName();
            $file->storeAs('public/', $name);
            return 'storage/' . $name;
        }
        return false;
    }

    public static function clearXSSArray($array, $removedElement = null)
    {
        $data = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $subArray = self::clearXSSArray($value);
                $data[$key] = $subArray;
            } else {
                if (is_file($value)) {
                    $data[$key] = self::uploadFile($value);
                } else {
                        $data[$key] = self::clearXSS($value);
                }
            }
        }
        if (!empty($removedElement)) {
            unset($data[$removedElement]);
        }
        return $data;
    }
}

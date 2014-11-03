<?php
namespace ddliu\Spider\Parser;

class ParseHelper {
    public static function extract($content, $regexp, $parts = 0) {
        if (!preg_match($regexp, $content, $match)) {
            return false;
        }

        if (!is_array($parts)) {
            return isset($match[$parts])?$match[$parts]:null;
        }

        $result = array();
        foreach ($parts as $key) {
            $result[$key] = isset($match[$key])?$match[$key]:null;
        }

        return $result;
    }

    public static function mustExtract($content, $regexp, $parts = 0) {
        $matchResult = self::extract($content, $regexp, $parts);
        if (false === $matchResult) {
            throw new ParseException('Regexp match failed: '.$regexp);
        }

        return $matchResult;
    } 

    public static function extractAll($content, $regexp, $parts = 0) {

    }

    public static function extractAll($content, $regexp, $parts = 0) {

    }

    public static function extractChain($content, $regexp) {
        
    }
}
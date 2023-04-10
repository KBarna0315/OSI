<?php

namespace utils;

class Log
{
    private static $messages = [];

    public static function addMessage($type, $message)
    {
        if (!isset(self::$messages[$type])) {
            self::$messages[$type] = [];
        }
        self::$messages[$type][] = $message;
    }

    public static function getMessages($type = null)
    {
        if ($type === null) {
            return self::$messages;
        }

        if (isset(self::$messages[$type])) {
            return self::$messages[$type];
        }

        return [];
    }
}

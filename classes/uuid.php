<?php

/**
 * This is a UUID class that generates VALID RFC 4122 COMPLIANT Universally Unique IDentifiers (UUID) version 3, 4 and 5.
 * @package MMExtranet
 * @version 1.0
 */

class uuid {
    /**
     * Generate v3 UUID
     *
     * Version 3 UUIDs are named based. They require a namespace (another
     * valid UUID) and a value (the name). Given the same namespace and
     * name, the output is always the same.
     * @param string $namespace UUID seed
     * @param string $name
     * @access public
     * @return string
     */
    final public static function v3(string $namespace, string $name): string {
        if (self::is_valid($namespace) === false) {
            return '';
        }
        else {
            $nhex = str_replace(array('-','{','}'), '', $namespace);
            $nstr = '';

            $limit = strlen($nhex);
            for ($i = 0; $i < $limit; $i += 2) {
                $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
            }

            $hash = md5($nstr . $name);

            return sprintf('%08s-%04s-%04x-%04x-%12s', substr($hash, 0, 8), substr($hash, 8, 4), (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x3000, (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000, substr($hash, 20, 12));
        }
    }

    /**
     * Generate v4 UUID
     * @access public
     * @return string
     */
    final public static function v4(): string {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x', mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0x0fff) | 0x4000, mt_rand(0, 0x3fff) | 0x8000, mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff));
    }

    /**
     * Generate v5 UUID
     *
     * Version 5 UUIDs are named based. They require a namespace (another
     * valid UUID) and a value (the name). Given the same namespace and
     * name, the output is always the same.
     * @param string $namespace UUID seed
     * @param string $name
     * @access public
     * @return string
     */
    final public static function v5(string $namespace, string $name): string {
        if (self::is_valid($namespace) === false) {
            return '';
        }
        else {
            $nhex = str_replace(array('-','{','}'), '', $namespace);
            $nstr = '';

            $limit = strlen($nhex);
            for ($i = 0; $i < $limit; $i += 2) {
                $nstr .= chr(hexdec($nhex[$i] . $nhex[$i + 1]));
            }

            $hash = sha1($nstr . $name);

            return sprintf('%08s-%04s-%04x-%04x-%12s', substr($hash, 0, 8), substr($hash, 8, 4), (hexdec(substr($hash, 12, 4)) & 0x0fff) | 0x5000, (hexdec(substr($hash, 16, 4)) & 0x3fff) | 0x8000, substr($hash, 20, 12));
        }
    }

    /**
     * validates a UUID
     * @param string $uuid
     * @access public
     * @return bool
     */
    public static function is_valid(string $uuid): bool {
        return preg_match('/^\{?[0-9a-f]{8}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{4}\-?[0-9a-f]{12}\}?$/i', $uuid) === 1;
    }
}
?>
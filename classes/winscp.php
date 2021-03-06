<?php

/**
 * This is a class for scripting against winscp
 * @package MMExtranet
 * @version 1.0
 */

class winscp {

    /**
     * the name of the winSCP session to use
     * @var string
     */
    private static $sessionName = '';

    /**
     * full path to the log file for winSCP output
     * @var string
     */
    private static $logFile = '';

    /**
     * the array of the CLI parameters and their associated values
     * @var array
     */
    private static $cliParameters = array();

    /**
     * Stores the string name of the configured winSCP session to use
     * @access public
     * @param string $sessionName the session name from winSCP
     */
    final public static function useSession(string $sessionName) {
        self::$sessionName = trim($sessionName);
    }

    /**
     * Stores the full path, including filename, of the winscp log file
     * @access public
     * @param string $fullPathAndFilename full path and filename of the log file
     */
    final public static function setLogfile(string $fullPathAndFilename) {
        self::$logFile = trim($fullPathAndFilename);
    }

    /**
     * stores a list of CLI parameters
     * @access public
     * @param string $optionName
     * @param mixed $optionValue
     */
    final public static function setCLIParameter(string $optionName, $optionValue = false) {
        $optionName = trim($optionName);

        switch ($optionName) {
            default:
                $optionValue = str_replace('"', '""', trim($optionValue));
                if ((is_string($optionValue) === true && $optionValue !== '') || is_int($optionValue) === true) {
                    self::$cliParameters[] = '/' . $optionName . '=' . $optionValue;
                }
                else {
                    self::$cliParameters[] = '/' . $optionName;
                }
                break;

            case 'command':
                if (is_array($optionValue) === true) {
                    $limit = count($optionValue);

                    if ($limit > 0) {
                        $tmp = array();
                        for ($i = 0; $i < $limit; $i++) {
                            $tmp[] = str_replace('"', '""', $optionValue[$i]);
                        }
                        self::$cliParameters[] = '/command "' . implode('" "', $tmp) . '"';
                    } else {}
                } else {}
                break;
        }
    }

    /**
     * roughly validate the config and run the CLI command
     * @access public
     */
    final public static function processSetup() {
        if (is_string(self::$sessionName) === true && self::$sessionName !== '') {
            $commandParts = array (
                    config::$winscp['executable'],
                    self::$sessionName,
                    '/console'
                );

            if (self::$logFile !== '') {
                $commandParts[] = '/log=' . self::$logFile;
            } else {}

            $limit = count(self::$cliParameters);
            for ($i = 0; $i < $limit; $i++) {
                $commandParts[] = self::$cliParameters[$i];
            }

            $command = implode(' ', $commandParts);

            $result_exec = array();
            @exec($command, $result_exec);
            $limit = count($result_exec);
            for ($i = 0; $i < $limit; $i++) {
                workhorse::logAlert($result_exec[$i]);
            }
        }
        else {
            workhorse::logAlert('No WinSCP Session Name Specified');
        }
    }
}
?>
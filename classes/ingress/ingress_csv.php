<?php

/**
 * This is a class for handling CSV inputs
 * @package MMExtranet
 * @version 1.0
 */

class ingress_csv implements i_ingress {

    private static $ingestFile = '';
    private static $fp = null;
    private static $headerRow = array();
    private static $recordNum = 0;

    /**
     * runs validation checks on csv input parameters
     * @access public
     * @return bool
     */
    final public static function init(): bool {
        $config = workhorse::getConfig();

        self::$ingestFile = driver::$currentExport['input']['location'];

        if (file_exists(self::$ingestFile) === true && is_readable(self::$ingestFile) === true) {
            self::$fp = fopen(self::$ingestFile, 'r');

            if (self::$fp === false) {
                log::logAlert(errors::$inputFileNotFoundorNotReadable);
                return false;
            } else {}
        }
        else {
            log::logAlert(errors::$inputFileNotFoundorNotReadable);
            return false;
        }

        driver::$currentExport['input']['headerRow'] = driver::$currentExport['input']['headerRow'] ?? true;
        driver::$currentExport['input']['delimeter'] = driver::$currentExport['input']['delimeter'] ?? ',';
        driver::$currentExport['input']['quantifier'] = driver::$currentExport['input']['quantifier'] ?? '"';

        return true;
    }

    /**
     * cycles through the input data
     * @access public
     */
    final public static function beginIteration() {
        while (($row = fgetcsv(self::$fp, 0, driver::$currentExport['input']['delimeter'], driver::$currentExport['input']['quantifier'])) !== false) {
            if (driver::$currentExport['input']['headerRow'] === true && self::$recordNum === 0) {
                self::$headerRow = $row;
            }
            else {
                if (driver::$currentExport['input']['headerRow'] === true) {
                    workhorse::routeRowToProcessor(array_combine(self::$headerRow, $row));
                }
                else {
                    workhorse::routeRowToProcessor($row);
                }
            }


            ++self::$recordNum;
        }
    }

    /**
     * performs any cleanup
     * @access public
     */
    final public static function cleanup() {
        fclose(self::$fp);
        self::$ingestFile = '';
        self::$headerRow = array();
        self::$recordNum = 0;
    }
}
?>
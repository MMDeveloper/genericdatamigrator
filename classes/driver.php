<?php

/**
 * This is the driver class. It stores pointers for a centralized point of access
 * @package MMExtranet
 */

class driver {
    /**
     * pointer to the shared DateTime Immutable object
     * @var object
     */
    public static $dto                  = null;

    /**
     * pointer to the crypto library
     * @var object
     */
    public static $crypto               = null;

    /**
     * the shared current time in timestamp form
     * @var object
     */
    public static $now                  = 0;

    /**
     * pointer to the primary database instance
     * @var object
     */
    public static $db                   = null;

    /**
     * array for current school year
     * @var object
     */
    public static $currentSY            = array(0,0);

    /**
     * the class name for the current ingress handler
     * @var string
     */
    public static $ingressClassName = '';

    /**
     * the class name for the current egress handler
     * @var string
     */
    public static $egressClassName = '';

    /**
     * the array for the current export job
     * @var array
     */
    public static $currentExport = array();

    /**
     * shared buckets of information for whatever
     * @var array
     */
    public static $sharedBuckets = array();

    /**
     * CLI parameters for getopt() on Shared Jobs
     * @var array
     */
    public static $cliOptions = array();

    /**
     * shared bools
     * @var array
     */
    public static $sharedBools = array(
            'job_jobTransformer' => false,
            'job_preQueueProcesserLoopHook' => false,
            'job_postQueueProcesserLoopHook' => false,
            'job_preQueueRecordProcessHook' => false,
            'job_postQueueRecordProcessHook' => false,
            'job_rowProcessor' => false,
            'job_useInterface_jobTransformer' => false
        );

    /**
     * This method creates the pointers
     * @access public
     */
    public static function init() {
        self::$dto = new DateTimeImmutable();
        self::$now = self::$dto->getTimestamp();
        self::$currentSY = utility::determineSchoolYear();
        self::$crypto = new crypt_aes(config::$encrytionKey);
    }
}
?>
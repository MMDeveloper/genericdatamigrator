<?php

/**
 * This is an abstraction layer for an encryption class
 * @package MMExtranet
 */

class crypt_aes {

    /**
     * encryption string
     * @var string
     */
    private $key = '';

    /**
     * Construct that sets the encryption key
     * @access public
     * @param string $key the encryption key
     */
    public function __construct(string $key) {
        $this->key = Defuse\Crypto\Key::loadFromAsciiSafeString($key);
    }

    /**
     * This method encrypts a string
     * @access public
     * @param string $data the string to encrypt
     * @param bool $encode whether to base64 and trim
     * @param bool $rawBinary whether to output raw bytestring or hex encoded result
     * @return mixed encrypted string or bool false
     */
    public function encrypt(string $data, bool $encode = true, bool $rawBinary = false) {
        try {
            if ($encode === true) {
                return $this->encodeForHTML(Defuse\Crypto\Crypto::encrypt($data, $this->key, $rawBinary));
            }
            else {
                return Defuse\Crypto\Crypto::encrypt($data, $this->key, $rawBinary);
            }
        }
        catch (Exception $e) {
            return false;
        }
    }

    /**
     * This method decrypts a string
     * @access public
     * @param string $data the string to decrypt
     * @param bool $decode whether to base64 and trim
     * @param bool $rawBinary whether to output raw bytestring or hex encoded result
     * @return mixed decrypted string or bool false
     */
    public function decrypt(string $data, bool $decode = true, bool $rawBinary = false) {
        try {
            if ($decode === true) {
                return Defuse\Crypto\Crypto::decrypt($this->decodeFromEncodeMethod($data), $this->key, $rawBinary);
            }
            else {
                return Defuse\Crypto\Crypto::decrypt($data, $this->key, $rawBinary);
            }
        }
        catch (Exception $e) {
            return false;
        }
    }

    /**
     * This method encrypts a string with a password
     * @access public
     * @param string $data the string to encrypt
     * @param string $password
     * @param bool $encode whether to base64 and trim
     * @param bool $rawBinary whether to output raw bytestring or hex encoded result
     * @return mixed encrypted string or bool false
     */
    public function encryptWithPassword(string $data, string $password, bool $encode = true, bool $rawBinary = false) {
        try {
            if ($encode === true) {
                return $this->encodeForHTML(Defuse\Crypto\Crypto::encryptWithPassword($data, $password, $rawBinary));
            }
            else {
                return Defuse\Crypto\Crypto::encryptWithPassword($data, $password, $rawBinary);
            }
        }
        catch (Exception $e) {
            return false;
        }
    }

    /**
     * This method dcrypts a string with a password
     * @access public
     * @param string $data the string to decrypt
     * @param string $password
     * @param bool $encode whether to base64 and trim
     * @param bool $rawBinary whether to output raw bytestring or hex encoded result
     * @return mixed encrypted string or bool false
     */
    public function decryptWithPassword(string $data, string $password, bool $decode = true, bool $rawBinary = false) {
        try {
            if ($decode === true) {
                return Defuse\Crypto\Crypto::decryptWithPassword($this->decodeFromEncodeMethod($data), $password, $rawBinary);
            }
            else {
                return Defuse\Crypto\Crypto::decryptWithPassword($data, $password, $rawBinary);
            }
        }
        catch (Exception $e) {
            return false;
        }
    }

    /**
     * This method encrypts a file
     * @access public
     * @param string $inputFile the unencrypted file, absolute path
     * @param string $outputFile the encrypted file, absolute path
     * @return bool
     */
    public function encryptFile(string $inputFile, string $outputFile): bool {
        if (file_exists($inputFile) === true && is_readable($inputFile) === true) {
            $pathinfo = pathinfo($outputFile);
            if (is_writable($pathinfo['dirname']) === true) {
                Defuse\Crypto\File::encryptFile($inputFile, $outputFile, $this->key);
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * This method encrypts a file
     * @access public
     * @param string $inputFile the unencrypted file, absolute path
     * @param string $outputFile the encrypted file, absolute path
     * @return bool
     */
    public function decryptFile(string $inputFile, string $outputFile): bool {
        if (file_exists($inputFile) === true && is_readable($inputFile) === true) {
            $pathinfo = pathinfo($outputFile);
            if (is_writable($pathinfo['dirname']) === true) {
                Defuse\Crypto\File::decryptFile($inputFile, $outputFile, $this->key);
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * This method encrypts a file with a password
     * @access public
     * @param string $inputFile the unencrypted file, absolute path
     * @param string $outputFile the encrypted file, absolute path
     * @param string $password
     * @return bool
     */
    public function encryptFileWithPassword(string $inputFile, string $outputFile, string $password): bool {
        if (file_exists($inputFile) === true && is_readable($inputFile) === true) {
            $pathinfo = pathinfo($outputFile);
            if (is_writable($pathinfo['dirname']) === true) {
                Defuse\Crypto\File::encryptFileWithPassword($inputFile, $outputFile, $password);
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * This method encrypts a file with a password
     * @access public
     * @param string $inputFile the unencrypted file, absolute path
     * @param string $outputFile the encrypted file, absolute path
     * @param string $password
     * @return bool
     */
    public function decryptFileWithPassword(string $inputFile, string $outputFile, string $password): bool {
        if (file_exists($inputFile) === true && is_readable($inputFile) === true) {
            $pathinfo = pathinfo($outputFile);
            if (is_writable($pathinfo['dirname']) === true) {
                Defuse\Crypto\File::decryptFileWithPassword($inputFile, $outputFile, $password);
                return true;
            }
            else {
                return false;
            }
        }
        else {
            return false;
        }
    }

    /**
     * This method encrypts a resource
     * @access public
     * @param resource $inputHandle the input resource
     * @param resource $outputHandle the output resource
     * @return bool
     */
    public function encryptResource(string $inputHandle, string $outputHandle): bool {
        if (is_resource($inputHandle) === true && is_resource($outputHandle) === true) {
            Defuse\Crypto\File::encryptResource($inputHandle, $outputHandle, $this->key);
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * This method decrypts a resource
     * @access public
     * @param resource $inputHandle the input resource
     * @param resource $outputHandle the output resource
     * @return bool
     */
    public function decryptResource(string $inputHandle, string $outputHandle): bool {
        if (is_resource($inputHandle) === true && is_resource($outputHandle) === true) {
            Defuse\Crypto\File::decryptResource($inputHandle, $outputHandle, $this->key);
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * This method encrypts a resource with a password
     * @access public
     * @param resource $inputHandle the input resource
     * @param resource $outputHandle the output resource
     * @param string $password
     * @return bool
     */
    public function encryptResourceWithPassword(string $inputHandle, string $outputHandle, string $password): bool {
        if (is_resource($inputHandle) === true && is_resource($outputHandle) === true) {
            Defuse\Crypto\File::encryptResourceWithPassword($inputHandle, $outputHandle, $password);
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * This method decrypts a resource with a password
     * @access public
     * @param resource $inputHandle the input resource
     * @param resource $outputHandle the output resource
     * @param string $password
     * @return bool
     */
    public function decryptResourceWithPassword(string $inputHandle, string $outputHandle, string $password): bool {
        if (is_resource($inputHandle) === true && is_resource($outputHandle) === true) {
            Defuse\Crypto\File::decryptResourceWithPassword($inputHandle, $outputHandle, $password);
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * This method encodes the result into a non-binary string
     * @access public
     * @param string $data the string to encode
     * @return string
     */
    private function encodeForHTML($data): string {
        return base64_encode($data);
    }

    /**
     * This method decodes undoes the above method
     * @access public
     * @param string $data the string to decode
     * @return string
     */
    private function decodeFromEncodeMethod($data): string {
        return base64_decode($data);
    }
}
?>
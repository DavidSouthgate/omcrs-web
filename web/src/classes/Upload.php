<?php

class Upload
{
    private $directory;
    private $extension;
    private $data;

    /**
     * Upload constructor.
     * @param string $directory
     */
    public function __construct($directory) {
        $this->directory = $directory;
    }

    /**
     * Use base64 data
     * @param $data
     */
    public function base64($data) {
        list($type, $data) = explode(';', $data);
        list(, $data)      = explode(',', $data);
        $this->data = base64_decode($data);

        // Use regex to extract file extension from type
        preg_match("/(data:)((image\/)([A-Za-z0-9]*))/", $type, $matches);
        $this->extension = $matches[4];
    }

    /**
     * Whether this is valid
     * @return bool
     */
    public function isValid() {
        $headers = self::getHeaders();
        return array_key_exists($this->extension, $headers);
    }

    /**
     * Save the upload
     * @return string The file path
     */
    public function save() {

        // Construct path
        $path = $this->getDirectory() . $this->getFilename();

        // If file doesn't already exist, write it to file
        if(!file_exists($path)) {
            try {
                file_put_contents($path, $this->data);
            }
            catch(Exception $e) {
                return null;
            }
        }

        return $path;
    }

    public function getFilename() {
        return md5($this->data) . "." . $this->extension;
    }

    /**
     * @return string
     */
    public function getDirectory() {
        return $this->directory;
    }

    /**
     * @param string $directory
     */
    public function setDirectory($directory) {
        $this->directory = $directory;
    }

    /**
     * @return mixed
     */
    public function getExtension() {
        return $this->extension;
    }

    /**
     * @param mixed $extension
     */
    public function setExtension($extension) {
        $this->extension = $extension;
    }

    /**
     * @return string
     */
    public function getHeader() {
        return self::getHeaderFromExtension($this->extension);
    }

    public static function getHeaders() {
        return [
            "gif" => "image/gif",
            "png" => "image/png"
        ];
    }

    public static function getHeaderFromExtension($extension) {
        $headers = self::getHeaders();

        // If this extension exists in headers
        if(array_key_exists($extension, $headers)) {
            return $headers[$extension];
        }

        return null;
    }

    public static function getExtensionFromFilename($filename) {
        $filenameExploded = explode(".", $filename);
        return $filenameExploded[1];
    }
}
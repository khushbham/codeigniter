<?php
class CSV
{
    /**
    * @var array
    */
    protected $header_items = array();

    /**
    *
    * Two-dimensional array, e.g:
    *
    * array (
    *              array('John', 'Doe'),
    *              array('Mark', 'Doe')
    * );
    *
    * @var array
    */
    protected $body_items = array();

    /**
    * Default constructor
    */
    public function __construct() {
    }

    /**
    * @param array $items
    */
    public function set_header_items(array $items) {
        $this->header_items = $items;
    }

    /**
    * @param array $items
    */
    public function set_body_items(array $items) {
        $this->body_items = $items;
    }

    /**
    * @param string $outputFilename
    */
    public function output_as_download($outputFilename = 'export.csv', $delimiter = "\t") {
        header('Content-Encoding: UTF-16LE');
        header("Content-type: text/xlsx; charset=UTF-16LE");
        header("Content-Disposition: attachment; filename=" . $outputFilename);
        header("Pragma: no-cache");
        header("Expires: 0");

        print chr(255) . chr(254); // UTF-16 LE byte order mark

        if (!empty($this->header_items)) { // header items are optional!
            print $this->utf16_encode(implode("\t", $this->header_items) . PHP_EOL); // Output header items, separated with a tab character
        }

        foreach ($this->body_items as $items) {
            print $this->utf16_encode(implode($delimiter, $items) . PHP_EOL);
        }

        exit;
    }

    public function output_as_downloadCSV($outputFilename = 'export.csv', $delimiter = "\t") {
        header("Content-type: application/csv; charset=UTF-8");
        header("Content-Disposition: attachment; filename=" . $outputFilename);
        header("Pragma: no-cache");
        header("Expires: 0");

        print chr(0xEF).chr(0xBB).chr(0xBF); // UTF-8 byte order mark

        if (!empty($this->header_items)) { // header items are optional!
            print $this->utf8_encode(implode($delimiter, $this->header_items) . PHP_EOL); // Output header items, separated with a tab character
        }

        foreach ($this->body_items as $items) {
            print $this->utf8_encode(implode($delimiter, $items) . PHP_EOL);
        }

        exit;
    }

    /**
    * @param $str
    * @return string
    */
    private function utf16_encode($str) {
        return mb_convert_encoding($str, 'UTF-16LE', 'UTF-8');
    }

    private function utf8_encode($str) {
        return mb_convert_encoding($str, 'UTF-8', 'UTF-8');
    }
}
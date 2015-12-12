<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Parse extends CI_Controller {

    //put your code here
    private $all_links = array();
    private $limit = 0;
    private $sleep = 0;
    private $pages = 0;
    private $dom = null;

    public function __construct($url = 'http://bsdei.gov.ua/', $limit = 100, $sleep = 0) {
        $this->limit = $limit;
        $this->sleep = $sleep;
        $this->all_links[] = $url;
        $this->dom = new DOMDocument;
    }

    public function run() {
        while ($this->pages < count($this->all_links)) {
            $this->parse($this->all_links[$this->pages]);
            $this->pages++;

            if ($this->limit && $this->pages >= $this->limit) {
                return;
            }

            sleep($this->sleep);
        }
    }

    public function getAllLinks() {
        return $this->all_links;
    }

    public function getLinksCount() {
        return count($this->all_links);
    }

    private function parse($url) {
        @$this->dom->loadHTMLFile($url);
        $links = $this->dom->getElementsByTagName('a');
        echo "<pre>";
        // print_r($this->dom);
        echo "</pre>";
        foreach ($links as $link) {
            $url = trim($link->getAttribute('href'));
            if (!empty($url) && !in_array($url, $this->all_links) && strpos($url, $this->all_links[0]) === 0) {
                $this->all_links[] = $url;
            }
        }
    }

    function index() {


        $this->run();

        var_dump($this->getLinksCount());
        var_dump($this->getAllLinks());
    }

}

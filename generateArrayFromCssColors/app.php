<?php

class app {
    private $url;
    private $colorsArray;
    private $colorsFileContents;
    private $language;
    private $languageInstance;
    private $argument;
    private $savePath;

    public function __construct($url, $post, $savePath = 'generated/Color') {
        $this->url = $url;

        $this->language = $post['language'];
        $this->argument = $post['argument'];

        $this->savePath = $savePath;

        $this->setup();
    }

    private function setup() {
        $urlContents = file_get_contents($this->url);
        if (empty($urlContents)) {
            $urlContents = file_get_contents('generated/colorSiteBackup');
        }

        $this->colorsArray = $this->generateColorArray($urlContents);

        if(!$this->validLanguage($this->language)) {
            echo 'Not a valid language, <a href="index.html">Back</a>';
            exit();
        }

        try {
            require_once sprintf('languages/%s.php', $this->language);
            $languageInstance = new $this->language();
        } catch (\Throwable $th) {
            echo 'Could not instanciate language';
            exit();
        }

        try {
            $languageInstance->generateContents($this->colorsArray, $this->argument);
        } catch (\Throwable $th) {
            exit();
        }

        $this->languageInstance = $languageInstance;
        $this->colorsFileContents = $languageInstance->getContents();

        $this->generateColorFile();
    }

    private function generateColorArray($contents) {
        $DOM = new DOMDocument;
        error_reporting(E_ERROR);
        $DOM->loadHTML($contents);
        error_reporting(E_ALL);
        
        $table = $DOM->getElementsByTagName('table')[0];
        $rows = $table->getElementsByTagName('tr');
        
        $tableDataArray = array();
        
        foreach ($rows as $node) {
            $data = $this->getTdText($node->childNodes);
            
            if ($data === array()) continue;
            
            $name = $data[0];
            $values = [
                'hex' => $data[1],
                'rgb' => $data[2]
            ];
            
            $tableDataArray[$name] =  $values;
        }
        
        return $tableDataArray;
    }

    private function getTdText($elements) {
        $tdText = [];
        
        foreach ($elements as $element) {
            $value = trim($element->nodeValue);
            if(empty($value) || $element->tagName === 'th') continue;
            $tdText[] .= $value;
        }
        
        return $tdText;
    }

    public function validLanguage($language) {
        $languagesDir = array_diff(scandir(__DIR__.'/languages'), array('..', '.', '.htaccess', 'language.php', 'languageInterface.php'));
        $languages = array();

        foreach ($languagesDir as $languageClass) 
            array_push($languages, str_replace('.php', '', $languageClass));
        
        return in_array($language, $languages);
    }

    public function generateColorFile() {
        $extension = $this->languageInstance->getExtension();
        $filePathName = $this->savePath.ucfirst($this->argument).$extension;
        $writtenBytes = file_put_contents($filePathName, $this->colorsFileContents);
        
        if ($writtenBytes === 0) {
            echo 'Bytes written is zero, someting\'s wrong...';
        } else {
            echo sprintf('%1$s color file generated! Click to download: <a href="%2$s?file=%3$s" target="_blank">%3$s</a>', ucfirst($this->language), 'downloadColorFile.php', $filePathName);
        }
    }

    public function getColorsArray() {
        return $this->colorsArray;
    }

}
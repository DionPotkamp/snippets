<?php
require_once 'languageInterface.php';

class language implements languageInterface {

    private $allowedArguments;
    private $argument;

    private $syntax = [
        'comment' => '',
        'variables' => [
            'format' => [
                'hex' => "",
                'rgb' => "",
            ],
            'prefix' => '',
            'suffix' => '',
        ],
        'object' => [
            'format' => [
                'hex' => '',
                'rgb' => '',
            ],
            'prefix' => "",
            'suffix' => '',
        ],
        'extension' => ''
    ];

    private $colorsArray;

    private $contents;

    public function generateContents($colorsArray, $argument) {
        $this->colorsArray = $colorsArray;
        $this->argument = $argument;
        
        $contents = $argument($colorsArray);
        $this->setContents($contents);
    }

    public function getContents() {
        return $this->contents;
    }

    protected function setContents($contents) {
        return $this->contents = $contents;
    }

    public function getColorsArray() {
        return $this->colorsArray;
    }

    public function getAllowedArguments() {
        return $this->allowedArguments;
    }

    public function getArgument() {
        return $this->argument;
    }

    protected function checkArgument($allowedArguments, $argument) {
        if (!in_array($argument, $allowedArguments))
            echo "Not a valid argument, <a href=\"index.php\">back</a>";
    }

    public function getExtension() {}

    public function getSyntax() {
        return $this->syntax;
    }

}
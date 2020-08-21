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

    public function __construct($colorsArray, $argument) {
        $this->colorsArray = $colorsArray;
        $this->argument = $argument;
    }

    public function generateContents() {
        $colorsArray = $this->getColorsArray();
        $arg = $this->getArgument();
        $contents = $this->$arg($colorsArray);
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

    protected function checkArgument($allowedArguments) {
        if (!in_array($this->getArgument(), $allowedArguments))
            throw new Exception("Not a valid argument", 1);
    }

    public function getExtension() {}

    public function getSyntax() {
        return $this->syntax;
    }

}
<?php

interface languageInterface {
    public function __construct($colorsArray, $argument);

    public function generateContents();
    public function getContents();

    public function getAllowedArguments();
    public function getArgument();
    public function getSyntax();
    public function getExtension();
}
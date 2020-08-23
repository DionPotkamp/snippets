<?php

interface languageInterface {
    public function generateContents($colorsArray, $argument);
    public function getContents();

    public function getAllowedArguments();
    public function getArgument();
    public function getSyntax();
    public function getExtension();
}
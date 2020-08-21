<?php
require_once 'language.php';

/**
 * @method //
 */
class python extends language {

    private $allowedArguments = ['variables', 'dictionairy'];

    private $syntax = [
        'comment' => '//',
        'variables' => [
            'format' => [
                'hex' => "%s_hex = \"%s\"\n",
                'rgb' => "%s_rgb = (%s)\n\n",
            ],
            'prefix' => '',
            'suffix' => '',
        ],
        'dictionairy' => [
            'format' => [
                'hex' => '',
                'rgb' => '',
            ],
            'prefix' => "colors = ",
            'suffix' => '',
        ],
        'extension' => '.py'
    ];

    public function generateContents() {
        $this->checkArgument($this->allowedArguments);
        parent::generateContents();
    }

    protected function dictionairy($colorsArray) {
        return $this->syntax['dictionairy']['prefix'] . json_encode($colorsArray, JSON_PRETTY_PRINT);
    }
    
    protected function variables($colorsArray) {
        $result = "";
        $hexFormat = $this->syntax['variables']['format']['hex'];
        $rgbFormat = $this->syntax['variables']['format']['rgb'];
    
        foreach ($colorsArray as $key => $value) {
            $result .= sprintf($hexFormat, $key, $value["hex"]);
            $result .= sprintf($rgbFormat, $key, $value["rgb"]);
        }

        return $result;
    }

    public function getExtension() {
        return $this->syntax['extension'];
    }

}

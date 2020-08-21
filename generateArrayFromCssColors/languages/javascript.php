<?php
require_once 'language.php';

/**
 * @method //
 */
class javascript extends language {

    private $allowedArguments = ['variables', 'object'];

    private $syntax = [
        'comment' => '//',
        'variables' => [
            'format' => [
                'hex' => "let %s_hex = \"#%s\";\n",
                'rgb' => "let %s_rgb = \"rgb(%s)\";\n\n",
            ],
            'prefix' => '',
            'suffix' => '',
        ],
        'object' => [
            'format' => [
                'hex' => '',
                'rgb' => '',
            ],
            'prefix' => "let colors = ",
            'suffix' => '',
        ],
        'extension' => '.js'
    ];

    public function generateContents() {
        $this->checkArgument($this->allowedArguments);
        parent::generateContents();
    }

    protected function object($colorsArray) {
        return $this->syntax['object']['prefix'] . json_encode($colorsArray, JSON_PRETTY_PRINT);
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

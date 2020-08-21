<?php
require_once 'language.php';

/**
 * @method //
 */
class css extends language {

    private $allowedArguments = ['variables', 'class'];

    private $syntax = [
        'comment' => '//',
        'variables' => [
            'format' => [
                'hex' => "    --%s_hex: #%s;\n",
                'rgb' => "    --%s_rgb: rgb(%s);\n\n",
            ],
            'prefix' => ":root {\n",
            'suffix' => "}",
        ],
        'class' => [
            'format' => [
                'text' => ".text-%s {\n    color: rgb(%s);\n}\n",
                'background' => ".bg-%s {\n    background-color: rgb(%s);\n}\n\n",
            ],
            'prefix' => '',
            'suffix' => '',
        ],
        'extension' => '.css'
    ];

    public function generateContents() {
        $this->checkArgument($this->allowedArguments);
        parent::generateContents();
    }

    protected function class($colorsArray) {
        $result = '';

        $textFormat = $this->syntax['class']['format']['text'];
        $backgroundFormat = $this->syntax['class']['format']['background'];
    
        foreach ($colorsArray as $key => $value) {
            $result .= sprintf($textFormat, $key, $value["hex"]);
            $result .= sprintf($backgroundFormat, $key, $value["rgb"]);
        }
    
        return $result;
    }
    
    protected function variables($colorsArray) {
        $result = $this->syntax['variables']['prefix'];

        $hexFormat = $this->syntax['variables']['format']['hex'];
        $rgbFormat = $this->syntax['variables']['format']['rgb'];
    
        foreach ($colorsArray as $key => $value) {
            $result .= sprintf($hexFormat, $key, $value["hex"]);
            $result .= sprintf($rgbFormat, $key, $value["rgb"]);
        }

        $result .= $this->syntax['variables']['suffix'];

        return $result;
    }

    public function getExtension() {
        return $this->syntax['extension'];
    }

}

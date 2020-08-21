<?php
require_once 'language.php';

class php extends language {

    private $allowedArguments = ['variables', 'object'];

    private $syntax = [
        'comment' => '//',
        'variables' => [
            'format' => [
                'hex' => "$%s_hex = \"#%s\";\n",
                'rgb' => "$%s_rgb = \"rgb(%s)\";\n\n",
            ],
            'prefix' => "<?php\n",
            'suffix' => '',
        ],
        'object' => [
            'format' => [
                'hex' => '',
                'rgb' => '',
            ],
            'prefix' => '<?php $colors = ',
            'suffix' => ';',
        ],
        'extension' => '.php'
    ];

    public function generateContents() {
        $this->checkArgument($this->allowedArguments);
        parent::generateContents();
    }

    protected function object($colorsArray) {
        $prefix = $this->syntax['object']['prefix'];
        $suffix = $this->syntax['object']['suffix'];

        return $prefix . var_export($colorsArray, true) . $suffix;
    }
    
    protected function variables($colorsArray) {
        $prefix = $this->syntax['object']['prefix'];

        $result = $prefix;
        
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

<?php
if ( 
    !isset($_POST['submit']) ||
    empty($_POST['submit'])
) {
    header('Location: index.html');
    exit();
}

function dd($_ = null){
    $args = func_get_args();
    echo '<code><pre>';
    foreach ($args as $arg) {
        if (is_string($arg)) var_dump(htmlentities($arg));
        var_dump($arg);
        echo '<hr>';
    }
    echo '</pre></code>';
    die();
}

/**
 * Use one of the folowing url's to obtain the data;
 */
$url = 'https://www.w3schools.com/colors/color_tryit.asp';
// $url = 'generated/.colorSiteBackup'; // Local

$dumpColorArray = isset($_POST['dump']) && $_POST['dump'] === 'on' ? true : false ;

/**
 * To generate a file with all the colors, fill in one of the possible languages.
 * Some languages have options, these will be inside the brackets.
 * Possible languages: php, js, css, python(dictionairy, variables)
 * 
 */
$lang = $_POST['lang'];
$langArg = $_POST['arg'];
$generatedPath = 'generated/ColorArray.'; // Notice the dot on the end

$fullSite = file_get_contents($url);

if (empty($fullSite)) {
    $fullSite = file_get_contents('generated/colorSiteBackup');
}

function getTdText($elements) {
    $tdText = [];
    
    foreach ($elements as $element) {
        $value = trim($element->nodeValue);
        if(empty($value) || $element->tagName === 'th') continue;
        $tdText[] .= $value;
    }
    
    return $tdText;
}

function generateColorArray($contents) {
    $DOM = new DOMDocument;
    error_reporting(E_ERROR);
    $DOM->loadHTML($contents);
    error_reporting(E_ALL);
    
    $table = $DOM->getElementsByTagName('table')[0];
    $rows = $table->getElementsByTagName('tr');
    
    $tableDataArray = array();
    
    foreach ($rows as $node) {
        $data = getTdText($node->childNodes);
        
        if ($data === array()) continue;
        
        $name = trim($data[0]);
        $values = [
            'hex' => $data[1],
            'rgb' => $data[2]
        ];
        
        $tableDataArray[$name] =  $values;
    }
    
    return $tableDataArray;
}

$colorArray = generateColorArray($fullSite);

function pythonVariableColorFile($colorArray) {
    $result = '';
    
    foreach ($colorArray as $key => $value) {
        $result .= sprintf("%s_hex = \"%s\"\n", $key, $value["hex"]);
        $result .= sprintf("%s_rgb = (%s)\n\n", $key, $value["rgb"]);
    }
    
    return $result;
}

function cssVariableColorFile($colorArray) {
    $result = ":root {\n";
    
    foreach ($colorArray as $key => $value) {
        $result .= sprintf("    --%s_hex: #%s;\n", $key, $value["hex"]);
        $result .= sprintf("    --%s_rgb: rgb(%s);\n\n", $key, $value["rgb"]);
    }

    $result .= '}';
    
    return $result;
}

function generateColorFile($path, $extention, $content) {
    $filePathName = $path.$extention;
    $writtenBytes = file_put_contents($filePathName, $content);
    
    if ($writtenBytes === 0) {
        echo 'Bytes written is zero, someting\'s wrong...';
    } else {
        echo sprintf('%1$s color file generated! Click to download: <a href="%2$s?file=%3$s" target="_blank">%3$s</a>', ucfirst($extention), 'downloadColorFile.php', $filePathName);
    }
}

switch ($lang) {
    case 'php':
        $content = '<?php $colors = ' . var_export($colorArray, true) . ';';
        generateColorFile($generatedPath, 'php', $content);
    break;
    
    case 'js':
        $content = 'let colors = ' . json_encode($colorArray, JSON_PRETTY_PRINT);
        generateColorFile($generatedPath, 'js', $content);
        break;

    case 'css':
        $contents = cssVariableColorFile($colorArray);
        generateColorFile($generatedPath, 'css', $contents);
        break;
        
    case 'python':
        if ('dictionairy' === $langArg) {
            $content = 'colors = ' . json_encode($colorArray, JSON_PRETTY_PRINT);
            generateColorFile($generatedPath, 'py', $content);
        } else 
        if ('variables' === $langArg) {
            $contents = pythonVariableColorFile($colorArray);
            generateColorFile($generatedPath, 'py', $contents);
        }
        break;
    
    default:
        break;
}

if ($dumpColorArray) 
    dd($colorArray);


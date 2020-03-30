<html lang="en">

<head>
    <title>Included or Called</title>
    <meta charset="UTF-8">
    <meta name="description" content="Included or Called">
    <meta name="keywords" content="HTML,PHP,included,called">
    <meta name="author" content="Dion Potkamp">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body>
  <h1>
    <?php
      if (basename(__FILE__) == basename($_SERVER["SCRIPT_FILENAME"])) {
        echo "called directly from the browser";
      } else {
        echo "included/required by another php script";
      }
    ?>
  </h1>
</body>

</html>

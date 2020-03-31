<?php
/**
 * Required:
 * A database (named reorderformitems) with the table: users and columns: id, first, last, sortOrder
 * column sortOrder is unique and can be null
 * feel free to change any name
 *
 * Inspiration: https://bootsnipp.com/snippets/P2pn5
 *
 */
require __DIR__ . '/reorderFormItemsController.php';
use controller\reorderFormItemsController as reorderController;
$reorder = new reorderController();

?>
<html lang="en">

<head>
    <title>Reorder Form Items</title>
    <meta charset="UTF-8">
    <meta name="description" content="Reorder Form Items">
    <meta name="keywords" content="HTML,JS,PHP,reorder,form,items">
    <meta name="author" content="Dion Potkamp">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
    <style>
        td:hover {
		    cursor:move;
		}
    </style>

    <script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.min.js" integrity="sha384-wfSDF2E50Y2D1uUdj0O3uMBJnjuUD4Ih7YwaYd1iqfktj0Uod8GCExl3Og8ifwB6" crossorigin="anonymous"></script>
    
    <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="jQuery.ui.Touch.Punch.0.2.3.js"></script>
    <script>
        // Keeps the original width of the dragged item
        let widthHelper = function(e, tr) {
		    let $originals = tr.children();
		    let $helper = tr.clone();
		    $helper.children().each(function(index) {
			    $(this).width($originals.eq(index).width())
		    });
		    return $helper;
        },
        updateOrder = function(e, ui) {
            $('input[type=number]', ui.item.parent()).each(function (i) {
                $(this).val(i + 1);
            });
        };

        $( function() {
            $("#sortable").sortable({
                axis: 'y', // Only allow vertical dragging
                delay: 200, // Deprecated but it does the job nicely
                helper: widthHelper,
                stop: updateOrder
            }).disableSelection();

            // Automatically close alert
            TriggerAlertClose();
        });

        function TriggerAlertClose() {
            window.setTimeout(function () {
                $(".alert").fadeTo(2000, 500).slideUp(500, function () {
                    $(this).slideUp(500);
                    $(this).remove();
                });
            }, 5000);
        }
    </script>
</head>

<body class="container">
    <div class="container">
    <div class="row">
        <div class="col-lg-12">
            <?php
                if(isset($_GET['message'])) {
                    $message = explode('.', htmlspecialchars($_GET['message']));

                    switch ($message[0]) {
                        case 'danger':
                            $messageType = 'danger';
                            break;
                        case 'success':
                            $messageType = 'success';
                            break;
                        default:
                            $messageType = 'warning';
                    }

                    switch ($message[1]) {
                        case 'connection':
                            $messageText = 'Could not connect to the server.';
                            break;
                        case 'validate':
                            $messageText = 'The input did not pass validation.';
                            break;
                        case 'update':
                            $messageText = 'Something went wrong while saving...';
                            break;
                        case 'saved':
                            $messageText = 'You did it! Your items are now freshly sorted.';
                            break;
                        default:
                            $messageText = "Oops? Yeah? Don't know what happened here...";
                    }

                    echo <<<MessageBox
<div class="alert alert-{$messageType} alert-dismissible fade show" role="alert">
  <strong>Holy guacamole!</strong> {$messageText}
  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
    <span aria-hidden="true">&times;</span>
  </button>
</div>
MessageBox;

                }
            ?>
            <form method="POST" action="<?=dirname($_SERVER['SCRIPT_NAME']).'/'?>reorderFormItemsPost.php" id="reorderForm">
                <table class="table table-hover" id="myTable">
                    <thead>
                        <tr>
                            <th scope="col" class="text-center">#</th>
                            <th scope="col">First</th>
                            <th scope="col">Last</th>
                            <th scope="col">Order</th>
                        </tr>
                    </thead>
                    <tbody id="sortable">
                        <?php
                            echo $reorder->showUsersInTable();
                        ?>
                    </tbody>
                </table>
            </form>
            <button class="btn btn-primary" type="submit" form="reorderForm" name="submit" value="reorder">Reorder</button>
        </div>
      </div>
    </div>
</body>

</html>

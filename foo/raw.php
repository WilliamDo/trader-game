<?php
// 1. Set the header so the client knows it's receiving JSON
header('Content-Type: application/json');
?>
{
    "foo": "bar",
    "php": "<?php echo "pointless echo" ?>"
}
broken json
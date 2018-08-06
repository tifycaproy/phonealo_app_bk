<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
<H2>RECARGAS <?=$countriename?></H2>
<h4>Saldo actual: <?=$balance->Balance; ?>&nbsp;<?=$balance->CurrencyIso?></h4>
<?php
    if (count($info_response) > 0)
        echo print_list($info_response);
?>
<form method="post" >
    <strong>Proveedor:</strong>
    <select name="sku">
        <option>Selecciona ...</option>
        <?php echo dropdown('providers', $productos, null, '', true); ?>
    </select><br>
    <strong>Info de recargar:</strong><br>
    <?=print_list($info_recargas) ?>
    <strong>Números</strong><br>
    <textarea name="numbers" rows="20" required="required"></textarea><br>
    <strong>Importe:</strong><br>
    <input type="text" name="importe" required="required"><br>
    <input type="submit" value=" Recargar !">
</form>

</body>
</html>
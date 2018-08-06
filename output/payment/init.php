<html>
    <body>
        <form method="post" >
            <input name="mobile" type="text" placeholder="Movil" value="<?php echo $mobile; ?>"><br>
            <input name="payid" value="<?=$card_secure_id ?>">
            <select name="country">
                <option value="0034" selected>España</option>
            </select><br>
            Recargar <br>
            <select name="amount">
                <option value="5">5 €</option>
                <option value="10">10 €</option>
                <option value="20">20 €</option>
                <option value="50">50 €</option>
                <option value="100">100 €</option>
                <option value="500">500 €</option>
            </select><br>
            <input type="submit" value="Recargar">
        </form>
    </body>
</html>


<?php include('theme/front/templates/default_header.php');?>

<div class="prices-block content content-center" id="prices">
    <form id="form_recarga" method="post">
        <div class="container">
            <br><br>
            <div class="row">
                <?php echo print_messages() ?>
            </div>
            <h2 class="margin-bottom-50"><strong>Bienvenido</strong> <span style="color: #D90A2C"> <?=utf8_encode($usu->data['usu_name']) ?></span> </h2>
            <div class="row">

                <div class="col-md-6 col-sm-3 col-xs-12">
                    <div class="porlet-body">
                        <h4>Registro de llamadas</h4>
                        <div id="callhistory" style="text-align: left !important;">
                            <div id="payhistory"  style="text-align: left !important;">
                                <?=$usu->tableCalls(); ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-sm-3 col-xs-12">
                    <div class="porlet-body">
                        <h4>Registro de pagos - Saldo actual: <?=$balance['credit'].' '.$balance['currency'] ?></h4>
                        <div id="payhistory"  style="text-align: left !important;">
                            <?=$usu->tablePayments(); ?>
                        </div>
                    </div>
                </div>


            </div>
                <!-- Pricing item END -->
                <!-- Pricing item BEGIN -->
                <!-- Pricing item END -->
        </div>
    </form>
</div>

<?php include('theme/front/templates/default_footer.php'); ?>


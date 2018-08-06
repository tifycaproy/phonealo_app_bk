<?php include 'theme/admin6/header.php'; ?>
<?php include 'theme/admin6/sidebar.php'; ?>
<div <?php echo (!isMobile()?'class="page-fixed-main-content"':''); ?>>
<!-- BEGIN PAGE BASE CONTENT -->
    <div class="row widget-row">
        <div class="col-md-3 col-sm-6 no-space">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">Minutos Disponibles</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-green fa fa-database"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle">MIN</span>
                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?=$min_disponibles?>">0</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
        <div class="col-md-3 col-sm-6 no-space">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">Minutos Compromiso</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-red fa fa-phone"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle">MIN</span>
                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?=$minutos_compromiso?>">0</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
        <div class="col-md-3 col-sm-6 no-space">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">Crédito Total</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-purple fa fa-euro"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle">EUR</span>
                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?=$total_credit?>">0</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
        <div class="col-md-3 col-sm-6 no-space">
            <!-- BEGIN WIDGET THUMB -->
            <div class="widget-thumb widget-bg-color-white text-uppercase margin-bottom-20 bordered">
                <h4 class="widget-thumb-heading">descargas/recargas</h4>
                <div class="widget-thumb-wrap">
                    <i class="widget-thumb-icon bg-blue fa fa-cloud-download"></i>
                    <div class="widget-thumb-body">
                        <span class="widget-thumb-subtitle"><?=$total_descargas?> DESCARGAS</span>
                        <span class="widget-thumb-body-stat" data-counter="counterup" data-value="<?=$total_recargas?>">0</span>
                    </div>
                </div>
            </div>
            <!-- END WIDGET THUMB -->
        </div>
    </div>

<!-- END PAGE BASE CONTENT -->
</div>
<?php include 'theme/admin6/footer.php'; ?>

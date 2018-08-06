<?php include('theme/front/templates/default_header.php');?>

<div class="prices-block content content-center" id="prices">
    <form id="form_recarga" method="post">
        <div class="container-fluid">
            <br><br>
            <div class="row">
                <?php echo print_messages() ?>
            </div>
            <!-- <img width="20%" class="margin-bottom-50" src="<?=base_path;?>images/logo.png" alt="Phonealo LLamadas baratas y de calidad"> -->
        
           
            <div class="container">
                 <div id="carousel-example-generic" class="carousel slide col-md-offset-3 col-md-6 col-xs-12" data-ride="carousel">
              <!-- Wrapper for slides -->
              <div class="carousel-inner" role="listbox">
                <div class="item active">
                  <img src="<?=base_path;?>images/img.png" alt="">
                  <div class="carousel-caption">
                    <h3>Llamadas Internacionales</h3>
                    <p>Phonealo utiliza la tecnología más innovadora para que puedas llamar en el extranjero</p>
                  </div>
                </div>
                <div class="item">
                  <img src="<?=base_path;?>images/img2.png" alt="">
                  <div class="carousel-caption">
                    <h3>Chatea y Cuéntaselo todo</h3>
                    <p>Hasta el último detalle con la gente que te importa.</p>
                  </div>
                </div>
             
              </div>

            </div>
            <br>
             <span class="col-xs-12" style="margin-top: 30px">
                Selecciona el país desde donde te has registrado y el número de móvil donde tienes instalada la aplicación que quieres recargar <br><br>
            </span>
            <div class="container-fluid">
                
            
            <div class="row">
                <div class="py-xs-3">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-xs-12">
                    <div class="porlet-body">
                        <h4>País</h4>
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail22">Email address</label>
                            <div class="input-icon">
                                <i class="fa fa-mobile-phone"></i>
                                <select class="form-control" name="country">
                                    <?=dropDownPaisCliente($country); ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-xs-12">
                    <div class="porlet-body">
                        <h4>Teléfono</h4>
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail22">Email address</label>
                            <div class="input-icon">
                                <i class="fa fa-mobile-phone"></i>
                                <input class="form-control" id="mobile" name="mobile" placeholder="Móvil" type="tel" value="<?=$mobile ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-md-offset-4 col-sm-6 col-xs-12">
                    <div class="porlet-body">
                        <h4>Importe</h4>
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail22">Importe</label>
                            <div class="input-icon">
                                <i class="fa fa-money"></i>
                                <select class="form-control" name="amount">
                                    <?=dropDownImportesRecarga($amount) ?>
                                </select>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-4 col-md-offset-4 col-sm-6 col-xs-12">

                    <div class="porlet-body">
                        <h4>&nbsp;</h4>
                        <div class="form-group">
                            <div class="col-md-12">
                                <button type="submit" class="btn blue">Recargar y Phonealo</button>
                            </div>
                        </div>
                    </div>

                </div>

            </div>
            </div>
            </div>
            </div>
            <!-- <div class="row">
                <br><br>
                <h2 class="margin-bottom-50"><strong>¿ Aún no tienes nuestra APP ?</h2>
                    <!-- Pricing item BEGIN -->
               <!--  <a href="<?=base_path;?>getNow" target="_blank">
                    <img src="<?=base_path;?>images/ic_launcher.png" width="135px" alt="Call53 LLamadas baratas y de calidad">
                </a>
                <a class="btn btn-default" href="<?=base_path?>getNow" target="_blank">Descárgala aquí</a>
            </div> -->
                <!-- Pricing item END -->
                <!-- Pricing item BEGIN -->
                <!-- Pricing item END -->
        </div>
    </form>
</div>

<?php include('theme/front/templates/default_footer.php'); ?>


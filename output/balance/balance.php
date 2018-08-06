<?php include('theme/front/templates/default_header.php');?>

<div class="prices-block content content-center" id="prices">
    <form id="form_recarga" method="post">
        <div class="container-fluid">
            <br><br>
            <div class="row">
                <?php echo print_messages() ?>
            </div>
            <!-- <img width="30%" class="margin-bottom-50" src="<?=base_path;?>images/img.png" alt="Phonealo LLamadas baratas y de calidad"> -->
            

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
            <div class="container-fluid">
                
            
            <div class="row">
 <div class="py-xs-3">
                <div class="col-md-4 col-md-offset-4 col-sm-6 col-xs-12" style="margin-top: 30px">
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
                            <label class="sr-only" for="mobile">Teléfono</label>
                            <div class="input-icon">
                                <i class="fa fa-mobile-phone"></i>
                                <input class="form-control" id="mobile" name="mobile" placeholder="Móvil" type="tel" value="<?=$mobile ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-md-offset-4 col-sm-6 col-xs-12">
                    <div class="porlet-body">
                        <h4>PIN</h4>
                        <div class="form-group">
                            <label class="sr-only" for="exampleInputEmail22">PIN</label>
                            <div class="input-icon">
                                <i class="fa fa-key"></i>
                                <input class="form-control" id="pin" name="pin" placeholder="PIN ####" type="password" >
                            </div>
                        </div>
                    </div>

                </div>

                <div class="col-md-4 col-md-offset-4 col-sm-6 col-xs-12">
                    <div class="porlet-body">
                        <h4>&nbsp;</h4>
                        <div class="form-group">
                            <div class="col-md-12 pull-left">
                                <button type="submit" class="btn blue">Entrar</button>
                                <button type="submit" name="remember" value="remember" class="btn yellow-crusta">Recordar PIN</button>
                            </div>
                        </div>
                    </div>

                </div>

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


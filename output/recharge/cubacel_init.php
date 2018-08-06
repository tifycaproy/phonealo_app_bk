<?php include('theme/front/templates/default_header.php');?>

<div class="modal fade" id="basic" tabindex="-1" role="basic" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                <h4 class="modal-title">Confirma los datos de la recarga</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-3 text-right">Correo electrónico:</div>
                    <div class="col-md-6 bold" id="confirm_correoe">juan@ckkss.com</div>
                </div>
                <div class="row">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-3 text-right">Teléfono Cubacel:</div>
                    <div class="col-md-6 bold" id="confirm_telcubacel">5353665454</div>
                </div>
                <div class="row">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-3 text-right">Recibe en Cuba:</div>
                    <div class="col-md-6 bold" id="confirm_inforecibe">Recibe 20 CUC + 10 CUC + 50Min + 50SMS</div>
                </div>
                <div class="row">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-3 text-right">Importe a pagar:</div>
                    <div class="col-md-6 bold" id="confirm_importe">20,00 EUR</div>
                </div>
                <br>
                <div class="row" id="confirm_usucall53">
                    <div class="col-md-1">&nbsp;</div>
                    <div class="col-md-9" id="confirm_usu"><strong>Juan perez - 0034 649482602</strong> <br>Recibe 2 minutos para llamar gratis a cuba con Call53 App</div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn dark btn-outline" data-dismiss="modal">Cancelar</button>
                <button type="button" class="btn green" id="dopay">Pasar a pago</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>

<div class="prices-block content content-center" id="prices">
        <form id="form_recarga" method="post">
            <div class="container">
                <?php if (count($age_data) > 0): ?>
                <div class="pull-right">
                    <span style="font-weight: bold; padding-top: 10px; margin-right: 5px;">
                        <?=$age_data['age_nombre'];?> Saldo: <?=euroFormat($age_data['age_saldo'])?>
                    </span>
                    <a href="?quit=now"> Salir</a>
                </div>
                <?php endif; ?>
                <br>
                <div class="row">
                    <?php echo print_messages() ?>
                </div>
                <h2 class="margin-bottom-50"><strong>RECARGAS</strong> <span style="color: #D90A2C"> CUBACEL</span> </h2>
                <div class="row blockusers">

                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="porlet-body">
                            <h4>Correo electrónico</h4>
                            <div class="form-group">
                                <label class="sr-only" for="mobilecu">Teléfono</label>
                                <div class="input-icon">
                                    <i class="fa fa-envelope"></i>
                                    <input class="form-control" id="correoe" name="correoe"  type="email"  required="required" title="Debe comenzar con 53 y 8 digitos restantes del movil Cubacel">
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php if (false) :?>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="porlet-body">
                            <h4>Servicio ETECSA:</h4>
                            <div class="form-group">
                                <label class="sr-only" for="exampleInputEmail22">Servicio en cuba</label>
                                <select class="form-control" name="service">
                                    <option value="1">Recarga Cubacel</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="porlet-body">
                            <h4>Tel.Cubacel a Recargar</h4>
                            <div class="form-group">
                                <label class="sr-only" for="mobilecu">Teléfono</label>
                                <div class="input-icon">
                                    <i class="fa fa-mobile-phone"></i>
                                    <input class="form-control" id="mobilecu" name="mobilecu" pattern="^0?53([0-9]{8})$" placeholder="53########" type="tel" value="<?=$mobilecu ?>" required="required" title="Debe comenzar con 53 y 8 digitos restantes del movil Cubacel">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-2 col-sm-6 col-xs-12">
                        <div class="porlet-body">
                            <h4>Selecciona oferta:</h4>
                            <div class="form-group">
                                <label class="sr-only" for="exampleInputEmail22">Oferta</label>
                                <select class="form-control" name="service" id="selectprod">
                                    <option value="0">Seleccione ...</option>
                                    <?php foreach ($a_servicios as $servi): ?>
                                        <option value="<?=$servi['servi_cod'] ?>" data-price="<?=$servi['servi_precioEUR']?>" data-infoprod="<?=$servi['servi_info']?>"><?=$servi['servi_desc'].' - '.$servi['servi_precioEUR'] ?> EUR</option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-5 col-sm-6 col-xs-12">
                        <div class="porlet-body">
                            <h4>&nbsp;</h4>
                            <div class="form-group">
                                <div class="col-md-12" style="text-align: left; font-weight: bold" id="prodinfo">

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
                <!-- Pricing item END -->
                <!-- Pricing item BEGIN -->
                <!-- Pricing item END -->
                <div class="row">
                    <?php if (count($age_data) == 0): ?>
                    <div class="blockusers">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="porlet-body">
                                <div class="form-group">
                                    <div class="col-md-12" style="text-align: left; " >
                                        Si eres cliente <span style="color: #336699; font-weight: bold">Call</span><span style="color:#D90A2C; font-weight: bold">53</span>, introduce tu número de móvil vinculado a tu cuenta y recibe 2 minutos de llamadas gratis a Cuba
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <div class="porlet-body">
                                <h4>Teléfono CALL53</h4>
                                <div class="form-group">
                                    <label class="sr-only" for="mobile">Teléfono</label>
                                    <div class="input-icon">
                                        <i class="fa fa-mobile-phone"></i>
                                        <input class="form-control" id="mobile" name="mobile" placeholder="Móvil" type="tel" value="<?=$mobile ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <div class="porlet-body">
                                <h4>País</h4>
                                <div class="form-group">
                                    <label class="sr-only" for="exampleInputEmail22">Teléfono CALL53</label>
                                    <div class="input-icon">
                                        <i class="fa fa-mobile-phone"></i>
                                        <select class="form-control" name="country" id="country">
                                            <?=dropDownPaisCliente($country); ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="blockagentes" style="display: none">
                        <div class="col-md-3 col-sm-6 col-xs-12">
                            <div class="porlet-body">
                                <div class="form-group">
                                    <div class="col-md-12" style="text-align: left; " >
                                        Si eres agente de recargas de <span style="color: #336699; font-weight: bold">Call</span><span style="color:#D90A2C; font-weight: bold">53</span>, introduce tu id de agente y tu password
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <div class="porlet-body">
                                <h4 class="labellogin">Agent ID</h4>
                                <div class="form-group">
                                    <label class="sr-only" for="exampleInputEmail22">Agente</label>
                                    <div class="input-icon">
                                        <i class="fa fa-user"></i>
                                        <input class="form-control" id="agent" name="agent" placeholder="ID Agente" type="text" value="<?=$agent ?>">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-6 col-xs-12">
                            <div class="porlet-body">
                                <h4>Clave</h4>
                                <div class="form-group">
                                    <label class="sr-only" for="mobile">Password</label>
                                    <input class="form-control" id="clave" name="clave"  type="password" value="">
                                </div>
                            </div>
                        </div>

                    </div>
                    <?php endif; ?>
                    <div class="col-md-3 col-sm-6 col-xs-12">
                        <div class="porlet-body">
                            <h4>&nbsp;</h4>
                            <div class="form-group">
                                <div class="col-md-12">
                                    <button type="submit" class="btn blue pull-left" id="btnrecarga" >Recargar</button>
                                    <button class="btn blue pull-left" style="display: none" id="btnsessionagent" >Iniciar Sesión</button>
                                    <?php if (count($age_data) == 0 && pget('access') == 'agent'): ?>
                                    <a href="#" id="btnagentes" class="btn yellow-casablanca">Agentes</a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <input name="freeforcall53" id="freeforcall53" value="0" type="hidden">
            <input name="okayform" id="okayform" value="0" type="hidden">
            <input name="pricerecarga" id="pricerecarga" value="0" type="hidden">

        </form>
    </div>
    <div id="user2min" class="services-block content-center"
         style="width: 150px; height: 60px; position: absolute; top: 4px; right: 180px; display: none">
        <i class="fa" style="margin-bottom: -10px;">2Min</i>
        <h3 id="name_user2min"></h3>
    </div>


<?php include('theme/front/templates/default_footer.php'); ?>


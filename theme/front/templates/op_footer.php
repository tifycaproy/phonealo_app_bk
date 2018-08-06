<!-- BEGIN PRE-FOOTER -->
<div class="pre-footer" id="contact">
    <div class="container">
        <div class="row">
            <!-- BEGIN BOTTOM ABOUT BLOCK -->
            <div class="col-md-4 col-sm-6 pre-footer-col" id="about">
                <h2>¿Qué es CALLl53 ?</h2>
                <p>CALL53 es una aplicación para hacer llamadas a cuba utilizando siempre las rutas de mejor calidad precio. </p>
                <p><b>Compromiso 50 céntimos en llamadas a Cuba</b></p>
                <p><b>Compromiso 10 céntimos en llamadas a Ecuador</b></p>
            </div>
            <!-- END BOTTOM ABOUT BLOCK -->
            <!-- BEGIN TWITTER BLOCK -->
            <div class="col-md-4 col-sm-6 pre-footer-col">
                <h2 class="margin-bottom-0">Conecta con nosotros</h2>
                <br>
                <p>Síguenos en twitter: #call53app </p>
                <p>Encuéntranos en facebook: call53app.</p>
            </div>
            <!-- END TWITTER BLOCK -->
            <div class="col-md-4 col-sm-6 pre-footer-col">
                <!-- BEGIN BOTTOM CONTACTS -->
                <h2>Contacta!</h2>
                <address class="margin-bottom-20">
                    Nicolas Copernico 12, Parque Tecnológico<br>
                    Paterna, C.Valenciana ES<br>
                    Teléfono: 961 323 356<br>
                    Email: <a href="mailto:info@call53.com">info@call53.com</a><br>
                    Skype: <a href="skype:call53app">call53app</a>
                </address>
                <!-- END BOTTOM CONTACTS -->
                <div class="pre-footer-subscribe-box hidden">
                    <h2>Newsletter</h2>
                    <form action="javascript:void(0);">
                        <div class="input-group">
                            <input type="text" placeholder="youremail@mail.com" class="form-control">
                <span class="input-group-btn">
                <button class="btn btn-primary" type="submit">Subscribe</button>
                </span>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- END PRE-FOOTER -->
<div class="footer">
    <div class="container">
        <div class="row">
            <!-- BEGIN COPYRIGHT -->
            <div class="col-md-6 col-sm-6">
                <div class="copyright"><?php echo date('Y'); ?> © Call53 LLamadas Baratas a Cuba - ALL Rights Reserved.</div>
            </div>
            <!-- END COPYRIGHT -->
            <!-- BEGIN SOCIAL ICONS -->
            <div class="col-md-6 col-sm-6 pull-right">
                <ul class="social-icons">
                    <li><a class="rss" data-original-title="rss" href="javascript:void(0);"></a></li>
                    <li><a class="facebook" data-original-title="facebook" href="javascript:void(0);"></a></li>
                    <li><a class="twitter" data-original-title="twitter" href="javascript:void(0);"></a></li>
                    <li><a class="googleplus" data-original-title="googleplus" href="javascript:void(0);"></a></li>
                    <li><a class="linkedin" data-original-title="linkedin" href="javascript:void(0);"></a></li>
                    <li><a class="youtube" data-original-title="youtube" href="javascript:void(0);"></a></li>
                    <li><a class="vimeo" data-original-title="vimeo" href="javascript:void(0);"></a></li>
                    <li><a class="skype" data-original-title="skype" href="javascript:void(0);"></a></li>
                </ul>
            </div>
            <!-- END SOCIAL ICONS -->
        </div>
    </div>
</div>
<!-- END FOOTER -->
<a href="#promo-block" class="go2top scroll"><i class="fa fa-arrow-up"></i></a>
<!--[if lt IE 9]>
<script src="<?=base_path;?>theme/front/assets/global/plugins/respond.min.js"></script>
<![endif]-->
<!-- Load JavaScripts at the bottom, because it will reduce page load time -->
<!-- Core plugins BEGIN (For ALL pages) -->
<script src="<?=base_path;?>theme/front/assets/global/plugins/jquery.min.js" type="text/javascript"></script>
<script src="<?=base_path;?>theme/front/assets/global/plugins/jquery-migrate.min.js" type="text/javascript"></script>
<script src="<?=base_path;?>theme/front/assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
<!-- Core plugins END (For ALL pages) -->
<!-- BEGIN RevolutionSlider -->
<script src="<?=base_path;?>theme/front/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.revolution.min.js" type="text/javascript"></script>
<script src="<?=base_path;?>theme/front/assets/global/plugins/slider-revolution-slider/rs-plugin/js/jquery.themepunch.tools.min.js" type="text/javascript"></script>
<script src="<?=base_path;?>theme/front/assets/frontend/onepage/scripts/revo-ini.js" type="text/javascript"></script>
<!-- END RevolutionSlider -->
<!-- Core plugins BEGIN (required only for current page) -->
<script src="<?=base_path;?>theme/front/assets/global/plugins/fancybox/source/jquery.fancybox.pack.js" type="text/javascript"></script><!-- pop up -->
<script src="<?=base_path;?>theme/front/assets/global/plugins/jquery.easing.js"></script>
<script src="<?=base_path;?>theme/front/assets/global/plugins/jquery.parallax.js"></script>
<script src="<?=base_path;?>theme/front/assets/global/plugins/jquery.scrollTo.min.js"></script>
<script src="<?=base_path;?>theme/front/assets/frontend/onepage/scripts/jquery.nav.js"></script>
<!-- Core plugins END (required only for current page) -->
<!-- Global js BEGIN -->
<script src="<?=base_path;?>theme/front/assets/frontend/onepage/scripts/layout.js" type="text/javascript"></script>
<script>
    $(document).ready(function() {
        Layout.init();

    });
</script>
<?php if (isset($js_page)): ?>
    <?php print_js_pluggins($js_page); ?>
<?php endif; ?>

<!-- Global js END -->
</body>
</html>
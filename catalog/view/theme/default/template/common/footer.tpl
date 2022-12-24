<footer>
  <div class="container">
    <div class="row">

	<?php 
	
	if ($tltblogs && $informations) {
		$cols = 2;
	} else if ($tltblogs || $informations) {
		$cols = 3;
	} else {
		$cols = 4;
	}
	
	?>

      <?php if ($informations) { ?>
      <div class="col-sm-<?= $cols;?>">
        <h5><?php echo $text_information; ?></h5>
        <ul class="list-unstyled">
          <?php foreach ($informations as $information) { ?>
          <li><a href="<?php echo $information['href']; ?>"><?php echo $information['title']; ?></a></li>
          <?php } ?>
        </ul>
      </div>
      <?php } ?>
      <div class="col-sm-<?= $cols;?>">
        <h5><?php echo $text_service; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $contact; ?>"><?php echo $text_contact; ?></a></li>
          <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
          <li><a href="<?php echo $sitemap; ?>"><?php echo $text_sitemap; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-<?= $cols;?>">
        <h5><?php echo $text_extra; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $manufacturer; ?>"><?php echo $text_manufacturer; ?></a></li>
          <li><a href="<?php echo $voucher; ?>"><?php echo $text_voucher; ?></a></li>
          <li><a href="<?php echo $affiliate; ?>"><?php echo $text_affiliate; ?></a></li>
          <li><a href="<?php echo $special; ?>"><?php echo $text_special; ?></a></li>
        </ul>
      </div>
      <div class="col-sm-<?= $cols;?>">
        <h5><?php echo $text_account; ?></h5>
        <ul class="list-unstyled">
          <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
          <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
          <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
          <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
        </ul>
	  </div>
	  <?php if ($tltblogs) { ?>
      <div class="col-sm-<?= $cols;?>">
        <h5>Наш блог</h5>
			<ul class="list-unstyled">
				<?php foreach ($tltblogs as $tltblog) { ?>
					<li><a href="<?php echo $tltblog['href']; ?>"><?php echo $tltblog['title']; ?></a></li>
				<?php } ?>
			</ul>
	  </div>
	  <?php } ?>
    </div>
    <hr>
    <p><?php echo $powered; ?></p>
  </div>
</footer>

<!--
OpenCart is open source software and you are free to remove the powered by OpenCart if you want, but its generally accepted practise to make a small donation.
Please donate via PayPal to donate@opencart.com
//-->

<!-- Theme created by Welford Media for OpenCart 2.0 www.welfordmedia.co.uk -->

<script type="text/javascript">
						/* UItoTop jQuery */
						jQuery(document).ready(function(){$().UItoTop({easingType:'easeOutQuint'});});
						(function($){
							$.fn.UItoTop = function(options) {
								var defaults = {
									text: 'To Top',
									min: 200,
									inDelay:600,
									outDelay:400,
									containerID: 'ToTop',
									containerHoverID: 'ToTopHover',
									scrollSpeed: 1600,
									easingType: 'linear'
								};
								var settings = $.extend(defaults, options);
								var containerIDhash = '#' + settings.containerID;
								var containerHoverIDHash = '#'+settings.containerHoverID;
								$('body').append('<span id="'+settings.containerID+'">'+settings.text+'</span>');
								$(containerIDhash).hide().click(function(event){
									$('html, body').animate({scrollTop: 0}, settings.scrollSpeed);
									event.preventDefault();
								})
								.prepend('<span id="'+settings.containerHoverID+'"></span>')
								.hover(function() {
										$(containerHoverIDHash, this).stop().animate({
											'opacity': 1
										}, 600, 'linear');
									}, function() { 
										$(containerHoverIDHash, this).stop().animate({
											'opacity': 0
										}, 700, 'linear');
									});			
								$(window).scroll(function() {
									var sd = $(window).scrollTop();
									if(typeof document.body.style.maxHeight === "undefined") {
										$(containerIDhash).css({
											'position': 'absolute',
											'top': $(window).scrollTop() + $(window).height() - 50
										});
									}
									if ( sd > settings.min ) 
										$(containerIDhash).fadeIn(settings.inDelay);
									else 
										$(containerIDhash).fadeOut(settings.Outdelay);
								});
						};
						})(jQuery);
						</script>

</body></html>
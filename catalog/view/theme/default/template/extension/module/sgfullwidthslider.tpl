
</div>
</div>
</div>
<style>

#content{
	min-height:0px;
}

.sg-pdnone{
	  padding-right: 0px;
    padding-left: 0px;
    max-width: 1920px;
}

.sg-pdnone .owl-wrapper-outer {
  border: unset;
  -webkit-border-radius: unset;
  border-radius: unset;
  -webkit-box-shadow: unset;
  box-shadow: unset;
}


</style>
<div class="container-fluid sg-pdnone">

<div id="slideshow<?php echo $module; ?>" class="owl-carousel" style="opacity: 1;">
  <?php foreach ($banners as $banner) { ?>
  <div class="item">
    <?php if ($banner['link']) { ?>
    <a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive slideimages" /></a>
    <?php } else { ?>
    <img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" class="img-responsive sg-slideimages" />
    <?php } ?>
  </div>
  <?php } ?>
</div>
<script type="text/javascript"><!--
$('#slideshow<?php echo $module; ?>').owlCarousel({
	items: 6,
	autoPlay: 3000,
	singleItem: true,
	navigation: true,
	navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
  pagination: true,
  transitionStyle : "fade"
});
--></script>

</div>

<div class="container">
<div class="row">
<div id="content" class="col-sm-12">
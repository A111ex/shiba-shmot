<?php echo $header; ?>
<div class="container" id="blog-page">
  <ul class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
    <?php } ?>
  </ul>
  <div class="row"><?php echo $column_left; ?>
    <?php if ($column_left && $column_right) { ?>
    <?php $class = 'col-sm-6'; ?>
    <?php } elseif ($column_left || $column_right) { ?>
    <?php $class = 'col-sm-9'; ?>
    <?php } else { ?>
    <?php $class = 'col-sm-12'; ?>
    <?php } ?>
    <div id="content" class="<?php echo $class; ?>">
    	<?php echo $content_top; ?>
      	<?php if ($show_title) { ?>
        <h1><?php echo $heading_title; ?></h1>
        <?php } ?>
        <?php if ($blog_image) { ?>
          <img class="blog-main-image" src="<?php echo $blog_image; ?>" alt="<?php echo $heading_title; ?>" title="<?php echo $heading_title; ?>" />
        <?php } ?>
        <div class="blog-description">
          <?php echo $description; ?>
        </div>   
        <?php if ($carousel_images){ ?>
          <br/>
          <h2>Галерея:</h2>
          <div class="blog-carousel-block">
            <div class="blog-carousel-images">
              <?php foreach ($carousel_images as $carousel_image) { ?>
                <?php if ($carousel_image['image']) { ?>
                  <a href="<?= $carousel_image['image']; ?>"><img src="<?= $carousel_image['image']; ?>" alt=""></a>
                <?php } ?>
                <?php } ?>
            </div>
          </div>
        <?php } ?>
      	<?php if ($tags) { ?>
        	<hr />
      		<p><?php echo $text_tags; ?>&nbsp;
        		<?php for ($i = 0; $i < count($tags); $i++) { ?>
        			<?php if ($i < (count($tags) - 1)) { ?>
        				<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['title']; ?></a>,&nbsp;
        			<?php } else { ?>
        				<a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['title']; ?></a>
        			<?php } ?>
        		<?php } ?>
      		</p>
      	<?php } ?>
      <?php if ($products) { ?>
        <br/>
      <h3><?php echo $text_related; ?></h3>
      <div class="row"  id="blog-products">
        <?php foreach ($products as $product) { ?>
          <div class="product-layout" >
          <div class="product-thumb">
            <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a></div>
            <div>
              <div class="caption">
                <p class="manufacturer"><?php echo $text_manufacturer; ?> <a href="<?php echo $product['manufacturers']; ?>" ><?php echo $product['manufacturer']; ?></a></p>
                <h4><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></h4>
                <p class="d-none"><?php echo $product['description']; ?></p>
                <p class="model"><?php echo $text_model; ?> <?php echo $product['model']; ?></p>
                
                <?php if ($product['price']) { ?>
                <p class="price">
                  <?php if (!$product['special']) { ?>
                  <?php echo $product['price']; ?>
                  <?php } else { ?>
                  <span class="price-new"><?php echo $product['special']; ?></span> <span class="price-old"><?php echo $product['price']; ?></span>
                  <?php } ?>
                  <?php if ($product['tax']) { ?>
                  <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
                  <?php } ?>
                </p>
                <?php } ?>
                <?php if ($product['rating']) { ?>
                <div class="rating">
                  <?php for ($i = 1; $i <= 5; $i++) { ?>
                  <?php if ($product['rating'] < $i) { ?>
                  <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } else { ?>
                  <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"></i><i class="fa fa-star-o fa-stack-2x"></i></span>
                  <?php } ?>
                  <?php } ?>
                </div>
                <?php } ?>
              </div>
              
            </div>
          </div>
        </div>
        <?php } ?>
      </div>
      <?php } ?>
      <?php echo $content_bottom; ?></div>
    <?php echo $column_right; ?></div>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.popup_imgs').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});
});
//--></script>
<?php echo $footer; ?>

<script type="text/javascript"><!--
  $('.blog-carousel-images').owlCarousel({
    stopOnHover: true,
    items: 2,
    autoPlay: 3000,
    navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
    navigation: true,
    pagination: true
  });
--></script>


<script type="text/javascript"><!--
$('#blog-products').owlCarousel({
  stopOnHover: true,
	items: 6,
  autoPlay: 3000,
  navigationText: ['<i class="fa fa-chevron-left fa-5x"></i>', '<i class="fa fa-chevron-right fa-5x"></i>'],
	navigation: true
});
--></script>
<div id="tabs-<?php echo $module; ?>" class="htabs product_tabs">
  <ul class="nav">

  <?php if ($featured_products || $bestseller_products || $latest_products || $special_products) { ?>
      <li class="chevrons-mobile">
        <div class="chevrons-product-tab">
          <span class="chevron-left" onclick="listProductTabSlide('prev');"></span>
          <span class="chevron-right" onclick="listProductTabSlide('next');"></span>
        </div>
      </li>
    <?php } ?>

  <?php if ($latest_products) { ?>
      <li>
        <a href="#tab-latest-<?php echo $module; ?>" data-toggle="tab" class="selected">
          <h3 class="product-title"><?php echo $tab_latest; ?></h3>
        </a>
      </li>
  <?php } ?>


    <?php if ($featured_products) { ?>
      <li class="active">
        <a href="#tab-featured-<?php echo $module; ?>" data-toggle="tab">
          <h3 class="product-title"><?php echo $tab_featured; ?></h3>
        </a>
      </li>
    <?php } ?>

    <?php if ($bestseller_products) { ?>
      <li>
        <a href="#tab-bestseller-<?php echo $module; ?>" data-toggle="tab">
          <h3 class="product-title"><?php echo $tab_bestseller; ?></h3>
        </a>
      </li>
    <?php } ?>


    <?php if ($special_products) { ?>
      <li>
        <a href="#tab-special-<?php echo $module; ?>" data-toggle="tab">
          <h3 class="product-title"><?php echo $tab_special; ?></h3>
        </a>
      </li>
    <?php } ?>

    <?php if ($featured_products || $bestseller_products || $latest_products || $special_products) { ?>
      <li class="chevrons-desktop">
        <div class="chevrons-product-tab">
          <span class="chevron-left" onclick="listProductTabSlide('prev');"></span>
          <span class="chevron-right" onclick="listProductTabSlide('next');"></span>
        </div>
      </li>
    <?php } ?>
  </ul>
</div>

<div class="product-module product_tab-module row no-gutters mb-20">
  <?php if ($latest_products) { ?>
    <div id="tab-latest-<?php echo $module; ?>" class="tab-content">

      <?php
      $counter = 0;
      $numItems = count($latest_products);
      ?>

      <div class="box-product tab-latest_products">
        <?php foreach ($latest_products as $product) { ?>

          <?php
          $counter++;

          if ($counter % 2 === 1) {
            $borderTop = '';
            echo '<div>';
          } else {
            $borderTop = ' bordered-top';
          }

          if ($counter % 2 === 1 && $counter === $numItems) {
            $borderBottom = ' bordered-bottom';
          } else {
            $borderBottom = '';
          }
          ?>

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

          <?php
          if ($counter % 2 === 0 || $counter === $numItems) {
            echo '</div>';
          }
          ?>

        <?php } ?>
      </div>
    </div>
  <?php } ?>


  <?php if ($featured_products) { ?>
    <div id="tab-featured-<?php echo $module; ?>" class="tab-content">
      <div class="box-product tab-featured_products">

        <?php
        $counter = 0;
        $numItems = count($featured_products);
        ?>

        <?php foreach ($featured_products as $product) { ?>

          <?php
          $counter++;
          if ($counter % 2 === 1) {
            $borderTop = '';
            echo '<div>';
          } else {
            $borderTop = ' bordered-top';
          }
          if ($counter % 2 === 1 && $counter === $numItems) {
            $borderBottom = ' bordered-bottom';
          } else {
            $borderBottom = '';
          }
          ?>

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

          <?php
          if ($counter % 2 === 0 || $counter === $numItems) {
            echo '</div>';
          }
          ?>

        <?php } ?>
      </div>
    </div>
  <?php } ?>

  <?php if ($bestseller_products) { ?>

    <div id="tab-bestseller-<?php echo $module; ?>" class="tab-content">
      <div class="box-product tab-bestseller_products">

        <?php
        $counter = 0;
        $numItems = count($bestseller_products);
        ?>

        <?php foreach ($bestseller_products as $product) { ?>

          <?php
          $counter++;
          if ($counter % 2 === 1) {
            $borderTop = '';
            echo '<div>';
          } else {
            $borderTop = ' bordered-top';
          }
          if ($counter % 2 === 1 && $counter === $numItems) {
            $borderBottom = ' bordered-bottom';
          } else {
            $borderBottom = '';
          }
          ?>

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

          <?php
          if ($counter % 2 === 0 || $counter === $numItems) {
            echo '</div>';
          }
          ?>

        <?php } ?>
      </div>
    </div>
  <?php } ?>

  <?php if ($special_products) { ?>

    <div id="tab-special-<?php echo $module; ?>" class="tab-content">
      <div class="box-product tab-special_products">

        <?php
        $counter = 0;
        $numItems = count($special_products);
        ?>

        <?php foreach ($special_products as $product) { ?>

          <?php
          $counter++;
          if ($counter % 2 === 1) {
            $borderTop = '';
            echo '<div>';
          } else {
            $borderTop = ' bordered-top';
          }
          if ($counter % 2 === 1 && $counter === $numItems) {
            $borderBottom = ' bordered-bottom';
          } else {
            $borderBottom = '';
          }
          ?>

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


          <?php
          if ($counter % 2 === 0 || $counter === $numItems) {
            echo '</div>';
          }
          ?>

        <?php } ?>
      </div>
    </div>
  <?php } ?>
</div>

<script type="text/javascript">
  $('#tabs-<?php echo $module; ?> a').tabs();
</script>
<div class="clear"></div>


<script type="text/javascript">
  $('.tab-latest_products').owlCarousel({
    stopOnHover: true,
    items: 6,
    itemsDesktop: [1399, 5],
    itemsDesktopSmall: [1199, 4],
    itemsTablet: [1080, 3],
    itemsTabletSmall: [768, 2],
    autoPlay: 6000,
    navigation: false,
    pagination: false
  });

  $('.tab-featured_products').owlCarousel({
    stopOnHover: true,
    items: 6,
    itemsDesktop: [1399, 5],
    itemsDesktopSmall: [1199, 4],
    itemsTablet: [1080, 3],
    itemsTabletSmall: [768, 2],
    autoPlay: 6000,
    navigation: false,
    pagination: false
  });

  $('.tab-bestseller_products').owlCarousel({
    stopOnHover: true,
    items: 6,
    itemsDesktop: [1399, 5],
    itemsDesktopSmall: [1199, 4],
    itemsTablet: [1080, 3],
    itemsTabletSmall: [768, 2],
    autoPlay: 6000,
    navigation: false,
    pagination: false
  });

  $('.tab-special_products').owlCarousel({
    stopOnHover: true,
    items: 6,
    itemsDesktop: [1399, 5],
    itemsDesktopSmall: [1199, 4],
    itemsTablet: [1080, 3],
    itemsTabletSmall: [768, 2],
    autoPlay: 6000,
    navigation: false,
    pagination: false
  });


  function listProductTabSlide(direction){

    if (direction == 'next') {
      let owl = $(".tab-content:visible .owl-carousel").data('owlCarousel');;
      // console.log(owl);
      owl.next();
    } else if (direction == 'prev') {
      let owl = $(".tab-content:visible .owl-carousel").data('owlCarousel');;
      // console.log(owl);
      owl.prev();
    }

  }
</script>
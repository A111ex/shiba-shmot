<!DOCTYPE html>
<!--[if IE]><![endif]-->
<!--[if IE 8 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie8"><![endif]-->
<!--[if IE 9 ]><html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" class="ie9"><![endif]-->
<!--[if (gt IE 9)|!(IE)]><!-->
<html dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>">
<!--<![endif]-->

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Cache-Control" content="no-cache, max-age=0, must-revalidate, no-store">
    <title><?php echo $title;  ?></title>
    <base href="<?php echo $base; ?>" />
    <?php if ($description) { ?>
    <meta name="description" content="<?php echo $description; ?>" />
    <?php } ?>
    <?php if ($keywords) { ?>
    <meta name="keywords" content="<?php echo $keywords; ?>" />
    <?php } ?>
    <meta property="og:title" content="<?php echo $title; ?>" />
    <meta property="og:type" content="website" />
    <meta property="og:url" content="<?php echo $og_url; ?>" />
    <?php if ($og_image) { ?>
    <meta property="og:image" content="<?php echo $og_image; ?>" />
    <?php } else { ?>
    <meta property="og:image" content="<?php echo $logo; ?>" />
    <?php } ?>
    <meta property="og:site_name" content="<?php echo $name; ?>" />
    <script src="catalog/view/javascript/jquery/jquery-2.1.1.min.js" type="text/javascript"></script>
    <link href="catalog/view/javascript/bootstrap/css/bootstrap.min.css" rel="stylesheet" media="screen" />
    <script src="catalog/view/javascript/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
    <link href="catalog/view/javascript/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
    <link href="//fonts.googleapis.com/css?family=Rubik:300,400,500,700" rel="stylesheet">
    <link href="catalog/view/theme/default/stylesheet/stylesheet.css" rel="stylesheet">
    <?php foreach ($styles as $style) { ?>
    <link href="<?php echo $style['href']; ?>" type="text/css" rel="<?php echo $style['rel']; ?>"
        media="<?php echo $style['media']; ?>" />
    <?php } ?>
    <script src="catalog/view/javascript/common.js" type="text/javascript"></script>
    <?php foreach ($links as $link) { ?>
    <link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
    <?php } ?>
    <?php foreach ($scripts as $script) { ?>
    <script src="<?php echo $script; ?>" type="text/javascript"></script>
    <?php } ?>
    <?php foreach ($analytics as $analytic) { ?>
    <?php echo $analytic; ?>
    <?php } ?>
</head>

<body class="<?php echo $class; ?> <?php if ($darkMode) { echo ' dark-mode'; }?>" onunload="">
<iframe style="height:0px;width:0px;visibility:hidden" src="about:blank">
    this frame prevents back forward cache
</iframe>
<nav id="top">
<?php if ($config_topper_text) { ?>
  <?php if ($config_topper_url) { ?>
    <a href="<?php echo $config_topper_url;?>" id="topper"></a>
  <?php } else {?>
    <span id="topper"></span>
  <?php } ?>
<?php } ?>
  <div class="container">

  <div id="top-links" class="nav pull-right">
      <ul class="list-inline">

        <li class="dropdown separated"><a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="dropdown-toggle" data-toggle="dropdown"><i class="fa fa-user"></i> <span class="hidden-xs hidden-sm hidden-md top-text"><?php echo $text_account; ?></span> <span class="top-chevron"><i class="fa fa-chevron-down top-chevron"></i></span></a>
          <ul class="dropdown-menu dropdown-menu-right">
            <?php if ($logged) { ?>
            <li><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a></li>
            <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
            <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
            <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
            <li><a href="<?php echo $logout; ?>"><?php echo $text_logout; ?></a></li>
            <?php } else { ?>
            <li><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></li>
            <li><a href="<?php echo $login; ?>"><?php echo $text_login; ?></a></li>
            <?php } ?>
          </ul>
        </li>
        <li><a href="<?php echo $compare; ?>" id="compare-total" title="<?php echo $text_compare; ?>"><i class="fa fa-bar-chart"></i> <span class="hidden-xs hidden-sm hidden-md"><?php echo $text_compare; ?></span></a></li>
        
        <li>
          <span class="theme-toggler">
            ТЕМНАЯ ТЕМА
            <label class="switch">        
              <input type="checkbox" id="theme-toggler" <?php if ($darkMode === true) { echo 'checked'; }?>>
              <span class="slider round"></span>
            </label>
          </span>
        </li>
      </ul>
        
    </div>

    <div id="logo">
      <?php if ($logo) { ?>
        <?php if ($home == $og_url) { ?>
            <p id="left">Магазин кросовок, <br> спортивной одежды <br> и аксессуаров</p>
          <img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" />
          <p id="right">Для мужчин, <br> женщин, и детей.</p>
        <?php } else { ?>
            <p id="left">Магазин кросовок, <br> спортивной одежды <br> и аксессуаров</p>
          <a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" class="img-responsive" /></a>
          <p id="right">Для мужчин, <br> женщин, и детей.</p>
        <?php } ?>
      <?php } else { ?>
        <h1><a href="<?php echo $home; ?>"><?php echo $name; ?></a></h1>
      <?php } ?>
    </div>

  <?php if ($categories) { ?>
    <div id="top-menu" class="hidden-xs">
        <div class="container">
        <nav id="menu" class="navbar">
            <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
            <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
              <li><a href="<?php echo $home; ?>">Главная</a></li>
              
              <?php foreach ($categories as $category) { ?>
                <?php if ($category['children']) { ?>
                    <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle"><?php echo $category['name']; ?></a>
                      <div class="dropdown-menu">
                        <div class="dropdown-inner">
                          <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
                          <ul class="list-unstyled">
                            <?php foreach ($children as $child) { ?>
                            <li>
                                <a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
                                <?php if($child['children']){ ?>
                                    <ul class="list-unstyled menu-category-level-3">
                                        <?php foreach ($child['children'] as $child) { ?>
                                            <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </li>
                            <?php } ?>
                          </ul>
                          <?php } ?>
                        </div>
                        <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a> </div>
                    </li>
                <?php } else { ?>
                    <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                <?php } ?>
              <?php } ?>

              <li><a class="text-danger" href="/specials/">Товары со скидкой</a></li>
              <li><a href="/brands/">Бренды</a></li>
              <li><a href="/about_us.html">О нас</a></li>
              <li><a href="/contact-us/">Контакты</a></li>
              <li><a href="/blog/">Наш блог</a></li>
            </ul>
            </div>
        </nav>
        </div>
    </div>
    <?php } ?>

    
  <?php if ($categories) { ?>
    <div id="top-menu" class="hidden-sm hidden-md hidden-lg">
        <div class="container">
        <nav id="menu" class="navbar">
            <div class="navbar-header"><span id="category" class="visible-xs"><?php echo $text_category; ?></span>
            <button type="button" class="btn btn-navbar navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse"><i class="fa fa-bars"></i></button>
            </div>
            <div class="collapse navbar-collapse navbar-ex1-collapse">
            <ul class="nav navbar-nav">
              <li><a href="<?php echo $home; ?>">Главная</a></li>
              
              <?php foreach ($categories as $category) { ?>
                <?php if ($category['children']) { ?>
                    <li class="dropdown"><a href="<?php echo $category['href']; ?>" class="dropdown-toggle" data-toggle="dropdown"><?php echo $category['name']; ?></a>
                      <div class="dropdown-menu">
                        <div class="dropdown-inner">
                          <?php foreach (array_chunk($category['children'], ceil(count($category['children']) / $category['column'])) as $children) { ?>
                          <ul class="list-unstyled">
                            <?php foreach ($children as $child) { ?>
                            <li>
                                <a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
                                <?php if($child['children']){ ?>
                                    <ul class="list-unstyled menu-category-level-3">
                                        <?php foreach ($child['children'] as $child) { ?>
                                            <li><a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a></li>
                                        <?php } ?>
                                    </ul>
                                <?php } ?>
                            </li>
                            <?php } ?>
                          </ul>
                          <?php } ?>
                        </div>
                        <a href="<?php echo $category['href']; ?>" class="see-all"><?php echo $text_all; ?> <?php echo $category['name']; ?></a> </div>
                    </li>
                <?php } else { ?>
                    <li><a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a></li>
                <?php } ?>
              <?php } ?>

              <li><a class="text-danger" href="/specials/">Товары со скидкой</a></li>
              <li><a href="/brands/">Бренды</a></li>
              <li><a href="/about_us.html">О нас</a></li>
              <li><a href="/contact-us/">Контакты</a></li>
              <li><a href="/blog/">Наш блог</a></li>
            </ul>
            </div>
        </nav>
        </div>
    </div>
    <?php } ?>

    <?php echo $currency; ?>
    <?php echo $language; ?>
    
  </div>
</nav>
<header>
  <div class="container">
    <div class="row">
      <div class="col-xs-12 col-sm-12 col-md-2 col-lg-2 dropdown contacts-dropdown">
        <!-- contacts -->
        <a href="<?php echo $contact; ?>" title="Контакты" class="dropdown-toggle contacts" data-toggle="dropdown">
                <i class="fa fa-phone"></i>
                <span class="contacts-title">Наши контакты</span>
                <span class="top-text contacts-phone-number"><?php echo $telephone; ?></span>
            </a>
            <ul class="dropdown-menu">
                <li><a href="tel:<?php echo preg_replace('/[^\d+]+/', '', $telephone); ?>"><i class="fa fa-phone" style="margin-right:10px;"></i><?php echo $telephone; ?></a></li>

                <?php if ($whatsapp) { ?>
                  <li><a target="_blank" title="WhatsApp" href="https://api.whatsapp.com/send?phone=<?php echo preg_replace('/[^0-9]/', '', $whatsapp); ?>"><i class="fa fa-whatsapp" style="margin-right:10px;"></i>WhatsApp</a></li>
                <?php } ?>

                <?php if ($viber) { ?>
                  <li><a target="_blank" title="Viber" href="viber://chat?number=<?php echo preg_replace('/[^0-9]/', '', $viber); ?>"><i class="fa fa-phone-square" style="margin-right:10px;"></i>Viber</a></li>
                <?php } ?>

                <li><a href="mailto:<?php echo $email; ?>"><i class="fa fa-envelope-o" style="margin-right:10px;"></i>Email</a></li>

                <?php if ($telegram) { ?>
                  <li><a target="_blank" href="<?= $telegram; ?>"><i class="fa fa-paper-plane-o" style="margin-right:10px;"></i>Telegram</a></li>
                <?php } ?>

                <?php if ($instagram) { ?>
                  <li><a target="_blank" href="<?= $instagram; ?>"><i class="fa fa-instagram" style="margin-right:10px;"></i>Instagram</a></li>
                <?php } ?>

                <?php if ($vkontakte) { ?>
                  <li><a target="_blank" href="<?= $vkontakte; ?>"><i class="fa fa-vk" style="margin-right:10px;"></i>Вконтакте</a></li>
                  <?php } ?>

                <li><a href="<?php echo $contact; ?>"><i class="fa fa-comment-o" style="margin-right:10px;"></i>Контакты</a></li>
            </ul>
      </div>
      <div id="search-block" class="col-xs-12 col-sm-12 col-md-5 col-lg-6 header-search-block"><?php echo $search; ?>
      </div>
      
      <div id="header-user-block" class="col-xs-12 col-sm-12 col-md-5 col-lg-4 text-right">
      <?php if ($logged) { ?>
        <div class="userblock account account-logged">
      <?php } else { ?>
        <div class="userblock account">
      <?php } ?> 
          <div>
          <?php if ($logged) { ?>
            <a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>">
          <?php } else { ?>
            <a href="<?php echo $account; ?>" title="<?php echo $text_account; ?>" class="userblock-link">
          <?php } ?> 

              <?php if ($logged) { ?>
                <div class="userblock-cont-logged">
              <?php } else { ?>
                <div class="userblock-cont">
              <?php } ?>
                <div class="userblock-icon">
                  <div class="userblock-svg">
                    <svg width="42" height="42" viewBox="0 0 12 12" class="icon icon-person" fill="currentColor" xmlns="https://www.w3.org/2000/svg"><path fill-rule="evenodd" clip-rule="evenodd" d="M8.05933 3C8.05933 4.08628 7.15593 5 6.00027 5C4.84461 5 3.94121 4.08628 3.94121 3C3.94121 1.91372 4.84461 1 6.00027 1C7.15593 1 8.05933 1.91372 8.05933 3ZM9.05933 3C9.05933 4.65685 7.68974 6 6.00027 6C4.3108 6 2.94121 4.65685 2.94121 3C2.94121 1.34315 4.3108 0 6.00027 0C7.68974 0 9.05933 1.34315 9.05933 3ZM3.14849 7.43385C3.82551 7.83759 4.79598 8.2 6.00027 8.2C7.20456 8.2 8.17503 7.83759 8.85205 7.43385C9.10493 7.28305 9.27575 7.196 9.40642 7.15938C9.42431 7.15437 9.43809 7.1512 9.44831 7.1492C9.89262 7.43654 10.3821 8.07065 10.6882 8.79104C10.8121 9.08268 10.9185 9.5618 10.994 10.136C11.0478 10.5454 10.71 11 10.1184 11H6.00027H1.88215C1.29058 11 0.952733 10.5454 1.00655 10.136C1.08204 9.5618 1.18846 9.08267 1.31237 8.79104C1.61844 8.07065 2.10792 7.43654 2.55223 7.1492C2.56246 7.15119 2.57623 7.15437 2.59412 7.15938C2.72479 7.196 2.8956 7.28305 3.14849 7.43385ZM9.47263 7.14595C9.47259 7.146 9.47159 7.14607 9.46977 7.14601C9.47176 7.14587 9.47267 7.1459 9.47263 7.14595ZM2.52791 7.14595C2.52787 7.1459 2.52876 7.14587 2.53073 7.14601L2.52791 7.14595ZM2.0691 6.27192C2.57065 5.96723 3.15666 6.27441 3.66068 6.57498C4.20508 6.89964 5.00059 7.2 6.00027 7.2C6.99995 7.2 7.79546 6.89964 8.33986 6.57498C8.84388 6.27441 9.42989 5.96723 9.93144 6.27192C10.626 6.69388 11.2461 7.54694 11.6085 8.4C11.7871 8.82027 11.907 9.40919 11.9855 10.0057C12.1294 11.1008 11.223 12 10.1184 12H6.00027H1.88215C0.777579 12 -0.128885 11.1008 0.0150857 10.0057C0.0935011 9.40919 0.213433 8.82027 0.391993 8.4C0.754431 7.54694 1.37452 6.69388 2.0691 6.27192Z"></path></svg>
                  </div>
                </div>                
              </div>  
              <?php if ($logged) { ?>
                <div class="userblock-cont-logged">
                  <div class="userblock-info userblock-info-logged">
                    <div class="userblock-text"><?php echo $firstname; ?></div>
                    <div class="userblock-text"><?php echo $lastname; ?></div>
                  </div>
                </div>
              
            <?php } else { ?>
              <div class="userblock-info">                    
                <div class="userblock-text"><a href="<?php echo $register; ?>"><?php echo $text_register; ?></a></div>
                <div class="userblock-text">или <span class="userblock-text-danger"><a href="<?php echo $login; ?>" id="popup-login" ><?php echo $text_login; ?></a></span></div>
              </div>
            <?php } ?>              
            </a>
            
          </div>
        </div>

        <div class="userblock wishlist">
          <div>
            <a href="<?php echo $wishlist; ?>" class="userblock-link">
              <div class="userblock-cont">
                <div class="userblock-icon">
                  <span class="userblock-count wishlist-count"><?php echo $text_total_wishlist; ?></span>
                  <div class="userblock-svg">
                    <svg xmlns="https://www.w3.org/2000/svg" width="42" height="42" viewBox="0 0 16 16" class="icon icon-heart" fill="currentColor"><path fill-rule="evenodd" d="M8 2.748l-.717-.737C5.6.281 2.514.878 1.4 3.053c-.523 1.023-.641 2.5.314 4.385.92 1.815 2.834 3.989 6.286 6.357 3.452-2.368 5.365-4.542 6.286-6.357.955-1.886.838-3.362.314-4.385C13.486.878 10.4.28 8.717 2.01L8 2.748zM8 15C-7.333 4.868 3.279-3.04 7.824 1.143c.06.055.119.112.176.171a3.12 3.12 0 0 1 .176-.17C12.72-3.042 23.333 4.867 8 15z"/></svg>
                  </div>
                </div>
              </div>
            </a>
          </div>
        </div>

        <?php echo $cart; ?>
      </div>
    </div>
  </div>
</header>


<?php if ($config_topper_text) { ?>
<script>
  function animateText(id, text, i) {
            let newText = text.substring(0, i);

            for (let j=i; j <= text.length; j++ ) {
                newText += '&#8199;';
            } 

            document.getElementById(id).innerHTML = newText;
            i++;
            if (i > text.length)  {
                i = 1;
                setTimeout("animateText('" + id + "','" + text + "'," + i + ")", 5000);
            } else {
                setTimeout("animateText('" + id + "','" + text + "'," + i + ")", 100);
            }           
        }
        setTimeout("animateText('topper','<?php echo $config_topper_text; ?>',1)", 100);
</script>
<?php } ?>

<script>
  $("#theme-toggler").click(function() {
    

    $.ajax({
      url: "index.php?route=common/header/darkModeToggler",
      type: "post",
      dataType: 'json',
      data: "toggleMode=true",
      success: function(json) {
        console.log(json);
        $('body').toggleClass('dark-mode');
      }
    });
  });
</script>
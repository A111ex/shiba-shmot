<div class="accordion border" id="smartFilter">
<?php if ($pricr_filter_status==1) { ?>
  <div class="card">
    <div class=" card-head" id="headingOne">
      <h2 class="text h2" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
        <?php echo $text_price; ?>
      </h2>
    </div>

    <div id="collapseOne" class="collapse in" aria-labelledby="headingOne" data-parent="#smartFilter">
      <div class="card-body">
     <div class="range-slider"><span>От 
    <input type="number" id="text_min_price" value="<?php echo round($prices['min_price']); ?>" min="<?php echo $prices['min_price']; ?>" max="<?php echo $prices['max_price']; ?>"/> До
    
    <input type="number" id="text_max_price" value="<?php echo round($prices['max_price']); ?>" min="<?php echo $prices['min_price']; ?>" max="<?php echo $prices['max_price']; ?>"/></span>
  <input value="<?php echo $prices['min_price']; ?>" name="price_min" min="<?php echo $prices['min_price']; ?>" max="<?php echo $prices['max_price']; ?>" step="10" type="range"/ id="min_price">
  <input value="<?php echo $prices['max_price']; ?>" name="price_max"  min="<?php echo $prices['min_price']; ?>" max="<?php echo $prices['max_price']; ?>" step="10" type="range"/ id="max_price">
  <svg width="100%" height="24">
    <line x1="4" y1="0" x2="360" y2="0" stroke="#444" stroke-width="12" stroke-dasharray="1 28"></line>
  </svg>
</div>
      </div>
    </div>
  </div>
  <?php } ?>
  <?php if ($category_filter_status==1) { ?>
    <?php if ($categories) { ?>
  <div class="card">
    <div class=" card-head" id="cat_row_<?php echo $categories['category_id']; ?>">
      <h2 class="text h2" data-toggle="collapse" data-target="#col_<?php echo $categories['category_id']; ?>" aria-expanded="true" aria-controls="col_<?php echo $categories['category_id']; ?>">
        <?php echo $text_categories; ?>
      </h2>
    </div>
   
    <div id="col_<?php echo $categories['category_id']; ?>" class="collapse in" aria-labelledby="cat_row_<?php echo $categories['category_id']; ?>" data-parent="#cat_row_<?php echo $categories['category_id']; ?>">
      <div class="card-body">
   <!--  <input type="checkbox" id="cat_<?php echo $category_id; ?>" name="category" value="<?php echo $category_id; ?>" checked="checked" style="display:none;"> -->
   <?php if ($categories) { ?>

    <?php foreach ($categories as $category) { ?>

<ul id="category-tabs">
    <li class="main-category">
      <input type="checkbox" id="cat_<?php echo $category['category_id']; ?>" name="category" value="<?php echo $category['category_id']; ?>">
      <label for="cat_<?php echo $category['category_id']; ?>">
        <a href="javascript:void(0)" class="main-category" data-id='<?php echo $category['category_id']; ?>'><?php echo $category['name']; ?><i class="fa fa-plus"></i>
        </a>
      </label>
        <ul class="sub-category-tabs" style="display: none;">
        <?php if ($category['children']) { ?>
          <?php foreach ($category['children'] as $children) { ?>
            <li><input type="checkbox" name="category"  id="cat_<?php echo $children['category_id']; ?>"  value="<?php echo $children['category_id']; ?>"><label for="cat_<?php echo $children['category_id']; ?>"><?php echo $children['name']; ?></label></li>
            <?php } ?>
            <?php } ?>
        </ul>
    </li>
</ul>
<?php } ?>
<?php } ?>
      </div>
    </div>
   
  </div>
  <?php } ?>
  <?php } ?>

  <?php if ($manufacturer_filter_status==1) { ?>
    <?php if (!is_null($manufacturers) && !empty($manufacturers)) { ?>
   <div class="card">
    <div class=" card-head" id="manu_row_manufacturer">
      <h2 class="text h2" data-toggle="collapse" data-target="#coll_manufacturer" aria-expanded="true" aria-controls="coll_manufacturer">
      <?php echo $text_manufrecturer; ?>
      </h2>
    </div>

    <div id="coll_manufacturer" class="collapse in" aria-labelledby="manu_row_manufacturer" data-parent="#manu_row_manufacturer">

      <div class="card-body">

<ul class="list-group">
          
        <?php if (!is_null($manufacturers) && !empty($manufacturers)) { ?>
          <?php foreach ($manufacturers as $manufacturer) { ?>
  <li class="list-group-item ">
     <input name="manufacture" type="checkbox" id="manu_<?php echo $manufacturer['manufacturer_id']; ?>" value="<?php echo $manufacturer['manufacturer_id']; ?>">
        <label for="manu_<?php echo $manufacturer['manufacturer_id']; ?>">
        <?php if ($manufacturer['image']) { ?>
            <img src ="<?php echo $manufacturer['image']; ?>" width="25px" height="25px"><?php } ?><?php echo $manufacturer['name']; ?>
        </label>
  </li>
  <?php } ?>
  <?php } ?>
</ul>
      </div>
    </div>
  </div>
  <?php } ?>
  <?php } ?>
  
  <?php if ($optin_status==1) { ?>
      <?php if ($options) { ?>
        <?php foreach ($options as $option) { ?>
          <div class="card">
            <div class=" card-head" id="option_row_<?php echo $option['option_id']; ?>">
              <h2 class="text h2" data-toggle="collapse" data-target="#colleps_option_row_<?php echo $option['option_id']; ?>" aria-expanded="true" aria-controls="colleps_row_data-page<?php echo $option['option_id']; ?>">
                <?php echo $option['name']; ?>
              </h2>
            </div>

            <div id="colleps_option_row_<?php echo $option['option_id']; ?>" class="collapse in" aria-labelledby="option_row_<?php echo $option['option_id']; ?>" data-parent="#option_row_<?php echo $option['option_id']; ?>">

              <div class="card-body">

                <ul class="list-group">
                  <?php if (!is_null($option['option_values']) && !empty($option['option_values'])) { ?>
                    <?php foreach ($option['option_values'] as $op_value) { ?>
                      <li class="list-group-item ">
                        <input name="options" type="checkbox" value="<?php echo $op_value['option_value_id']; ?>" id="option_<?php echo $op_value['option_value_id']; ?>" >
                            <label for="option_<?php echo $op_value['option_value_id']; ?>" >
                              <?php if ($op_value['image']) { ?>
                                  <img src ="<?php echo $img_url; ?><?php echo $op_value['image']; ?>" width="25px" height="25px"><?php } ?><?php echo $op_value['name']; ?>
                            </label>
                      </li>
                  <?php } ?>
                  <?php } ?>
                </ul>
              </div>
            </div>
          </div>
        <?php } ?>
    <?php } ?>
  <?php } ?>

  <?php if ($attribute_status==1) { ?>
    <?php if ($attributes) { ?>
      <?php foreach ($attributes as $attrGroupKey => $attribute_group) { ?>
        <?php foreach ($attribute_group['attribute_values'] as $attrKey => $attribute) { ?>

          <div class="card">
            <div class=" card-head" id="attribute_row_<?php echo $attrGroupKey; ?>">
              <h2 class="text h2 <?= ($attribute['name'] == 'Цвет') ? '' : 'collapsed' ?>" data-toggle="collapse" data-target="#colleps_attr_row_<?php echo $attrKey; ?>" aria-expanded="<?= ($attribute['name'] == 'Цвет') ? 'true' : 'false' ?>" aria-controls="colleps_row_<?php echo $attrGroupKey; ?>">
                <?php echo $attribute['name']; ?>
              </h2>
          </div>

          <div id="colleps_attr_row_<?php echo $attrKey; ?>" class="collapse <?= ($attribute['name'] == 'Цвет') ? 'in' : '' ?>" aria-labelledby="attribute_row_<?php echo $attrGroupKey; ?>" data-parent="#attribute_row_<?php echo $attrGroupKey; ?>">

            <div class="card-body">
            <?php if ($attribute['name'] == 'Цвет') { ?>
              <ul class="list-group">
              <?php foreach ($attribute['values'] as $attr) { ?>
                    <li class="list-group-item ">
                      <input name="attribute" type="checkbox" value="<?php echo $attrKey; ?>_<?php echo $attr; ?>" id="attr_<?php echo $attr; ?>">
                      <label 
                      <?= ($attr == 'Бежевый') ? 'style="background-color: #f5f5dc;color: #444;"' : '' ?>
                      <?= ($attr == 'Белый') ? 'style="background-color: #fff;color: #444;"' : '' ?>
                      <?= ($attr == 'Бирюзовый') ? 'style="background-color: #30d5c8;"' : '' ?>
                      <?= ($attr == 'Бордовый') ? 'style="background-color: #dc143c;color: #fff;"' : '' ?>
                      <?= ($attr == 'Голубой') ? 'style="background-color: #00bfff;color: #fff;"' : '' ?>
                      <?= ($attr == 'Желтый') ? 'style="background-color: #ffff00;color: #444;"' : '' ?>
                      <?= ($attr == 'Зеленый') ? 'style="background-color: #2e8b57;color: #fff;"' : '' ?>
                      <?= ($attr == 'Коралловый') ? 'style="background-color: #ff7f50;"' : '' ?>
                      <?= ($attr == 'Коричневый') ? 'style="background-color: #7b3f00;color: #fff;"' : '' ?>
                      <?= ($attr == 'Красный') ? 'style="background-color: #ff0000;color: #fff;"' : '' ?>
                      <?= ($attr == 'Мятный') ? 'style="background-color: #98ff98;color: #444;"' : '' ?>
                      <?= ($attr == 'Оранжевый') ? 'style="background-color: #ff4f00;color: #fff;"' : '' ?>
                      <?= ($attr == 'Песочный') ? 'style="background-color: #f4a460;"' : '' ?>

                      <?= ($attr == 'Разноцветный') ? 'style="background: linear-gradient(45deg, red, orange, yellow, green, blue, indigo, violet);color: #fff;text-shadow: #444 0 0 2px;"' : '' ?>
                      <?= ($attr == 'Розовый') ? 'style="background-color: #ff1493;color: #fff;"' : '' ?>

                      <?= ($attr == 'Серебристый') ? 'style="background: linear-gradient(42deg, rgba(224,224,224,1) 0%, rgba(209,208,198,1) 13%, rgba(224,224,224,1) 23%, rgba(247,243,243,1) 35%, rgba(209,208,198,1) 50%, rgba(247,243,243,1) 65%, rgba(224,224,224,1) 80%, rgba(209,208,198,1) 88%, rgba(224,224,224,1) 100%);color: #444;text-shadow: #fff 0 0 2px;"' : '' ?>

                      <?= ($attr == 'Серый') ? 'style="background-color: #808080;color: #fff;"' : '' ?>
                      <?= ($attr == 'Синий') ? 'style="background-color: #007dff;color: #fff;"' : '' ?>
                      <?= ($attr == 'Фиолетовый') ? 'style="background-color: #8b00ff;color: #fff;"' : '' ?>
                      <?= ($attr == 'Черный') ? 'style="background-color: #000;color: #fff;"' : '' ?>
                      <?= ($attr == 'Вишня') ? 'style="background-color: #de3163;color: #fff;"' : '' ?>

                      <?= ($attr == 'Камуфляж' || $attr == 'Хаки') ? 'style="background: linear-gradient(42deg, rgba(189,183,107,1) 0%, rgba(195,176,145,1) 13%, rgba(120,134,107,1) 23%, rgba(195,176,145,1) 35%, rgba(189,183,107,1) 50%, rgba(195,176,145,1) 65%, rgba(120,134,107,1) 80%, rgba(195,176,145,1) 88%, rgba(189,183,107,1) 100%);color: #444;text-shadow: #fff 0 0 2px;"' : '' ?>

                      <?= ($attr == 'Чёрный/белый') ? 'style="background: linear-gradient(to right, black 50%, white 50%);color: #fff;text-shadow: #000 0 0 4px;"' : '' ?>
                      <?= ($attr == 'Чёрный/красный') ? 'style="background: linear-gradient(to right, black 50%, red 50%);color: #fff;text-shadow: #000 0 0 4px;"' : '' ?>
                      <?= ($attr == 'черный/голубой') ? 'style="background: linear-gradient(to right, black 50%, #00bfff 50%);color: #fff;text-shadow: #000 0 0 4px;"' : '' ?>
                      <?= ($attr == 'черный/серый') ? 'style="background: linear-gradient(to right, black 50%, gray 50%);color: #fff;text-shadow: #000 0 0 4px;"' : '' ?>
                      <?= ($attr == 'черный/синий') ? 'style="background: linear-gradient(to right, black 50%, #007dff 50%);color: #fff;text-shadow: #000 0 0 4px;"' : '' ?>

                      for="attr_<?php echo $attr; ?>"><?php echo $attr; ?></label>
                    </li>
                  
              <?php } ?>
              </ul>
            <?php } else {?>
              <?php foreach ($attribute['values'] as $attr) { ?>
                  <ul class="list-group">
                    <li class="list-group-item ">
                      <input name="attribute" type="checkbox" value="<?php echo $attrKey; ?>_<?php echo $attr; ?>" id="attr_<?php echo $attr; ?>">
                      <label for="attr_<?php echo $attr; ?>"><?php echo $attr; ?></label>
                    </li>
                  </ul>
              <?php } ?>
            <?php } ?>
            </div>
        </div>
      
      </div>
      <?php } ?>
      <?php } ?>
    <?php } ?>
  <?php } ?>

 
</div>

<script type="text/javascript">
$(document).ready(function(){

  // Product Grid
  
  $(document).on('click','#list-view',function() {

    $('#content .product-grid > .clearfix').remove();

    $('#content .row > .product-grid').attr('class', 'product-layout product-list col-xs-12');
    $('#grid-view').removeClass('active');
    $('#list-view').addClass('active');

    localStorage.setItem('display', 'list');
  });

  $(document).on('click','#grid-view',function() {
    var cols = $('#column-right, #column-left').length;

    if (cols == 2) {
      $('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
    } else if (cols == 1) {
      $('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-6');
    } else {
      $('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
    }

    $('#list-view').removeClass('active');
    $('#grid-view').addClass('active');

    localStorage.setItem('display', 'grid');
  });
});


var path='&path=<?php echo $category_id; ?>';

$(document).ready(function(){

  var checked = [];
    $(document).on('click','.pagination li a',function(e) {
      e.preventDefault();

      $('.pagination li a').removeClass('active');
      $(this).addClass('active');
      
      // var pagenumber = $(this).attr('data-page');

      if ($(this).attr('href').includes('?page=')) {
        var pagenumber = $(this).attr('href').split('=').pop();      
      } else {
        pagenumber = '1';
      }

      // console.log(pagenumber);

      sessionStorage.setItem('pagenumber', pagenumber);      
      
      var names = [];
        // page
        
        if(names.indexOf('page') == -1) {
          names.push('page');
        }

        if(names.indexOf('sort') == -1) {
          names.push('sort');
        } 

        if(names.indexOf('limit') == -1) {
          names.push('limit');
        }

        $.each($('input[type="checkbox"]:checked'), function() {

          if(names.indexOf($(this).attr('name')) == -1) {
            names.push($(this).attr('name'));
          }
        });

        if(names.indexOf($('#min_price').attr('name')) == -1) {
            names.push($('#min_price').attr('name'));
          }

        if(names.indexOf($('#max_price').attr('name')) == -1) {
            names.push($('#max_price').attr('name'));
         }

        $.each(names, function(k, v){
          var items = [];  
          $('input[name="'+v+'"]:checked').each(function(){
            items.push($(this).val());
          });

          checked[k] = {
            name : v,
            items : items
          };
          
        if(v=='price_min'){ 
          checked[k] = {
            name : v,
            items : $('#min_price').val()
          }; 
        }
          
        if(v=='price_max'){ 
          checked[k] = {
            name : v,
            items : $('#max_price').val()
          }; 
        }

        if(v=='sort'){ 
            checked[k] = {
              name : v,
              items : $('#input-sort').val()
            }; 
          } 

          if(v=='limit'){ 
            checked[k] = {
              name : v,
              items : $('#input-limit').val()
            }; 
          }

        if(v=='page'){ 
          checked[k] = {
            name : v,
            items : pagenumber
          }; 
        }


      });


    
      // let optionsChecked = false;
      // for (i in checked){
      //   if (checked[i]['name'] == 'options'){
      //     optionsChecked = true;
      //     break;
      //   }
      // }

      // if (!optionsChecked){
      //   var items = []; 
      //   $('input[name="options"]').each(function(){
      //     items.push($(this).val());
      //   }); 

      //   checked.push({
      //     name : 'options',
      //     items : items
      //   });
      // }

      sessionStorage.setItem('checked', JSON.stringify(checked));

      if (!location.href.includes('#')) {
        location.href = location.href + '#';
      }

      set_hash(checked);

      jQuery.ajax({
        url: 'index.php?route=smart_filter/smart_filter/filter_category'+path,
        type :"POST",
        dataType: 'JSON',
        data: {
          filter:JSON.stringify(checked),
          request:'<?php echo $request; ?>'
        },
        beforeSend: function() {
          $('body').append('<div id="body-hover"></div>');
		    },
        cache: false,
        success: function(data){
          $('#content').html(data);
          if (localStorage.getItem('display') == 'list') {

            $('#content .product-grid > .clearfix').remove();

            $('#content .row > .product-grid').attr('class', 'product-layout product-list col-xs-12');
            $('#grid-view').removeClass('active');
            $('#list-view').addClass('active');

            localStorage.setItem('display', 'list');
          } else {

            var cols = $('#column-right, #column-left').length;

            if (cols == 2) {
              $('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
            } else if (cols == 1) {
              $('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-6');
            } else {
              $('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
            }

            $('#list-view').removeClass('active');
            $('#grid-view').addClass('active');

            localStorage.setItem('display', 'grid');
          }
          $('#body-hover').remove();

          $('html, body').animate({
              scrollTop: $("#content").offset().top - 150  
          }, 0); 
        },
        
      });

    });
      



    $(document).on('change','#smartFilter input[type="checkbox"],#min_price,#max_price,#input-sort,#input-limit',function() {
      //alert('aaa');

          // var pagenumber=$('.pagination li.active span').text();
          var pagenumber=1;
          
          var names = [];
            // page
            
          if(names.indexOf('page') == -1) {
            names.push('page');
          }

          if(names.indexOf('sort') == -1) {
            names.push('sort');
          } 

          if(names.indexOf('limit') == -1) {
            names.push('limit');
          }

         

          $.each($('input[type="checkbox"]:checked'), function(){

            if(names.indexOf($(this).attr('name')) == -1) {
              names.push($(this).attr('name'));
            }
          });
          
         
          if(names.indexOf($('#min_price').attr('name')) == -1) {
              names.push($('#min_price').attr('name'));
            }
           

          if(names.indexOf($('#max_price').attr('name')) == -1) {
              names.push($('#max_price').attr('name'));
          }
           
        $.each(names, function(k, v){

          var items = []; 

          $('input[name="'+v+'"]:checked').each(function(){
            items.push($(this).val());
          }); 


          checked[k] = {
            name : v,
            items : items
          };

          if(v=='price_min'){ 
            checked[k] = {
              name : v,
              items : $('#min_price').val()
            }; 
          }

          if(v=='price_max'){ 
            checked[k] = {
              name : v,
              items : $('#max_price').val()
            }; 
          } 

          if($('#input-sort').val()){
            if(v=='sort'){ 
              checked[k] = {
                name : v,
                items : $('#input-sort').val()
              }; 
            } 
          }
         
          if($('#input-limit').val()){
              if(v=='limit'){ 
              checked[k] = {
                name : v,
                items : $('#input-limit').val()
              }; 
            }
          }
          

          if(v=='page'){ 
            checked[k] = {
              name : v,
              items : pagenumber
            }; 
          }
       
        });

        // let optionsChecked = false;
        // for (i in checked){
        //   if (checked[i]['name'] == 'options'){
        //     optionsChecked = true;
        //     break;
        //   }
        // }

        // if (!optionsChecked){
        //   var items = []; 
        //   $('input[name="options"]').each(function(){
        //     items.push($(this).val());
        //   }); 

        //   checked.push({
        //     name : 'options',
        //     items : items
        //   });
        // }



      sessionStorage.setItem('checked', JSON.stringify(checked));

      if (!location.href.includes('#')) {
        location.href = location.href + '#';
      }
      
      set_hash(checked);

      jQuery.ajax({
        url: 'index.php?route=smart_filter/smart_filter/filter_category'+path,
        type :"POST",
        dataType: 'JSON',
        data: {
          filter:JSON.stringify(checked),
          request:'<?php echo $request; ?>'
        },
        beforeSend: function() {
          $('body').append('<div id="body-hover"></div>');
          $('body').addClass('fixedPage');
		    },
        cache: false,
        success: function(data) {
          $('#content').html(data);
          if (localStorage.getItem('display') == 'list') {
          
            $('#content .product-grid > .clearfix').remove();

            $('#content .row > .product-grid').attr('class', 'product-layout product-list col-xs-12');
            $('#grid-view').removeClass('active');
            $('#list-view').addClass('active');

            localStorage.setItem('display', 'list');
          } else {
            var cols = $('#column-right, #column-left').length;

            if (cols == 2) {
              $('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
            } else if (cols == 1) {
              $('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-6');
            } else {
              $('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
            }

            $('#list-view').removeClass('active');
            $('#grid-view').addClass('active');

            localStorage.setItem('display', 'grid');
          }

          $('body').removeClass('fixedPage');
          $('#body-hover').remove();
        },
          
      });   

          
    });


    let lastPagenumber = sessionStorage.getItem('pagenumber');

    if (lastPagenumber) {
      $('.pagination li a').removeClass('active');
      $('.pagination li a[data-page^="'+lastPagenumber+'"]').addClass('active');
    }


    lastChecked = JSON.parse('[' + sessionStorage.getItem('checked') + ']');


    if (1==1) {

      if ((location.href).split('?')[1]) {

        let urlParams = (location.href).split('?')[1].split('&');
        
        var pagenumber=$('.pagination li.active span').text();
       

        for (i in urlParams) {
          let paramName = urlParams[i].split('=')[0];
          let paramItems = urlParams[i].split('=')[1].split(',');

          for (i in paramItems) {
            if (paramName == 'manufacture') {
              $("#manu_"+decodeURI(paramItems[i])).prop('checked', true);
            } else if (paramName == 'attribute') {
              $( "input[value='"+decodeURI(paramItems[i])+"']" ).prop('checked', true);
            }  else if (paramName == 'options') {
              $( "#option_"+decodeURI(paramItems[i])).prop('checked', true);
            }  else if (paramName == 'price_min' &&  $( "#text_min_price").val() < paramItems[i]) {
              $( "#text_min_price").val(paramItems[i]);
              $( "#min_price").val(paramItems[i]);
            }  else if (paramName == 'price_max' &&  $( "#text_max_price").val() > paramItems[i]) {
              $( "#text_max_price").val(paramItems[i]);
              $( "#max_price").val(paramItems[i]);
            } else if (paramName == 'page') {
              pagenumber = paramItems[i];
            }
          }
        }
      }
      
      if (pagenumber == undefined) {
          pagenumber = 1;
        }
          
          var names = [];
            // page
            
          if(names.indexOf('page') == -1) {
            names.push('page');
          }

          if(names.indexOf('sort') == -1) {
            names.push('sort');
          } 

          if(names.indexOf('limit') == -1) {
            names.push('limit');
          }

         

          $.each($('input[type="checkbox"]:checked'), function(){

            if(names.indexOf($(this).attr('name')) == -1) {
              names.push($(this).attr('name'));
            }
          });
          
         
          if(names.indexOf($('#min_price').attr('name')) == -1) {
              names.push($('#min_price').attr('name'));
            }
           

          if(names.indexOf($('#max_price').attr('name')) == -1) {
              names.push($('#max_price').attr('name'));
          }
           
        $.each(names, function(k, v){

          var items = []; 

          $('input[name="'+v+'"]:checked').each(function(){
            items.push($(this).val());
          }); 


          checked[k] = {
            name : v,
            items : items
          };

          if(v=='price_min'){ 
            checked[k] = {
              name : v,
              items : $('#min_price').val()
            }; 
          }

          if(v=='price_max'){ 
            checked[k] = {
              name : v,
              items : $('#max_price').val()
            }; 
          } 

          if($('#input-sort').val()){
            if(v=='sort'){ 
              checked[k] = {
                name : v,
                items : $('#input-sort').val()
              }; 
            } 
          }
         
          if($('#input-limit').val()){
              if(v=='limit'){ 
              checked[k] = {
                name : v,
                items : $('#input-limit').val()
              }; 
            }
          }
          

          if(v=='page'){ 
            checked[k] = {
              name : v,
              items : pagenumber
            }; 
          }
       
        });

        // let optionsChecked = false;
        // for (i in checked){
        //   if (checked[i]['name'] == 'options'){
        //     optionsChecked = true;
        //     break;
        //   }
        // }

        // if (!optionsChecked){
        //   var items = []; 
        //   $('input[name="options"]').each(function(){
        //     items.push($(this).val());
        //   }); 

        //   checked.push({
        //     name : 'options',
        //     items : items
        //   });
        // }



        sessionStorage.setItem('checked', JSON.stringify(checked));

        if (!location.href.includes('#')) {
          location.href = location.href + '#';
        }
        
        set_hash(checked);

        jQuery.ajax({
          url: 'index.php?route=smart_filter/smart_filter/filter_category'+path,
          type :"POST",
          dataType: 'JSON',
          data: {
            filter:JSON.stringify(checked),
            request:'<?php echo $request; ?>'
          },
          beforeSend: function() {
            $('body').append('<div id="body-hover"></div>');
            $('body').addClass('fixedPage');
          },
          cache: false,
          success: function(data) {
            $('#content').html(data);
            if (localStorage.getItem('display') == 'list') {
            
              $('#content .product-grid > .clearfix').remove();

              $('#content .row > .product-grid').attr('class', 'product-layout product-list col-xs-12');
              $('#grid-view').removeClass('active');
              $('#list-view').addClass('active');

              localStorage.setItem('display', 'list');
            } else {
              var cols = $('#column-right, #column-left').length;

              if (cols == 2) {
                $('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
              } else if (cols == 1) {
                $('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-6');
              } else {
                $('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
              }

              $('#list-view').removeClass('active');
              $('#grid-view').addClass('active');

              localStorage.setItem('display', 'grid');
            }

            $('body').removeClass('fixedPage');
            $('#body-hover').remove();
          },
            
        });
      

    } else if (lastChecked[0]) {
      checked = lastChecked[0];

      if ( (lastPagenumber || window.location.href == sessionStorage.getItem('lastCategory')) && window.location.href.includes('#')) {
        jQuery.ajax({
          url: 'index.php?route=smart_filter/smart_filter/filter_category'+path,
          type :"POST",
          dataType: 'JSON',
          data: {
            filter:JSON.stringify(checked),
            request:'<?php echo $request; ?>'
          },
          beforeSend: function() {
            $('body').append('<div id="body-hover"></div>');
            $('body').addClass('fixedPage');
          },
          cache: false,
          success: function(data) {
            $('#content').html(data);
            if (localStorage.getItem('display') == 'list') {
            
              $('#content .product-grid > .clearfix').remove();

              $('#content .row > .product-grid').attr('class', 'product-layout product-list col-xs-12');
              $('#grid-view').removeClass('active');
              $('#list-view').addClass('active');

              localStorage.setItem('display', 'list');
            } else {
              var cols = $('#column-right, #column-left').length;

              if (cols == 2) {
                $('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-12');
              } else if (cols == 1) {
                $('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-6');
              } else {
                $('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-6 col-xs-12');
              }

              $('#list-view').removeClass('active');
              $('#grid-view').addClass('active');

              localStorage.setItem('display', 'grid');
            }

            $('body').removeClass('fixedPage');
            $('#body-hover').remove();
          },
            
        });  
      } else {
        sessionStorage.removeItem('pagenumber');
        sessionStorage.removeItem('checked');
      }
    }
    
    
    sessionStorage.setItem('lastCategory', window.location.href );
    
});


  function set_hash(checked) {

    let parameters = ''
    let parametersCounter = 0
    for ( i in checked ) {
      
      if (checked[i].name == 'attribute' || checked[i].name == 'options' || checked[i].name == 'manufacture' || checked[i].name == 'page' || checked[i].name == 'price_min' || checked[i].name == 'price_max') {

        parametersCounter++;

        if (parameters != '') {
          parameters += '&';
        }

        if (checked[i].name == 'attribute' || checked[i].name == 'options' || checked[i].name == 'manufacture') {
          parameters += checked[i].name + '=' + checked[i].items.join();
        } else {
          if (checked[i].name == 'page' && checked[i].items == '') {
            parameters += checked[i].name + '=1';
          } else {
            parameters += checked[i].name + '=' + checked[i].items;
          }
        }
      }
    }

    if (parametersCounter > 1) {
      parameters.substring(0, parameters.length - 1);
    } 


    try{
      history.replaceState(null,null,(location.href).split('?')[0]+'?'+parameters);
      return;
    }
    catch(e){

    }
    location.hash = h;
  }
</script>
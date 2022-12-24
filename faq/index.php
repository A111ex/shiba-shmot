<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/dashboard.css">
    <link rel="stylesheet" href="./css/style.css">
    <script src="./js/bootstrap.min.js"></script>
    <script src="./js/jquery-3.5.1.min.js"></script>
    <script src="./js/main.js"></script>
    <title>Часто задаваемые вопросы</title>
</head>
<body>

<?php
session_start();

if (isset($_GET['logout'])) {
    unset($_SESSION['domain']);
    header( "Location: /faq/" );
}

if (!isset($_SESSION['domain'])) {
    if (!isset($_POST["domain"])) { ?>

    <main class="form-signin mt-5" style="width:400px; margin:auto; display:block; text-align:center;">
        <form action="/faq/" method="post">
            <h1 class="h3 mb-3 fw-normal">Введите адрес вашего сайта</h1>
            <input type="text" id="domain" name="domain" class="form-control mb-2" placeholder="your.site" required="" autofocus="">
            <button class="w-100 btn btn-lg btn-primary" type="submit">Войти</button>
        </form>
    </main>
        
    <?php
    } else {
        $f = fopen('ijf2hff3h2gh24g29jg3jf42f2f.d', 'r');
        while (($buffer = fgets($f)) !== false) {
            if (trim($buffer) == $_POST["domain"] ) {
                 $_SESSION['domain'] = $_POST["domain"];
                 header( "Location: /faq/" );
            }
        }

        if (!isset($_SESSION['domain'])) { ?>

        <main class="form-signin mt-5" style="width:400px; margin:auto; display:block; text-align:center;">        
            <form action="/faq/" method="post">
                <h1 class="h3 mb-3 fw-normal">Введите адрес вашего сайта</h1>
                <input type="text" id="domain" name="domain" class="form-control mb-2" placeholder="your.site" required="" autofocus="">
                <button class="w-100 btn btn-lg btn-primary" type="submit">Войти</button>
                <h5 class="text-danger mt-1">Введенный вами домен сайта не найден</h5>
            </form>
        </main>

        <?php }
    } ?>
  
<?php } else { ?>

    <header class="navbar navbar-dark sticky-top bg-dark flex-md-nowrap p-0 shadow">
        <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3"><?= $_SESSION['domain']; ?></a>
        <button id="menu-button" class="navbar-toggler position-absolute d-md-none collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>
        <input class="form-control form-control-dark w-100" type="text" placeholder="Поиск" aria-label="Search" onkeyup="search()" id="search">
        <ul class="navbar-nav px-3">
            <li class="nav-item text-nowrap">
                <a class="nav-link" href="/faq/?logout=true">Выход</a>
            </li>
        </ul>
      </header>
    
    <div class="container-fluid mb-4">
        <div class="row">

            <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse">
                <div class="position-sticky pt-3">
                    <ul class="nav flex-column">
                        
                        <li class="nav-item">
                            <a class="nav-link active" onclick="showAll()">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-layers"><polygon points="12 2 2 7 12 12 22 7 12 2"></polygon><polyline points="2 17 12 22 22 17"></polyline><polyline points="2 12 12 17 22 12"></polyline></svg>
                              Всё
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" onclick="showDesign()">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-home"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                                Настройка витрины
                            </a>
                        </li>
                        
                        <li class="nav-item">
                            <a class="nav-link" onclick="showProducts()">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-shopping-cart"><circle cx="9" cy="21" r="1"></circle><circle cx="20" cy="21" r="1"></circle><path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"></path></svg>
                              Товары
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" onclick="showCustomers()">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-users"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                              Клиенты
                            </a>
                        </li>

                        <li class="nav-item">
                            <a class="nav-link" onclick="showOrders()">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-file"><path d="M13 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V9z"></path><polyline points="13 2 13 9 20 9"></polyline></svg>
                              Заказы
                            </a>
                        </li>


                        <li class="nav-item">
                            <a class="nav-link" onclick="showStatistics()">
                              <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-bar-chart-2"><line x1="18" y1="20" x2="18" y2="10"></line><line x1="12" y1="20" x2="12" y2="4"></line><line x1="6" y1="20" x2="6" y2="14"></line></svg>
                              Статистика
                            </a>
                        </li>
                    </ul>
                </div>
            </nav>

            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4">

                <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
                    <h1 class="h1">Часто задаваемые вопросы</h1>
                </div>

                <div id="dataDesign" class="dataBlock">
                    <h2 class="documentation-header">Настройка витрины</h2>

                    <div class="accordion" id="accordionDesign">                   
                    
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="сhangeLogo">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseChangeLogo" aria-expanded="true" aria-controls="collapseChangeLogo">
                                Как поменять логотип? Как поменять иконку (favicon)?
                                </button>
                            </h3>
                            <div id="collapseChangeLogo" class="accordion-collapse collapse" aria-labelledby="сhangeLogo" data-bs-parent="#accordionDesign">
                                <div class="accordion-body">
                                    <p>
                                        <strong>Для смены логотипа или иконки войдите в административную панель сайта по адресу:</strong>
                                    </p>
                                    <p>
                                        <a target="_blank" href="https://<?= $_SESSION['domain']; ?>/admin">https://<?= $_SESSION['domain']; ?>/admin</a>
                                    </p>
                                    <p>
                                        Затем перейдите на страницу настроек магазина:
                                    </p>
                                    <p>
                                        <img src="./images/design/change-logo/change-logo-1.jpg">
                                    </p>
                                    <p>
                                        Откройте вкладку Изображения и нажмите на фотографию, затем на синюю кнопку, чтобы заменить изображение логотипа
                                    </p>
                                    <p>
                                        <img src="./images/design/change-logo/change-logo-2.jpg">
                                    </p>
                                    <p>
                                        Выберите папку на сервере или создайте новую, в котору будете загружать логотип с вашего ПК.
                                    </p>
                                    <p>Размер логотипа в демонстрационном шаблоне 117x46px, перед загрузкой логотипа на сайт стоит предварительно сохранить его в аналогичном размере.
                                    </p>
                                    <p>
                                        <img src="./images/design/change-logo/change-logo-3.jpg">
                                    </p>
                                    <p>
                                        Затем кликните по нему мышкой и нажмите на кнопку Сохранить, находящуюся справа вверху страницы
                                    </p>
                                    <p>
                                        <img src="./images/design/change-logo/change-logo-4.jpg">
                                    </p>                                    
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h3 class="accordion-header" id="сhangeContacts">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseChangeContacts" aria-expanded="false" aria-controls="collapseChangeContacts">
                                Как поменять контактные данные в шапке сайта? как поменять телефон? как поменять ссылки на социальные сети?
                            </button>
                            </h3>
                            <div id="collapseChangeContacts" class="accordion-collapse collapse" aria-labelledby="сhangeContacts" data-bs-parent="#accordionDesign">
                                <div class="accordion-body">
                                    <p>
                                        <strong>Для изменения или иконки войдите в административную панель сайта по адресу:</strong>
                                    </p>
                                    <p>
                                        <a target="_blank" href="https://<?= $_SESSION['domain']; ?>/admin">https://<?= $_SESSION['domain']; ?>/admin</a>
                                    </p>
                                    <p>
                                        Затем перейдите на страницу настроек магазина:
                                    </p>
                                    <p>
                                        <img src="./images/design/change-contacts/change-contacts-1.jpg">
                                    </p>
                                    <p>
                                        Откройте вкладку Витрина
                                    </p>
                                    <p>
                                        <img src="./images/design/change-contacts/change-contacts-2.jpg">
                                    </p>
                                    <p>
                                        Внизу страницы отредактируйте ваши контакты и сохраните страницу нажав на кнопку Сохранить, находящуюся справа вверху страницы
                                    </p>
                                    <p>
                                        <img src="./images/design/change-contacts/change-contacts-3.jpg">
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h3 class="accordion-header" id="clearCache">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseClearCache" aria-expanded="false" aria-controls="collapseClearCache">
                                Как удалить кэш изображений и системных файлов на сервере?
                            </button>
                            </h3>
                            <div id="collapseClearCache" class="accordion-collapse collapse" aria-labelledby="clearCache" data-bs-parent="#accordionDesign">
                                <div class="accordion-body">
                                    <p>
                                        <strong>Для удаления кэша изображений и системных файлов войдите в административную панель сайта по адресу:</strong>
                                    </p>
                                    <p>
                                        <a target="_blank" href="https://<?= $_SESSION['domain']; ?>/admin/index.php?route=octeam/toolset">https://<?= $_SESSION['domain']; ?>/admin/index.php?route=octeam/toolset</a>
                                    </p>
                                    <p>
                                        Затем перейдите на страницу Очистка кэша:
                                    </p>
                                    <p>
                                        <img src="./images/design/clear-cache/clear-cache-1.jpg">
                                    </p>
                                    <p>
                                        и нажмите на соответствующую кнопку
                                    </p>
                                    <p>
                                        <img src="./images/design/clear-cache/clear-cache-2.jpg">
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h3 class="accordion-header" id="chat">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseChat" aria-expanded="false" aria-controls="collapseChat">
                                Как установить на сайт чат?
                            </button>
                            </h3>
                            <div id="collapseChat" class="accordion-collapse collapse" aria-labelledby="chat" data-bs-parent="#accordionDesign">
                                <div class="accordion-body">
                                    <p>
                                        <strong>Для добавления на сайт чата войдите в административную панель сайта по адресу:</strong>
                                    </p>
                                    <p>
                                        <a target="_blank" href="https://<?= $_SESSION['domain']; ?>/admin/index.php?route=extension/extension">https://<?= $_SESSION['domain']; ?>/admin/index.php?route=extension/extension</a>
                                    </p>
                                    <p>
                                        Затем внизу страницы откройте настройки модуля Чат:
                                    </p>
                                    <p>
                                        <img src="./images/design/chat/chat-1.jpg">
                                    </p>
                                    <p>
                                        <ol>
                                            <li>Зарегистрируйтесь на сайте выбранного вами чата, например на <a href="https://www.jivo.ru/">jivo.ru</a>, настройте чат и скопируйте созданный код чата для установки на ваш сайт.</li>
                                            <li>Вставьте полученный код в поле Код чата</li>
                                            <li>Статус модуля установите на Включено</li>
                                            <li>Сохраните настройки модуля</li>
                                        </ol>
                                    </p>
                                    <p>
                                        <img src="./images/design/chat/chat-2.jpg">
                                    </p>
                                    <p>
                                       Затем чтобы указать на каких страницах будет отображаться чат перейдите по ссылке:
                                    </p>
                                    <p>
                                        <a target="_blank" href="https://<?= $_SESSION['domain']; ?>/admin/index.php?route=design/layout">https://<?= $_SESSION['domain']; ?>/admin/index.php?route=design/layout</a>
                                    </p>
                                    <p>
                                       и откройте настройку нужной вам страницы, например, Главная: 
                                    </p>
                                    <p>
                                        <img src="./images/design/chat/chat-3.jpg">
                                    </p>
                                    <p>
                                        <ol>
                                            <li>В выпадающем списке модулей выберите Чат</li>
                                            <li>Нажмите кнопку Добавить модуль</li>
                                            <li>Сохраните настройки</li>
                                        </ol>                                       
                                    </p>
                                    <p>
                                        <img src="./images/design/chat/chat-4.jpg">
                                    </p>
                                    <p>
                                        Если вы больше не хотите выводить чат на какой-то из страниц, то перейдите в настройки этой страницы и нажмите на кнопку Удалить, затем сохраните настройки страницы
                                    </p>
                                    <p>
                                        <img src="./images/design/chat/chat-5.jpg">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div id="dataProducts" class="dataBlock">
                    <h2 class="documentation-header">Товары</h2>

                    <div class="accordion" id="accordionProducts">  
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="changeMargin">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseChangeMargin" aria-expanded="false" aria-controls="collapseChangeMargin">
                                Как изменить наценку на товары?
                            </button>
                            </h3>
                            <div id="collapseChangeMargin" class="accordion-collapse collapse" aria-labelledby="changeMargin" data-bs-parent="#accordionDesign">
                                <div class="accordion-body">
                                    <p>
                                        <strong>Для изменения наценки на товары войдите в административную панель сайта по адресу:</strong>
                                    </p>
                                    <p>
                                        <a target="_blank" href="https://<?= $_SESSION['domain']; ?>/admin/index.php?route=tool/import_yml">https://<?= $_SESSION['domain']; ?>/admin/index.php?route=tool/import_yml</a>
                                    </p>
                                    <p>
                                        <img src="./images/products/change-margin/change-margin-1.jpg">
                                    </p>
                                    <p>
                                        Укажите как нужно осуществлять наценку: в процентах или в рублях.
                                    </p>
                                    <p>
                                        <img src="./images/products/change-margin/change-margin-2.jpg">
                                    </p>
                                    <p>
                                        Затем ниже в полях пропишите на сколько нужно делать наценку на товары по категориям, по видам одежды, по артикулам и по содержащемуся в названии тексту.
                                    </p>
                                    <p>
                                        После заполнения нужных полей нажмите на зеленую кнопку Сохратиь настройки без импорта.
                                    </p>
                                    <p>
                                        <img src="./images/products/change-margin/change-margin-3.jpg">
                                    </p>
                                    <p>
                                        А затем можно запустить импорт нажав на красную кнопку Импортировать товары.
                                    </p>
                                    <p>
                                        <img src="./images/products/change-margin/change-margin-4.jpg">
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                <div id="dataCustomers" class="dataBlock">
                    <h2 class="documentation-header">Клиенты</h2>

                    <div class="accordion" id="accordionCustomers">  
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="newsletter">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseNewsletter" aria-expanded="false" aria-controls="collapseNewsletter">
                                Как отправлять письма подписчикам на новости?
                            </button>
                            </h3>
                            <div id="collapseNewsletter" class="accordion-collapse collapse" aria-labelledby="Newsletter" data-bs-parent="#accordionDesign">
                                <div class="accordion-body">
                                    <p>
                                        <strong>Для отправки писем подписчикам на новости перейдите по ссылке:</strong>
                                    </p>
                                    <p>
                                        <a target="_blank" href="https://<?= $_SESSION['domain']; ?>/admin/index.php?route=marketing/contact">https://<?= $_SESSION['domain']; ?>/admin/index.php?route=marketing/contact</a>
                                    </p>
                                    <p>
                                        <img src="./images/customers/newsletter/newsletter-1.jpg">
                                    </p>
                                    <p>
                                        Укажите получателей, заполните тему письма, напишите сообщение и нажмите синюю кнопку с конвертом в правом верхнем углу для отправки писем.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="dataOrders" class="dataBlock">
                    <h2 class="documentation-header">Заказы</h2>

                    <div class="accordion" id="accordionOrders">  
                        <div class="accordion-item">
                            <h3 class="accordion-header" id="orderApi">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOrderApi" aria-expanded="false" aria-controls="collapseOrderApi">
                                Что делать если на странице заказа появилось красное сообщение о том, что ваш IP адрес не имеет доступа к API?
                            </button>
                            </h3>
                            <div id="collapseOrderApi" class="accordion-collapse collapse" aria-labelledby="OrderApi" data-bs-parent="#accordionDesign">
                                <div class="accordion-body">
                                    <p>
                                        Если на странице заказа вы видите такое сообщение, то достаточно просто нажать на красную кнопку и у вас появятся права на работу с заказами.
                                    </p>
                                    <p>
                                        <img src="./images/orders/order-api/order-api-1.jpg">
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="dataStatistics" class="dataBlock">
                    <h2 class="documentation-header">Статистика</h2>

                    <div class="accordion" id="accordionStatistics">

                        <div class="accordion-item">
                            <h3 class="accordion-header" id="YandexMetrika">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseYandexMetrika" aria-expanded="false" aria-controls="collapseYandexMetrika">
                                Как добавить на сайт счетчик Яндекс-Метрика, Google Analytics и т.д.?
                            </button>
                            </h3>
                            <div id="collapseYandexMetrika" class="accordion-collapse collapse" aria-labelledby="YandexMetrika" data-bs-parent="#accordionDesign">
                                <div class="accordion-body">
                                    <p>
                                        <strong>Для добавления кода счетчика войдите в административную панель сайта по адресу:</strong>
                                    </p>
                                    <p>
                                        <a target="_blank" href="https://<?= $_SESSION['domain']; ?>/admin/index.php?route=extension/extension">https://<?= $_SESSION['domain']; ?>/admin/index.php?route=extension/extension</a>
                                    </p>
                                    <p>
                                        В фильтре модулей виберите Статистика
                                    </p>
                                    <p>
                                        <img src="./images/statistics/yandex-metrika/yandex-metrika-1.jpg">
                                    </p>
                                    <p>
                                        Установите модуль для добавления кода 
                                    </p>
                                    <p>
                                        <img src="./images/statistics/yandex-metrika/yandex-metrika-2.jpg">
                                    </p>
                                    <p>
                                        Перейдите в настройки установленного модуля
                                    </p>
                                    <p>
                                        <img src="./images/statistics/yandex-metrika/yandex-metrika-3.jpg">
                                    </p>
                                    <p>
                                       <ol>
                                           <li>Вставьте код вашего счетчика</li>
                                           <li>Включите модуль</li>
                                           <li>Сохраните настройки модуля</li>
                                       </ol>
                                    </p>
                                    <p>
                                        <img src="./images/statistics/yandex-metrika/yandex-metrika-4.jpg">
                                    </p>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </main>
        </div>
    </div>

<?php } ?>
</body>
</html>
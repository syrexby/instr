<?php
    $this->title = Yii::app()->getModule('yupe')->siteName;
    $this->description = Yii::app()->getModule('yupe')->siteDescription;
    $this->keywords = Yii::app()->getModule('yupe')->siteKeyWords;
?>
<div class="meta" itemscope itemtype="http://schema.org/LocalBusiness">
    <span itemprop="name" content="<?= Yii::app()->getModule('yupe')->siteName; ?>"></span>
    <meta itemprop="telephone" content="+375 16 444-10-63">
    <span itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
        <meta itemprop="streetAddress" content="ул. Шевченко, д. 93А">
        <meta itemprop="addressLocality" content="Дрогичин">
        <meta itemprop="addressRegion" content="Брестская область">
        <meta itemprop="addressCountry" content="Беларусь">
        <meta itemprop="postalCode" content="225612">
    </span>
    <time itemprop="openingHours" datetime="Mo-Fr 9:00-18:00">
        <p>Пн - Пт: 9:00 - 18:00</p>
        <p>Обед: 14:00 - 15:00</p>
    </time>
    <time itemprop="openingHours" datetime="Sa-Su 9:00-14:00">
        <p>Сб, Вс: 9:00 - 14:00</p>
    </time>
    <meta itemprop="email" content="korenyuk@tut.by">
    <a itemprop="url" href="https://xn--b1adqkjbb.xn--90ais/">https://домовой.бел/</a>
    <meta itemprop="priceRange" content="$">
    <span itemprop="logo">
        <?= CHtml::normalizeUrl(Yii::app()->getBaseUrl(true).$this->mainAssets.'/'.$this->yupe->logo); ?>
    </span>
    <span itemprop="image">
        <?= CHtml::normalizeUrl(Yii::app()->getBaseUrl(true).$this->mainAssets.'/'.$this->yupe->logo); ?>
    </span>
</div>
    <div class = "slider">
        <img src="<?= $this->mainAssets?>/img/slider/1.png" alt="">
    </div>
    <h3 class = "catalog__caption">Акционные товары</h3>
    <div class = "catalog__list">
      <?php $this->widget('application.modules.store.widgets.ProductsFromCategoryWidget', ['slug' => 'sale']); ?>
    </div>
    <!--<button class = "catalog__button-discount">
        <a href = "">Все акции и распродажи</a>
    </button>-->
    <div class = "catalog__article">
        <h2 class = "catalog__article__caption">Приветствуем Вас в интернет-каталоге строительных материалов "Домовой"!</h2>
        <p class = "catalog__article__paragraph">С недавних пор покупка и доставка стройматериалов через интернет перестала быть чем-то необычным. Теперь не требуется ездить по городу,
            а иногда и в соседние города, чтобы найти те материалы, которые Вам нужны, по хорошей цене, думать о доставке и разгрузке.
            Все что нужно для ремонта и отделочных работ есть у нас. Вы можете ознакомиться с ассортиментом на нашем сайте и заказать всё это с доставкой на дом!
            Мы уже 10 лет занимаемся продажей строительных материалов и товаров для дома. Наше обслуживание порадует Вас и Вы гарантированно вернётесь к нам за покупками еще не один раз!</p>
        <p class = "catalog__article__paragraph">На данный момент все цены на сайте - ориентировочные. Уточняйте подробности по телефонам, указанным на сайте.
        </p>

        <div class="catalog-list">
            Наш ассортимент насчитывает уже более 10 тысяч наименований, мы постоянно развиваемся и стараемся предоставить нашим клиентам лучший сервис.<br>
            <br>
            Смотрите что мы можем Вам предложить на данный момент:
            <ul>
                <li>строительные материалы,</li>
                <li>крепеж всех видов,</li>
                <li>товары для дома и дачи,</li>
                <li>кухонную технику (духовки, плиты, вытяжки),</li>
                <li>сантехнику и аксессуары,</li>
                <li>плитку керамическую,</li>
                <li>электро и бензоинструмент,</li>
                <li>электрику,</li>
                <li>двери входные и межкомнатные,</li>
                <li>металлопрокат,</li>
                <li>плитку тротуарную и бордюры,</li>
                <li>блоки газосиликатные и кирпич.</li>
            </ul>
            <br>
            Купить стройматериалы в Дрогичине с доставкой  стоит у нас потому что
            в нашем магазине доступные цены, отличное обслуживание и доставка на дом по всей Брестской области!<br>
            К нам уже обращаются клиенты из городов: Кобрин, Ивацевичи, Белоозерск, Иваново, Ганцевичи, Пинск, Береза и многих других.
        </div>
    </div>



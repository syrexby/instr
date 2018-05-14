<li>
    <a href="<?= ProductHelper::getUrl($data); ?>">
        <div class="similar__img"><img src="<?= $data->getImageUrl(60, 100, false); ?>"></div>
        <p  class="similar__title">
          <?= Chtml::encode($data->getName()); ?><br><br><span><?= $data->getResultPrice(); ?> руб./шт.</span>
        </p>
    </a>
</li>
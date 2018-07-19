<?php if ($dataProvider->getTotalItemCount()): ?>
    <aside class = "similar">
        <p class = "similar__caption">Сопутствующие товары</p>
        <ul class = "similar-ul">
                <?php $this->widget(
                    'zii.widgets.CListView',
                    [
                        'dataProvider' => $dataProvider,
                        'template' => '{items}',
                        'itemView' => '_item',
                        'itemsCssClass' => 'h-slider__slides js-slick__container',
                        'cssFile' => false,
                        'pager' => false,
                    ]
                ); ?>
        </ul>
    </aside>
<?php endif; ?>

<?php
$this->title = 'Список товаров';

$cols = [
    'name' => 'Название',
    'price' => 'Цена',
    'marks' => 'Отзывы',
    'author' => 'Автор',
    'time' => 'Дата',
];
$sort = $this->vars['sort'];
?>
<h1><?= $this->title ?></h1>
<div class="text-right">
    <a href="/product" class="btn btn-lg btn-success">Добавить товар</a>
</div>

<?php if (!empty($this->vars['list'])): ?>
    <div id="index-table">
        <table class="table table-hover mt-4">
            <thead class="thead-dark">
            <tr>
                <th></th>
                <?php foreach ($cols as $k => $v): ?>
                    <?php if (isset($sort['fields'][$k])):
                        $class = $asc = '';
                        if ($k == $sort['key']){
                            $class = ' thead-sort';
                            if (empty($sort['asc'])){
                                $asc = '&amp;asc=1';
                            } else {
                                $class .= ' thead-sort-asc';
                            }
                        } ?>
                        <th class="text-center<?= $class ?>">
                            <a href="/?sort=<?= $k ?><?= $asc ?>"><?= $v ?></a>
                        </th>
                    <?php else: ?>
                        <th class="text-center"><?= $v ?></th>
                    <?php endif; ?>
                <?php endforeach; ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($this->vars['list'] as $v): ?>
                <tr>
                    <td class="w-1">
                        <img src="<?= $v['image'] ?>" onerror="this.src='/images/none.png'" width="64" alt="">
                    </td>
                    <td>
                        <a href="/product/<?= $v['id'] ?>"><?= htmlspecialchars($v['name']) ?></a>
                    </td>
                    <td class="text-center"><?= $v['price'] ?></td>
                    <td class="text-center"><?= $v['marks'] ?></td>
                    <td class="text-center"><?= htmlspecialchars($v['author']) ?></td>
                    <td class="w-1 text-center"><?= $this->date($v['time']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php $this->paging($this->vars['paging']['root'], $this->vars['paging']['page'], $this->vars['paging']['total']); ?>
    </div>

<?php else: ?>
    <p class="lead">Товары не найдены</p>
<?php endif; ?>

<script>
$(document).ready(function(){
    let table = $('#index-table'),
        getData = function(url){
            table.css('opacity', 0.4);
            $.get(url, function(d){
                table.html($(d).find('#index-table').html()).css('opacity', 1);
                history.pushState({}, '', decodeURIComponent(this.url));
            });
        };
    table.on('click', 'thead a', function(e){
    	e.preventDefault();
    	getData($(this).attr('href'));
    });
    table.on('click', '.page-item a', function(e){
    	e.preventDefault();
    	getData($(this).attr('href'));
        let offset = table.offset().top;
        if (offset < window.pageYOffset) window.scrollTo(0, offset);
    });
});
</script>

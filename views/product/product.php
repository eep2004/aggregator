<?php
$this->title = htmlspecialchars($this->vars['item']['name']);
?>
<div class="row">
    <div class="col-2">
        <img src="<?= $this->vars['item']['image'] ?>" onerror="this.src='/images/none.png'" alt="<?= $this->title ?>" class="img-fluid">
    </div>
    <div class="col offset-1">
        <h1>
            <?= $this->title ?>
            <?php if (isset($this->vars['item']['mark'])): ?>
                <span class="badge badge-info ml-4">&#11088; <?= $this->vars['item']['mark'] ?></span>
            <?php endif; ?>
        </h1>
        <strong class="h2 text-danger">
            <?= $this->vars['item']['price'] ?>
        </strong>
        <p class="mt-4">
            <?= htmlspecialchars($this->vars['item']['author']) ?>
            <span class="d-block text-muted"><?= $this->date($this->vars['item']['time']) ?></span>
        </p>
    </div>
</div>

<div id="product-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Добавление отзыва</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form method="post" autocomplete="off">
                <input type="hidden" name="email">
                <div class="modal-body">
                    <div class="form-group form-stars">
                        <label class="d-block">Ваша оценка</label>
                        <div class="stars">
                            <?php for ($i = 10; $i > 0; $i--): ?>
                            <span title="<?= $i ?>">&#11088;</span>
                            <?php endfor; ?>
                        </div>
                        <input type="hidden" name="mark" class="form-control">
                    </div>
                    <div class="form-group">
                        <label for="pm1">Ваше имя</label>
                        <input type="text" id="pm1" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="pm2">Ваш отзыв</label>
                        <textarea id="pm2" name="text" class="form-control" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <a href="#" class="btn btn-primary">Добавить</a>
                </div>
            </form>
        </div>
    </div>
</div>
<div class="text-center">
    <a href="#" class="btn btn-lg btn-success" data-target="#product-modal" data-toggle="modal">Добавить отзыв</a>
</div>

<?php if (!empty($this->vars['list'])): ?>
    <div id="product-table">

        <table class="mt-4 table table-hover">
            <thead>
            <tr>
                <th>Оценка</th>
                <th>Автор</th>
                <th>Комментарий</th>
                <th class="text-center">Дата</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($this->vars['list'] as $v): ?>
                <tr>
                    <td class="w-1">
                        <div class="stars">
                            <?php for ($i = 10; $i > 0; $i--): ?>
                                <span title="<?= $i ?>"<?php if ($i == $v['mark']): ?> class="active"<?php endif; ?>>&#11088;</span>
                            <?php endfor; ?>
                        </div>
                    </td>
                    <td><?= htmlspecialchars($v['name']) ?></td>
                    <td><?= htmlspecialchars($v['text']) ?></td>
                    <td class="w-1 text-center"><?= $this->date($v['time']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php $this->paging($this->vars['paging']['root'], $this->vars['paging']['page'], $this->vars['paging']['total']); ?>
    </div>
<?php endif; ?>

<script>
$(document).ready(function(){
    let form = $('#product-modal form'),
        input = form.find('.form-control'),
        stars = form.find('.stars');
    stars.find('span').click(function(){
        let self = $(this),
            active = !self.hasClass('active');
        self.toggleClass('active', active).siblings().removeClass('active');
        input.filter('[name=mark]').val(active ? 10 - self.index() : 0).triggerHandler('change');
    });
    input.change(function(){
        this.value = $.trim(this.value);
        if (this.value != '') $(this).removeClass('is-invalid').siblings('label').removeClass('text-danger');
    });
    form.find('.btn').click(function(e){
        e.preventDefault();
        let error = false;
        input.each(function(){
            switch (this.name){
                default:
                    if (this.value == '') error = true;
                    break;
            }
            if (error){
                $(this).addClass('is-invalid').focus().siblings('label').addClass('text-danger');
                return false;
            }
        });
        if (!error) form.submit();
    });

    let table = $('#product-table'),
        getData = function(url){
            table.css('opacity', 0.4);
            $.get(url, function(d){
                table.html($(d).find('#product-table').html()).css('opacity', 1);
                history.pushState({}, '', decodeURIComponent(this.url));
            });
        };
    table.on('click', '.page-item a', function(e){
        e.preventDefault();
        getData($(this).attr('href'));
        let offset = table.offset().top;
        if (offset < window.pageYOffset) window.scrollTo(0, offset);
    });
});
</script>

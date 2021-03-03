<?php
$this->title = 'Добавление товара';
?>
<div class="row">
    <div class="col-8 offset-2">
        <h1><?= $this->title ?></h1>
        <form id="product-form" method="post" autocomplete="off">
            <input type="hidden" name="email">
            <div class="form-group">
                <label for="pf1">Название товара</label>
                <input type="text" id="pf1" name="name" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="pf2">Изображение товара</label>
                <input type="text" id="pf2" name="image" class="form-control" required>
                <small class="form-text text-muted">Ссылка на изображение в формате jpg, jpeg, gif, png или svg</small>
            </div>
            <div class="form-group">
                <label for="pf3">Средняя цена</label>
                <input type="text" id="pf3" name="price" class="form-control" maxlength="11" required>
            </div>
            <div class="form-group">
                <label for="pf4">Ваше имя</label>
                <input type="text" id="pf4" name="author" class="form-control" required>
            </div>
            <div class="text-right">
                <a href="#" class="btn btn-primary">Добавить</a>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function(){
	let form = $('#product-form'),
        input = form.find('.form-control');
	input.change(function(){
	    this.value = $.trim(this.value);
        if (this.name == 'price'){
            this.value = this.value.replace(',', '.');
            this.value = this.value > 0 ? Math.round(this.value * 100) / 100 : '';
        }
        if (this.value != '') $(this).removeClass('is-invalid').siblings('label').removeClass('text-danger');
    });
	form.find('.btn').click(function(e){
        e.preventDefault();
        let error = false;
        input.each(function(){
            switch (this.name){
                case 'image':
            	    if (!/^https?:\/\/.+\/.+\.(jpg|jpeg|gif|png|svg)$/i.test(this.value)) error = true;
            		break;
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
});
</script>

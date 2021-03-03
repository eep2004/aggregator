<!DOCTYPE html>
<html lang="ru">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="/style/bootstrap.min.css?20210210">
<link rel="stylesheet" type="text/css" href="/style/style.css?20210210">
<script src="/script/jquery.min.js?20210210"></script>
<script src="/script/bootstrap.bundle.min.js?20210210"></script>
<title><?= $this->title ?></title>
</head>
<body>
<header class="navbar navbar-dark bg-dark">
    <a href="/" class="navbar-brand"><?= $this->sitename ?></a>
    <ul class="navbar-nav">
        <li class="nav-item">
            <a href="/product" class="nav-link">Добавить товар</a>
        </li>
    </ul>
</header>

<div class="container p-5">
    <?= $this->content ?>
</div>

<?php if (!empty($this->message)): ?>
    <div id="message-modal" class="modal fade">
        <div class="modal-dialog">
            <div class="modal-content">
                <?php if (empty($this->message['success'])): ?>
                    <div class="modal-header">
                        <h5 class="modal-title">Ошибка!</h5>
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p class="lead"><?= $this->message['text'] ?></p>
                    </div>
                <?php else: ?>
                    <div class="modal-body">
                        <button type="button" class="close" data-dismiss="modal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                        <p class="lead mt-3"><?= $this->message['text'] ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    <script>
    $(document).ready(function(){
    	let msg = $('#message-modal').modal();
        setTimeout(function(){
            msg.modal('hide');
        }, 5000);
    });
    </script>
<?php endif; ?>

<footer class="p-4 bg-dark">
    <div class="container text-white">Copyright <?= date('Y') ?> &copy; <?= $this->sitename ?></div>
</footer>
</body>
</html>
<?php
/**
 * @var array $list
 */
?>
<nav>
    <ul class="pagination justify-content-center">
        <?php foreach ($list as $v): ?>
            <?php if (empty($v['active'])): ?>
                <li class="page-item"><a href="<?= htmlspecialchars($v['url']) ?>" class="page-link"><?= $v['name'] ?></a></li>
            <?php else: ?>
                <li class="page-item active"><span class="page-link"><?= $v['name'] ?></span></li>
            <?php endif; ?>
        <?php endforeach; ?>
    </ul>
</nav>

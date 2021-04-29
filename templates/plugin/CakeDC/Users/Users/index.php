<?php
/**
 * Copyright 2010 - 2019, Cake Development Corporation (https://www.cakedc.com)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright Copyright 2010 - 2018, Cake Development Corporation (https://www.cakedc.com)
 * @license MIT License (http://www.opensource.org/licenses/mit-license.php)
 */
?>
<div class="container-fluid">
<div class="row justify-content-md-center" id="colorful">
<div class="col-6">
<h1 class="mt-5">Users</h1>
<div class="bg-white mb-5 p-5 shadow-sm">

<?php foreach (${$tableAlias} as $user) : ?>
<div class="bg-light mb-3 p-3">
    <div class="">
    <?= $this->Html->link(__d('cake_d_c/users', h($user->username)), ['action' => 'view', $user->id]) ?> 
    <?= h($user->email) ?> <?= h($user->first_name) ?> <?= h($user->last_name) ?>
    </div>
</div>

<?php endforeach; ?>

<div class="paginator">
<ul class="pagination">
<?= $this->Paginator->prev('< ' . __d('cake_d_c/users', 'previous')) ?>
<?= $this->Paginator->numbers() ?>
<?= $this->Paginator->next(__d('cake_d_c/users', 'next') . ' >') ?>
</ul>
<p><?= $this->Paginator->counter() ?></p>
</div>


</div>
</div>
</div>
</div>

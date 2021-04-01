<?php
/**
 * @var \App\View\AppView $this
 * @var \App\Model\Entity\ActivitiesTag $activitiesTag
 */
?>
<div class="row">
    <aside class="column">
        <div class="side-nav">
            <h4 class="heading"><?= __('Actions') ?></h4>
            <?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $activitiesTag->id],
                ['confirm' => __('Are you sure you want to delete # {0}?', $activitiesTag->id), 'class' => 'side-nav-item']
            ) ?>
            <?= $this->Html->link(__('List Activities Tags'), ['action' => 'index'], ['class' => 'side-nav-item']) ?>
        </div>
    </aside>
    <div class="column-responsive column-80">
        <div class="activitiesTags form content">
            <?= $this->Form->create($activitiesTag) ?>
            <fieldset>
                <legend><?= __('Edit Activities Tag') ?></legend>
                <?php
                    echo $this->Form->control('activity_id', ['options' => $activities]);
                    echo $this->Form->control('tag_id', ['options' => $tags]);
                ?>
            </fieldset>
            <?= $this->Form->button(__('Submit')) ?>
            <?= $this->Form->end() ?>
        </div>
    </div>
</div>

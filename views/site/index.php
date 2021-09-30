<?php

/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<div class="site-index">

    <div class="jumbotron text-center bg-transparent">

    </div>

    <div class="body-content">
        <?



        ?>
        <div class="row">
            <table class="table">
                <thead>
                <tr>
                    <th>&#35;</th>
                    <th>State</th>
                    <th>Store Name</th>
                    <th>Loaded</th>
                    <th>Not loaded</th>
                </tr>
                </thead>
                <tbody>
                <?php
                    foreach ($imports as $import) {
                ?>
                <tr>
                    <td><?=$import['id'];?></td>
                    <td><?=$import['state'];?></td>
                    <td><?=$import['store']['title'];?></td>
                    <td><?=count($import['products']);?></td>
                    <td><?=$import['not_loaded'] ? $import['not_loaded'] : '-';?></td>
                </tr>
                <?php
                    }
                ?>
                </tbody>
            </table>
        </div>

    </div>
</div>

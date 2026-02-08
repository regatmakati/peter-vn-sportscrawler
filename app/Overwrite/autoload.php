<?php
spl_autoload_register(function ($class) {
    $map = [
        'FFMpeg\Media\AbstractVideo' => __DIR__ . '/AbstractVideo.php',
        'Encore\Admin\Grid' => __DIR__ . '/Grid.php',
//        'Illuminate\Routing\Controller' => __DIR__ . '/Illuminate/Routing/Controller/Controller.php',
//        'Illuminate\Database\Eloquent\Model' => __DIR__ . '/Illuminate/Database/Eloquent/Model.php',
//        'Encore\Admin\Controllers\HandleController' => __DIR__ . '/Encore/Admin/Controllers/HandleController.php',
    ];

    if (isset($map[$class])) include $map[$class];

}, true, true);

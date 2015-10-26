<?php
/**
 * Created by PhpStorm.
 * User: Администратор
 * Date: 05.07.2015
 * Time: 0:51
 */
echo "<pre>";
require_once 'classes/db.class.php';
$obj = new Image_uploader();
print_r($obj->insertNewImages(array(
    'image' => 'test_img1',
    'image_name' => 'test1',
    'image_thumb' => 'img_tgumb1',
    ), null));
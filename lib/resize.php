<?php

function resizeImage($image, $max_width, $max_height)
{
    if (!$image) {
        return false;
    }

    $old_width  = imagesx($image);
    $old_height = imagesy($image);

    $scale      = min($max_width/$old_width, $max_height/$old_height);

    $new_width  = ceil($scale*$old_width);
    $new_height = ceil($scale*$old_height);

    return imagescale($image, $new_width, $new_height, IMG_TRIANGLE);
}
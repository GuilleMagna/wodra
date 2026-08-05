<!DOCTYPE html>
<html lang="es">

<head>

    <? if( is_home() ): ?>
        <meta property="og:type" content="website" />
        <meta property="og:url" content="<?php echo BURGER_URL ?>" />
        <meta property="og:image" content="<?php echo BURGER_OPTIONS['logo'] ?>" />
    <? endif ?>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=0"/>
    <meta name="author" content="WODRA | Marketing Digital">

    <title><?php echo BURGER_TITLE ?></title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="<?php echo BURGER_OPTIONS['url_font_family'] ?>" rel="stylesheet">

    <?php wp_head() ?>

    <style>
        <?php echo generate_dynamic_css() ?>
        <?php echo generate_button_classes_css() ?>
        <?php echo generate_icon_classes_css() ?>
    </style>

    <?php echo BURGER_OPTIONS['header_scripts'] ?>

</head>

<body style="background-color:<?php echo BURGER_OPTIONS[ 'color_fondo' ] ?>">

    <?php echo BURGER_OPTIONS['body_scripts'] ?>

    <main id="post-<? the_ID() ?>">
<?php
if ( ! defined( 'ABSPATH' ) ) exit;
$title = burger_site_setting( 'soon_title', 'Algo nuevo está llegando.' );
$message = burger_site_setting( 'soon_message', 'Mientras tanto, podés contactarnos y encontrarnos en:' );
$logo = burger_site_image( 'soon_logo' );
$background = burger_site_image( 'soon_background' );
$button_label = burger_site_setting( 'soon_button_label' );
$button_url = esc_url( burger_site_setting( 'soon_button_url' ) );
$email = sanitize_email( burger_site_setting( 'soon_email' ) );
$overlay = max( 0, min( 90, (float) burger_site_setting( 'soon_overlay', 20 ) ) ) / 100;
$logo_width = max( 80, min( 600, (int) burger_site_setting( 'soon_logo_width', 310 ) ) );
$socials = [];
if ( burger_site_setting( 'soon_show_socials', true ) ) {
    $networks = (array) ( BURGER_OPTIONS['redes'] ?? [] );
    $phone = preg_replace( '/\D/', '', (string) ( BURGER_OPTIONS['whatsapp'] ?? '' ) );
    if ( $phone ) $networks['whatsapp'] = 'https://wa.me/' . $phone;
    foreach ( [ 'whatsapp' => 'WhatsApp', 'instagram' => 'Instagram', 'facebook' => 'Facebook', 'youtube' => 'YouTube', 'linkedin' => 'LinkedIn', 'twitter' => 'X / Twitter' ] as $network => $label ) {
        $url = esc_url( $networks[ $network ] ?? '', [ 'https', 'http' ] );
        if ( $url ) $socials[] = [ 'url' => $url, 'label' => $label, 'icon' => get_burger_icon( 'icon-' . $network ) ];
    }
}
?>
<!doctype html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php echo esc_attr( get_option( 'blog_charset', 'UTF-8' ) ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="robots" content="noindex,nofollow">
    <title><?php echo esc_html( $title . ' · ' . get_bloginfo( 'name' ) ); ?></title>
    <style>
        :root{color-scheme:dark;--page:<?php echo burger_site_color( 'soon_background_color', '#25201c' ); ?>;--text:<?php echo burger_site_color( 'soon_text_color', '#ffffff' ); ?>;--button:<?php echo burger_site_color( 'soon_button_color', '#4b8dff' ); ?>;--button-text:<?php echo burger_site_color( 'soon_button_text_color', '#ffffff' ); ?>;}
        *{box-sizing:border-box;}
        body{margin:0;min-height:100vh;min-height:100svh;display:grid;place-items:center;padding:56px 24px;background:var(--page);<?php if ( $background ) echo 'background-image:linear-gradient(rgb(0 0 0 / ' . $overlay . '),rgb(0 0 0 / ' . $overlay . ')),' . burger_site_css_url( $background ) . ';'; ?>background-size:cover;background-position:center;color:var(--text);font-family:Arial,sans-serif;line-height:1.6;}
        main{width:min(100%,680px);text-align:center;overflow-wrap:anywhere;}
        .logo{display:block;max-width:85%;width:<?php echo $logo_width; ?>px;height:auto;max-height:160px;object-fit:contain;margin:0 auto clamp(32px,6vh,56px);}
        .site-name{font-family:Georgia,serif;font-size:32px;font-weight:700;margin:0 0 48px;}
        h1,.message{font-family:Georgia,serif;font-size:clamp(28px,3.1vw,44px);font-weight:700;line-height:1.05;text-shadow:0 2px 18px #0003;}
        h1{margin:0 0 24px;}
        .message{max-width:590px;white-space:pre-line;margin:0 auto;}
        .socials{display:flex;flex-wrap:wrap;justify-content:center;gap:2px;margin-top:40px;}
        .socials a{display:inline-flex;align-items:center;justify-content:center;min-width:44px;min-height:44px;color:inherit;text-decoration:none;border-radius:50%;transition:opacity .2s;}
        .socials a:hover{opacity:.75;}
        .socials span,.socials svg{display:block;}
        .socials svg{width:30px;height:30px;}
        .socials svg path{fill:currentColor;}
        .socials .social-label{padding:0 10px;}
        .button{display:inline-block;margin-top:32px;padding:12px 24px;border-radius:999px;background:var(--button);color:var(--button-text);font-weight:700;text-decoration:none;}
        .button:hover{filter:brightness(.9);}
        a:focus-visible{outline:3px solid currentColor;outline-offset:5px;}
        .contact{margin:24px 0 0;}.contact a,.preview a{color:inherit;}
        .preview{font-size:13px;background:#000b;padding:8px 16px;margin:0;text-align:center;}
        .preview-bar{position:fixed;inset:0 0 auto;z-index:1;color:#fff;}
        @media(max-width:480px){body{padding:72px 24px 40px;}.logo{max-width:78%;}.message{line-height:1.15;}.socials{margin-top:28px;}}
    </style>
</head>
<body>
    <?php if ( ! empty( $preview ) ) : ?>
        <aside class="preview-bar">
            <p class="preview">Vista previa para administradores. <a href="<?php echo esc_url( admin_url( 'admin.php?page=configuracion-general' ) ); ?>">Volver a la configuración</a></p>
        </aside>
    <?php endif; ?>
    <main>
        <?php if ( $logo ) : ?>
            <img class="logo" src="<?php echo esc_url( $logo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
        <?php else : ?>
            <p class="site-name"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></p>
        <?php endif; ?>
        <h1><?php echo esc_html( $title ); ?></h1>
        <p class="message"><?php echo esc_html( $message ); ?></p>
        <?php if ( $socials ) : ?>
            <nav class="socials" aria-label="Redes sociales">
                <?php foreach ( $socials as $social ) : ?>
                    <a href="<?php echo $social['url']; ?>" target="_blank" rel="noopener noreferrer" aria-label="<?php echo esc_attr( $social['label'] ); ?>">
                        <?php echo $social['icon'] ?: '<span class="social-label">' . esc_html( $social['label'] ) . '</span>'; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        <?php endif; ?>
        <?php if ( $button_label && $button_url ) : ?>
            <a class="button" href="<?php echo $button_url; ?>"><?php echo esc_html( $button_label ); ?></a>
        <?php endif; ?>
        <?php if ( $email ) : ?>
            <p class="contact"><a href="<?php echo esc_url( 'mailto:' . $email ); ?>"><?php echo esc_html( $email ); ?></a></p>
        <?php endif; ?>
    </main>
</body>
</html>

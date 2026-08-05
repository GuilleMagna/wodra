<?php
//Block Name: Footer contenido

$logo_footer_contenidos = BURGER_OPTIONS['logo_footer_contenidos'] ?? BURGER_URL . '/wp-content/uploads/2025/04/a331a68ac769459f7e91cd073a6527e9-1.png';

$logo_datafiscal = BURGER_OPTIONS['logo_datafiscal'] ?? BURGER_THEME_URL . '/themes/images/data-fiscal.png';
$logo_certificados = BURGER_OPTIONS['logo_certificados'] ?? BURGER_THEME_URL . '/themes/images/certificados-color.png';

$titulo_asociados = BURGER_OPTIONS['titulo_asociados'] ?? 'Lorem Ipsum';
$galeria_asociados = BURGER_OPTIONS['galeria_asociados'] ?? [];

$titulo_sedes = BURGER_OPTIONS['titulo_sedes'] ?? 'Lorem Ipsum';
$sedes = BURGER_OPTIONS['sedes'] ?? [];

$habilitar_sedes_dos = BURGER_OPTIONS['habilitar_sedes_dos'] ?? false;
$sedes_dos = BURGER_OPTIONS['sedes_dos'] ?? [];

$titulo_seguinos = BURGER_OPTIONS['titulo_seguinos'] ?? 'Lorem';
$enlace_seguinos = BURGER_OPTIONS['enlace_seguinos'] ?? '#';

$redes = BURGER_OPTIONS['redes'] ?? [];

$design = get_block_design($block);
extract($design);

$block_id = $block['id'];
?>

<style>
    .<?= $block_id ?> {
        margin: <?= $section_margin ?> !important;
        padding: <?= $section_padding ?> !important;
        border-radius: <?= $border_radius ?> !important;
        background-color: <?= $color_fondo ?>;
        background-image: url('<?= $imagen_fondo ?>')
    }
    .<?= $block_id ?> .titulo,
    .<?= $block_id ?> .color-secundario {
        color: <?php echo $color_secundario ?>
    }
    .<?= $block_id ?> .titulo span,
    .<?= $block_id ?> .color-primario {
        color: <?php echo $color_primario ?>
    }
</style>

<div id="footer-contenido" class="section bg-white border-top border-light<?php echo @get_block_classes() ?>">

	<div class="container">

		<div class="row g-4 aling-items-center justify-content-center">

			<div class="col-auto d-flex text-center align-items-start">

				<a href="http://qr.afip.gob.ar/?qr=0BMrsiS7edpbML4j5IHtDw,," target="_F960AFIPInfo">
					<img src="<?php echo $logo_datafiscal ?>" alt="logo" data-aos="fade-in" data-aos-delay="200"
						style="width: 60px;">
				</a>

			</div>

			<div class="col-auto d-flex flex-column">

				<img src="<?php echo $logo_footer_contenidos ?>" alt="logo" class="mb-2" data-aos="fade-in"
					data-aos-delay="000" style="width: 125px !important;">

				<div>

                    <?php if ($redes['linkedin']): ?>
                        <a href="<?php echo $redes['linkedin'] ?>" target="_blank" class="text-decoration-none icon-redes-footer me-1" data-aos="fade-in" data-aos-delay="000">
                            <?php echo get_burger_icon( 'icon-linkedin-primario' ) ?>
                        </a>
                    <?php endif ?>
                    
                    <?php if ($redes['facebook']): ?>
                        <a href="<?php echo $redes['facebook'] ?>" target="_blank" class="text-decoration-none icon-redes-footer me-1" data-aos="fade-in" data-aos-delay="000">
                            <?php echo get_burger_icon( 'icon-facebook-primario' ) ?>
                        </a>
                    <?php endif ?>

                    <?php if ($redes['instagram']): ?>
                        <a href="<?php echo $redes['instagram'] ?>" target="_blank" class="text-decoration-none icon-redes-footer me-1" data-aos="fade-in" data-aos-delay="000">
                            <?php echo get_burger_icon( 'icon-instagram-primario' ) ?>
                        </a>
                    <?php endif ?>

                    <?php if ($redes['youtube']): ?>
                        <a href="<?php echo $redes['youtube'] ?>" target="_blank" class="text-decoration-none icon-redes-footer" data-aos="fade-in" data-aos-delay="000">
                            <?php echo get_burger_icon( 'icon-youtube-primario' ) ?>
                        </a>
                    <?php endif ?>

				</div>

			</div>

			<div class="col-12 col-md">

				<h6 class="mb-2 text-center text-md-start fw-bold">
					<?= $titulo_sedes ?>
				</h6>

                <? if($habilitar_sedes_dos): ?>

                    <div class="row g-3">
                    
                        <div class="col-12 col-md-6 col-lg-auto">

                            <? if( !empty($sedes) && count($sedes) > 0 ): ?>

                                <? foreach( $sedes as $item ): extract($item) ?>

                                    <p class="d-flex align-items-center justify-content-center justify-content-md-start text-black mb-2">

                                        <svg width="9" height="12" viewBox="0 0 9 12" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            class="me-1">
                                            <path d="M4.03753 11.7579C0.632109 6.82104 0 6.31437 0 4.5C0 2.01471 2.01471 0 4.5 0C6.98529 0 9 2.01471 9 4.5C9 6.31437 8.36789 6.82104 4.96247 11.7579C4.73899 12.0807 4.26098 12.0807 4.03753 11.7579ZM4.5 6.375C5.53554 6.375 6.375 5.53554 6.375 4.5C6.375 3.46446 5.53554 2.625 4.5 2.625C3.46446 2.625 2.625 3.46446 2.625 4.5C2.625 5.53554 3.46446 6.375 4.5 6.375Z"
                                                fill="var(--primary)" />
                                        </svg>

                                        <a href="<?= $link ?>" target="_blank">

                                            <?= $titulo ?>
                                            &nbsp;<span style="color: var(--primary)">¿Cómo llego?</span>
                                            
                                            <svg width="9" height="6" viewBox="0 0 9 6" fill="none" xmlns="http://www.w3.org/2000/svg" 
                                                class="me-2">
                                                <path d="M1 0.900391L3.1 3.00039L1 5.10039" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M1 0.900391L3.1 3.00039L1 5.10039" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6.1001 0.900391L8.2001 3.00039L6.1001 5.10039" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>

                                        </a>

                                        <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg" 
                                            class="me-1">
                                            <path d="M10.4029 1.74375C9.28055 0.61875 7.78591 0 6.19752 0C2.91895 0 0.251088 2.66786 0.251088 5.94643C0.251088 6.99375 0.524302 8.01696 1.04395 8.91964L0.200195 12L3.35287 11.1723C4.22073 11.6464 5.19841 11.8955 6.19484 11.8955H6.19752C9.47341 11.8955 12.2002 9.22768 12.2002 5.94911C12.2002 4.36071 11.5252 2.86875 10.4029 1.74375ZM6.19752 10.8938C5.30823 10.8938 4.4377 10.6554 3.67966 10.2054L3.5002 10.0982L1.63055 10.5884L2.12877 8.76429L2.01091 8.57679C1.51537 7.78929 1.25555 6.88125 1.25555 5.94643C1.25555 3.22232 3.47341 1.00446 6.2002 1.00446C7.52073 1.00446 8.76091 1.51875 9.69305 2.45357C10.6252 3.38839 11.1984 4.62857 11.1957 5.94911C11.1957 8.67589 8.92162 10.8938 6.19752 10.8938ZM8.90823 7.19196C8.76091 7.11696 8.02966 6.75804 7.89305 6.70982C7.75645 6.65893 7.65734 6.63482 7.55823 6.78482C7.45912 6.93482 7.1752 7.26696 7.0868 7.36875C7.00109 7.46786 6.9127 7.48125 6.76537 7.40625C5.89216 6.96964 5.31895 6.62679 4.74305 5.63839C4.59037 5.37589 4.89573 5.39464 5.17966 4.82679C5.22787 4.72768 5.20377 4.64196 5.16627 4.56696C5.12877 4.49196 4.83145 3.76071 4.70823 3.46339C4.5877 3.17411 4.46448 3.21429 4.37341 3.20893C4.2877 3.20357 4.18859 3.20357 4.08948 3.20357C3.99037 3.20357 3.82966 3.24107 3.69305 3.38839C3.55645 3.53839 3.17341 3.89732 3.17341 4.62857C3.17341 5.35982 3.70645 6.06696 3.77877 6.16607C3.85377 6.26518 4.82609 7.76518 6.31805 8.41071C7.26091 8.81786 7.63055 8.85268 8.10198 8.78304C8.38859 8.74018 8.98055 8.42411 9.10377 8.07589C9.22698 7.72768 9.22698 7.43036 9.18948 7.36875C9.15466 7.30179 9.05555 7.26429 8.90823 7.19196Z"
                                                fill="#25282A" />
                                        </svg>

                                        <a href="<?= $whatsapp ?>"
                                            target="_blank" class="text-decoration-none">
                                            WhatsApp
                                        </a>

                                    </p>
                                
                                <? endforeach ?>

                            <? endif ?>

                        </div>

                        <div class="col-12 col-md-6 col-lg">

                            <? if( !empty($sedes_dos) && count($sedes_dos) > 0 ): ?>

                                <? foreach( $sedes_dos as $item ): extract($item) ?>

                                    <p class="d-flex align-items-center justify-content-center justify-content-md-start text-black mb-2">

                                        <svg width="9" height="12" viewBox="0 0 9 12" fill="none" xmlns="http://www.w3.org/2000/svg"
                                            class="me-1">
                                            <path d="M4.03753 11.7579C0.632109 6.82104 0 6.31437 0 4.5C0 2.01471 2.01471 0 4.5 0C6.98529 0 9 2.01471 9 4.5C9 6.31437 8.36789 6.82104 4.96247 11.7579C4.73899 12.0807 4.26098 12.0807 4.03753 11.7579ZM4.5 6.375C5.53554 6.375 6.375 5.53554 6.375 4.5C6.375 3.46446 5.53554 2.625 4.5 2.625C3.46446 2.625 2.625 3.46446 2.625 4.5C2.625 5.53554 3.46446 6.375 4.5 6.375Z"
                                                fill="var(--primary)" />
                                        </svg>

                                        <a href="<?= $link ?>" target="_blank">

                                            <?= $titulo ?>
                                            &nbsp;<span style="color: var(--primary)">¿Cómo llego?</span>
                                            
                                            <svg width="9" height="6" viewBox="0 0 9 6" fill="none" xmlns="http://www.w3.org/2000/svg" 
                                                class="me-2">
                                                <path d="M1 0.900391L3.1 3.00039L1 5.10039" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M1 0.900391L3.1 3.00039L1 5.10039" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6.1001 0.900391L8.2001 3.00039L6.1001 5.10039" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>

                                        </a>

                                        <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg" 
                                            class="me-1">
                                            <path d="M10.4029 1.74375C9.28055 0.61875 7.78591 0 6.19752 0C2.91895 0 0.251088 2.66786 0.251088 5.94643C0.251088 6.99375 0.524302 8.01696 1.04395 8.91964L0.200195 12L3.35287 11.1723C4.22073 11.6464 5.19841 11.8955 6.19484 11.8955H6.19752C9.47341 11.8955 12.2002 9.22768 12.2002 5.94911C12.2002 4.36071 11.5252 2.86875 10.4029 1.74375ZM6.19752 10.8938C5.30823 10.8938 4.4377 10.6554 3.67966 10.2054L3.5002 10.0982L1.63055 10.5884L2.12877 8.76429L2.01091 8.57679C1.51537 7.78929 1.25555 6.88125 1.25555 5.94643C1.25555 3.22232 3.47341 1.00446 6.2002 1.00446C7.52073 1.00446 8.76091 1.51875 9.69305 2.45357C10.6252 3.38839 11.1984 4.62857 11.1957 5.94911C11.1957 8.67589 8.92162 10.8938 6.19752 10.8938ZM8.90823 7.19196C8.76091 7.11696 8.02966 6.75804 7.89305 6.70982C7.75645 6.65893 7.65734 6.63482 7.55823 6.78482C7.45912 6.93482 7.1752 7.26696 7.0868 7.36875C7.00109 7.46786 6.9127 7.48125 6.76537 7.40625C5.89216 6.96964 5.31895 6.62679 4.74305 5.63839C4.59037 5.37589 4.89573 5.39464 5.17966 4.82679C5.22787 4.72768 5.20377 4.64196 5.16627 4.56696C5.12877 4.49196 4.83145 3.76071 4.70823 3.46339C4.5877 3.17411 4.46448 3.21429 4.37341 3.20893C4.2877 3.20357 4.18859 3.20357 4.08948 3.20357C3.99037 3.20357 3.82966 3.24107 3.69305 3.38839C3.55645 3.53839 3.17341 3.89732 3.17341 4.62857C3.17341 5.35982 3.70645 6.06696 3.77877 6.16607C3.85377 6.26518 4.82609 7.76518 6.31805 8.41071C7.26091 8.81786 7.63055 8.85268 8.10198 8.78304C8.38859 8.74018 8.98055 8.42411 9.10377 8.07589C9.22698 7.72768 9.22698 7.43036 9.18948 7.36875C9.15466 7.30179 9.05555 7.26429 8.90823 7.19196Z"
                                                fill="#25282A" />
                                        </svg>

                                        <a href="<?= $whatsapp ?>"
                                            target="_blank" class="text-decoration-none">
                                            WhatsApp
                                        </a>

                                    </p>
                                
                                <? endforeach ?>

                            <? endif ?>

                        </div>

                    </div>

                <? else: ?>

                    <? if( !empty($sedes) && count($sedes) > 0 ): ?>

                        <? foreach( $sedes as $item ): extract($item) ?>

                            <p class="d-flex align-items-center justify-content-center justify-content-md-start text-black mb-2">

                                <svg width="9" height="12" viewBox="0 0 9 12" fill="none" xmlns="http://www.w3.org/2000/svg"
                                    class="me-1">
                                    <path d="M4.03753 11.7579C0.632109 6.82104 0 6.31437 0 4.5C0 2.01471 2.01471 0 4.5 0C6.98529 0 9 2.01471 9 4.5C9 6.31437 8.36789 6.82104 4.96247 11.7579C4.73899 12.0807 4.26098 12.0807 4.03753 11.7579ZM4.5 6.375C5.53554 6.375 6.375 5.53554 6.375 4.5C6.375 3.46446 5.53554 2.625 4.5 2.625C3.46446 2.625 2.625 3.46446 2.625 4.5C2.625 5.53554 3.46446 6.375 4.5 6.375Z"
                                        fill="var(--primary)" />
                                </svg>

                                <a href="<?= $link ?>" target="_blank">

                                    <?= $titulo ?>
                                    &nbsp;<span style="color: var(--primary)">¿Cómo llego?</span>
                                    
                                    <svg width="9" height="6" viewBox="0 0 9 6" fill="none" xmlns="http://www.w3.org/2000/svg" 
                                        class="me-2">
                                        <path d="M1 0.900391L3.1 3.00039L1 5.10039" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M1 0.900391L3.1 3.00039L1 5.10039" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M6.1001 0.900391L8.2001 3.00039L6.1001 5.10039" stroke="var(--primary)" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>

                                </a>

                                <svg width="13" height="12" viewBox="0 0 13 12" fill="none" xmlns="http://www.w3.org/2000/svg" 
                                    class="me-1">
                                    <path d="M10.4029 1.74375C9.28055 0.61875 7.78591 0 6.19752 0C2.91895 0 0.251088 2.66786 0.251088 5.94643C0.251088 6.99375 0.524302 8.01696 1.04395 8.91964L0.200195 12L3.35287 11.1723C4.22073 11.6464 5.19841 11.8955 6.19484 11.8955H6.19752C9.47341 11.8955 12.2002 9.22768 12.2002 5.94911C12.2002 4.36071 11.5252 2.86875 10.4029 1.74375ZM6.19752 10.8938C5.30823 10.8938 4.4377 10.6554 3.67966 10.2054L3.5002 10.0982L1.63055 10.5884L2.12877 8.76429L2.01091 8.57679C1.51537 7.78929 1.25555 6.88125 1.25555 5.94643C1.25555 3.22232 3.47341 1.00446 6.2002 1.00446C7.52073 1.00446 8.76091 1.51875 9.69305 2.45357C10.6252 3.38839 11.1984 4.62857 11.1957 5.94911C11.1957 8.67589 8.92162 10.8938 6.19752 10.8938ZM8.90823 7.19196C8.76091 7.11696 8.02966 6.75804 7.89305 6.70982C7.75645 6.65893 7.65734 6.63482 7.55823 6.78482C7.45912 6.93482 7.1752 7.26696 7.0868 7.36875C7.00109 7.46786 6.9127 7.48125 6.76537 7.40625C5.89216 6.96964 5.31895 6.62679 4.74305 5.63839C4.59037 5.37589 4.89573 5.39464 5.17966 4.82679C5.22787 4.72768 5.20377 4.64196 5.16627 4.56696C5.12877 4.49196 4.83145 3.76071 4.70823 3.46339C4.5877 3.17411 4.46448 3.21429 4.37341 3.20893C4.2877 3.20357 4.18859 3.20357 4.08948 3.20357C3.99037 3.20357 3.82966 3.24107 3.69305 3.38839C3.55645 3.53839 3.17341 3.89732 3.17341 4.62857C3.17341 5.35982 3.70645 6.06696 3.77877 6.16607C3.85377 6.26518 4.82609 7.76518 6.31805 8.41071C7.26091 8.81786 7.63055 8.85268 8.10198 8.78304C8.38859 8.74018 8.98055 8.42411 9.10377 8.07589C9.22698 7.72768 9.22698 7.43036 9.18948 7.36875C9.15466 7.30179 9.05555 7.26429 8.90823 7.19196Z"
                                        fill="#25282A" />
                                </svg>

                                <a href="<?= $whatsapp ?>"
                                    target="_blank" class="text-decoration-none">
                                    WhatsApp
                                </a>

                            </p>
                        
                        <? endforeach ?>

                    <? endif ?>

                <? endif; ?>

			</div>

			<div class="col-10 col-md-6 col-lg-3">

				<div class="text-center text-md-start">

					<h6 class="fw-bold">
						<?php echo $titulo_asociados ?>
					</h6>

					<?php if ( !empty($galeria_asociados) && count($galeria_asociados) > 0): ?>

						<div class="owl-carousel owl-logos-footer owl-theme">

							<?php foreach ($galeria_asociados as $key => $item): extract($item) ?>

								<div class="item">

									<?php if ( !empty($link) && count($link) > 0): ?><a href="<?php echo $link['url'] ?>" target="_blank"><? endif ?>
										<img src="<?php echo $imagen ?>" alt="asociado">
									<?php if ( !empty($link) && count($link) > 0): ?></a><? endif ?>

								</div>

							<?php endforeach ?>

						</div>

					<?php endif ?>

				</div>

			</div>

		</div>

	</div>

</div>
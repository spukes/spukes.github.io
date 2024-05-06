<!DOCTYPE html>
<html lang="es">
<head>
	<title><?php echo isset($title) ? _h($title) : config('blog.title') ?></title>

	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
	<meta name="description" content="<?php echo config('blog.description')?>" />
	<link rel="icon" type="image/png" href="<?php echo site_url() ?>assets/notepad.png">
	<link rel="alternate" type="application/rss+xml" title="<?php echo config('blog.title')?>  Feed" href="<?php echo site_url()?>rss" />
	<link rel="stylesheet" href="https://fonts.xz.style/serve/inter.css"> 
	<link rel="stylesheet" media="screen" href="<?php echo site_url() ?>assets/new.css" async />
	<link rel="stylesheet" media="screen" href="<?php echo site_url() ?>assets/custom.css" async />


	<!--[if lt IE 9]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->

</head>
<body>
	<header>
		<div class="header">
			<a href="<?php echo site_url(); ?>" title="<?php echo config('blog.title'); ?>">
			<img src="<?php echo config('blog.image'); ?>" alt="<?php echo config('blog.title');?>" width="54" height="54" class="brand"></a>
			<hgroup>
				<h1><a href="<?php echo site_url() ?>"><?php echo config('blog.title') ?></a></h1>
				<p class="description"><?php echo config('blog.description')?></p>
			</hgroup>

			
			<ul class="nav-menu-social ml-auto">
				<?php if (!empty(config('show.rss'))): ?>
					<li><a href="./feed" style="margin-left: auto;" alt="RSS" title="Suscríbete por RSS"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 104 104" width="16" height="16">
						<path fill="var(--nc-tx-1)" d="M32.08 87.619c0 8.67-7.03 15.698-15.699 15.698-8.67 0-15.698-7.028-15.698-15.698 0-8.67 7.028-15.698 15.698-15.698 8.67 0 15.698 7.028 15.698 15.698ZM104 103.317a103.309 103.309 0 0 0-30.261-73.056A103.318 103.318 0 0 0 .683 0v18.85a84.468 84.468 0 0 1 84.467 84.467H104Z"></path>
						<path fill="var(--nc-tx-1)" d="M68.222 103.5A67.724 67.724 0 0 0 .5 35.778v15.441A52.282 52.282 0 0 1 52.781 103.5h15.441Z"></path></svg>
					</a></li>
				<?php endif; ?>
				<?php if (!empty(config('social.mastodon'))): ?>
					<li>
					<a href="<?php echo config('social.mastodon'); ?>" alt="Mastodon" title="Sígueme en Mastodon"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 216.4144 232.00976" width="20" height="20">
					<path d="M211.80734 139.0875c-3.18125 16.36625-28.4925 34.2775-57.5625 37.74875-15.15875 1.80875-30.08375 3.47125-45.99875 2.74125-26.0275-1.1925-46.565-6.2125-46.565-6.2125 0 2.53375.15625 4.94625.46875 7.2025 3.38375 25.68625 25.47 27.225 46.39125 27.9425 21.11625.7225 39.91875-5.20625 39.91875-5.20625l.8675 19.09s-14.77 7.93125-41.08125 9.39c-14.50875.7975-32.52375-.365-53.50625-5.91875C9.23234 213.82 1.40609 165.31125.20859 116.09125c-.365-14.61375-.14-28.39375-.14-39.91875 0-50.33 32.97625-65.0825 32.97625-65.0825C49.67234 3.45375 78.20359.2425 107.86484 0h.72875c29.66125.2425 58.21125 3.45375 74.8375 11.09 0 0 32.975 14.7525 32.975 65.0825 0 0 .41375 37.13375-4.59875 62.915" fill="var(--nc-tx-1)"></path>
					<path fill="var(--nc-bg-1)" d="M177.50984 80.077v60.94125h-24.14375v-59.15c0-12.46875-5.24625-18.7975-15.74-18.7975-11.6025 0-17.4175 7.5075-17.4175 22.3525v32.37625H96.20734V85.42325c0-14.845-5.81625-22.3525-17.41875-22.3525-10.49375 0-15.74 6.32875-15.74 18.7975v59.15H38.90484V80.077c0-12.455 3.17125-22.3525 9.54125-29.675 6.56875-7.3225 15.17125-11.07625 25.85-11.07625 12.355 0 21.71125 4.74875 27.8975 14.2475l6.01375 10.08125 6.015-10.08125c6.185-9.49875 15.54125-14.2475 27.8975-14.2475 10.6775 0 19.28 3.75375 25.85 11.07625 6.36875 7.3225 9.54 17.22 9.54 29.675"></path>
				</svg></a>
				</li>
				<?php endif; ?>
			</ul>			
		</div>
		<ul class="nav-menu only-desktop">
                <?php
                    $links = get_links();
                    foreach ($links as $link) {
                        echo '<li><a href="' . $link['url'] . '">' . $link['title'] . '</a></li>';
                    }
                ?>
		</ul>
		<details class="nav-menu-movil">
			<summary>Menu ⌘</summary>
			<ul class="nav-menu">
                <?php
                    $links = get_links();
                    foreach ($links as $link) {
                        echo '<li><a href="' . $link['url'] . '">' . $link['title'] . '</a></li>';
                    }
                ?>
			</ul>
		</details>
	</header>



	<section id="content">
		<?php echo content()?>
	</section>
	<footer>
		<hr>
		<div class="author">
  		<img alt="<?php echo config('blog.author'); ?>" src="<?php echo config('blog.author.avatar'); ?>">
  		<hgroup><author><b><?php echo config('blog.author') ?> </b></author><p><small><?php echo config('blog.authorbio') ?></small></p></hgroup>
  		</div>
		<hr>
		<small>Built with &hearts; and <a href="https://portabloc.xyz">Portabloc</a><br>GPL3 License · Some rights reserved &copy; <?php echo date('Y'); ?> </small>
	</footer>
</body>
</html>

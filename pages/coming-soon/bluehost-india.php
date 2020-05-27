<!DOCTYPE html>
<html>
<head>
<title>
	<?php
	printf(
	/* translators: %s: Blog name */
		__( '%s &mdash; Coming Soon', 'mojo-marketplace-wp-plugin' ),
		get_option( 'blogname' )
	);
	?>
</title>
<?php echo mm_cs_meta(); ?>
<style type='text/css'>
body{
	background-color: #2D2A25;
	background-image: url( <?php echo MM_BASE_URL . 'images/cs-bluehost-bg.jpg'; ?> );
	background-position: top right;
	background-repeat: no-repeat;
	font-family: 'Helvetica Neue', sans-serif;
	overflow-x: hidden;
}
#wrap{
	max-width: 460px;
	margin: 320px auto 0;
	color: #444;
	text-align: center;
}
#wrap h1{
	font-weight: 300;
	font-size: 28px;
}

#wrap h2{
	font-weight: 300;
	font-size: 38px;
}

footer{
	background-color: #fff;
	width: 100%;
	position: absolute;
	bottom:0;
	left:0;
	color: #666;
}
footer p{
	font-size: 18px;
	text-decoration: none;
	text-align: center;
	padding: 8px;
}
footer p a{
	color: #2e66ba;
	text-decoration: none;
}
footer p a:hover{
	text-decoration: underline;
}

.btn {
	display: inline-block;
	font-weight: 400;
	text-align: center;
	vertical-align: middle;
	-ms-touch-action: manipulation;
	touch-action: manipulation;
	cursor: pointer;
	background-image: none;
	border: 1px solid transparent;
	white-space: nowrap;
	-webkit-user-select: none;
	-moz-user-select: none;
	-ms-user-select: none;
	user-select: none;
	padding: 8px 16px;
	font-size: 14px;
	line-height: 1.5;
	border: 1px solid #2e66ba;
	background: #2e66ba;
	color: #fff;
	box-shadow: none;
	border-radius: 3px;
	text-decoration: none;
	margin-top: 60px;
}

.btn:hover {
	border: 1px solid #2e66ba;
	background-color: #fff;
	color: #2e66ba;
}
@media (max-width: 500px) {
	#wrap{
		max-width: 320px;
		margin: 60px auto 0;
		color: #444;
	}
	.btn {
		margin-top: 10px;
	}
}
@media (max-width: 360px) {
	#wrap{
		max-width: 320px;
		margin: 100px auto 0;
		color: #444;
	}
	#wrap h1 {
		font-size: 26px;
	}
	.btn {
		margin-top: 40px;
	}
}

</style>
</head>
<body>
	<div id='wrap'>
		<div class='content'>
			<h1><?php esc_html_e( 'A New WordPress Site', 'mojo-marketplace-wp-plugin' ); ?></h1>
			<h2><?php esc_html_e( 'Coming Soon!', 'mojo-marketplace-wp-plugin' ); ?></h2>
			<a class='btn' href='<?php echo site_url( 'wp-login.php' ); ?>'><?php esc_html_e( 'Admin Login', 'mojo-marketplace-wp-plugin' ); ?></a>
		</div>
	</div>
	<footer>
			<p class='text-center'>
				<?php
					printf(
						/* translators: %1$s is replaced with opening link tag, %2$s is replaced with closing link tag */
						esc_html__( 'a %1$sbluehost india%2$s powered website', 'mojo-marketplace-wp-plugin' ),
						'<a href="https://www.bluehost.in/optimized-wordpress-hosting.php" class="bluehost">',
						'</a>'
					);
					?>
			</p>
	</footer>
</body>
</html>

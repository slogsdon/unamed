<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<title>Unamed</title>
	<!-- Begin CSS -->
	<?php get_admin_head();?>
	<!-- End CSS -->
</head>
<body class="lava">
<!-- Begin Structure -->
<div class="wrapper">	
	<div class="container">
		<div id="top-bar">
			<div class="inside">
				<nav class="user">
					<ul>
						<li>
							<a href="<?php admin_url();?>users/edit/:id"><span>admin</span></a>
							<ul>
								<li><a href="<?php admin_url();?>users/edit/:id"><span>edit</span></a></li>
								<li><a href="<?php admin_url();?>users/logout"><span>log out</span></a></li>
							</ul>
						</li>
					</ul>
				</nav>
			</div>
		</div>
		<div id="left" class="one first">
			<div class="inside">
				<nav class="main">
					<ul>
						<li><a href="<?php admin_url();?>"><span>Overview</span></a></li>
						<li><a href="<?php admin_url();?>posts"><span>Posts</span></a></li>
						<li><a href="<?php admin_url();?>plugins"><span>Plugins</span></a></li>
						<li><a href="<?php admin_url();?>themes"><span>Themes</span></a></li>
						<li><a href="<?php admin_url();?>users"><span>Users</span></a></li>
						<li><a href="<?php admin_url();?>settings"><span>Settings</span></a></li>
					</ul>
				</nav>
			</div>
		</div>
		<div id="content" class="eleven last">
			<div class="inside">
				<section>
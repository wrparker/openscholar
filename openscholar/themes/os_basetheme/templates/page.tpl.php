<div id="admin_panel" class="admin_panel">
<!--REGION TO HOUSE RESPONSIVE MENU. OTHER CONTENT CAN'T BE PLACED HERE-->
<div class="responive-menu-container clearfix">
  <?php print render($page['responsive_menu']); ?>
</div>

<!--FLEXIBLE ADMIN HEADER FOR USE BY SELECT GROUPS USING OS-->
<?php if ($branding_header = render($page['branding_header'])): ?>
	<div id="branding_header">
		<div  class="branding-container clearfix">
		  <?php print $branding_header; ?>
		</div>
	</div>
<?php endif; ?>

<div id="page" class="container <?php print $classes; ?>">
	<div id="page-wrapper">

		<?php print $messages; ?>

		<?php if (
			$page['header_top'] ||
			$page['header_first'] ||
			$page['header_second'] ||
			$page['header_third'] ||
			$page['header_bottom']
			): ?>
			<!--header regions beg-->
			<header id="header" class="clearfix" role="banner">
			 <div id="header-container">
				 <div id="header-panels" class="at-panel gpanel panel-display three-col clearfix">
					  <?php print render($page['header_top']); ?>
					  <?php print render($page['header_second']); ?>
					  <?php print render($page['header_first']); ?>
					  <?php print render($page['header_third']); ?>
					  <?php print render($page['header_bottom']); ?>
				 </div>
			  </div>
		  </header>
      <!--header regions end-->
		<?php endif; ?>

		<?php if($menu_bar = render($page['menu_bar'])): ?>
		  <!--main menu region beg-->
		  <?php print $menu_bar; ?>
		  <!--main menu region end-->
		<?php endif; ?>

    <?php if($help = render($page['help'])): ?>
		  <!--help region beg-->
		  <?php print $help; ?>
		  <!--help region end-->
		<?php endif; ?>

		<div id="columns" class="clearfix">
			<div class="hg-container">
				<div id="content-column" role="main">
					<div class="content-inner">
					  <?php
					if ($breadcrumb) : print $breadcrumb;
					endif;
 ?>

						<?php if ($is_front || $use_content_regions): ?>
							<?php print render($title_prefix); ?>
              <a name="<?php echo $skip_link; ?>"></a>
							<?php if (!$is_front && $title): ?>
								<header id="main-content-header">
									<a name="<?php echo $skip_link; ?>"></a>
                    <h1 id="page-title"<?php print $attributes; ?>>
                      <?php print $title; ?>
                    </h1>
                </header>
							<?php endif; ?>
							<?php print render($title_suffix); ?>

							<?php if (
								$page['content_top'] ||
								$page['content_first'] ||
								$page['content_second'] ||
								$page['content_bottom']
								): ?>
								<!--front panel regions beg-->
								<div id="content-panels" class="at-panel gpanel panel-display content clearfix">
									<?php print render($page['content_top']); ?>
									<?php print render($page['content_first']); ?>
									<?php print render($page['content_second']); ?>
									<?php print render($page['content_bottom']); ?>
								</div>
								<!--front panel regions end-->
							<?php endif; ?>
						<?php endif; ?>

						<?php if (!$is_front && !$use_content_regions): ?>
							<<?php print $tag; ?> id="main-content">
								<a name="<?php echo $skip_link; ?>"></a>
								<?php print render($title_prefix); ?>
								<?php if ($title || $primary_local_tasks || $secondary_local_tasks || $action_links = render($action_links)): ?>
									<header id="main-content-header">
										<?php if (!$is_front && $title): ?>
											<h1 id="page-title"<?php print $attributes; ?>>
												<?php print $title; ?>
											</h1>
										<?php endif; ?>
										<?php if ($primary_local_tasks || $secondary_local_tasks || $action_links): ?>
											<div id="tasks">
												<?php if ($primary_local_tasks): ?>
													<ul class="tabs primary clearfix"><?php print render($primary_local_tasks); ?></ul>
												<?php endif; ?>
												<?php if ($secondary_local_tasks): ?>
													<ul class="tabs secondary clearfix"><?php print render($secondary_local_tasks); ?></ul>
												<?php endif; ?>
												<?php if ($action_links = render($action_links)): ?>
													<ul class="action-links clearfix"><?php print $action_links; ?></ul>
												<?php endif; ?>
											</div>
										<?php endif; ?>
									</header>
								<?php endif; ?>
								<?php print render($title_suffix); ?>

								<?php if ($content = render($page['content'])): ?>
									<div id="content">
										<?php print $content; ?>
									</div>
								<?php endif; ?>

							</<?php print $tag; ?>><!--main content ends-->
						<?php endif; ?>
					</div>
				</div>

				<?php if ($sidebar_first = render($page['sidebar_first'])): ?>
				  <!--sidebar first region beg-->
				  <?php print $sidebar_first; ?>
				  <!--sidebar first region end-->
        <?php endif; ?>

				<?php if ($sidebar_second = render($page['sidebar_second'])): ?>
				  <!--sidebar second region beg-->
				  <?php print $sidebar_second; ?>
				  <!--sidebar second region end-->
        <?php endif; ?>

			</div>
		</div>
		<!--footer region beg-->
		<footer id="footer" class="clearfix" role="contentinfo">
		<!-- Three column 3x33 Gpanel -->
		<?php if (
			$page['footer_top'] ||
			$page['footer_first'] ||
			$page['footer'] ||
			$page['footer_third'] ||
			$page['footer_bottom']
			): ?>

			<div class="at-panel gpanel panel-display footer clearfix">
				<?php print render($page['footer_top']); ?>
				<?php print render($page['footer_first']); ?>
				<?php print render($page['footer']); ?>
				<?php print render($page['footer_third']); ?>
				<?php print render($page['footer_bottom']); ?>
			</div>

		<!--footer region end-->
  <?php endif; ?>
  <div id="powerby-login">
  	<?php
	if (isset($login_link)) {
		print render($login_link);
	}
 ?>
  	<div id="powered-by"><a href="http://theopenscholar.org">OpenScholar</a></div>
    </div>
  </footer>
  </div>
</div><!--page area ends-->

<div id="extradiv"></div>

<?php if ($branding_footer = render($page['branding_footer'])): ?>
  <!--FLEXIBLE ADMIN FOOTER FOR USE BY SELECT GROUPS USING OS-->
  <div id="branding_footer">
		<div class="branding-container">
	    <?php print $branding_footer; ?>
		</div>
  </div>
<?php endif; ?>
</div><!-- /admin_panel -->
<div class="morph-button morph-button-sidebar morph-button-fixed">
			<button type="button">
				<img src="img/os-cp_button.png">
				<!--<span class="icon icon-cog">Settings Menu</span>-->
			</button>
			<div class="morph-content">
				<div>
					<div class="content-style-sidebar">
						<a href="#" style="position:absolute;left:10px;top:7px;"><img src="img/home_button.png" width="25" height="25"></a>
						<div class="close_panel">
							<span style="color:#a39d9d;">O</span><span style="color:#df1f26;">S</span>
						</div><span class="icon icon-close">&#171; Close</span>
						<div id='cssmenu'>
							<ul>
								<li>
									&nbsp;
								</li>

								<li class='has-sub'>
									<a href='#'>Site Content</a>
									<ul class='sub-menu'>
										<li class="heading has-sub submenu">
											<a href='#' class="submenu_heading">Browse</a>
											<ul>
												<li>
													<a href='#'>Content</a>
												</li>
												<li>
													<a href='#'>Files</a>
												</li>
												<li>
													<a href='#'>Widgets</a>
												</li>
												<li>
													<a href='#'>Tagging</a>
												</li>
											</ul>
										</li>

										<li class="heading has-sub submenu">
											<a href='#' class="submenu_heading">Add</a>
											<ul>
												<li>
													<a href='#'>Basic Page</a>
												</li>
												<li>
													<a href='#'>Files</a>
												</li>
												<li>
													<a href='#'>ContentType 2</a>
												</li>
												<li>
													<a href='#'>ContentType 3</a>
												</li>
												<li>
													<a href='#'>ContentType N</a>
												</li>
											</ul>
										</li>
										<li class="heading has-sub submenu">
											<a href='#' class="submenu_heading">Import</a>
											<ul>
												<li>
													<a href='#'>Page</a>
												</li>
												<li>
													<a href='#'>ContentType 2</a>
												</li>
												<li>
													<a href='#'>ContentType 3</a>
												</li>
												<li>
													<a href='#'>ContentType N</a>
												</li>
											</ul>
										</li>
									</ul>
								</li>
								<li>
									<a href='#'>Menus</a>
								</li>
								<li>
								<li class='has-sub'>
									<a href='#'>Appearance</a>
									<ul class='sub-menu'>
										<li>
											<a href='#'>Themes</a>
										</li>
										<li>
											<a href='#'>Layout</a>
										</li>
										<li>
											<a href='#'>Theme Settings</a>
										</li>
									</ul>
								</li>
								<li class='has-sub'>
									<a href='#'>Settings</a>
									<ul class='sub-menu'>
										<li>
											<a href='#'>Apps</a>
										</li>
										<li>
											<a href='#'>Publications</a>
										</li>
										<li>
											<a href='#'>Blog</a>
										</li>
										<li>
											<a href='#'>Profiles</a>
										</li>
										
										<li>
											<a href='#'>Private Files</a>
										</li>
										<li>
											<a href='#'>FAQs</a>
										</li>
										<li>
											<a href='#'>Booklets</a>
										</li>
										<li>
											<a href='#'>Advanced</a>
										</li>
									</ul>
								</li>
								<li>
									<a href='#'>Users &amp; Roles</a>
								</li>
								<li class='has-sub'>
									<a href='#'>Help</a>
									<ul class='sub-menu'>
										<li>
											<a href='#'>Support</a>
										</li>
										<li>
											<a href='#'>Documentation</a>
										</li>

									</ul>
								<li class='has-sub'>
									<a href='#'>Admin</a>
									<ul class='sub-menu'>
										<li>
											<a href='#'>Content</a>
										</li>
										<li>
											<a href='#'>Structure</a>
										</li>
										<li>
											<a href='#'>Appearance</a>
										</li>
										<li>
											<a href='#'>People</a>
										</li>
										<li>
											<a href='#'>Modules</a>
										</li>
										<li>
											<a href='#'>Configuration</a>
										</li>
										<li>
											<a href='#'>Reports</a>
										</li>
										<li>
											<a href='#'>Help</a>
										</li>
									</ul>
								</li>
							</ul>

						</div>
					</div>
				</div>
			</div>
		</div><!-- morph-button -->

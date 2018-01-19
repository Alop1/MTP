<div id="cooked-welcome-screen">
	<div class="wrap about-wrap">
		<h1><?php echo sprintf( __('%s is now available.','cooked'), 'Cooked Pro' ); ?></h1>
		<div class="about-text">
			<?php echo sprintf( __("We've cooked up something great and we call it %s. This upgrade adds loads of new features like ratings, favorites, user profiles and more. Check out the list below for more details.","cooked"),"Cooked Pro" ); ?>
		</div>
		<div class="cooked-badge">
			<img src="<?php echo COOKED_URL; ?>/assets/admin/images/badge-pro.png">
		</div>

		<div id="welcome-panel" class="welcome-panel">

			<img src="<?php echo COOKED_URL; ?>/assets/admin/images/pro-banner.jpg" class="cooked-welcome-banner">

			<div class="welcome-panel-content">
				<div class="welcome-panel-column-container">
					<div class="welcome-panel-column welcome-panel-full">
						<h3 style="font-size:2em; text-align:center;"><?php esc_html_e( 'So, what does Cooked Pro include?','cooked' ); ?>&nbsp;&nbsp;<span style="font-weight:400;"><?php esc_html_e( "I'm glad you asked","cooked" ); ?></span></h3>
						<div class="cooked-clearfix" style="width:85%; margin:0 auto;">
							<ul class="cooked-whatsnew-list cooked-whatsnew-pro">
								<li><i class="cooked-icon cooked-icon-star"></i> <?php esc_html_e( "User Profiles","cooked" ); ?></li>
								<li><i class="cooked-icon cooked-icon-star"></i> <?php esc_html_e( "Recipe Submissions","cooked" ); ?></li>
								<li><i class="cooked-icon cooked-icon-star"></i> <?php esc_html_e( "Favorite Hearts","cooked" ); ?></li>
								<li><i class="cooked-icon cooked-icon-star"></i> <?php esc_html_e( "User Ratings","cooked" ); ?></li>

							</ul>
							<ul class="cooked-whatsnew-list cooked-whatsnew-pro">
								<li><i class="cooked-icon cooked-icon-star"></i> <?php esc_html_e( "Social Sharing","cooked" ); ?></li>
								<li><i class="cooked-icon cooked-icon-star"></i> <?php esc_html_e( "Ajax Pagination","cooked" ); ?></li>
								<li><i class="cooked-icon cooked-icon-star"></i> <?php esc_html_e( "Modern Grid Layout","cooked" ); ?></li>
								<li><i class="cooked-icon cooked-icon-star"></i> <?php esc_html_e( "Full-Width Layout","cooked" ); ?></li>
							</ul>
							<ul class="cooked-whatsnew-list cooked-whatsnew-pro">
								<li><i class="cooked-icon cooked-icon-star"></i> <?php esc_html_e( "Compact List Layout","cooked" ); ?></li>
								<li><i class="cooked-icon cooked-icon-star"></i> <?php esc_html_e( "Cuisines","cooked" ); ?></li>
								<li><i class="cooked-icon cooked-icon-star"></i> <?php esc_html_e( "Cooking Methods","cooked" ); ?></li>
								<li><i class="cooked-icon cooked-icon-star"></i> <?php esc_html_e( "Tags","cooked" ); ?></li>
							</ul>
						</div>
						<a href="https://cooked.pro/" target="_blank" class="cooked-pro-button"><?php echo sprintf( esc_html__( "Get %s","cooked" ), "Cooked Pro" ); ?></a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

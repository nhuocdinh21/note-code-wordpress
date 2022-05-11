<?php 
get_header();
?>
	<div id="content" class="content-area page-wrapper" role="main">
		<div class="row row-small layout_hoidap">
			<div class="col large-3 hoidap_sidebar">
				<div class="col-inner">						
					<?php 
						$catechild = get_terms( 'chude', array(
						    'orderby'    => 'name',
						    'order'=>'ASC',
						    'hide_empty' => 0,		    
						    'parent'=> 0
						) );
						if( $catechild ): ?>
							<div class="box box_chude">
								<h3 class="title"><?php echo __('Chủ đề','custom'); ?></h3>
								<ul class="ul_box list_chude">
									<?php foreach ($catechild as $catathumti): ?>
										<li onclick="get_question_by_category(<?php echo $catathumti->term_id; ?>)">
											<a title="<?php echo $catathumti->name; ?>"><?php echo $catathumti->name; ?></a>
										</li>
									<?php endforeach; ?>
								</ul>
							</div>
					<?php endif; ?>
					<?php 
						$args = array(
							'post_type'=> 'cauhoi',
							'posts_per_page' => '5',
						);
						$my_query = new wp_query( $args );
						if( $my_query->have_posts() ) : ?>
							<div class="box box_list_cauhoi">
								<h3 class="title"><?php echo __('Danh sách câu hỏi mới nhất','custom'); ?></h3>
								<ul class="ul_box list_cauhoi">
									<?php while( $my_query->have_posts() ) : $my_query->the_post(); ?>
										<li>
											<a href="<?php the_permalink(); ?>" title="<?php echo get_the_title(); ?>"><?php echo get_the_content(); ?></a>
										</li>
									<?php endwhile; ?>
								</ul>
							</div>
						<?php
						endif;
						wp_reset_query(); 
					?>
					<?php dynamic_sidebar('hoidap_sidebar'); ?>
				</div>
			</div>
			<div class="col large-9 hoidap_content medium-col-first">
				<div class="col-inner">
					<div class="loading-screen" style="display: none;">
	                    <div class="timeline-item">
	                        <div class="animated-background facebook">
	                            <div class="background-masker header-top-1"></div>
	                            <div class="background-masker header-left"></div>
	                            <div class="background-masker header-right"></div>
	                            <div class="background-masker header-bottom-1"></div>
	                            <div class="background-masker subheader-left"></div>
	                            <div class="background-masker subheader-right"></div>
	                            <div class="background-masker subheader-bottom"></div>
	                            <div class="background-masker content-top"></div>
	                            <div class="background-masker content-first-end"></div>
	                            <div class="background-masker content-second-line"></div>
	                            <div class="background-masker content-second-end"></div>
	                            <div class="background-masker content-third-line"></div>
	                            <div class="background-masker content-third-end"></div>
	                        </div>
	                    </div>
	                </div>                        
	                <div>
	                	<div id="all_reply">
	                		<?php 
	                			$parentid_question = get_post_meta( get_the_ID(), 'parentid_question', true );
	                			$args = array(
							        'post_type' => 'cauhoi',
									'post_status' => 'publish',	
							        'p' => $parentid_question,
							    );
							    $my_posts = new WP_Query($args);
							    if($my_posts->have_posts()) : 
							    	while ( $my_posts->have_posts() ) : $my_posts->the_post();
							    		global $post;			
										$author_id = $post->post_author;
										$cat = get_the_terms( $post->ID, 'chude');
										$user_id = get_current_user_id();
										$post_id = $post->ID;
										?>
											<div class="social-feed-box">
											    <div class="social-avatar">
											        <span class="image">
											        	<img src="<?php echo esc_url(get_avatar_url( $author_id )); ?> " width="40" height="40" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id ); ?>" />
											        </span>
											        <div class="media-body">
											            <p>
											                <strong><?php echo get_the_author(); ?></strong>
											                <span><?php echo __('đã hỏi','custom'); ?>:</span>
											            </p>
											            <small>
											                <span><?php echo get_the_date('d/m/Y H:i'); ?></span>
											            </small>
											        </div>
											    </div>
											    <div class="social-body">
											        <div class="title"><?php echo get_the_content(); ?></div>
											        <?php if( has_post_thumbnail() ): ?>
												        <div class="img_question">
												        	<?php the_post_thumbnail(); ?>
												        </div>
												    <?php endif; ?>
											        <div class="social-select m-b-xs">
											            <span class="bg-white"><?php echo __('Chủ đề','custom'); ?>:</span>
											            <ul class="list-unstyled list-inline">
											                <li onclick="get_question_by_category(<?php echo $cat[0]->term_id; ?>)">
											                    <a href="javascript:;"><?php echo $cat[0]->name; ?></a>
											                </li>
											            </ul>
											        </div>
											    </div>
											    <div class="social-footer has-active-reply-box" id="box_reply_<?php echo $post_id; ?>">
											        <div class="social-like-box">
											            <span>
											                <a href="<?php the_permalink(); ?>"><?php echo __('Xem chi tiết','custom'); ?></a>
											            </span>
											        </div>
											        <?php 
											        	$args_tl = array(
															'post_type' => 'traloi',
															'post_status' => 'publish',							
															'meta_query' => array( 
																array(
																	'key' => 'parentid_question',
							            							'value' => $post_id,
																)
															),
															'ignore_sticky_posts' => true,
														);
														$loop_tl = new WP_Query($args_tl);
														if( $loop_tl->have_posts() ):
															while ($loop_tl->have_posts()) : $loop_tl->the_post();
																global $post;			
																$author_id_1 = $post->post_author;
																?>
																	<div data-author="<?php echo $author_id_1; ?>" class="social-comment">
																	    <a href="javascript:;">
																	        <span class="image">
																	            <img src="<?php echo esc_url(get_avatar_url( $author_id_1 )); ?> " width="40" height="40" class="avatar" alt="<?php echo the_author_meta( 'display_name' , $author_id_1 ); ?>" />  
																	        </span>
																	    </a>
																	    <div class="media-body">
																	        <div class="media-name">
																	            <a href="javascript:;"><?php echo get_the_author(); ?></a>
																	        </div>
																	        <div class="title"><?php echo get_the_title(); ?></div>										        
																	    </div>
																	    <div class="media-footer">
																	        <ul class="list-unstyled media-meta">
																	            <li><?php echo get_the_date('d/m/Y H:i'); ?></li>
																	        </ul>
																	    </div>
																	</div>
																<?php
															endwhile;
														endif;
														wp_reset_query();
											        ?>
											        <?php 
											        	$args_tt = array(
															'post_type' => 'traloi',
															'post_status' => 'publish',
															'posts_per_page' => -1,								
															'meta_query' => array( 
																array(
																	'key' => 'parentid_question',
							            							'value' => $post_id,
																)
															),
															'ignore_sticky_posts' => true,
														);
														$loop_tt = new WP_Query($args_tt);
														$total_tt = $loop_tt->found_posts;
														echo '<input type="text" id="total_traloi_'.$post_id.'" value="'.$total_tt.'" hidden>';
														wp_reset_query();
											        ?>
											        <div id="add-reply-<?php echo $post_id; ?>"></div>
											        <div class="social-reply">
											            <div class="media-body">
											                <div class="form-group">
											                    <textarea placeholder="<?php echo __('Viết trả lời','custom'); ?>..." rows="1" id="content_reply_<?php echo $post_id; ?>" class="form-control resize-textarea"></textarea>
											                </div>
											                <button <?php if( is_user_logged_in() ) echo 'data-user="'.$user_id.'"'; ?> id="btn_reply_<?php echo $post_id; ?>" class="btn"><i aria-hidden="true" class="fa fa-paper-plane"></i><?php echo __('Gửi','custom'); ?></button>
											                <?php if( !is_user_logged_in() ): ?>
																<script type="text/javascript">
																	jQuery(function($) {
																		$('#btn_reply_<?php echo $post_id; ?>').click(function() {
																			$.magnificPopup.open({
																	    		items: {
																	        		src: '#popup_login' 
																	    		},
																	    		type: 'inline'
																	      	});
																		});
																	});
																</script>
															<?php else: ?>
																<script type="text/javascript">
																	jQuery(function($) {
																		$('#btn_reply_<?php echo $post_id; ?>').attr('onclick','postQuestion(<?php echo $post_id; ?>)');
																	});
																</script>	
															<?php endif; ?>
											            </div>
											        </div>
											    </div>
											</div>
										<?php
									endwhile;
								endif;
								wp_reset_query();
	                		?>	                		
	                	</div>
	                </div>                                              
				</div>
			</div>
		</div>
	</div>
<?php get_footer(); ?>
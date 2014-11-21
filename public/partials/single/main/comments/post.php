<?php

function fossil_post_comment_form( $fossil=null ) {
    if ( ! is_user_logged_in() ) return;
    ?>
    <form method="post" role="complementary" class="form">
        <div id="fossil-comment-box">
            <div class="status-update">
                <div class="status-update-body">
                    <div id="whats-new-user-avatar">
                        <?php bp_loggedin_user_avatar('width=50&height=50' ); ?>
                    </div>
                    <div id="whats-new-textarea" class="form-group">
                        <textarea 
                                class="form-control bp-suggestions" 
                                name="whats-new" id="fossil-comment-textarea"><?php if (isset($_GET['r'])): ?> @<?php echo esc_textarea($_GET['r']); ?> <?php endif; ?></textarea>
                    </div>

                    <div id="whats-new-options">
                        <div id="whats-new-submit">
                            <input type="hidden" id="fossil-id"
                                value="<?=$fossil->id ?>" />
                            <button type="button" class="btn btn-default"
                                    name="aw-whats-new-submit"
                                    id="fossil-comment-submit">
                                Comment
                            </button>
                        </div>
                    </div><!-- #whats-new-options -->
                </div>
            </div>
        </div><!-- #whats-new-content -->

        <?php wp_nonce_field('post_update', '_wpnonce_post_update'); ?>
    </form><!-- #whats-new-form -->

    <?php
}

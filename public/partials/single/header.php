<?php

function fossil_header( $fossil=null, $view='main' ) {
    ?>

    <div id="buddypress-header" class="dark">
        <div id="item-header" class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
                    <img class="avatar img-responsive fossil-image" src="<?=$fossil->image ?>" />
                </div>
                <div class="col-xs-12 col-sm-12 col-md-6 col-lg-8">
                    <h1>Fossil <?=sprintf( "%06d", $fossil->id ) ?></h1>
                    <input type="hidden" id="post_id" value="<?=$fossil->id ?>" />
                    <input type="hidden" id="myfossil_specimen_nonce" 
                            value="<?=wp_create_nonce( 'myfossil_specimen' ) ?>" />
                    <dl class="inline fossil-header">
                        <dt>Author</dt>
                        <dd>
                            <a href="<?=bp_core_get_user_domain( $fossil->author->ID ) ?>">
                                <?=trim( $fossil->author->display_name ) ?>
                            </a>
                        </dd>
                        <dt>Updated</dt><dd><?=$fossil->updated_at ?></dd>
                        <?php if ( $fossil->location && $fossil->location->country ): ?>
                            <dt>Location</dt><dd><?=$fossil->location->country ?></dd>
                        <?php endif; ?>
                    </dl>
                </div>
            </div>
        </div>

        <div id="item-nav" class="container">
            <ul class="nav nav-tabs">
                <li class="<?=( $view == 'main' ) ? "active" : null ?>">
                    <a href="/fossils/<?=$fossil->id ?>/">Information</a>
                </li>
                <li class="<?=( $view == 'images' ) ? "active" : null ?>">
                    <a href="/fossils/<?=$fossil->id ?>/images">Images</a>
                </li>
                <li class="<?=( $view == 'history' ) ? "active" : null ?>">
                    <a href="/fossils/<?=$fossil->id ?>/history">History</a>
                </li>
                <li class="<?=( $view == 'discussion' ) ? "active" : null ?>">
                    <a href="/fossils/<?=$fossil->id ?>/discussion">Discussion</a>
                </li>
                <?php if ( current_user_can( 'edit_post', $fossil->ID ) ) : ?>
                <li class="<?=( $view == 'settings' ) ? "active" : null ?>">
                    <a href="/fossils/<?=$fossil->id ?>/settings">Settings</a>
                </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <?php
}

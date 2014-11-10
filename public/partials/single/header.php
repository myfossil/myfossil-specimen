<?php

function fossil_header( $fossil=null, $view='main' ) {
    ?>

    <div id="buddypress-header" class="dark">
        <div id="item-header" class="container">
            <div class="row">
                <div class="col-xs-12 col-sm-12 col-md-3 col-lg-2">
                    <img class="avatar img-responsive" src="<?=$fossil->image ?>" />
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
                        <dt>Status</dt>
                        <dd>
                            <?php $draft = ( get_post_status( $fossil->id ) == 'draft' ); ?>
                            <div class="btn-group" data-toggle="buttons">
                                <label class="btn btn-default btn-sm <?=( $draft ) ? "btn-active active" : null ?>">
                                    <input type="radio" name="status" 
                                        id="draft" autcomplete="off" 
                                        <?=( $draft ) ? "checked" : null ?>
                                        class="post_status" value="draft" />
                                    <i class="fa fa-fw fa-eye-slash"></i>
                                    Draft
                                </label>
                                <label class="btn btn-default btn-sm <?=( $draft ) ? null : "btn-active active" ?>">
                                    <input type="radio" name="status" 
                                        id="published" autcomplete="off"
                                        <?=( $draft ) ? null : "checked" ?>
                                        class="post_status" value="publish" />
                                    <i class="fa fa-fw fa-eye"></i>
                                    Published 
                                </label>
                            </div>
                        </dd>
                    </dl>
                </div>
            </div>
        </div>

        <div id="item-nav" class="container">
            <ul class="nav nav-tabs">
                <li class="<?=( $view == 'main' ) ? "active" : null ?>">
                    <a href="/fossils/<?=$fossil->id ?>/">Information</a>
                </li>
                <li class="<?=( $view == 'history' ) ? "active" : null ?>">
                    <a href="/fossils/<?=$fossil->id ?>/history">History</a>
                </li>
                <li class="<?=( $view == 'discussion' ) ? "active" : null ?>">
                    <a href="/fossils/<?=$fossil->id ?>/discussion">Discussion</a>
                </li>
            </ul>
        </div>
    </div>

    <?php
}

<?php

require_once '_common.php';

function fossil_edit_taxonomy( $fossil=null )
{
?>

    <div id="edit-fossil-taxon" class="edit-fossil-popup">
        <div class="edit-fossil">
            <div class="edit-fossil-heading">
                <h4>Taxonomy</h4>
            </div>
            <div class="edit-fossil-body">
                <form class="form">
                    <div class="form-group">
                        <label class="control-label">Taxon</label>
                        <input class="form-control" type="text"
                                id="edit-fossil-taxon-name"
                                placeholder="Begin typing your Taxon" />
                    </div>
                    <?php edit_comment_box( 'taxon' ); ?>
                </form>
            </div>
            <div class="edit-fossil-footer">
                <ul id="edit-fossil-taxon-results">
                </ul>
            </div>
        </div>
    </div>

    <?php
}

function fossil_view_taxonomy( $fossil=null )
{
?>
    <input type="hidden" id="fossil-taxon-name" value="<?php echo $fossil->taxon->name ?>" />
    <input type="hidden" id="fossil-taxon-rank" value="<?php echo $fossil->taxon->rank ?>" />
    <input type="hidden" id="fossil-taxon-pbdb" value="<?php echo $fossil->taxon->pbdbid ?>" />

    <h3>
        Classification
        <i style="display: none" class="fa fa-fw fa-circle-o-notch fa-spin"
                id="fossil-taxon-loading"></i>
        <i style="display: none" class="fa fa-fw fa-check"
                id="fossil-taxon-success"></i>
        <i style="display: none" class="fa fa-fw fa-warning"
                id="fossil-taxon-error"></i>

    </h3>

    <?php save_alert( 'taxon' ); ?>

    <table id="fossil-taxon" class="table">
        <tr class="sr-only">
            <th>Taxonomy Level</th>
            <th>Value</th>
            <th>Options</th>
        </tr>
        <?php foreach ( array( 'phylum', 'class', 'order', 'family', 'genus', 'species' ) as $k ): ?>
            <tr>
                <td class="fossil-property"><?php echo ucwords( $k ) ?></td>
                <td class="fossil-property-value<?php echo ( current_user_can( 'edit_post', $fossil->id ) ) ? " edit-fossil-taxon_open editable" : null ?>"
                        id="fossil-taxon-<?php echo $k ?>"
                        data-edit="<?php echo current_user_can( 'edit_post', $fossil->id )?>"
                        data-popup-ordinal="<?php echo current_user_can( 'edit_post', $fossil->id )?>">
                    <?php if ( $fossil->taxon && ( $fossil->taxon->{ $k } ) && ( $v = $fossil->taxon->{ $k }->name ) ): ?>
                        <?php echo $v ?>
                    <?php else: ?>
                        <span class="unknown">Unknown</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>

    <?php
}

function fossil_taxonomy( $fossil=null )
{
    fossil_view_taxonomy( $fossil );
    fossil_edit_taxonomy( $fossil );
}

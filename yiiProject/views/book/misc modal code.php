<?php $modal = Modal::begin([
        'toggleButton' => 
            ['label' => 'Manage Genre'], 
        'title' => 'Manage genres', 
        'id' => 'genreModal',
    ]); ?>


[ show list and stuff ]


<div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
    </div>

    
 <?php Modal::end(); ?>
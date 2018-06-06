<?php self::renderView('partial/Header', $model); ?>
<div class="container main-container">
    <h1>Einen Review anlegen</h1>
    <?php 
        if (isset($model['review']) && $model['review'] !== null) {
            self::beginActionForm('Modify', 'Review', array('pid' => $model['Review']->getId()), 'post');
        } else {
            self::beginActionForm('Create', 'Review', null, 'post');            
        }
    ?>
        <div class="form-group">
            <label for="rating"> Rating</label>
            <input class="form-control" id="rating" name="rating" value="<?php self::htmlOut($model['rating']) ?>">
        </div>
        <div class="form-group">
            <label for="comment"> Kommentar</label>
            <input class="form-control" id="comment" name="comment" value="<?php self::htmlOut($model['comment']) ?>">
        </div>
        <button class="btn btn-info">Bewertung speichern</button>
    <?php self::endActionForm(); ?>
</div>

<?php self::renderView('partial/Footer', $model); ?>
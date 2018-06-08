<?php self::renderView('partial/Header', $model); ?>
<div class="container main-container">
    <?php if (isset($model['review']) && $model['review'] !== null): ?>
        <h1>Einen Review verändern</h1>
    <?php else: ?>
        <h1>Einen Review anlegen</h1>
    <?php endif; ?>
    <br>
    <h3>
        <?php if (isset($model['product']) && $model['product'] !== null) {
            echo($model['product']->getName());
        } ?>
    </h3>
    <?php 
        if (isset($model['review']) && $model['review'] !== null) {
            self::beginActionForm('Modify', 'Review', array('rid' => $model['review']->getId()), 'post');
        } else {
            self::beginActionForm('Create', 'Review', array( 'pid' => $model['product']->getId()), 'post');            
        }
    ?>
        <div class="form-group">
            <select name="rating" id="rating">
                <option value="1" <?php if (isset($model['rating']) && $model['rating'] == 1) { echo('selected="selected"'); } ?>>1</option>
                <option value="2" <?php if (isset($model['rating']) && $model['rating'] == 2) { echo('selected="selected"'); } ?>>2</option>
                <option value="3" <?php if (isset($model['rating']) && $model['rating'] == 3) { echo('selected="selected"'); } ?>>3</option>
                <option value="4" <?php if (isset($model['rating']) && $model['rating'] == 4) { echo('selected="selected"'); } ?>>4</option>
                <option value="5" <?php if (isset($model['rating']) && $model['rating'] == 5) { echo('selected="selected"'); } ?>>5</option>
            </select>
        </div>
        <!--<div class="form-group">    
            <label for="rating"> Rating</label>
            <input class="form-control" id="rating" name="rating" value="<?php if (isset($model['rating'])) { self::htmlOut($model['rating']); } ?>">
        </div>-->
        <div class="form-group">
            <label for="comment"> Kommentar</label>
            <input class="form-control" id="comment" name="comment" value="<?php if (isset($model['comment'])) {self::htmlOut($model['comment']); } ?>">
        </div>
        <button class="btn btn-info">Bewertung speichern</button>
        <?php if (isset($model['review'])) { self::actionLink('Bewertung löschen', 'Delete', 'Review', array('rid' => $model['review']->getId()), 'btn btn-outline-success'); } ?>
    <?php self::endActionForm(); ?>
</div>

<?php self::renderView('partial/Footer', $model); ?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-bar-rating/1.2.2/jquery.barrating.min.js"></script>
<script type="text/javascript">
   $(function() {
      $('#rating').barrating({
        theme: 'fontawesome-stars'
      });
   });
</script>
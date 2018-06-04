<?php self::renderView('partial/Header', $model); ?>
<div class="container main-container">
    <h1>Eine neues Produkt anlegen</h1>
    <?php 
        if (isset($model['product']) && $model['product'] !== null) {
            self::beginActionForm('Modify', 'Product', array('pid' => $model['product']->getId()), 'post');
        } else {
            self::beginActionForm('Create', 'Product', null, 'post');            
        }
    ?>
        <div class="form-group">
            <label for="category"> Kategorie</label>
            <input class="form-control" id="category" name="cat" value="<?php self::htmlOut($model['category']) ?>">
        </div>
        <div class="form-group">
            <label for="name"> Name</label>
            <input class="form-control" id="name" name="name" value="<?php self::htmlOut($model['name']) ?>">
        </div>
        <div class="form-group">
            <label for="manufacturer"> Hersteller</label>
            <input class="form-control" id="manufacturer" name="manu" value="<?php self::htmlOut($model['manufacturer']) ?>">
        </div>
        <button class="btn btn-info">Produkt speichern</button>
    <?php self::endActionForm(); ?>
</div>

<?php self::renderView('partial/Footer', $model); ?>
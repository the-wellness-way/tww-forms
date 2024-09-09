<?php
/**
 * Template for the grams2ounces form
 *
 * @package TWWForms
 */
 $justify = $atts['justify'] ?? 'flex-start';
?>


<div class="grams2ounces-container">
    <div class="grams2ounces" style="justify-content: <?php echo $justify; ?>">
        <div class="grams2ounces__inner">
            <form id="grams2ounces-form" class="grams2ounces__form">
                <div class="form-group">
                    <div class="grams2ounces__conversion-group">
                        <div class="grams2ounces__conversion-group-item">
                            <input type="number" name="grams" id="grams2ounces-grams" value="1" placeholder="Grams" />
                            <label>Grams</label>
                        </div>
                        <div class="grams2ounces__comparison">=</div> 
                        <div class="grams2ounces__conversion-group-item">
                            <input type="number" name="ounces" id="grams2ounces-ounces" value="0.035274" placeholder="Ounces" />
                            <label>Ounces</label>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
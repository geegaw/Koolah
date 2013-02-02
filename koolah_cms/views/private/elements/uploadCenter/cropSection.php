<?php
    $ratios = new RatiosTYPE();
    $ratios->get(); 
?>
<!-- cropSection -->
<section id="cropSection" class="hide centerHoriz">
    <div id="cropImgArea" class="" >
        <img id="cropImg" src="" />
        <div id="cropImgHeight"><span></span></div>
        <div id="cropImgWidth"><span></span></div>
    </div>
    
    <form id="cropForm">        
        <input type="hidden" id="cropRatioID" value="" />
        <fieldset id="dimensions">
            <legend>Ratios</legend>
            <fieldset>
                <fieldset id="ratios">
                    <button type="button" data-id="freeForm" class="active">Free Form</button>
                    <?php foreach ($ratios->ratios() as $ratio):?>
                        <button type="button" data-id="<?php echo $ratio->getID();?>" data-w="<?php echo $ratio->w; ?>" data-h="<?php echo $ratio->h; ?>" >
                            <?php echo $ratio->label->label.' ('.$ratio->w.':'.$ratio->h.')'; ?>
                        </button>
                    <?php endforeach; ?>
                </fieldset>    
            </fieldset>
            <fieldset id="freeFormArea">
                <legend>Free Form</legend>
                <fieldset>
                    <label for="cropName">Name</label>
                    <input type="text" id="cropName" value="" placeholder="Name" />
                </fieldset>
                <fieldset>
                    <label for="cropWidth">Width</label>
                    <input type="text" id="cropWidth" value="" placeholder="Width" />
                </fieldset>
                <fieldset>
                    <label for="cropHeight">Height</label>
                    <input type="text" id="cropHeight"  value=""placeholder="Height"/>
                </fieldset>
            </fieldset>
        </fieldset>    
        
        
        <div id="cropMsgBlock"></div>
        <fieldset class="commands">
            <input type="submit" class="cancel noreset" value="close" />
            <input type="submit" class="reset noreset"  value="reset" />
            <input type="submit" class="save noreset"  value="save" />            
        </fieldset>
        
    </div>
</section>
<!-- /cropSection -->

<!-- imgPreview -->
<section id="imgPreview" class="hide">
    <button type="button" class="close" id="closeImgPreview">X</button>
    <img src="" alt="preview" />
</section>
<!-- /imgPreview -->
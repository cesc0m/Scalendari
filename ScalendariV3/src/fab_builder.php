<?php
class FabButton{
    private $onClickFunction;
    private $image;
    private $bgColor = NULL;
    
    function FabButton($image){
        $this->image = $image;
    }
    
    function setOnClickFunction($function){
        $this->onClickFunction = $function;
    }
    
    function display(){
        ?>
        <div class="fab" <?php 
        if($this->onClickFunction != NULL) {
            echo "onclick=\"".$this->onClickFunction."\" ";
        }
        ?>>
        	<img src="<?php echo $this->image; ?>"/>
        </div>
        <?php        
    }
}
class FabBuilder{
    private $buttons = NULL;
    function FabBuilder($buttonsArray){
        $this->buttons = $buttonsArray;
    }
    
    function display(){
        ?>
        <div class="fabContainer">
        <?php 
        foreach($this->buttons as $button){
            $button->display();
        }
        ?>
        </div>
        <?php
    }
}
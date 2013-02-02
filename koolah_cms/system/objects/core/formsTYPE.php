<?php

class FormsTYPE
{
    public $id;
    public $label;
    public $action;
    public $html_class;
    
    private $inputs;
    private $error_msg;
    private $value;
    
    public function __construct($inputs=null)
    {
        $this->inputs = $inputs;
        $this->html_class = null;
        $this->id = null;
        $this->label = null;
        $this->action = null;
        $this->error_msg = null;
        $this->value = null;
    }
    
    public function setInputs($inputs)
    {
        $this->inputs = $inputs;
    }
    
    public function beginForm()
    {
        if ($this->label)
            echo '<label for="'.$this->id.'">'.$this->label.'</label>';
        echo '<form id="'.$this->id.'" class="'.$this->html_class.'" action="'.$this->action.'" method="POST">';
    }
    
    public function simplePrintForm()
    {
        $this->beginForm();
        $this->printInputs();
        $this->endForm();
        $this->printJS();
    }
    
    public function endForm()
    {
        echo '<input type="hidden" name="formSubmitted" value="true"/></form>';
    }
    
    public function printJS(){ echo "<script src='public/js/forms.js'></script>"; }
    
    
    public function isSubmitted()
    {
        return isset($_POST['formSubmitted']);
    }
    
    public function validateForm($testing=false)
    {
        if ($this->inputs)
        {
            if ( !$this->readAndcheckInputs() )      
            {
                if ($testing)
                    $this->printErrors();
                return false;
            }
            else
            {
                if ($testing)
                    $this->printData();
                return true;
            }
        }
        else
        {
            $this->printErrors();
            return false;
        }
    }
    
    public function readInput(){ return null;}    
    public function getValue(){ return $this->value; }
    public function getErrorMsg(){ return $this->error_msg;}
    
    private function printInputs()
    {
        if ($this->inputs)
        {
            foreach ($this->inputs as $input)
                $input->printInput();
        }
    }
    
    private function readAndcheckInputs()
    {
        $valid = true;
        if ($this->inputs)
        {
            foreach($this->inputs as $input)
            {
                if (get_class($input) != 'SubmitInputTYPE')
                {
                    $input->readInput();
                    if($input->getErrorMsg() != null)
                        $valid = false;
                }
            }
        }
        else
            $valid = false;
            
        return $valid;
    }
    
    public function printErrors()
    {
        if ($this->inputs)
        {
            echo '<div id="formErrors" class="error"><ul>';
            foreach($this->inputs as $input)
            {
                if (get_class($input) != 'SubmitInputTYPE')
                {
                    if($input->getErrorMsg() != null)
                        echo '<li>'.$input->getErrorMsg().'</li>';
                }
            }
            echo '</ul></div>';
        }
    }
    
    private function printData()
    {
        if ($this->inputs)
        {
            echo '<div id="formData" class="testing"><ul>';
            foreach($this->inputs as $input)
            {
                if (get_class($input) != 'SubmitInputTYPE')
                    echo '<li>'.$input->id.': '.$input->getValue().'</li>';
            }
            echo '</ul></div>';
        }
    }
    
    
}

class TextInputTYPE extends FormsTYPE
{
    public $placeholder;
    public $required;
    public $description;
    public $fieldset;
    public $fieldsetClass;
    
    public function __construct()
    {
        $this->id = null;
        $this->html_class = null;
        $this->label = null;
        $this->placeholder = null;
        $this->required = false;        
        $this->error_msg = null;
        $this->value = null;
        $this->description = null;
        $this->fieldset =false;
        $this->fieldsetClass = null;
    }
    
    public function printInput()
    {    
        if ($this->fieldset)
            echo '<fieldset class="'.$this->fieldsetClass.'">';
        
        if ($this->label)
            echo '<label for="'.$this->id.'">'.$this->label.'</label>';
        echo '<input type="text" id="'.$this->id.'" name="'.$this->id.'" class="'.$this->html_class;
        if ($this->required)
            echo ' required';
        if ($this->value)
            echo '" value="'.$this->value.'"';
        else
            echo '" value=""';
        echo ' placeholder="'.$this->placeholder.'" />';
        if ( $this->description )
            echo '<span class="description">'.$this->description.'</span>';
        
        if ($this->fieldset)
            echo '</fieldset>';
    }
    
    public function readInput()
    {
        if ( (isset($_POST[$this->id])) && (!empty($_POST[$this->id])) && ($_POST[$this->id] != $this->placeholder) )
            $this->value = $_POST[$this->id];
        elseif ($this->required)
            $this->error_msg = $this->placeholder." can not be left blank";    
    }
    
    public function getValue(){ return $this->value; }
    public function getErrorMsg(){ return $this->error_msg;}
}

class EmailInputTYPE extends TextInputTYPE
{
    public function printInput()
    {    
        if ($this->fieldset)
            echo '<fieldset class="'.$this->fieldsetClass.'">';
        
        if ($this->label)
            echo '<label for="'.$this->id.'">'.$this->label.'</label>';
        echo '<input type="email" id="'.$this->id.'" name="'.$this->id.'" class="'.$this->html_class;
        if ($this->required)
            echo ' required';
        if ($this->value)
            echo '" value="'.$this->value.'"';
        else
            echo '" value="'.$this->placeholder.'"';
        echo ' placeholder="'.$this->placeholder.'" />';
        if ( $this->description )
            echo '<span class="description">'.$this->description.'</span>';
        
        if ($this->fieldset)
            echo '</fieldset>';
    }
    public function getValue(){ return $this->value; }
    public function getErrorMsg(){ return $this->error_msg;}
}


class SelectInputTYPE extends FormsTYPE
{
    public $placeholder;
    public $select_options;
    public $required;
    public $fieldset;
    public $fieldsetClass;
    
        
    public function __construct()
    {
        $this->id = null;
        $this->label = null;
        $this->placeholder = null;
        $this->select_options = null;
        $this->required = false;        
        $this->error_msg = null;
        $this->value = null;
        $this->fieldset =false;
        $this->fieldsetClass = null;
    }
    
    public function printInput()
    {    
        if ($this->fieldset)
            echo '<fieldset class="'.$this->fieldsetClass.'">';
        
        if ($this->label)
            echo '<label for="'.$this->id.'">'.$this->label.'</label>';
        echo '<select id="'.$this->id.'" name="'.$this->id.'" class="'.$this->html_class;
        if ($this->required)
            echo ' required';
        echo '">
        <option value="no_selection">'.$this->placeholder.'</option>';
        if ($this->select_options)
        {
            foreach ($this->select_options as $option)
            {
                if ( isset($option['value']) )
                    echo "<option value='".$option['value']."'>".$option['html']."</option>";
                else
                    echo "<option value='$option'>$option</option>";
            }
        }
        echo '</select>';            
        if ( $this->description )
            echo '<span class="description">'.$this->description.'</span>';
        
        if ($this->fieldset)
            echo '</fieldset>';   
    }
    
    public function readInput()
    {
        if ( (isset($_POST[$this->id])) && ($_POST[$this->id] != 'no_selection') )
            $this->value = $_POST[$this->id];
        elseif ($this->required)
            $this->error_msg = $this->placeholder." can not be left blank";    
    }
    public function getValue(){ return $this->value; }
    public function getErrorMsg(){ return $this->error_msg;}
}

class SubmitInputTYPE extends FormsTYPE
{
    public $placeholder;
    public $fieldset;
    public $fieldsetClass;  
    
    public function __construct()
    {
        $this->id = null;
        $this->html_class = null;
        $this->label = null;
        $this->placeholder = null;
        $this->fieldset =false;
        $this->fieldsetClass = null;
    }
    
    public function printInput()
    {    
        if ($this->fieldset)
            echo '<fieldset class="'.$this->fieldsetClass.'">';
        
        if ($this->label)
            echo '<label for="'.$this->id.'">'.$this->label.'</label>';
        echo '<input type="submit" id="'.$this->id.'" name="'.$this->id.'" class="'.$this->html_class.'" value="'.$this->placeholder.'"/>';
        
        if ($this->fieldset)
            echo '</fieldset>';
    }       
    

}

class PasswordInputTYPE extends FormsTYPE
{
    public $required;
    public $description;
    public $fieldset;
    public $fieldsetClass;
    
    
    public function __construct()
    {
        $this->id = null;
        $this->html_class = null;
        $this->label = null;
        $this->required = false;        
        $this->error_msg = null;
        $this->value = null;
        $this->description = null;
        $this->fieldset =false;
        $this->fieldsetClass = null;
    }
    
    public function printInput()
    {    
        if ($this->fieldset)
            echo '<fieldset class="'.$this->fieldsetClass.'">';
        
        if ($this->label)
            echo '<label for="'.$this->id.'">'.$this->label.'</label>';
        echo '<input type="password" id="'.$this->id.'" name="'.$this->id.'" class="'.$this->html_class;
        if ($this->required)
            echo ' required';
        echo '" value="" />';
        if ( $this->description )
            echo '<span class="description">'.$this->description.'</span>';
        
        if ($this->fieldset)
            echo '</fieldset>';
    }
    
    public function readInput()
    {
        if ( (isset($_POST[$this->id])) && (!empty($_POST[$this->id])) )
            $this->value = $_POST[$this->id];
        elseif ($this->required)
            $this->error_msg = "password can not be left blank";    
    }
    public function getValue(){ return $this->value; }
    public function getErrorMsg(){ return $this->error_msg;}
}

class CheckboxInputTYPE extends FormsTYPE
{
    public $placeholder;
    public $required;
    public $multiple;
    public $name;
    public $description;
    public $fieldset;
    public $fieldsetClass;
    
    public function __construct()
    {
        $this->id = null;
        $this->name = null;
        $this->html_class = null;
        $this->label = null;
        $this->placeholder = null;
        $this->required = false;        
        $this->error_msg = null;
        $this->value = null;
        $this->multiple = false;
        $this->description = null;
        $this->fieldset =false;
        $this->fieldsetClass = null;    
    }
    
    public function printInput()
    {    
        if ($this->fieldset)
            echo '<fieldset class="'.$this->fieldsetClass.'">';
        
        if ($this->name && $this->multiple)
            $this->name.='[]';
        elseif (!$this->name)
            $this->name=$this->id;
        
        echo '<input type="checkbox" id="'.$this->id.'" name="'.$this->name.'" class="'.$this->html_class;
        if ($this->required)
            echo ' required';
        echo '" ';
        if ($this->value)
            echo "value='".$this->value."'";
        else
            echo 'value=""';
        echo '/>';
        if ($this->label)
            echo '<label for="'.$this->id.'">'.$this->label.'</label>';
        if ( $this->description )
            echo '<span class="description">'.$this->description.'</span>';
        
        if ($this->fieldset)
            echo '</fieldset>';        
    }
    
    public function readInput()
    {
        if ( (isset($_POST[$this->name])) && (!empty($_POST[$this->name])))
            $this->value = $_POST[$this->name];
        elseif ($this->required)
            $this->error_msg = $this->placeholder." can not be left blank";    
    }
    
    public function getValue(){ return $this->value; }
    public function getErrorMsg(){ return $this->error_msg;}
}


?>
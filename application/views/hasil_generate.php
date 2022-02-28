<?php 
    echo form_open('welcome/export/');
    
    echo br(2);
    
    foreach ($barcode as $key => $v_barcode) {
        echo "<img src='".base_url()."img/".$v_barcode."'><br>".rtrim($v_barcode,'.png')."<br><br>";
        echo "<input type='hidden' value='".$v_barcode."' name='p_barcode[]'>";
    }

    echo br(3);
    
    echo '<input type="submit" value="Genereate">';

    echo form_close(); 
?>
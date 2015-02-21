<?php
ob_clean();

if ( $download )
    header('Content-Disposition: attachment; filename="'.$fm->name.'.'.$fm->ext.'"');
else
    header('Content-Type: image/'.$fm->imageExt());
readfile($fm->path);
?>

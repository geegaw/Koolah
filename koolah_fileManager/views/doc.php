<?php
header('Content-type: application/'.$fm->ext);
if ( $download )
    header('Content-Disposition: attachment; filename="'.$fm->name.'.'.$fm->ext.'"');
readfile($fm->path);

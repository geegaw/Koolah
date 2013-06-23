<?php
if (!isset($_REQUEST['template']) || !isset($_REQUEST['data']))
    return;
$alias = '/preview';
if (isset($_REQUEST['alias']))
    $alias = $_REQUEST['alias'];
if ($alias[0] != '/')
    $alias = '/'.$alias;
$alias = trim($alias);
?>

<form id="previewForm" method="post" action="http://<?php echo PREVIEW_URL.$alias;?>">
    <input type="hidden" name="secret_key" value="<?php echo PREVIEW_SECRET_KEY; ?>" />
    <input type="hidden" name="template" value="<?php echo $_REQUEST['template']; ?>" />
    <input type="hidden" name="data" value='<?php echo $_REQUEST['data']; ?>' />
</form>

<script>document.getElementById("previewForm").submit();</script>
/*
$(document).ready(function(){
    $('.catBox').click(function(){
        var classes = $(this).attr('class');
        classes = classes.split(' ');
        var cat = '';
        var found = false;
        for (var i=0; ((i < classes.length) && (!found)); i++)
        {
            if (classes[i] != 'catBox')
            {
                cat = classes[i];
                found = true;
            }
        }    
        
        var checked = $(this).attr('checked')
        if (checked)
            $('.'+cat).attr('checked', 'checked');
        else
            $('.'+cat).removeAttr('checked');
    });
    
    $('#grantAll').click(function(){
        var checked = $(this).attr('checked')
        if (checked)
        {
            $('.catBox').attr('checked', 'checked');
            $('.perm').attr('checked', 'checked');
        }
        else
        {
            $('.catBox').removeAttr('checked');
            $('.perm').removeAttr('checked');
        }
    });
    
    var userCanModifyPermission = $('#userCanModifyPerms').val();
    if (!userCanModifyPermission)
        $(':checkbox').click(function(){return false});
});

*/
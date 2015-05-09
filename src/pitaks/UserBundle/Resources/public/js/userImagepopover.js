/**
 * Created by Aurimas on 2015.05.09.
 */
$('span[rel=popover]').popover({
    html: true,
    trigger: 'hover',
    placement: 'bottom',
    content: function(){return '<img src="'+$(this).data('img') + '" />';}
});

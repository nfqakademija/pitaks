var descriptionArray = $('.description').toArray();
for ( var i = 0; i < descriptionArray.length; i++ )
{
    var tektas =$(descriptionArray[ i]).text();
    $(descriptionArray[i]).html(tektas);
}
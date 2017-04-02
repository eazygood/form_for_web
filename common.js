var x = document.getElementById('openModal');
var createContainer = document.createElement('div');
createContainer.setAttribute('id', 'container');
x.appendChild(createContainer);

var modalHeader = document.createElement('div');
modalHeader.setAttribute('class', 'modalHeader');
createContainer.appendChild(modalHeader);


var result = document.createElement('div');
result.setAttribute('id', 'result');
createContainer.appendChild(result);

/*
var heading = document.createElement('h2');
heading.innerHTML = "Заявка успешно отправлена";
var heading2 = document.createElement('h2');
heading2.innerHTML = "Päring on edukalt saatnud";
modalHeader.appendChild(heading);
modalHeader.appendChild(heading2);
*/

var linkClose = document.createElement('a');
linkClose.setAttribute('href', '#');
linkClose.setAttribute('class', 'close');
linkClose.innerHTML = 'X';
modalHeader.appendChild(linkClose);

/*
var modalFooter = document.createElement('div');
modalFooter.setAttribute('class', 'modalFooter');
createContainer.appendChild(modalFooter);

var linkCancel = document.createElement('a');
linkCancel.setAttribute('href', '#cancel');
linkCancel.setAttribute('class', 'cancel');
linkCancel.innerHTML = 'Cancel';
modalFooter.appendChild(linkCancel);

*/

/*The text of a label becomes the name of the selected file. 
If there were multiple files selected, 
the text will tell us how many of them were selected.

*/
var inputs = document.querySelectorAll('.uploadfile');
Array.prototype.forEach.call( inputs, function( input )
{
	var label	 = input.nextElementSibling,
		labelVal = label.innerHTML;

	input.addEventListener( 'change', function( e )
	{
		var fileName = '';
		if( this.files && this.files.length > 1 )
			fileName = ( this.getAttribute( 'data-multiple-caption' ) || '' ).replace( '{count}', this.files.length );
		else
			fileName = e.target.value.split( '\\' ).pop();

		if( fileName )
			label.querySelector( 'span' ).innerHTML = fileName;
		else
			label.innerHTML = labelVal;
	});
	/* For FireFox Bug that ignores input[type="file"]:focus */
	input.addEventListener( 'focus', function(){ input.classList.add( 'has-focus' ); });
	input.addEventListener( 'blur', function(){ input.classList.remove( 'has-focus' ); });
});




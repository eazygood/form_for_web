	$(document).ready(function() {
		$(document).ajaxStart(function() {
			// finding an element with img and remove it invisibility
			var imgObj = $("#load-indicator");
			imgObj.show();
			// make some calculation for getting coorinates and put loader.gif
			// that it will be at the center of page;
			var centerY = $(window).scrollTop() + ($(window).height()  + imgObj.height()) / 2 ;
			var centerX = $(window).scrollLeft() + ($(window).width() + imgObj.width()) / 2;
			imgObj.offset({
				left: centerX,
				top: centerY
			});
		});
		// hide image after AJAX-request ending
			$(document).ajaxStop(function() {
				$('#load-indicator').hide();
			});

			$('.close').click(function() { 
				$('#openModal').hide()
			});

			$('#close').click(function() {
				$('#request')[0].reset();
			});
	});



	// assign action for the form submit event
	$('#request').submit(function (evtObj) {
		evtObj.preventDefault();
			// If the element of form "uploadfile" has values(e.g choosen file for sending),
			// then using FormData() insted of AJAX-request
			// because files does not transfer through AJAX-requests
			
			if (document.getElementById("file").value !== '') {
				// showing gif image of awaiting
				var imgObj = $("#load-indicator");
				imgObj.show();
				var centerY = $(window).scrollTop() + ($(window).height()  + imgObj.height()) / 2 ;
				var centerX = $(window).scrollLeft() + ($(window).width() + imgObj.width()) / 2;
				imgObj.offset({
					left: centerX,
					top: centerY
				});

											// var form = document.forms.request;
											var formData = new FormData(document.querySelector('form'));
											var xhr = new XMLHttpRequest();
											xhr.open("POST", "mail_sender.php");
											xhr.onreadystatechange = function () {
													if (xhr.readyState == 4) {
															if (xhr.status == 200) {
																	data = xhr.responseText;
																	
																	// Application successfully sent
																	if (data === 'Отправлено письмо с вложением.') {
																			document.getElementById("request").reset();
																			$('span.filesInputed').html('Vali faili'); // changes span conent to Vali faili
																			$("#result").html('Отправлено письмо c вложениeм.<br>Kiri on saadetud lisandusega.');
																			$("#openModal").show(); // shows Modal window that applications successfully sent
																	} else {
																					$("#result").html(data);
																	}
																	// remove loader.gif
																	$('#load-indicator').hide();
																	
															}
													}
											};
				xhr.send(formData);
				// Otherwise (file does not attached)
				// Making AJAX-request for sending an email
			} else {
				var form = $(this);
				$.ajax({
					// Here is the file, which process received data from users and sending an email
					url: 'mail_sender.php',
					type: 'POST',
					data: form.serialize(),
					// Some acts if successfully sent AJAX-request (not a letter!)
					// Here is data - recieved from mail_sender.php message
					success: function(data) {
						if (data === 'Отправлено письмо без вложения.') {
							
							// Letter sent, reset all form data
							document.getElementById("request").reset();
							$("#result").html('Отправлено письмо без вложения.<br>Kiri on saadetud lisanduseta.');
							// The next string after successful message's sent
							// redirect user for other page of website
							// it is more enough to uncomment below line
							// document.location.href = 'http://kimtest.com'
							$("#openModal").show(); // shows Modal window that applications successfully sent
						} else {
							$("#result").html(data);
						}
					},
					error: function(data) {
						$("#result").html('Результат выполнения: ' + data);
					}
				});
			}
	});


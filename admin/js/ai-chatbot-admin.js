(function ($) {

	"use strict";
	jQuery(document).ready(function() {

		$('tbody tr').on('click', function(e) {
			if (e.target.type !== 'checkbox') {
				$(':checkbox', this).trigger('click');
			}
		});
		$('.check-all').click(function() {
			var ai_chatbot_table_nearest = $(this).closest('.table');
			ai_chatbot_table_nearest.find('input[type="checkbox"]').prop('checked', $(this).prop('checked'));
		});
		$('.card').each(function() {
			if ($(this).find('.ai_chatbot_child_checkbox:checked').length > 0) {
				$(this).find('.ai_chatbot_switch_button').prop('checked', true);
				$(this).find('.ai_chatbot_displaybox').removeClass('d-none');
			}
			$(this).find('.ai_chatbot_switch_button').prop('checked', true);
			$(this).find('.ai_chatbot_displaybox').removeClass('d-none');
		});
		// Add active class to the first tab
		$('.nav-tabs li:first-child a').addClass('active');
		// Show the first tab content
		$('.tab-content #pages-posts').addClass('in active');
		// Define an array of chat account IDs
		const chatAccountIds = [1, 2, 3];

		function handleChatAccountChange(id) {
			const checkbox = jQuery(`#ai_chatbot_is_ac_${id}`);
			const display = jQuery(`#ai_chatbot_display_ac_${id}`);
			if (checkbox.prop("checked")) {
				display.removeClass("d-none");
			} else {
				display.addClass("d-none");
			}
		}

		// Loop through the chat account IDs and add change handlers
		chatAccountIds.forEach((id) => {
			jQuery(`#ai_chatbot_is_ac_${id}`).change(() => handleChatAccountChange(id));
			if (jQuery(`#ai_chatbot_is_ac_${id}`).prop("checked")) {
				jQuery(`#ai_chatbot_display_ac_${id}`).removeClass("d-none");
			} else {
				jQuery(`#ai_chatbot_display_ac_${id}`).addClass("d-none");
			}
		});

		// Define a function to handle tab clicks
		function handleTabClick(event) {
			event.preventDefault();
			jQuery(this).tab("show");
		}

		// Add a click handler to all nav tab links
		jQuery("ul.nav-tabs a").click(handleTabClick);

		// Define a function to handle the click to chat account enable button
		function handleChatAccountEnableClick() {
			checkac();
		}

		// Add a click handler to the click to chat account enable button
		jQuery("#ai_chatbot_ac_enable").click(handleChatAccountEnableClick);
		jQuery("#ai_chatbot_email").keyup(function () {
			var ai_chatbot_email = jQuery("#ai_chatbot_email").val();
			var regex = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
			if (!regex.test(ai_chatbot_email)) {
				var ai_chatbot_ele = document.getElementById("ai_chatbot_error_email");
				if (ai_chatbot_ele) {
					document.getElementById("ai_chatbot_error_email").style.display = "block";
				}
				jQuery("#ai_chatbot_getbutton").addClass("ai_chatbot_btn-theme_border");
				jQuery("#ai_chatbot_getbutton").removeClass("ai_chatbot_btn-theme");
			} else {
				var ai_chatbot_ele = document.getElementById("ai_chatbot_error_email");
				if (ai_chatbot_ele) {
					document.getElementById("ai_chatbot_error_email").style.display = "none";
				}
				jQuery("#ai_chatbot_getbutton").removeClass("ai_chatbot_btn-theme_border");
				jQuery("#ai_chatbot_getbutton").addClass("ai_chatbot_btn-theme");
			}
		});
		jQuery("#ai_chatbot_otp1").keyup(function () {
			var ai_chatbot_otp1 = jQuery("#ai_chatbot_otp1").val();
			if (ai_chatbot_otp1 != "") {
				jQuery("#ai_chatbot_otp2").focus();
			}
		});
		jQuery("#ai_chatbot_otp2").keyup(function () {
			var ai_chatbot_otp2 = jQuery("#ai_chatbot_otp2").val();
			if (ai_chatbot_otp2 != "") {
				jQuery("#ai_chatbot_otp3").focus();
			}
		});
		jQuery("#ai_chatbot_otp3").keyup(function () {
			var ai_chatbot_otp3 = jQuery("#ai_chatbot_otp3").val();
			if (ai_chatbot_otp3 != "") {
				jQuery("#ai_chatbot_otp4").focus();
			}
		});
		var ai_chatbot_element1 = jQuery("#ai_chatbot_email").val();
		if (ai_chatbot_element1 != "") {
			jQuery("#ai_chatbot_alert").css("display", "block");
			jQuery("#ai_chatbot_usernameform").removeClass("d-none");
			jQuery("#ai_chatbot_emailform").addClass("d-none");

		} else {
			jQuery("#ai_chatbot_alert").css("display", "none");
			jQuery("#ai_chatbot_emailform").removeClass("d-none");
			jQuery("#ai_chatbot_usernameform").addClass("d-none");
		}
		var element = document.getElementById("ai_chatbot_resend_btn");
		if (typeof (element) != 'undefined' && element != null) {
			countdown('clock', 0, 9);
		}
		jQuery("#ai_chatbot_url").keyup(function () {
			var ai_chatbot_email = jQuery("#ai_chatbot_email").val();
			const regex = /^(https?|ftp):\/\/(-\.)?([^\s/?\.#]+\.?)+([^\s]*)?$/;
			if (!regex.test(ai_chatbot_email)) {

				jQuery("#ai_chatbot_siteurl_button").addClass("ai_chatbot_btn-theme_border");
				jQuery("#ai_chatbot_siteurl_button").removeClass("ai_chatbot_btn-theme");
			} else {

				jQuery("#ai_chatbot_siteurl_button").removeClass("ai_chatbot_btn-theme_border");
				jQuery("#ai_chatbot_siteurl_button").addClass("ai_chatbot_btn-theme");
			}
		});

		// Pagination script
		let currentPage = 1;
		const rowsPerPage = 5;

		function displayRows() {
			const rows = document.querySelectorAll('#tableBody tr');
			const totalRows = rows.length;
			const totalPages = Math.ceil(totalRows / rowsPerPage);

			rows.forEach((row, index) => {
				row.style.display = 'none';
				if (index >= (currentPage - 1) * rowsPerPage && index < currentPage * rowsPerPage) {
					row.style.display = 'table-row';
				}
			});

			document.getElementById('prevBtn').disabled = currentPage === 1;
			document.getElementById('nextBtn').disabled = currentPage === totalPages;
		}

		function nextPage() {
			currentPage++;
			displayRows();
		}

		function prevPage() {
			currentPage--;
			displayRows();
		}

		displayRows();

		jQuery("#prevBtn").click(prevPage);
		jQuery("#nextBtn").click(nextPage);

	});
})(jQuery);
function ai_chatbot_refreshPage() {
	location.reload();
}

/**
 * function to get messages and display error message if null
 *
 * @since    1.0.0
 */
function ai_chatbot_submitfunction() {
	const ai_chatbot_owner_message = document.getElementById("ai_chatbot_message").value;
	const ai_chatbot_customer_maessage = document.getElementById("ai_chatbot_customer_message").value;
	const ai_chatbot_word = "{#var#}";

	if (ai_chatbot_owner_message.includes(ai_chatbot_word)) {
		event.preventDefault ? event.preventDefault() : (event.returnValue = false);
		document.getElementById("ai_chatbot_message_variable_error").style.display = "block";
		return false;
	}

	if (ai_chatbot_customer_maessage.includes(ai_chatbot_word)) {
		event.preventDefault ? event.preventDefault() : (event.returnValue = false);
		document.getElementById("ai_chatbot_message_variable_error").style.display = "block";
		return false;
	}
	document.getElementById("ai_chatbot_message_variable_error").style.display = "none";
	return true;
}

/**
 * function to check for form validation
 *
 * @since    1.0.0
 */

function ai_chatbot_FormValidation() {
	const txtmobile = document.getElementById("ai_chatbot_admin_mobile").value;
	const phoneno = /^[0-9]*$/;

	// Check if the input is valid
	if (txtmobile.length < 5 || txtmobile.length > 15 || !phoneno.test(txtmobile)) {
		document.getElementById("ai_chatbot_phonemsg").innerHTML = "Please enter a valid mobile number.";
		return false;
	} else {
		document.getElementById("ai_chatbot_phonemsg").innerHTML = "";
		return true;
	}
}

function ai_chatbot_otpvalidation() {
	var otp1 = document.getElementById("ai_chatbot_otp1").value;
	var otp2 = document.getElementById("ai_chatbot_otp2").value;
	var otp3 = document.getElementById("ai_chatbot_otp3").value;
	var otp4 = document.getElementById("ai_chatbot_otp4").value;

	var otp = otp1.concat(otp2, otp3, otp4);
	jQuery("#ai_chatbot_otp").val(otp);
}

jQuery(document).on("ready",function () {



	jQuery(".ai_chatbot_chat_card input[type='radio']").change(function () {
		ai_chatbot_check_widget_type_radio();
		ai_chatbot_check_radio_icon_default();
	});
	ai_chatbot_check_widget_type_radio();

	function ai_chatbot_check_widget_type_radio(){
		if (jQuery('input[name=ai_chatbot_only_icon]').is(":checked") === true) {
			jQuery('input:radio[name=ai_chatbot_only_icon]').parent().removeClass('ai_chatbot_border');
			jQuery('input:radio[name=ai_chatbot_only_icon]').parent().removeClass('shadow');
			jQuery("input[type='radio'][name='ai_chatbot_only_icon']:checked").parent().addClass('shadow');
			jQuery("input[type='radio'][name='ai_chatbot_only_icon']:checked").parent().addClass('ai_chatbot_border');

			var ai_chatbot_i=jQuery("input[type='radio'][name='ai_chatbot_only_icon']:checked").val();
			if(ai_chatbot_i==="onlyicon"){
				jQuery("#ai_chatbot_widget_text").attr("readonly", "readonly");
				jQuery("#ai_chatbot_widget_tooltip").removeClass('d-none');

				for(ai_chatbot_i=1;ai_chatbot_i<9; ai_chatbot_i++){
					jQuery('input[value=ai_chatbot_icon_'+ai_chatbot_i+']').parent().removeClass('opacity-50');
					jQuery('input[name=ai_chatbot_icon_set]').attr("disabled",false);

				}
			}
			else {
				jQuery("#ai_chatbot_widget_text").removeAttr("readonly");
				jQuery("#ai_chatbot_widget_tooltip").addClass('d-none');
				for(ai_chatbot_i=2;ai_chatbot_i<9; ai_chatbot_i++){
					jQuery('input[value=ai_chatbot_icon_'+ai_chatbot_i+']').parent().removeClass('ai_chatbot_border');
					jQuery('input[value=ai_chatbot_icon_'+ai_chatbot_i+']').parent().addClass('opacity-50');
					jQuery('input[name=ai_chatbot_icon_set]').attr("disabled",true);
				}
			}
		}
	}
	jQuery(".ai_chatbot_chat_icon_card input[type='radio']").change(function () {
		if (jQuery(this).is(":checked") === true) {
			jQuery('input:radio[name=' + this.name + ']').parent().removeClass('ai_chatbot_border');
			jQuery('input:radio[name=' + this.name + ']').parent().removeClass('shadow');
			jQuery(this).parent().addClass('shadow');
			jQuery(this).parent().addClass('ai_chatbot_border');
		}
	});
	jQuery(".ai_chatbot_icon_type_div input[type='radio']").change(function () {
		ai_chatbot_check_widget_type_radio();
		ai_chatbot_check_radio_icon_default();

	});
	ai_chatbot_check_radio_icon_default();
	// function to check customer checkbox is checked or not
	jQuery("#ai_chatbot_checkbox_post").change(function () {
		if (this.checked) {
			jQuery("#ai_chatbot_displaypost").removeClass("d-none");
		} else {
			jQuery("#ai_chatbot_displaypost").addClass("d-none");
		}
	});
	if (jQuery("#ai_chatbot_checkbox_post").prop("checked") === true) {
		jQuery("#ai_chatbot_displaypost").removeClass("d-none");
	} else {
		jQuery("#ai_chatbot_displaypost").addClass("d-none");
	}
	jQuery("#ai_chatbot_checkbox_page").change(function () {
		if (this.checked) {
			jQuery("#ai_chatbot_displaypage").removeClass("d-none");
		} else {
			jQuery("#ai_chatbot_displaypage").addClass("d-none");
		}
	});
	if (jQuery("#ai_chatbot_checkbox_page").prop("checked") === true) {
		jQuery("#ai_chatbot_displaypage").removeClass("d-none");
	} else {
		jQuery("#ai_chatbot_displaypage").addClass("d-none");
	}
	jQuery("#ai_chatbot_checkbox_product").change(function () {
		if (this.checked) {
			jQuery("#ai_chatbot_displayproduct").removeClass("d-none");
		} else {
			jQuery("#ai_chatbot_displayproduct").addClass("d-none");
		}
	});
	if (jQuery("#ai_chatbot_checkbox_product").prop("checked") === true) {
		jQuery("#ai_chatbot_displayproduct").removeClass("d-none");
	} else {
		jQuery("#ai_chatbot_displayproduct").addClass("d-none");
	}
	function ai_chatbot_check_radio_icon_default(){
		if (jQuery('input[name=ai_chatbot_icon_type_radio]').is(":checked") === true) {
			var ai_chatbot_i=jQuery("input[type='radio'][name='ai_chatbot_icon_type_radio']:checked").val();
			if(ai_chatbot_i==="ai_chatbot_default"){
				jQuery("#ai_chatbot_icon_link").attr("readonly", "readonly");
				jQuery("#ai_chatbot_icon_type_tooltip").removeClass('d-none');
				jQuery('input[name=ai_chatbot_icon_set]').attr("disabled",false);
				jQuery('input[name=ai_chatbot_icon_set]').parent().removeClass('opacity-50');


				var ai_chatbot_i=jQuery("input[type='radio'][name='ai_chatbot_only_icon']:checked").val();
				if(ai_chatbot_i==="onlyicon"){
					jQuery("#ai_chatbot_widget_text").attr("readonly", "readonly");
					jQuery("#ai_chatbot_widget_tooltip").removeClass('d-none');

					for(ai_chatbot_i=2;ai_chatbot_i<9; ai_chatbot_i++){
						jQuery('input[value=ai_chatbot_icon_'+ai_chatbot_i+']').parent().removeClass('opacity-50');
						jQuery('input[name=ai_chatbot_icon_set]').attr("disabled",false);
					}
				}
				else {
					jQuery("#ai_chatbot_widget_text").removeAttr("readonly");
					jQuery("#ai_chatbot_widget_tooltip").addClass('d-none');

					for(ai_chatbot_i=2;ai_chatbot_i<9; ai_chatbot_i++){
						jQuery('input[value=ai_chatbot_icon_'+ai_chatbot_i+']').parent().removeClass('ai_chatbot_border');
						jQuery('input[value=ai_chatbot_icon_'+ai_chatbot_i+']').parent().removeClass('shadow');
						jQuery('input[value=ai_chatbot_icon_'+ai_chatbot_i+']').parent().addClass('opacity-50');
						jQuery('input[name=ai_chatbot_icon_set]').attr("disabled",true);
					}
					jQuery('input[value=ai_chatbot_icon_1]').parent().addClass('shadow');
					jQuery('input[value=ai_chatbot_icon_1]').parent().addClass('ai_chatbot_border');
					jQuery('input[value=ai_chatbot_icon_1]').attr("checked","checked");
					jQuery('input[value=ai_chatbot_icon_1]').attr("disabled",true);

				}
			}
			else {
				jQuery('input[name=ai_chatbot_icon_set]').attr("disabled",true);
				jQuery('input[name=ai_chatbot_icon_set]').parent().removeClass('shadow');
				jQuery('input[name=ai_chatbot_icon_set]').parent().removeClass('ai_chatbot_border');
				jQuery('input[name=ai_chatbot_icon_set]').parent().addClass('opacity-50');
				jQuery("#ai_chatbot_icon_link").removeAttr("readonly");
				jQuery("#ai_chatbot_icon_type_tooltip").addClass('d-none');

			}
		}
	}
});
function ai_chatbot_backbtn() {
	jQuery("#ai_chatbot_alert").css("display", "none");
	jQuery("#ai_chatbot_emailform").removeClass("d-none");
	jQuery("#ai_chatbot_usernameform").addClass("d-none");
}
function countdown(element, minutes, seconds) {
	document.getElementById("ai_chatbot_resend_btn").style.display = "none";
	// set time for the particular countdown
	var time = minutes * 60 + seconds;
	var interval = setInterval(function () {
		var el = document.getElementById(element);
		// if the time is 0 then end the counter
		if (time <= 0) {
			var text = "";
			el.innerHTML = text;
			document.getElementById("ai_chatbot_resend_btn").style.display = "block";
			clearInterval(interval);
			return;
		}
		var minutes = Math.floor(time / 60);
		if (minutes < 10) minutes = "0" + minutes;
		var seconds = time % 60;
		if (seconds < 10) seconds = "0" + seconds;
		var text = 'Resend Code in ' + minutes + ':' + seconds;
		el.innerHTML = text;
		time--;
	}, 1000);
}
function confirmDelete(buttonName) {
	var userConfirmed = confirm("Are you sure you want to delete?");
	if (userConfirmed) {
		// If the user confirmed, find the button by name and submit the form
		document.getElementsByName(buttonName)[0].type = 'submit';
		document.getElementsByName(buttonName)[0].click();
	}
	// If the user did not confirm, do nothing
}


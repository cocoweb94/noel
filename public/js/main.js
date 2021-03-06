jQuery.parseJSON = function( data ) {
	if ( !data || typeof data !== "string") {
		return null;
	}

	// Make sure leading/trailing whitespace is removed (IE can't handle it)
	data = jQuery.trim( data );

	// Attempt to parse using the native JSON parser first
	if ( window.JSON && window.JSON.parse ) {
		return window.JSON.parse( data );
	}

	// Make sure the incoming data is actual JSON
	// Logic borrowed from http://json.org/json2.js
	if ( rvalidchars.test( data.replace( rvalidescape, "@" )
			.replace( rvalidtokens, "]" )
			.replace( rvalidbraces, "")) ) {

		return ( new Function( "return " + data ) )();

	}
	jQuery.error( "Invalid JSON: " + data );
}

$(document).ready(function() {

	const queryString = window.location.search;
	const urlParams = new URLSearchParams(queryString);

	if(getCookie("commande") == "" || urlParams.get('commande') == "valide"){
		setCookie("{}");
	}
	$(".dropdown img.flag").addClass("flagvisibility");

	$(".dropdown dt a").click(function() {
		$(".dropdown dd ul").toggle();
	});

	$(".dropdown dd ul li a").click(function() {
		var text = $(this).html();
		$(".dropdown dt a span").html(text);
		$(".dropdown dd ul").hide();
		$("#result").html("Selected value is: " + getSelectedValue("sample"));
	});

	$('body').on('click', 'li.closepanier', function(e){
		var id = $(this).data("id");
		var tabCommande = $.parseJSON(getCookie("commande"));
		if(id in tabCommande){
			delete tabCommande[id];
			setCookie(JSON.stringify(tabCommande));

			$.ajax({
				url: "/getpanier",
				type: "POST",
				dataType: "text",
				contentType: "application/json",
				data: getCookie("commande"),
				success:function(result){
					$("#panier").html(result);
					$("#articlePanier").html("<b>Mon panier ("+$(".closepanier").length+")</b>");
					if($(".closepanier").length == 0)
						$(".login_buttons").remove();
				},
				error:function(xhr,status,error){
					console.log(status);
				}
			});
		}
	});

	$(".buttons .cart").click(function() {
		var id = $(this).data("id");
		var tabCommande = $.parseJSON(getCookie("commande"));
		if(id in tabCommande){
			tabCommande[id] = tabCommande[id] + 1;
		} else{
			tabCommande[id] = 1;
		}

		setCookie(JSON.stringify(tabCommande));

		$.ajax({
			url: "/getpanier",
			type: "POST",
			dataType: "text",
			contentType: "application/json",
			data: getCookie("commande"),
			success:function(result){
				$("#panier").html(result);
				$("#articlePanier").html("<b>Mon panier ("+$(".closepanier").length+")</b>");
				if($(".login_buttons").length == 0)
					$("#panier").after( '<div class="login_buttons"><div class="check_button"><a href="/commande">Commander</a></div><div class="clear"></div></div>' );
			},
			error:function(xhr,status,error){
				console.log(status);
			}
		});

	});

	function setCookie(cvalue) {
		var d = new Date();
		d.setTime(d.getTime() + (1*24*60*60*1000));
		var expires = "expires="+d.toUTCString();

		document.cookie = "commande=" + cvalue + "; " + expires+';path=/';
	}

	function getCookie(cname) {
		var name = cname + "=";
		var decodedCookie = decodeURIComponent(document.cookie);
		var ca = decodedCookie.split(';');
		for(var i = 0; i <ca.length; i++) {
			var c = ca[i];
			while (c.charAt(0) == ' ') {
				c = c.substring(1);
			}
			if (c.indexOf(name) == 0) {
				return c.substring(name.length, c.length);
			}
		}
		return "";
	}

	function getSelectedValue(id) {
		return $("#" + id).find("dt a span.value").html();
	}

	$(document).bind('click', function(e) {
		var $clicked = $(e.target);
		if (! $clicked.parents().hasClass("dropdown"))
			$(".dropdown dd ul").hide();
	});


	$("#flagSwitcher").click(function() {
		$(".dropdown img.flag").toggleClass("flagvisibility");
	});
});
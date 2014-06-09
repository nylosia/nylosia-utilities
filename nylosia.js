/*****
 *
 * nylosia-js
 *
 **/

function renderRatingValue(el, value) {
	if (value == undefined) {
		value = jQuery(el).attr("data-rating");
	}

	//aggiorno descrizione voto
	var captions = ["", "pessimo", "scarso", "nella media", "molto buono", "eccellente"];
	jQuery(".nylosia-rating-caption", el).html(captions[value]);

	//
	jQuery("a", el).each(function(index, el) {
		if ( jQuery(el).attr("data-star") <= value) {
			jQuery(el).addClass("nylosia-rating-star-full");
		} else {
			jQuery(el).removeClass("nylosia-rating-star-full");
		}
	});
}

function updateRating(url, postid, value, el) {
	jQuery.ajax({
		url: url,
		type: "POST",
		data: {
			"action": "nylosia_manage_rating",
			"postid": postid,
			"vote": value
		},
		dataType: "json"
	}).done(function (data) {
		// console.log("updateRating", data);

		if (data.totratings && data.totratings > 1) {
			jQuery(".nylosia-rating-history", el).html(data.totratings + " voti, " + Math.ceil(data.avg) + " di media")
		} else {
			jQuery(".nylosia-rating-history", el).empty();
		}

	}).fail(function() {
		//console.log("fail", arguments)
	})
}

window.fbAsyncInit = function() {
	var fbappid = jQuery('meta[property="fb:app_id"]').attr('content');

	FB.init({
	  appId      : fbappid,
	  xfbml      : true,
	  version    : 'v2.0'
	});
};

function nylosiaCountFBShare(el, url) {
	//recupero il numero di share della pagina
	jQuery.ajax({
		url: 'http://graph.facebook.com/?id=' + url,
		dataType: 'json'
	}).done(function(data) {
		if(data.shares) {
			jQuery(el).attr("data-nylosia-social-title", data.shares);
		}
	});	 			
}

function nylosiaCountTWShare(el, url) {
	//recupero il numero di share della pagina
	jQuery.ajax({
		url: 'http://urls.api.twitter.com/1/urls/count.json?url=' + url,
		dataType: 'json'
	}).done(function(data) {
		if(data.shares) {
			jQuery(el).attr("data-nylosia-social-title", data.shares);
		}
	});	 			
}

function nylosiaSocialFBShare(el, url) {
	FB.ui(
	  {
	    method: 'stream.share',
	    href: url
	  },
	  function(resp) { nylosiaCountFBShare(el, url); }
	);				
}

function nylosiaSocialTWShare(el, url, twuser, twtext) {
	window.open('http://twitter.com/share?url=' + url + '&via=' + twuser + '&text=' + twtext, '', 'toolbar=0,status=0,width=548,height=325');
}

function nylosiaSocialGPShare(el, url) {
	window.open('https://plus.google.com/share?url=' + url, '', 'toolbar=0,status=0,width=548,height=325');
}

jQuery(function() {

	//social
	var fblang = jQuery('meta[property="fb:lang"]').attr('content');

	(function(d, s, id){
	 var js, fjs = d.getElementsByTagName(s)[0];
	 if (d.getElementById(id)) {return;}
	 js = d.createElement(s); js.id = id;
	 js.src = "http://connect.facebook.net/" + fblang + "/sdk.js";
	 fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	jQuery(".nylosia-social-container .nylosia-social-fb").each(function(index, el) {
		nylosiaCountFBShare(el, jQuery(el).attr("data-link"));
	}).click(function() {
		nylosiaSocialFBShare(this, jQuery(this).attr("data-link"));
	});

	jQuery(".nylosia-social-container .nylosia-social-tw").each(function(index, el) {
		nylosiaCountTWShare(el, jQuery(el).attr("data-link"));
	}).click(function() {
		nylosiaSocialTWShare(this, jQuery(this).attr("data-link"), jQuery(this).attr("data-twuser"), jQuery(this).attr("data-twtext"));
	});

	jQuery(".nylosia-social-container .nylosia-social-gp").each(function(index, el) {
		//TODO nylosiaCountGPShare(el, jQuery(el).attr("data-link"));
	}).click(function() {
		nylosiaSocialGPShare(this, jQuery(this).attr("data-link"));
	});

	//TODO linkedin e pinterest

	//valutazione utente
	jQuery(".nylosia-rating-container").each(function(index, el) {

		var url = jQuery(el).attr("data-url");

		//leggo il valore
		jQuery.ajax({
			url: url,
			type: "POST",
			data: {
				"action": "nylosia_manage_rating",
				"postid": jQuery(el).attr("data-post-id")
			},
			dataType: "json"
		}).done(function (data) {
			// console.log('data', data);

			if (data.vote) {
				jQuery(el).attr("data-rating", data.vote);
				renderRatingValue(el, data.vote);
			}

			if (data.totratings && data.totratings > 1) {
				jQuery(".nylosia-rating-history", el).html(data.totratings + " voti, " + Math.ceil(data.avg) + " di media")
			}

		}).fail(function() {
			// console.log("fail", arguments);
		});

		//quondo esco dal contenitore ripristino il voto
		jQuery(el).mouseleave(function() {
			renderRatingValue(el, jQuery(this).attr("data-rating"));
		});

		jQuery("a", el)
		.hover(function() {
			//aggiorno il voto ma solo graficamente
			var value = jQuery(this).attr("data-star");
			renderRatingValue(el, value);
		})
		.click(function() {
			var value = jQuery(this).attr("data-star");
			var recipe = jQuery(el);
			//se seleziono nuovamente la stessa stella tolgo il voto
			if (recipe.attr("data-rating") == value) {
				value = 0;
			}

			recipe.attr("data-rating", value);
			//aggiorno il voto
			updateRating(url, recipe.attr("data-post-id"), value, el);

			return false;	
		});

	});

});
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
			jQuery(el).attr("data-nylosia-social-count", data.shares);
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
			jQuery(el).attr("data-nylosia-social-count", data.shares);
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

function nylosiaSocialINShare(el, url, title, summary, source) {
	window.open('http://www.linkedin.com/shareArticle?mini=true&url=' + url + '&title=' + title + '&summary=' + summary + '&source=' + source, '', 'toolbar=0,status=0,width=520,height=570');
}

function nylosiaSocialPIShare(el, url, media, description) {
	window.open('//www.pinterest.com/pin/create/button/?url=' + url + '&media=' + media + '&description=' + description, '', 'toolbar=0,status=0,width=548,height=325');
	//www.pinterest.com/pin/create/button/?url=http%3A%2F%2Fwww.flickr.com%2Fphotos%2Fkentbrew%2F6851755809%2F&media=http%3A%2F%2Ffarm8.staticflickr.com%2F7027%2F6851755809_df5b2051c9_z.jpg&description=Next%20stop%3A%20Pinterest
}

jQuery(function() {

	//social
	var locale = jQuery('meta[property="og:locale"]').attr('content');
	var sitename = jQuery('meta[property="og:site_name"]').attr('content');
	var image = encodeURIComponent(jQuery('meta[property="og:image"]').attr('content'));
	var url = encodeURIComponent(jQuery('meta[property="og:url"]').attr('content'));
	var title = jQuery('meta[property="og:title"]').attr('content');
	var twuser = (jQuery('meta[name="twitter:site"]').attr('content') || '').replace(/@/g, '');

	(function(d, s, id){
	 var js, fjs = d.getElementsByTagName(s)[0];
	 if (d.getElementById(id)) {return;}
	 js = d.createElement(s); js.id = id;
	 js.src = "http://connect.facebook.net/" + locale + "/sdk.js";
	 fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

	// (function(d, s, id){
	//  var js, fjs = d.getElementsByTagName(s)[0];
	//  if (d.getElementById(id)) {return;}
	//  js = d.createElement(s); js.id = id;
	//  js.src = "//assets.pinterest.com/js/pinit.js";
	//  fjs.parentNode.insertBefore(js, fjs);
	// }(document, 'script', 'pinterest-jssdk'));

	jQuery(".nylosia-social-container .nylosia-social-fb").each(function(index, el) {
		nylosiaCountFBShare(el, url);
	}).click(function() {
		nylosiaSocialFBShare(this, url);
	});

	jQuery(".nylosia-social-container .nylosia-social-tw").each(function(index, el) {
		//nylosiaCountTWShare(el, url);
	}).click(function() {
		nylosiaSocialTWShare(this, url, twuser, title);
	});

	jQuery(".nylosia-social-container .nylosia-social-gp").each(function(index, el) {
		//TODO nylosiaCountGPShare(el, url);
	}).click(function() {
		nylosiaSocialGPShare(this, url);
	});

	jQuery(".nylosia-social-container .nylosia-social-in").each(function(index, el) {
		//TODO nylosiaCountGPShare(el, url);
	}).click(function() {
		nylosiaSocialINShare(this, url, sitename, title, sitename);
	});

	jQuery(".nylosia-social-container .nylosia-social-pi").each(function(index, el) {
		//TODO nylosiaCountGPShare(el, url);
	}).click(function() {
		nylosiaSocialPIShare(this, url, image, title);
	});

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
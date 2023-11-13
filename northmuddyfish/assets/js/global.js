/**
 * @author		Brett Shenk  https://www.linkedin.com/in/brett-shenk-59480794/
 * @package  	Helper Functions
**/

/**
 * @package Website URL from site's JSON file
 * 
 * @returns https://www.your-site.com
 */
var site_domain = (function(){
	let wp_json_url = document.querySelector('link[rel="https://api.w.org/"]').href
	return wp_json_url.replace('/wp-json/', '');
})();

/**
 * @package Wait for final event
 * 
 * $(document.body).on('updated_cart_totals', function(){
 * 		waitForFinalEvent(function(){
 * 			console.log('do stuff');
 * 		}, 500);
 * });
**/
var waitForFinalEvent = (function(){
	let timers = {};
	return function (callback, ms, uniqueId) {
		if (!uniqueId) {
			uniqueId = "Don't call this twice without a uniqueId";
		}
		if (timers[uniqueId]) {
			clearTimeout (timers[uniqueId]);
		}
		timers[uniqueId] = setTimeout(callback, ms);
	};
})();

/**
 * @package Cookie functions
 * 
 * setCookie - To create a cookie through javascript.
 * getCookie - Get the cookie you just made.
 * 
 * @param {string} 	name 
 * @param {*} 		value 
 * @param {number} 	days 
**/
function setCookie(name, value, days) {
	let expires = "";
	if (days) {
		let date = new Date();
		date.setTime( date.getTime() + (days * 24 * 60 * 60 * 1000) );
		expires = "; expires=" + date.toUTCString();
	}
	document.cookie = name + "=" + (value || "")  + expires + "; path=/; Secure";
}
/**
 * @param   {string} 	name 
 * @returns The value of the cookie
**/
function getCookie(name) {
	let nameEQ = name + "=";
	let ca = document.cookie.split(';');
	for(let i=0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

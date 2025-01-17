(function($) {
return {
	render: function(item, tag) {
		var dfr = $.Deferred();
		var m = 'get_' + tag.get_prop();
		var ret = ( this.util.is_method(item, m) ) ? item[m]() : item.get_attribute(tag.get_prop(), '');
		if ( this.util.is_promise(ret) ) {
			ret.done(function(output) {
				dfr.resolve(output);
			});
		} else {
			dfr.resolve(ret);
		}
		return dfr.promise();
	}
}
})(jQuery);
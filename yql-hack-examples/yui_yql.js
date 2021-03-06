function flickrSearch(){

    var search = document.getElementById('search'),
        term = search.value;

    YUI().use('yql', 'node', function(Y) {

        // Specify the YQL query
        var query  = "SELECT * FROM flickr.photos.search(15) WHERE (text='" + term + "') AND (safe_search = 1) AND (media = 'photos') AND (api_key = '1895311ec0d2e23431a6407f3e8dffcc')";
        //var query  = "SELECT * FROM flickr.photos.search(15) WHERE (lat,lon) IN (SELECT centroid.latitude, centroid.longitude FROM geo.places  WHERE text='" + term + "') AND safe_search = 1 AND media = 'photos' AND api_key = '1895311ec0d2e23431a6407f3e8dffcc'";

        var wrapper = Y.one('#wrapper');
        Y.YQL(query, function(r) {
            Y.log(r);
            if (r.query && r.query.results) {
                var photos = r.query.results.photo;

                wrapper.set('innerHTML', '');
                //Walk the returned photos array
                Y.each(photos, function(v, k) {
                    //create the photo url
                    var url = 'http:/'+'/static.flickr.com/' + v.server + '/' + v.id + '_' + v.secret + '_m.jpg',
                    li = Y.Node.create('<li class="photo"><img src="' + url + '" title="' + v.title + '"></li>');
                    wrapper.appendChild(li);
                });
            }
        });
    });
}

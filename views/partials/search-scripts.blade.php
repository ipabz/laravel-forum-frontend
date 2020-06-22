<div class="search-hidden-wrapper" style="display: none">
    <iframe class="search-iframe"></iframe>
</div>
<script>
    var searchDirty = false;

    function iframeURLChange(iframe, callback) {
        var lastDispatched = null;

        var dispatchChange = function () {
            var newHref = iframe.contentWindow.location.href;

            if (newHref !== lastDispatched) {
                callback(newHref);
                lastDispatched = newHref;
            }
        };

        var unloadHandler = function () {
            // Timeout needed because the URL changes immediately after
            // the `unload` event is dispatched.
            setTimeout(dispatchChange, 0);
        };

        function attachUnload() {
            // Remove the unloadHandler in case it was already attached.
            // Otherwise, there will be two handlers, which is unnecessary.
            iframe.contentWindow.removeEventListener("unload", unloadHandler);
            iframe.contentWindow.addEventListener("unload", unloadHandler);
        }

        iframe.addEventListener("load", function () {
            attachUnload();

            // Just in case the change wasn't dispatched during the unload event...
            dispatchChange();
        });

        attachUnload();
    };

    iframeURLChange(document.querySelector('.search-iframe'), function (newURL) {
        setTimeout(function() {
            var innerDoc = document.querySelector('.search-iframe').contentWindow.document;
            var content = innerDoc.querySelector('.search-content');

            var contentObject = $(content);

            if (!contentObject.html() && searchDirty) {
                $('.search-content').html('<div class="alert alert-info mt-3">No results found.</div>');
            } else {
                $('.search-content').html(contentObject.html());
            }
        }, 100);
    });

    function search()
    {
        $('.search-content').html('<div class="pt-5 text-center"><span class="fa fa-circle-notch fa-spin fa-7x"> </span></div>');

        var keyword = $('.search-form .search-field').val();
        var url = "{{ Forum::route('search') }}?q=" + keyword + '&t=' + (new Date().getTime());

        if (!keyword) {
            url = "{{ \Illuminate\Support\Facades\URL::current() }}?t=" + (new Date().getTime());
        }

        history.pushState({id: 'Search-' + url}, 'Search - ' + url, url);

        $('.search-iframe').attr('src', url);
    };

    $(function () {
        $('.search-form .search-button').click(function(e) {
            e.preventDefault();

            searchDirty = true;
            search();
        });

        $('.search-form .search-field').keypress(function (e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                searchDirty = true;

                search();
            }
        });
    });
</script>

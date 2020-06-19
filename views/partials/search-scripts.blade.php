<div class="search-hidden-wrapper" style="display: none">
    <iframe class="search-iframe"></iframe>
</div>
<script>
    var searchTimeout = null;
    var searchFieldDirty = false;

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

            if (!searchTimeout) {
                var contentObject = $(content);

                if (!contentObject.html() && searchFieldDirty) {
                    $('.search-content').html('<div class="alert alert-info mt-3">No results found.</div>');
                } else {
                    $('.search-content').html(contentObject.html());
                }
            }
        }, 100);
    });

    $(function () {
        $('.search-form .search-field').keyup(function (e) {
            searchFieldDirty = true;

            if (searchTimeout) {
                clearTimeout(searchTimeout);
            }

            if (e.keyCode === 13) {
                e.preventDefault();
            }

            $('.search-content').html('<div class="pt-5 text-center"><span class="fa fa-circle-notch fa-spin fa-7x"> </span></div>');

            var keyword = $('.search-form .search-field').val();
            var url = "{{ Forum::route('index') }}?q=" + keyword;

            var timeout = 200;

            if (!keyword) {
                url = "{{ \Illuminate\Support\Facades\URL::current() }}";
                timeout = 10;
            }

            searchTimeout = setTimeout(function () {
                $('.search-iframe').attr('src', url);

                searchTimeout = null;
            }, timeout);
        });
    });
</script>

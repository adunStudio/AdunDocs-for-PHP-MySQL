<script>

    $(function() {
        if( !"{$search_keyword}" )
        {
            $('#ptr').show();
            var ready = true;
            var $adun = $('#hscroll');
            var currentPage = "{$page}";
            var exampleLoadingFunction = function() {
                return $.ajax({
                    type: "GET",
                    contentType: "application/json; charset=utf-8",
                    url: "/?mid=hy&m=1&page={$page}&best={$best}",
                    dataType: "json",

                }).done(function(data) {
                    var data = data.data;
                    $('#ptr').hide();
                    setTimeout(function() {
                        $('.' + currentPage + "_li").remove();
                        $adun.append(data);
                        ready = true;
                    }, 1000);
                })
            };
            WebPullToRefresh.init( {
                loadingFunction: exampleLoadingFunction,
            } );

        }

    });
</script>
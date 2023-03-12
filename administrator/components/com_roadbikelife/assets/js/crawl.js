$.noConflict();

jQuery(function ($) {
    jQuery(document).ready(function () {

        var es;

        function startTask() {
            let options = '';
            let depthVal = $('#crawl_depth').val();
            let url = $('#crawl_url').val();
            if (depthVal > 0) {
                options += '&crawl_depth=' + depthVal
            }
            if (url > 0) {
                options += '&crawl_url=' + url
            }
            es = new EventSource('index.php?option=com_roadbikelife&&task=crawl.start' + options);
            $('#toolbar-icon-play > span').attr('class','icon-refresh icon-spin icon-fw');

            //a message is received
            es.addEventListener('message', function (e) {
                var result = JSON.parse(e.data);
                console.log(result);
                addLog(result.message);
            });

            es.addEventListener('error', function (e) {
                addLog('Error');
                es.close();
            });
        }

        function stopTask() {
            es.close();
            $('#toolbar-icon-play > span').attr('class','icon-stop icon-fw')
            addLog('Caching gestoppt');
        }

        function addLog(message) {
            var r = $('#results');
            r.prepend('<div class="alert alert-info mb-2">' + message + '</div>');
        }

        $('#toolbar-icon-play').on('click', function (e) {
            e.preventDefault();
            $('#results').html('');
            startTask();
        });

        $('#toolbar-icon-stop').on('click', function (e) {
            e.preventDefault();
            stopTask()
        });
    });
});

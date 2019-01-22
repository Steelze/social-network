<?php
    use app\Assets;
    use app\Token;
    use app\Router;
    use app\Auth\Auth;
    use app\Config;
?>
<script src="<?= Assets::url('js/jquery.min.js') ?>"></script>	
<script src="<?= Assets::url('js/bootstrap.min.js') ?>"></script>
<script src="<?= Assets::url('js/jquery.slimscroll.min.js') ?>"></script>
<script src="https://js.pusher.com/4.3/pusher.min.js"></script>
<script>
    const id = <?= Auth::user()->id ?>;
    // Enable pusher logging - don't include this in production
    Pusher.logToConsole = true;
    var pusher = new Pusher("<?= Config::get('PUSHER_APP_KEY') ?>", {
      cluster: "<?= Config::get('PUSHER_APP_CLUSTER') ?>",
      forceTLS: <?= Config::get('PUSHER_APP_USE_TLS') ?>,
    });

    const  channel = pusher.subscribe(`channel-${id}`);
    $(document).ready(function() {

        $('#drop').on('show.bs.dropdown', function() {
            $.post({
                url: "<?= Router::route('handlers.ajax.msg-count')?>",
                data: {token: "<?= Token::getToken()?>"},
                cache: false,
                success(data) {
                    // console.log(data);
                    // location.reload();
                    $(".drop").remove();
                },
                error(e) {
                    console.log(e  + 'error');
                }
            })
        });
        $('#notif').on('show.bs.dropdown', function() {
            $.post({
                url: "<?= Router::route('handlers.ajax.notif-count')?>",
                data: {token: "<?= Token::getToken()?>"},
                cache: false,
                success(data) {
                    // console.log(data);
                    // location.reload();
                    $(".notif").remove();
                },
                error(e) {
                    console.log(e  + 'error');
                }
            })
        });
    })
    function searchFriends(value) {
        if (value.trim() == '') {
            $('.search-results').text('');
            return;
        }
        $.post({
            url: "<?= Router::route('handlers.ajax.search-all')?>",
            data: {value},
            cache: false,
            success(data) {
                if (data === '') {
                    $('.search-results').html("<div class='p-3'>No results found</div>");
                } else {
                    $('.search-results').html(data);
                }
            }
        })
    }
</script>
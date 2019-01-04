<?php
    use app\Assets;
    use app\Token;
    use app\Router;
?>
<script src="<?= Assets::url('js/jquery.min.js') ?>"></script>	
<script src="<?= Assets::url('js/bootstrap.min.js') ?>"></script>
<script src="<?= Assets::url('js/jquery.slimscroll.min.js') ?>"></script>
<script>
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
<?php
    use app\Assets;
    use app\Token;
    use app\Router;
?>
<script src="<?= Assets::url('js/jquery.min.js') ?>"></script>	
<script src="<?= Assets::url('js/bootstrap.min.js') ?>"></script>
<script src="<?= Assets::url('js/jquery.slimscroll.min.js') ?>"></script>
<script>
    $('#drop').on('show.bs.dropdown', function() {
        $(document).ready(function() {
            $.post({
                url: "<?= Router::route('handlers.ajax.msg-count')?>",
                data: {token: "<?= Token::getToken()?>"},
                cache: false,
                success(data) {
                    // console.log(data);
                    // location.reload();
                    $(".badge-pill").remove();
                },
                error(e) {
                    console.log(e  + 'error');
                }
            })
        });
    })
</script>
<?php
require_once('functions.php');
date_default_timezone_set('Asia/Shanghai');

// 获取全部Player
$players = get_player();
$page_title = 'Set Players';

$_REQUEST['action'] = isset($_REQUEST['action']) ? $_REQUEST['action'] : '';

if ($_REQUEST['action'] === 'creat_player') {
    if (isset($_REQUEST['newplayers']) && !empty($_REQUEST['newplayers'])) {
        creat_player($_REQUEST['newplayers']);
    }
    $url = "player.php?page_name=Player Set";
    $swal_script = '
        <script type="text/javascript">
            document.addEventListener("DOMContentLoaded", function() {
                Swal.fire({
                    title: "Welcome & Have Fun",
                    icon: "success",
                    text: "Success",
                }).then(function () {
                    window.location.href = "'.$url.'"
                });
            });
        </script>
    ';
}

require_once "kog_header.php";
?>

<div class="container-fluid mt--6">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Players</h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <?php if (empty($players)): ?>
                            <?php for ($i = 1; $i < 7; $i++): ?>
                                <div class="form-group">
                                    <label class="form-control-label" for="player-input-<?php _e($i)?>">Player <?php _e($i)?></label>
                                    <input type="text" class="form-control" id="player-input-<?php _e($i)?>" name="newplayers[<?php _e($i)?>]">
                                </div>
                            <?php endfor; ?>
                        <?php else: ?>
                            <?php foreach ($players as $key => $value): ?>
                                <div class="form-group">
                                    <input disabled type="text" class="form-control" value="<?php _e($value->nickname)?>">
                                </div>
                            <?php endforeach; ?>
                            <div class="form-group">
                                <label class="form-control-label" for="add-new-player">Add New Player</label>
                                <input type="text" class="form-control" id="add-new-player" name="newplayers[0]">
                            </div>
                        <?php endif; ?>
                        <button type="submit" name="action" value="creat_player" class="btn btn-primary btn-lg btn-block">Welcome</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
if (isset($swal_script)) {
    echo $swal_script;
}
require_once "kog_footer.php"; 
?>
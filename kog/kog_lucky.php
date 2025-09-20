<?php
require_once('functions.php');

// Display logic first
require_once("kog_header.php");

if (empty($_REQUEST['uid'])) {
    die("缺少UID");
}

$game = get_game($_REQUEST['gid']);
$game_player = get_game_player($_REQUEST['gid']);

$game_player_select_array = [];
foreach ($game_player as $key => $value) {
    $game_player_select_array[$value->uid] = $value->player;
}

?>

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="mb-0">Lucky One: <?php _e(get_player($_REQUEST['uid'])[0]->nickname) ?></h3>
                </div>
                <div class="card-body">
                    <form method="post">
                        <div class="form-group row">
                            <label class="col-md-2 col-form-label form-control-label">Dealer</label>
                            <div class="col-md-10">
                                <?php erp_html_form_input(array(
                                    'name' => 'dealar',
                                    'class' => 'form-control',
                                    'type' => 'select',
                                    'required' => true,
                                    'options' => array('' => '- Select -') + $game_player_select_array
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label form-control-label">Lucky Type</label>
                            <div class="col-md-10">
                                <?php erp_html_form_input(array(
                                    'name' => 'lucky_type',
                                    'class' => 'form-control',
                                    'type' => 'select',
                                    'required' => true,
                                    'options' => array('' => '- Select -') + get_lucky_bonus()
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label form-control-label">Cards</label>
                            <div class="col-md-10">
                                <?php erp_html_form_input(array(
                                    'name' => 'cards',
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'required' => true,
                                    'value' => '',
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-md-2 col-form-label form-control-label">Bonus</label>
                            <div class="col-md-10">
                                <?php erp_html_form_input(array(
                                    'name' => 'bonus',
                                    'class' => 'form-control',
                                    'type' => 'text',
                                    'required' => true,
                                    'value' => '',
                                ));
                                ?>
                            </div>
                        </div>

                        <div class="text-center">
                            <input type="hidden" name="action" value="lucky">
                            <input type="hidden" name="uid" value="<?php _e($_REQUEST['uid']) ?>">
                            <input type="hidden" name="gid" value="<?php _e($_REQUEST['gid']) ?>">
                            <button type="submit" class="btn btn-primary btn-lg w-100">Lucky</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// --- PROCESS FORM SUBMISSION AT THE END ---
if (isset($_POST['action']) && $_POST['action'] == 'lucky') {
    if (creat_lucky_info($_POST['uid'])) {
        $url = "kog_detail.php?gid=" . $_POST['gid'];
        $swal_script = <<<HTML
<script type="text/javascript">
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            title: "Success",
            text: "Let's all in again!",
            icon: "success",
            timer: 1500,
            timerProgressBar: true,
            showConfirmButton: false
        }).then((result) => {
            window.location.href = '{$url}';
        });
    });
</script>
HTML;
        echo $swal_script;
    }
}

require_once "kog_footer.php";
?>
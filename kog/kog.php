<?php
$page_title = 'Create Game';
require_once('functions.php');

$players_count = isset($_REQUEST['players']) ? intval($_REQUEST['players']) : 4;

date_default_timezone_set('Asia/Shanghai');

$_REQUEST['game_type'] = isset($_REQUEST['game_type'])?$_REQUEST['game_type']:'CASH';
$cash_checked = ($_REQUEST['game_type'] == 'CASH') ? "checked=''": "";
$sng_checked = ($_REQUEST['game_type'] == 'SNG') ? "checked=''": "";

$start_chips = ($_REQUEST['game_type'] == "ONLINE") ? "300" : "3000";

$player_array	= 	get_player();
$player_name_array = [];
foreach ($player_array as $key => $value) {
	$player_name_array[$value->id] = $value->nickname;
}
$players_json = json_encode($player_name_array);

if (isset($_REQUEST['action']) && $_REQUEST['action'] == 'creat_game') {
    if($gid = creat_game()){
        // 安排一个一小时后的涨盲通知
        global $wpdb;
        $table_name = 'scheduled_notifications'; // 直接用表名，wpdb 会处理前缀（如果设置了）
        $execute_at_time = date('Y-m-d H:i:s', time() + 3600);
        $message_to_send = "牌局 GID-{$gid}: 即将涨盲，请做好准备！";

        $wpdb->insert(
            $table_name,
            [
                'game_id'      => $gid,
                'message'      => $message_to_send,
                'execute_at'   => $execute_at_time,
                'status'       => 'pending',
            ],
            [
                '%d', // game_id
                '%s', // message
                '%s', // execute_at
                '%s', // status
            ]
        );
        $url = "kog_detail.php?page_name=Have Fun !&gid=".$gid;
        $swal_script = "
			<script type=\"text/javascript\">
                document.addEventListener(\"DOMContentLoaded\", function() {
				Swal.fire({
					title: \"Success\",
					text: \"Ok Let\'s All\",
					icon: \"success\",
					timer: 2000,
				timerProgressBar: true,
					showConfirmButton: false
				}).then((result) => {
					window.location.href = " . json_encode($url) . ";
				});
			});
			</script>
		";
    }
}

require_once "kog_header.php";
?>

<div class="container-fluid py-4">
    <form method="post">
        <div class="row">
            <div class="col-lg-12">
                
                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">How Many People</h3>
                    </div>
                    <div class="card-body" id="player-count-buttons">
                        <button type="button" class="btn btn-primary <?php echo ($players_count == 3) ? 'active' : ''; ?>" data-players="3">3 人</button>
                        <button type="button" class="btn btn-primary <?php echo ($players_count == 4) ? 'active' : ''; ?>" data-players="4">4 人</button>
                        <button type="button" class="btn btn-primary <?php echo ($players_count == 5) ? 'active' : ''; ?>" data-players="5">5 人</button>
                        <button type="button" class="btn btn-primary <?php echo ($players_count == 6) ? 'active' : ''; ?>" data-players="6">6 人</button>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">Game Type</h3>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input name="game_type" class="form-check-input" id="customRadio5" type="radio" value="CASH" <?php _e($cash_checked)?>> 
                            <label class="custom-control-label" for="customRadio5">CASH</label>
                        </div>
                        <div class="form-check">
                            <input name="game_type" class="form-check-input" id="customRadio6" type="radio" value="SNG" <?php _e($sng_checked)?>>
                            <label class="custom-control-label" for="customRadio6">SNG</label>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">Chips & Money</h3>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Chips Start</label>
                                    <input name="chips_start" type="number" class="form-control" placeholder="3000" value="<?php _e(isset($_REQUEST['chips_start']) ? $_REQUEST['chips_start'] : $start_chips)?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">RMB(￥)</label>
                                    <input name="rebuy_rate" type="text" class="form-control" placeholder="300" value="<?php _e(isset($_REQUEST['rebuy_rate']) ? $_REQUEST['rebuy_rate'] : '300')?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Start Time</label>
                                    <input name="start_time" type="text" class="form-control" placeholder="H:i" value="<?php _e(isset($_REQUEST['start_time']) ? $_REQUEST['start_time'] : date('H:i',time()))?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Small Blind</label>
                                    <input name="small_blind" type="text" class="form-control" placeholder="5" value="<?php _e(isset($_REQUEST['small_blind'])?$_REQUEST['small_blind']:'5')?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Big Blind</label>
                                    <input name="big_blind" type="text" class="form-control" placeholder="10" value="<?php _e(isset($_REQUEST['big_blind'])?$_REQUEST['big_blind']:'10')?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label class="form-control-label">Straddle</label>
                                    <input name="straddle" type="text" class="form-control" placeholder="0" value="<?php _e(isset($_REQUEST['straddle'])?$_REQUEST['straddle']:'0')?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header">
                        <h3 class="mb-0">Position</h3>
                    </div>
                    <div class="card-body" id="position-fields">
                        <?php for ($i = 1; $i <= $players_count; $i++):
                            $options = ['' => '- Select -'] + $player_name_array;
                        ?>
                            <div class="form-group row">
                                <label class="col-md-2 col-form-label form-control-label"><?php echo $i; ?>号位</label>
                                <div class="col-md-10">
                                    <?php erp_html_form_input(['name' => 'position['.$i.']', 'class' => 'form-control player-select', 'type' => 'select', 'required' => true, 'options' => $options]); ?>
                                </div>
                            </div>
                        <?php endfor; ?>
                    </div>
                </div>

                <input name="action" type="hidden" value="creat_game">
                <button type="submit" class="btn btn-primary btn-lg w-100">Shuffle up and Deal!</button>

            </div>
        </div>
    </form>
</div>

<?php 
if (isset($swal_script)) {
    echo $swal_script;
}
require_once "kog_footer.php";
?>

<script>
$(document).ready(function() {
    const players = <?php echo $players_json; ?>;

    function generatePositionFields(playerCount) {
        const container = $('#position-fields');
        container.empty();

        for (let i = 1; i <= playerCount; i++) {
            let optionsHtml = '<option value="">- Select -</option>';
            for (const id in players) {
                optionsHtml += `<option value="${id}">${players[id]}</option>`;
            }

            const fieldHtml = `
                <div class=\"form-group row\">
                    <label class=\"col-md-2 col-form-label form-control-label\">${i}号位</label>
                    <div class=\"col-md-10\">
                        <select name=\"position[${i}]\" class=\"form-control player-select\" required>
                            ${optionsHtml}
                        </select>
                    </div>
                </div>`;
            container.append(fieldHtml);
        }
        updatePlayerDropdowns();
    }

    function updatePlayerDropdowns() {
        let selectedPlayers = [];
        $('.player-select').each(function() {
            let selectedValue = $(this).val();
            if (selectedValue && selectedValue !== '') {
                selectedPlayers.push(selectedValue);
            }
        });

        $('.player-select').each(function() {
            let ownSelection = $(this).val();
            $(this).find('option').each(function() {
                let optionValue = $(this).val();
                if (optionValue === '') return;

                if (selectedPlayers.includes(optionValue) && optionValue !== ownSelection) {
                    $(this).prop('disabled', true).hide();
                } else {
                    $(this).prop('disabled', false).show();
                }
            });
        });
    }

    $('#player-count-buttons').on('click', 'button', function() {
        const playerCount = $(this).data('players');
        $(this).addClass('active').siblings().removeClass('active');
        generatePositionFields(playerCount);
    });

    $(document).on('change', '.player-select', updatePlayerDropdowns);

    updatePlayerDropdowns();
});
</script>

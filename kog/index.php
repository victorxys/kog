<?php
$page_title = 'Game List';
require_once('functions.php'); 
$y= isset($_REQUEST['y'])?$_REQUEST['y']:date('Y');
date_default_timezone_set("PRC");
$game_year_list = get_game_history_year();
$this_year = date('Y');
$game_list 	=	get_game_list($y);
$game_memo_list 	=	get_game_memo_list($y);
if (!empty($game_list)) {
	foreach ($game_list as $key => $value) {
		$game_player 	=	get_game_player($value->id);
		if (is_array($game_player) && !empty($game_player)) {
			foreach ($game_player  as $k => $val) {
				$game_player_str[$value->id] 	= isset($game_player_str[$value->id])?$game_player_str[$value->id]."、".$val->player:$val->player;

			}
		}	
	}
}
$url = "kog_detail.php?gid=";

// 标签样式数组 
$span_style = array(
	"default",
	"primary",
	"secondary",
	"info",
	"success",
	"danger",
	"warning"
	);
if (isset($game_memo_list['memo_total']['duration']) && is_array($game_memo_list['memo_total']['duration'])){
	krsort($game_memo_list['memo_total']['duration']);
}else{
	// echo "暂无今年数据，请新建游戏";
}

require_once('kog_header.php');
?>

<div class="container-fluid py-4">
      <div class="row">
        <div class="col-12">
          <div class="card mb-4">
            <div class="card-header pb-0">
              <div class="d-flex justify-content-between">
                <h6 class="mb-2">Game List</h6>
                  <div class="col-5 text-right">
	                  <select class="form-control" id="start_year" name="start_year" onchange="startYearChange(this.value)">
	                  	<option select="selected" value=<?php _e($this_year)?>>今年</option>
						<?php foreach ($game_year_list as $key => $value): ?>
							<option <?php _e(test_js($value))?> value=<?php _e($value)?> ><?php _e($value)?></option>
						<?php endforeach ?>	
						<option <?php _e(test_js('all'))?> value="all">全部</option>		                    
	                    </select>	                  
	                </div>
              </div>
            </div>
            <div class="card-body px-0 pt-0 pb-2">
              <div class="table-responsive p-0">
                <table class="table align-items-center mb-0">
                  <thead>
                    <tr>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">#</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Date</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Games</th>
                      <th class="text-uppercase text-secondary text-xxs font-weight-bolder opacity-7 ps-2">Result</th>
                      <th class="text-center text-uppercase text-secondary text-xxs font-weight-bolder opacity-7">Duration</th>
                    </tr>
                  </thead>
                  <tbody>
                  <?php if (isset($game_memo_list['memo_total']['duration']) && is_array($game_memo_list['memo_total']['duration'])): ?>
                    <?php $i = 0; ?>
			            	<?php foreach($game_memo_list['memo_total']['duration'] as $key=>$value):?>
                      <?php $i++; ?>
                      <tr>
                        <td>
                          <div class="d-flex px-3 py-1">
                            <div class="d-flex flex-column justify-content-center">
                              <h6 class="mb-0 text-sm"><?php _e($i)?></h6>
                            </div>
                          </div>
                        </td>
                        <td>
                          <p class="text-xs font-weight-bold mb-0"><a href=kog_summary.php?memo=<?php _e($key)?>><?php _e($key)?></a></p>
                        </td>
                        <td class="align-middle text-center text-sm">
                          <span class="text-secondary text-xs font-weight-bold"><?php _e($game_memo_list['memo_total']['count_games'][$key])?></span>
                        </td>
                        <td class="align-middle">
                            <div class="d-flex justify-content-start">
                              <?php 
                                $cost = get_cost($key,null,'total');
                                if (!empty($cost) && isset($cost)) {
                                  echo "<span class='badge badge-sm bg-gradient-secondary m-1'>
                                        成本:".$cost."
                                      </span>";
                                }
                                
                                foreach($game_memo_list['memo_total']['player_info'][$key] as $k=>$val){
                                  if ($val->total_fact >=0) {
                                    echo "
                                    <span class='badge badge-sm badge-success m-1'>".$val->player.":".$val->total_fact."</span>
                                    ";
                                  }else{
                                    echo "
                                    <span class='badge badge-sm badge-danger m-1'>".$val->player.":".$val->total_fact."</span>
                                    ";
                                  }
                                }
                              ?>
                            </div>
                        </td>
                        <td class="align-middle text-center">
                          <span class="text-secondary text-xs font-weight-bold">
                              <?php 
                                $duration = $value/3600;
                                if ($duration >= 1) {
                                  $duration = round(($value/3600),1)." H";
                                }else{
                                  $duration = round(($value/60),1)." Min";
                                }
                                echo $duration;
                              ?>
                          </span>
                        </td>
                      </tr>
                    <?php endforeach?>
			            	<?php endif ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>

      <footer class="footer pt-3">
      </footer>

</div>

<script type="text/javascript">
	function startYearChange(value) {
		if (location.href.indexOf('?') == -1) {
			window.location.href = location.href + "?y=" + value;
		} else {
            let currentUrl = new URL(window.location.href);
            currentUrl.searchParams.set('y', value);
            window.location.href = currentUrl.href;
		}
	}
</script>

<?php
require_once('kog_footer.php');
?>
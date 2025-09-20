<?php
require_once('functions.php'); 

$y= isset($_REQUEST['y'])?$_REQUEST['y']:date('Y');
date_default_timezone_set("PRC");
$game_memo = $_REQUEST['memo'];
$game_cost = get_cost($game_memo,null,'total');
$game_list 	=	get_game_list($y);
$game_memo_list 	=	get_game_memo_list(null,$game_memo);
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
$url_stat = "stat.php?type";

$page_title = "Game Summary";

require_once "kog_header.php";
?>
<div class="container-fluid mt--6">
    <div class="card">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0">Summary</h3>
                </div>
                <div class="col-auto">
                    <button class="btn btn-sm btn-primary" id="openDialog">Add Cost</button>
                    <a href='stat.php?type=detail&md=<?php _e($game_memo)?>' class="btn btn-sm btn-primary">Detail</a>
                    <a href='stat.php?type=total&md=<?php _e($game_memo)?>' class="btn btn-sm btn-primary">Final count</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                <div class="timeline-block">
                    <span class="timeline-step badge-success"><i class="fa fa-trophy"></i></span>
                    <div class="timeline-content">
                        <small class="text-muted font-weight-bold">Duration: <?php _e(round(($game_memo_list['memo_total']['duration'][$game_memo])/3600,2))?>H (<?php _e(date("H:i",$game_memo_list['memo_games'][$game_memo][0]->start_time))?> ~ <?php if(null != end($game_memo_list['memo_games'][$game_memo])->end_time ){echo date("H:i",end($game_memo_list['memo_games'][$game_memo])->end_time);}else{echo "Now";}?>)</small>
                        <h5 class="mt-3 mb-0">ENDING</h5>
                        <p class="text-sm mt-1 mb-0">共计：<?php _e($game_memo_list['memo_total']['count_games'][$game_memo])?> 场</p>
                        <div class="mt-3">
                            <?php foreach($game_memo_list['memo_total']['player_info'][$game_memo] as $key=>$value):?>
                                <?php 
                                $badge_class = 'badge-secondary';
                                if($value->total_fact > 0) $badge_class = 'badge-success';
                                if($value->total_fact < 0) $badge_class = 'badge-danger';
                                ?>
                                <span class="badge badge-pill <?php echo $badge_class; ?>"><?php _e($value->player)?>: ￥<?php _e($value->total_fact)?></span>
                            <?php endforeach?>
                        </div>
                    </div>
                </div>
                <div class="timeline-block">
                    <span class="timeline-step badge-primary"><i class="fa fa-yen-sign"></i></span>
                    <div class="timeline-content">
                        <span class="badge badge-pill badge-danger"><i class="fa fa-trophy"></i> 成本：<?php _e($game_cost)?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <?php foreach($game_memo_list['memo_games'][$game_memo] as $key=>$value):?>
    <div class="card mt-4">
        <div class="card-header">
            <div class="row align-items-center">
                <div class="col">
                    <h3 class="mb-0"><?php _e($game_memo)?> #<?php _e($key+1)?>
                        <small class="text-muted font-weight-bold">
                        <?php 
                        $duration = $value->duration/3600;
                        if ($duration >= 1) {
                            $duration = "Duration ".round(($value->duration/3600),1)." H";
                        } elseif($duration>0){
                            $duration = "Duration ".round(($value->duration/60),1)." Min";
                        } else {
                            $duration = "尚未结束";
                        }
                        echo $duration;
                        ?>
                        </small>
                    </h3>
                </div>
                <div class="col-auto">
                    <a href='kog_detail.php?gid=<?php _e($value->id)?>' class="btn btn-sm btn-primary">View</a>
                </div>
            </div>
        </div>
        <div class="card-body">
            <div class="timeline timeline-one-side" data-timeline-content="axis" data-timeline-axis-style="dashed">
                <div class="timeline-block">
                    <span class="timeline-step badge-default"><i class="ni ni-bell-55"></i></span>
                    <div class="timeline-content">
                        <small class="text-muted font-weight-bold"><?php _e(date("H:i",$value->start_time))?></small>
                        <h5 class="mt-3 mb-0">Shuffl & Deal</h5>
                        <div class="mt-3">
                            <?php $game = get_game_detail($value->id); foreach($game as $k=>$val):?>
                            <span class="badge badge-pill badge-default"><?php _e($val->seat_position)?>:<?php _e($val->player)?></span>
                            <?php endforeach?>
                        </div>
                    </div>
                </div>

                <?php $luckys = get_luck_by_gid($value->id); if(null != $luckys):?>
                <div class="timeline-block">
                    <span class="timeline-step badge-info"><i class="ni ni-like-2"></i></span>
                    <div class="timeline-content">
                    <?php foreach($luckys as $k=>$val):?>
                        <small class="text-muted font-weight-bold"><?php _e(date("H:i",$val->created_at))?></small>
                        <h5 class="mt-3 mb-0"><?php _e(get_lucky_bonus($val->lucky_type))?> <?php _e($val->cards)?></h5>
                        <div class="mt-3">
                            <span class="badge badge-pill badge-info"><?php _e(get_player($val->uid)[0]->nickname)?></span>
                            <span class="badge badge-pill badge-info">Chips:<?php _e($val->bonus)?></span>
                            <span class="badge badge-pill badge-secondary">Dealer:<?php _e(get_player($val->dealar)[0]->nickname)?></span>
                        </div>
                    <?php endforeach?>
                    </div>
                </div>
                <?php endif?>

                <?php if($value->duration >=3600):?>
                <div class="timeline-block">
                    <span class="timeline-step badge-danger"><i class="ni ni-air-baloon"></i></span>
                    <div class="timeline-content">
                        <small class="text-muted font-weight-bold"><?php _e(date("H:i",($value->start_time+3600))) ?></small>
                        <h5 class="mt-3 mb-0">Straddle</h5>
                        <div class="mt-3">
                            <span class="badge badge-pill badge-danger">Straddle:20</span>
                        </div>
                    </div>
                </div>
                <?php endif?>

                <?php if($value->duration >=7200):?>
                <div class="timeline-block">
                    <span class="timeline-step badge-danger"><i class="ni ni-air-baloon"></i></span>
                    <div class="timeline-content">
                        <small class="text-muted font-weight-bold"><?php _e(date("H:i",($value->start_time+7200))) ?></small>
                        <h5 class="mt-3 mb-0">Blind Up</h5>
                        <div class="mt-3">
                            <span class="badge badge-pill badge-danger">SB:10</span>
                            <span class="badge badge-pill badge-danger">BB:20</span>
                            <span class="badge badge-pill badge-danger">Straddle:40</span>
                        </div>
                    </div>
                </div>
                <?php endif?>

                <?php $rebuy_info = get_rebuy_info($value->id); if(null != $rebuy_info):?>
                <?php foreach($rebuy_info as $k => $val):?>
                <div class="timeline-block">
                    <span class="timeline-step badge-danger"><i class="fa fa-yen-sign"></i></span>
                    <div class="timeline-content">
                        <small class="text-muted font-weight-bold"><?php _e(date("H:i",$val->created_at))?></small>
                        <h5 class="mt-3 mb-0">Rebuy</h5>
                        <div class="mt-3">
                            <span class="badge badge-pill badge-danger"><?php _e(get_player($val->uid)[0]->nickname)?> : <?php _e($val->rebuy)?></span>
                            <span class="badge badge-pill badge-success"><i class="fa fa-trophy"></i> <?php _e(get_player($val->killed_by)[0]->nickname)?></span>
                        </div>
                    </div>
                </div>
                <?php endforeach?>
                <?php endif?>

                <?php if(null != $value->end_time): $game_count = get_game_detail($value->id,'total_fact');?>
                <div class="timeline-block">
                    <span class="timeline-step badge-success"><i class="fa fa-calculator"></i></span>
                    <div class="timeline-content">
                        <small class="text-muted font-weight-bold"><?php _e(date("H:i",$value->end_time))?>
                        <?php if (date("Y-m-d",$value->start_time) != date("Y-m-d",$value->end_time)): ?>
                            +<?php _e(intval(($value->end_time - $value->start_time)/86400))?>Days
                        <?php endif ?>
                        </small>
                        <h5 class="mt-3 mb-0">Counting</h5>
                        <div class="mt-3">
                            <?php foreach($game_count as $k=>$val):?>
                                <?php 
                                $badge_class = 'badge-secondary';
                                if($val->total_fact > 0) $badge_class = 'badge-success';
                                if($val->total_fact < 0) $badge_class = 'badge-danger';
                                ?>
                                <span class="badge badge-pill <?php echo $badge_class; ?>"><?php _e($val->player)?>: ￥<?php _e($val->total_fact)?></span>
                            <?php endforeach?>
                        </div>
                    </div>
                </div>
                <?php endif?>
            </div>
        </div>
    </div>
    <?php endforeach?>
</div>
<?php require_once "kog_footer.php";?>
<script>
$(document).ready(function() {
  $('#openDialog').click(function() {
    Swal.fire({
      title: '输入成本',
      input: 'number',
      inputAttributes: {
        min: 0
      },
      showCancelButton: true,
      confirmButtonText: '提交',
      cancelButtonText: '取消',
      showLoaderOnConfirm: true,
      preConfirm: (value) => {
        if (value === '') {
          Swal.showValidationMessage('请输入一个数字');
        }
        return value;
      },
      allowOutsideClick: () => !Swal.isLoading()
    }).then((result) => {
      if (result.value) {
        let cost = result.value;
        let memo = "<?php echo $game_memo; ?>";
        $.ajax({
          url: 'kog_addcost.php',
          type: 'POST',
          data: { cost: cost, memo: memo },
          success: function(response) {
           	Swal.fire('成功', response, 'success').then(function() {
     				 	window.location.href = 'stat.php?type=total&md='+memo;
   					 });
          },
          error: function(xhr, status, error) {
            Swal.fire('错误', '处理请求时发生错误', 'error');
          }
        });
      }
    });
  });
});
</script>


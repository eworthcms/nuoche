<link rel="stylesheet" type="text/css" href="<?php echo  base_url();?>css/feedback.css"/>
<!--选项卡-->
<div class="pageSession-content">
	<div class="pageSession-top">
<!-- 		<div class="read-box"> -->
<!-- 			<button class="Marked-read">标为已读</button> -->
<!-- 			<button class="Marked-unread">标为未读</button> -->
<!-- 		</div> -->
		<div class="timeBox">
			<!-- <select name="">
				<option value="">未读</option>
				<option value="">已读</option>
			</select>  -->
			<form id="form" action="/feed/index" method="GET">
				<label for="from">从</label> <input type="text" id="from"
				name="from" value="<?php echo $map['from'];?>"><label for="to">-</label> <input type="text" id="to"
				name="to" value="<?php echo $map['to'];?>"> <input type="text" name="content" id="content" 
				placeholder="反馈意见" value="<?php echo $map['content'];?>"> <button type="submit" class="search-btn icon iconfont icon-tubiao17">搜索</button>
			</form>
		</div>

	</div>
	<!-------挪车列表部分-------->
	<div id="dataBox">
		<!--列表部分开始-->
		<div class="ticket-list  oldTelList">
			<table border="">
				<tbody>
					<tr>
										<td class="firstTd"><i>用户ID</i></td>
                                        <td><i>用户昵称</i></td>
                                        <td><i>反馈信息</i></td>
                                        <td><i>反馈时间</i></td>
										<td><i>读取状态</i></td>										
					</tr>
					<?php if (is_array($feeds) && count($feeds)){
						foreach ($feeds as $key =>$feed){
							?>
							<tr>
								<td class="first NUMber"><?php if ($feed['open_id']=='' || $feed['open_id'] ==null){echo '未绑定';}else{echo $feed['user_id'];}?></td>
                                <td><?php echo $feed['nick_name']?></td>
                                <td><?php if (mb_strlen($feed['content'],'UTF8')>300){echo mb_strcut($feed['content'],0,300,'UTF8');}else{echo $feed['content'];}?></td>
                                <td><?php echo date('Y-m-d H:i',$feed['create_time']);?></td>
                                <td>已读</td>
							</tr>
							<?php 
						}
					}else{
					?>
						<tr><td>---</td><td>---</td><td>---</td><td>---</td></tr>
					<?php 	
					} ?>
					
					
				</tbody>
			</table>
		</div>
		<div class="pageNum">
			<em>共<?php echo $total_page;?>页</em><?php echo $page;?>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(function(){
		$( "#from" ).datepicker({
	      defaultDate: "+1w",
	      changeMonth: true,
	      numberOfMonths: 1,
	      onClose: function( selectedDate ) {
	        $( "#to" ).datepicker( "option", "minDate", selectedDate );
	      }
	    });
	    $( "#to" ).datepicker({
	      defaultDate: "+1w",
	      changeMonth: true,
	      numberOfMonths: 1,
	      onClose: function( selectedDate ) {
	        $( "#from" ).datepicker( "option", "maxDate", selectedDate );
	      }
	    });
	    //点击编辑按钮遮罩层出现，出现弹出
	    $(".compile").click(function(){
	    	$("#mark").show();
	    	$(".pop-up-windows").show();
	    });
	    $(".icon-guanbi").click(function(){
	    	$("#mark").hide();
	    	$(".pop-up-windows").hide();
	    })
	    //点击取消按钮
	    $(".cancel-pop-up").click(function(){
	    	$("#mark").hide();
	    	$(".pop-up-windows").hide();
	    })
	    //点击修改
	    $(".change-pop-up").click(function(){
	    	$("#mark").hide();
	    	$(".pop-up-windows").hide();
	    });
	    //二维码出现；	    
	    $(".ticket-list table td:nth-child(6)").each(function(index){
			$(".ticket-list table td:nth-child(6)").eq(index).mouseover(function(){
				$(".erweima-box").eq(index - 1).show();
			}).mouseout(function(){
				$(".erweima-box").eq(index - 1).hide();
			})			
		});
	    
	})
</script>
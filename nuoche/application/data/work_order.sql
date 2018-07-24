ALTER TABLE `jia_work_order`
ADD COLUMN `start_time`  datetime NOT NULL DEFAULT 0 COMMENT '开始时间' AFTER `modify_time`;
ADD COLUMN `end_time`  datetime NOT NULL DEFAULT 0 COMMENT '结束时间' AFTER `start_time`;
ADD COLUMN `user_id`  int(11) NOT NULL DEFAULT 0 COMMENT '广告主id' AFTER `end_time`;
ADD COLUMN `sms_template_id`  int(11) NOT NULL DEFAULT 0 COMMENT '广告主id' AFTER `user_id`;
ADD COLUMN `province_id`  int(11) NOT NULL DEFAULT 0 COMMENT '省id' AFTER `sms_template_id`;
ADD COLUMN `city_id`  int(11) NOT NULL DEFAULT 0 COMMENT '城市id' AFTER `province_id`;
ADD COLUMN `clicknum`  int(11) NOT NULL DEFAULT 0 COMMENT '城市id' AFTER `city_id`;
ADD COLUMN `sendnum`  int(11) NOT NULL DEFAULT 0 COMMENT '城市id' AFTER `clicknum`;

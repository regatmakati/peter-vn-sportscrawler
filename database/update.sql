ALTER TABLE `peter_sport`.`sports_football_match`
    ADD COLUMN `is_hot` tinyint NULL DEFAULT 0 COMMENT '是否热门:0=否,1=是' AFTER `updated_at`;


ALTER TABLE `peter_sport`.`sports_basketball_match`
    ADD COLUMN `is_hot` tinyint NULL DEFAULT 0 COMMENT '是否热门:0=否,1=是' AFTER `updated_at`;
DROP TABLE IF EXISTS `syy_admins`;
CREATE TABLE `syy_admins` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT, 
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '管理员账号',
  `password` varchar(50) NOT NULL DEFAULT '' COMMENT '密码',
  `status` tinyint(1) NOT NULL DEFAULT '0' COMMENT '管理员账号状态 0：禁用 1：开启',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='管理员表';

INSERT INTO `syy_admins` VALUES (1, 'root', 'e10adc3949ba59abbe56e057f20f883e', 1, 1642750967, 1642750967);

DROP TABLE IF EXISTS `syy_menus`;
CREATE TABLE `syy_menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父级id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `url` varchar(300) NOT NULL DEFAULT '' COMMENT '前端路由',
  `icon` varchar(300) NOT NULL DEFAULT '' COMMENT 'icon',
  `is_enabled` tinyint(1) NOT NULL DEFAULT 1 COMMENT '是否启用 0：不启用 1：启用',
  `sort` int(11) NOT NULL DEFAULT 0 COMMENT '排序',
  `created_at` int(20) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(20) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='菜单表';

DROP TABLE IF EXISTS `syy_role_has_menus`;
CREATE TABLE `syy_role_has_menus` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `role_id` int(11) NOT NULL DEFAULT '0' COMMENT '角色id',
  `menu_id` int(11) NOT NULL DEFAULT '0' COMMENT '菜单id',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='角色菜单表';

DROP TABLE IF EXISTS `syy_upload_files`;
CREATE TABLE `syy_upload_files` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '文件id',
  `storage` tinyint(1) NOT NULL DEFAULT 0 COMMENT '存储方式 0：本地',
  `file_url` varchar(255) NOT NULL DEFAULT '' COMMENT '存储路径',
  `file_name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件名',
  `file_size` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '文件大小(字节)',
  `file_size_m` varchar(100) NOT NULL DEFAULT '' COMMENT '文件大小(兆)',
  `file_type` varchar(200) NOT NULL DEFAULT '' COMMENT '文件类型',
  `real_name` varchar(255) NOT NULL DEFAULT '' COMMENT '文件真实名',
  `extension` varchar(20) NOT NULL DEFAULT '' COMMENT '文件扩展名',
  `created_at` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COMMENT='文件库记录表';

DROP TABLE IF EXISTS `syy_cases`;
CREATE TABLE `syy_cases` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL DEFAULT 0 COMMENT '管理员id',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '病员姓名',
  `age` int(11) NOT NULL DEFAULT 0 COMMENT '病员年龄',
  `sex` tinyint(3) NOT NULL DEFAULT 0 COMMENT '性别 0：男 1：女',
  `abstract` varchar(500) NOT NULL DEFAULT '' COMMENT '病史摘要',
  `type` varchar(100) NOT NULL DEFAULT '' COMMENT '病例类型',
  `part` varchar(100) NOT NULL DEFAULT '' COMMENT '部位',
  `img_id` int(11) NOT NULL DEFAULT 0 COMMENT '图片id',
  `video_id` int(11) NOT NULL DEFAULT 0 COMMENT '视频id',
  `attachment_id` varchar(20) NOT NULL DEFAULT '' COMMENT '附件id',
  `status` tinyint(3) NOT NULL DEFAULT 1 COMMENT '状态 0：删除 1：正常',
  `created_at` int(11) NOT NULL DEFAULT '0' COMMENT '创建时间',
  `updated_at` int(11) NOT NULL DEFAULT '0' COMMENT '更新时间',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci COMMENT='病例表';
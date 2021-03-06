-- 博客文章表
CREATE TABLE IF NOT EXISTS pb_blog(
  id int unsigned auto_increment comment '自增长ID',
  post_id int(11) unsigned not null default '0' comment '文章ID', 
  title varchar(200) not null default '' comment '标题 ', 
  author_id mediumint(8) unsigned not null default 0 comment '作者ID', 
  content text comment '博客内容', 
  status enum('0','1')  default '1' comment '博客状态',  
  crate_time datetime not null comment '创建时间',
  primary key(id),
  index post_id(post_id),
  index author_id(author_id)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='博客文章表';

-- 博客分类表
CREATE TABLE IF NOT EXISTS pb_category(
  caterogry_id mediumint unsigned auto_increment comment '分类ID',
  parent_id mediumint(11) unsigned not null default '0' comment '父级ID', 
  cat_name varchar(90) not null default '' comment '分类名 ', 
  description varchar(1000) comment '分类描述', 
  post_count int(11) unsigned not null  default '0' comment '分类文章量',  
  primary key(caterogry_id),
  index parent_id(parent_id),
  index cat_name(cat_name)

) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='博客分类表';


-- 标签表
CREATE TABLE IF NOT EXISTS pb_tag(
  tag_id mediumint unsigned auto_increment comment '标签ID',
  tag_name varchar(90) not null default '' comment '标签 ', 
  post_count int(11) unsigned not null  default '0' comment '文章量',
  primary key(tag_id),
  UNIQUE KEY  tag_name(tag_name)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='标签表';



-- 分类-文章关联表
CREATE TABLE IF NOT EXISTS pb_category_relation(
  id int(11) unsigned auto_increment comment '自增长ID',
  post_id int(11) unsigned not null default '0' comment '文章ID', 
  caterogry_id mediumint(8) unsigned not null default 0 comment '分类ID', 
  primary key(id),
  index post_id(post_id),
  index caterogry_id(caterogry_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='标签表';

-- 标签-文章关联表
CREATE TABLE IF NOT EXISTS pb_tag_relation(
  id int(11) unsigned auto_increment comment '自增长ID',
  post_id int(11) unsigned not null default '0' comment '文章ID', 
  tag_id mediumint(8) unsigned not null default 0 comment '分类ID', 
  primary key(id),
  index post_id(post_id),
  index tag_id(tag_id)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='标签-文章关联表';
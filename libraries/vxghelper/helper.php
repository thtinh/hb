<?php
defined('_JEXEC') or die('Restricted access');
require_once (JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');

class helper {
    //function nay dung de lay ra tat ca cac title,img,introtext va link cua bai viet dua theo cat id ma nguoi dung nhap vao
    public function getContentbyCatid($catid,$count,$mostview) {
        $db	=& JFactory::getDBO();
        //dieu kien la phai lay bai viet publish
        $where = $this->_getWhereContentByCat($db,$catid);
        //lay dieu kien cho order by co hit hoac khong co hit va sap xep bai viet theo thu tu giam dan
        $ordering = $this->_getOrdering($mostview);
        //lay query trong ham getQueryContent ra
        $query = $this->_getQueryContent();

        $query = $query . $where .' ORDER BY '. $ordering;

        $db->setQuery($query, 0, $count);
        //function getIDetails se tra ra mot mang row co cac link,title,introtext,image
        $rows = $this->_getDetails($db, $count);

        return $rows;

    }
    //function nay dung de lay article theo dang array ma nguoi dung nhap vao
    public function getContentbyArticleid($articleid) {
        $db	=& JFactory::getDBO();
        //dieu kien la phai lay bai viet publish
        $where = $this->_getWhereContentByArticle($db,$articleid);
        //lay query trong ham getQueryContent ra
        $query = $this->_getQueryContent();

        $query = $query . $where ;

        $db->setQuery($query);
        //function getIDetails se tra ra mot mang row co cac link,title,introtext,image
        $ids = explode( ',', $articleid );
        $count = count($ids);
        $rows = $this->_getDetails($db, $count);

        return $rows;

    }
    //function nay dung de lay img,link...trong nhóm hoi ma nguoi dung nhap vao(nam trong carehub)
    public function getCommunityGroup($limit) {
        include_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'defines.community.php' );
        require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
        require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'tooltip.php' );

        $db	=& JFactory::getDBO();
        $query = "SELECT * FROM #__community_groups WHERE published=1 ORDER BY created DESC LIMIT ".$limit;
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $list = array();
        $i=0;
        foreach ($rows as $row) {
            $user =& CFactory::getUser($row->ownerid);
            $list[$i]->membercount = $row->membercount;
            $list[$i]->ownerid = $row->ownerid;
            $list[$i]->avatar = $row->avatar;
            $list[$i]->user = $user->name;
            $list[$i]->link = CRoute::_('index.php?option=com_community&view=groups&task=group&groupid='.$row->id.'&name='.$row->name);
            $list[$i]->view = $row->discusscount;
            $list[$i]->nameGroup = $row->name;
            $list[$i]->text = $row->description;

            $i++;
        }
        return $list;
    }
    //function nay dung de lay ra tat ca cac title,img,introtext va link cua bai viet dua theo keyword ma nguoi dung nhap vao
    public function getContentByKeyword($metakey,$count,$mostview) {
        $db	=& JFactory::getDBO();
        //dieu kien la phai lay bai viet publish
        $where = $this->_getWhereContentByKeyword($db,$metakey);
        //lay dieu kien cho order by co hit hoac khong co hit va sap xep bai viet theo thu tu giam dan
        $ordering = $this->_getOrdering($mostview);
        //lay query trong ham getQueryContent ra
        $query = $this->_getQueryContent();

        $query = $query . $where .' ORDER BY '. $ordering;

        $db->setQuery($query, 0, $count);
        //function getIDetails se tra ra mot mang row co cac link,title,introtext,image
        $rows = $this->_getDetails($db, $count);

        return $rows;
        //return $query;
    }

    public function getBlogContent($limit) {
        include(JPATH_SITE.DS.'distribution'.DS.'config.php');
        $user =& JFactory::getUser();

        $option = array();
        $option['driver']   = $dbms;            // Database driver name
        $option['host']     = $dbhost;    // Database host name
        $option['user']     = $dbuser;       // User for database authentication
        $option['password'] = $dbpasswd;  // Password for database authentication
        $option['database'] = $dbname;      // Database name
        $option['prefix']   = $table_prefix;   // Database prefix (may be empty)
        $db = & JDatabase::getInstance( $option );

        $query = " SELECT b.blog_id, b.user_id, b.icon_id, b.blog_subject, b.blog_text, b.blog_time,
		b.blog_tags, b.blog_read_count, b.blog_reply_count, u.user_avatar, u.username
		FROM ".$table_prefix."blogs AS b INNER JOIN ".$table_prefix."users AS u ON b.user_id = u.user_id ORDER BY b.blog_time DESC LIMIT $limit ";

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $list = $this->_getDetailsBlog($rows);
        return $list;
    }
    public function isUserLoggedIn() {
        $user =& JFactory::getUser();
        if($user->username!="") return true;
        else return false;
    }
    public function getUserBlogContent($limit) {
        include(JPATH_SITE.DS.'distribution'.DS.'config.php');
        $user =& JFactory::getUser();

        $option = array();
        $option['driver']   = $dbms;            // Database driver name
        $option['host']     = $dbhost;    // Database host name
        $option['user']     = $dbuser;       // User for database authentication
        $option['password'] = $dbpasswd;  // Password for database authentication
        $option['database'] = $dbname;      // Database name
        $option['prefix']   = $table_prefix;   // Database prefix (may be empty)
        $db = & JDatabase::getInstance( $option );

        $query = " SELECT b.blog_id, b.user_id, b.icon_id, b.blog_subject, b.blog_text, b.blog_time,
		b.blog_tags, b.blog_read_count, b.blog_reply_count, u.user_avatar, u.username
		FROM ".$table_prefix."blogs AS b INNER JOIN ".$table_prefix."users AS u ON b.user_id = u.user_id WHERE u.username = '$user->username' ORDER BY b.blog_time DESC LIMIT $limit ";

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $list = $this->_getDetailsBlog($rows);
        return $list;

    }

    //function nay lay cai prefix cho content forum
    private function _getDetailsBlog($rows) {
        $baseurl = '/my/blog';
        $list = array();
        $i=0;
        foreach ($rows as $row) {
            $link = $baseurl . "/blog.php?u=" . $row->user_id . "&b=" . $row->blog_id;
            $avatar = $row->user_avatar;
            $list[$i]->title = $row->blog_subject;
            $list[$i]->link = $link;
            $text =  preg_replace("/^(([\w]+:)?\/\/)?(([\d\w]|%[a-fA-f\d]{2,2})+(:([\d\w]|%[a-fA-f\d]{2,2})+)?@)?([\d\w][-\d\w]{0,253}[\d\w]\.)+[\w]{2,4}(:[\d]+)?(\/([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)*(\?(&amp;?([-+_~.\d\w]|%[a-fA-f\d]{2,2})=?)*)?(#([-+_~.\d\w]|%[a-fA-f\d]{2,2})*)?$/", " ",  $row->blog_text);
            $list[$i]->text = preg_replace('/\[[^\]]+:[^\]]+\]/',' ',$text);
            if($avatar!="") {
                $list[$i]->avatar = "/distribution/download/file.php?avatar=$avatar " ;
            }
            else {
                $list[$i]->avatar = "/distribution/images/avatars/gallery/avatar.png " ;
            }
            $list[$i]->username = $row->username;
            $date = date("Y-m-d H:i:s",$row->blog_time);
            $list[$i]->date = $date;
            $list[$i]->count = $row->blog_read_count;

            $i++;
        }
        return $list;
    }


    public function getMarkingHubContent($options) {
        include_once(JPATH_SITE.DS.'markinghub/libs/dbconnect.php');
        $limit = $options['limit'];
        $mostview = $options['mostviewComment'];

        $option = array();
        $option['driver']   = 'mysql';            // Database driver name
        $option['host']     = EZSQL_DB_HOST;    // Database host name
        $option['user']     = EZSQL_DB_USER;       // User for database authentication
        $option['password'] = EZSQL_DB_PASSWORD;  // Password for database authentication
        $option['database'] = EZSQL_DB_NAME;      // Database name
        $option['prefix']   = 'pligg_';   // Database prefix (may be empty)
        $db = & JDatabase::getInstance( $option );

        if($mostview == 1) {
            $query = "select l.*, cc.category_id, cc.category_name, u.* from pligg_links as l inner join pligg_users as u on l.link_author = u.user_id
            inner join pligg_categories as cc on l.link_category = cc.category_id where l.link_status = 'published' order by l.link_votes DESC limit $limit " ;
        }
        else {
            $query = "select l.*, cc.category_id, cc.category_name, u.* from pligg_links as l inner join pligg_users as u on l.link_author = u.user_id inner join
            pligg_categories as cc on l.link_category = cc.category_id where l.link_status = 'published' order by l.link_date DESC limit $limit ";
        }
        $db->setQuery($query);

        $rows = $db->loadObjectList();
        $list = $this->_getDetailsMarkingHub($rows);
        return $list;
    }

    //function lay tat ca bai viet cua user khi dang nhap
    public function getUserMarkingHubContent($options) {
        include_once(JPATH_SITE.DS.'markinghub/libs/dbconnect.php');
        $user =& JFactory::getUser();
        $limit = $options['limit'];
        $mostview = $options['mostviewComment'];

        $option = array();
        $option['driver']   = 'mysql';            // Database driver name
        $option['host']     = EZSQL_DB_HOST;    // Database host name
        $option['user']     = EZSQL_DB_USER;       // User for database authentication
        $option['password'] = EZSQL_DB_PASSWORD;  // Password for database authentication
        $option['database'] = EZSQL_DB_NAME;      // Database name
        $option['prefix']   = 'pligg_';   // Database prefix (may be empty)
        $db = & JDatabase::getInstance( $option );

        if($mostview == 1) {
            $query = "SELECT l.*, cc.category_id, cc.category_name, u.* FROM pligg_links AS l INNER JOIN pligg_users AS u ON l.link_author = u.user_id
            INNER JOIN pligg_categories AS cc ON l.link_category = cc.category_id
            WHERE l.link_status = 'published' AND u.user_names = '$user->username' ORDER BY l.link_votes DESC LIMIT $limit " ;
        }
        else {
            $query = "SELECT l.*, cc.category_id, cc.category_name, u.* FROM pligg_links AS l INNER JOIN pligg_users AS u
            ON l.link_author = u.user_id INNER JOIN pligg_categories AS cc ON l.link_category = cc.category_id
             WHERE l.link_status = 'published' AND u.user_names = '$user->username' ORDER BY l.link_published_date DESC LIMIT $limit ";
        }
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $list = $this->_getDetailsMarkingHub($rows);
        return $list;
    }
    //function lay tat ca nhung thuoc tinh co trong markinghub
    private function _getDetailsMarkingHub($rows) {
        require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
        $baseurl = '/markinghub';
        $list = array();
        $i=0;
        foreach ($rows as $row) {
            $url = str_replace('&amp;', '&', htmlspecialchars($row->link_url));
            $url_short = $this->cutString($url,80);
            if($row->link_url == "http://" || $row->link_url == '') {
                $url_short = "http://";
            } else {
                $parsed = parse_url($row->link_url);
                if(isset($parsed['scheme'])) {
                    $url_short = $parsed['scheme'] . "://" . $parsed['host'];
                }
            }

            $link = $baseurl.'/story.php?id='.$row->link_id;
            $list[$i]->link = $link;
            //need to do UTF-8 Conversion here
            $list[$i]->title =  mb_convert_encoding($row->link_title, "Windows-1252", "UTF-8");
            $list[$i]->votes = $row->link_votes;
            $list[$i]->reports = $row->link_reports;
            $list[$i]->randkey = $row->link_randkey;
            $list[$i]->author = $row->link_author;
            $list[$i]->comments = $row->link_comments;
            $list[$i]->karma = $row->link_karma;
            $list[$i]->date = $row->link_published_date;
            $list[$i]->category = $row->link_category;
            $list[$i]->url_short = $url_short;
            $list[$i]->url = $row->link_url;
            $list[$i]->title_url = mb_convert_encoding($row->link_title_url, "Windows-1252", "UTF-8");
            $list[$i]->url_title = mb_convert_encoding($row->link_url_title, "Windows-1252", "UTF-8");
            $list[$i]->content = mb_convert_encoding($row->link_content, "Windows-1252", "UTF-8");
            $list[$i]->summary = $row->link_summary;
            $list[$i]->tags = mb_convert_encoding($row->link_tags, "Windows-1252", "UTF-8");
            $list[$i]->catename = mb_convert_encoding($row->category_name, "Windows-1252", "UTF-8");
            $list[$i]->linkcateid = $baseurl.'/index.php?category='.mb_convert_encoding($row->category_name, "Windows-1252", "UTF-8");
            $list[$i]->linktag = $baseurl.'/search.php?search='.$row->link_tags.'&amp;=true';

            $list[$i]->userName = $row->user_login;
            $i++;
        }
        return $list;
    }
    public function getCommentMarkinghub($options) {
        require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
        include_once(JPATH_SITE.DS.'markinghub/libs/dbconnect.php');

        $limit = $options['limit'];
        $mostview = $options['mostviewComment'];

        $option = array();
        $option['driver']   = 'mysql';            // Database driver name
        $option['host']     = EZSQL_DB_HOST;    // Database host name
        $option['user']     = EZSQL_DB_USER;       // User for database authentication
        $option['password'] = EZSQL_DB_PASSWORD;  // Password for database authentication
        $option['database'] = EZSQL_DB_NAME;      // Database name
        $option['prefix']   = 'pligg_';   // Database prefix (may be empty)
        $db = & JDatabase::getInstance( $option );

        if($mostview == 1) {
            $query = "SELECT l.*, c.comment_content, c.comment_votes, u.user_names FROM pligg_links AS l INNER JOIN pligg_comments AS c
            ON l.link_id = c.comment_link_id INNER JOIN pligg_users AS u ON l.link_author = u.user_id
            WHERE l.link_status = 'published' ORDER BY c.comment_votes DESC LIMIT $limit " ;
        }
        else {
            $query = "SELECT l.*, c.comment_content, c.comment_votes, u.user_names FROM pligg_links AS l INNER JOIN pligg_comments AS c
            ON l.link_id = c.comment_link_id INNER JOIN pligg_users AS u ON l.link_author = u.user_id
            WHERE l.link_status = 'published' ORDER BY c.comment_date DESC LIMIT $limit ";
        }
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $baseurl = '/markinghub';
        $list = array();
        $i=0;
        foreach ($rows as $row) {
            $link = $baseurl.'/story.php?title='.$row->link_title;
            $list[$i]->link = $link;
            //need to do UTF-8 Conversion here
            $list[$i]->title =  mb_convert_encoding($row->comment_content, "Windows-1252", "UTF-8");
            $list[$i]->votes = $row->comment_votes;
            $list[$i]->date = $row->comment_date;
            $i++;
        }
        return $list;
    }

    public function getCategoryMarkinghub($limit) {
        require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
        include_once(JPATH_SITE.DS.'markinghub/libs/dbconnect.php');
        $option = array();
        $option['driver']   = 'mysql';            // Database driver name
        $option['host']     = EZSQL_DB_HOST;    // Database host name
        $option['user']     = EZSQL_DB_USER;       // User for database authentication
        $option['password'] = EZSQL_DB_PASSWORD;  // Password for database authentication
        $option['database'] = EZSQL_DB_NAME;      // Database name
        $option['prefix']   = 'pligg_';   // Database prefix (may be empty)
        $db = & JDatabase::getInstance( $option );

        $query = "SELECT * FROM pligg_categories where category_name != 'all' limit $limit";
        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $baseurl = '/markinghub';
        $list = array();
        $i=0;
        foreach ($rows as $row) {
            $link = $baseurl.'/index.php?category='.mb_convert_encoding($row->category_safe_name, "Windows-1252", "UTF-8");
            $list[$i]->link = $link;
            //need to do UTF-8 Conversion here
            $list[$i]->namecate =  mb_convert_encoding($row->category_name, "Windows-1252", "UTF-8");
            $list[$i]->alt = $row->category_desc;

            $i++;
        }
        return $list;
    }
    public function getForumContent($options) {
        include(JPATH_SITE.DS.'distribution'.DS.'config.php');
        $limit = $options['limit'];
        $showNumRepl = $options['showNumRepl'];
        $formatDate = $options['formatDate'];
        $excludeForums = $options['excludeForums'];
        $showTopicContent = $options['showTopicContent'];

        $option = array();
        $option['driver']   = $dbms;            // Database driver name
        $option['host']     = $dbhost;    // Database host name
        $option['user']     = $dbuser;       // User for database authentication
        $option['password'] = $dbpasswd;  // Password for database authentication
        $option['database'] = $dbname;      // Database name
        $option['prefix']   = $table_prefix;   // Database prefix (may be empty)
        $db = & JDatabase::getInstance( $option );

        if ($showNumRepl) {
            // find how many posts is configured per page
            $query = "SELECT config_value FROM " . $table_prefix . "config where config_name = 'posts_per_page' ";
            $db->setQuery($query);

            if ($results = $db->query()) {
                $rows = mysql_fetch_assoc($results);
                $number_of_post_per_page = $rows['config_value'];
            }
        }
        // cau query chinh
        $query = "SELECT t.forum_id, t.topic_id, t.topic_title, t.topic_last_post_time,
					t.topic_replies_real, t.topic_last_poster_id, t.topic_last_poster_name, t.topic_last_post_id, p.post_text, u.user_avatar, u.username
					FROM " . $table_prefix . "topics as t INNER JOIN ".$table_prefix."posts AS p ON t.topic_id = p.topic_id INNER JOIN ".$table_prefix.
                "users AS u ON u.user_id = t.topic_last_poster_id ";

        if($excludeForums)
            $query .= "WHERE t.forum_id NOT IN (" . $excludeForums . ") ";
        $query .= "ORDER BY t.topic_last_post_time DESC LIMIT " . $limit . ";";

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $list = $this->_getDetailsForum($rows,$number_of_post_per_page);
        return $list;

    }
    //function nay chi lay baiv iet trong forum cua nguoi dang nhap vao(ko lay tat ca)
    public function getUserForumContent($options) {
        include(JPATH_SITE.DS.'distribution'.DS.'config.php');
        $limit = $options['limit'];
        $showNumRepl = $options['showNumRepl'];
        $formatDate = $options['formatDate'];
        $excludeForums = $options['excludeForums'];
        $showTopicContent = $options['showTopicContent'];

        $option = array();
        $option['driver']   = $dbms;            // Database driver name
        $option['host']     = $dbhost;    // Database host name
        $option['user']     = $dbuser;       // User for database authentication
        $option['password'] = $dbpasswd;  // Password for database authentication
        $option['database'] = $dbname;      // Database name
        $option['prefix']   = $table_prefix;   // Database prefix (may be empty)
        $db = & JDatabase::getInstance( $option );

        $user =& JFactory::getUser();

        if ($showNumRepl) {
            // find how many posts is configured per page
            $query = "SELECT config_value FROM " . $table_prefix . "config where config_name = 'posts_per_page' ";
            $db->setQuery($query);

            if ($results = $db->query()) {
                $rows = mysql_fetch_assoc($results);
                $number_of_post_per_page = $rows['config_value'];
            }
        }
        // cau query chinh
        $query = "SELECT t.forum_id, t.topic_id, t.topic_title, t.topic_last_post_time,
					t.topic_replies_real, t.topic_last_poster_id, t.topic_last_poster_name, f.forum_image, t.topic_last_post_id, p.post_text, u.user_avatar, u.username
					FROM " . $table_prefix . "topics as t INNER JOIN ".$table_prefix."posts AS p ON t.topic_id = p.topic_id INNER JOIN ".$table_prefix.
                "users AS u ON u.user_id = t.topic_last_poster_id INNER JOIN ".$table_prefix."forums f ON f.forum_id = t.forum_id ";

        if($excludeForums)
            $query .= "WHERE t.forum_id NOT IN (" . $excludeForums . ") AND u.username = '$user->username' GROUP BY t.topic_last_post_id ";
        $query .= "ORDER BY t.topic_last_post_time DESC LIMIT " . $limit . ";";

        $db->setQuery($query);
        $rows = $db->loadObjectList();
        $list = $this->_getDetailsForum($rows,$number_of_post_per_page);
        return $list;

    }

    private function _getDetailsForum($rows,$number_of_post_per_page) {
        include_once(JPATH_SITE.DS.'libraries'.DS.'joomla'.DS.'utilities'.DS.'date.php');
        $baseurl = '/distribution';
        $list = array();
        $i=0;
        foreach ($rows as $row) {
            $start_page = floor( ($row->topic_replies_real + 1) / $number_of_post_per_page ) * $number_of_post_per_page;
            $topic = $baseurl . "/viewtopic.php?f=" . $row->forum_id . "&t=" . $row->topic_id;
            $last_post = $topic . "&start=" . $start_page . "#p" . $row->topic_last_post_id;

            $date_pre = new JDate ($row->topic_last_post_time);
            $post_date = $date_pre->toFormat($formatDate);
            $list[$i]->topictitle = $row->topic_title;
            $list[$i]->topicreply = $row->topic_replies_real;
            $raw_data = $row->post_text;
            $content_data=  preg_replace("#\[quote(.*)](.*)\[\/quote(.*)]#is", " ", $raw_data);
            $content_data=  preg_replace('/\[[^\]]+:[^\]]+\]/',' ', $content_data);
            $list[$i]->posttext = $content_data;
            $list[$i]->topicpostname = $row->topic_last_poster_name;
            $list[$i]->linktopictitle = $topic;
            $list[$i]->linklastpost = $last_post;
            $list[$i]->postdate = $post_date;
            $list[$i]->forum_image = $row->forum_image;
            $list[$i]->topicview = $row->topic_last_view_time;
            $list[$i]->username = $row->username;
            $avatar = $row->user_avatar;
            if($avatar!="") {
                $list[$i]->avatar = $baseurl. "/download/file.php?avatar=$avatar ";
            }
            else {
                $list[$i]->avatar = $baseurl. "/images/avatars/gallery/avatar.png ";
            }
            $i++;
        }
        return $list;
    }

    public function getMosetContentByCatID($catid,$count) {
        global $mt_itemid;
        include(JPATH_SITE.DS.'components'.DS.'com_mtree'.DS.'init.php');
        require_once( JPATH_SITE.DS.'administrator'.DS.'components'.DS.'com_mtree'.DS.'admin.mtree.class.php');
        $mtCats = new mtCats( $database );
        if(!isset($mt_itemid)) {
            $database->setQuery("SELECT id FROM #__menu"
                    .	"\nWHERE link='index.php?option=com_mtree'"
                    .	"\nAND published='1'"
                    .	"\nLIMIT 1");
            $mt_itemid = $database->loadResult();
        }
        $now = date( "Y-m-d H:i:s", time()+$mtconf->getjconf('offset')*60*60 );
        $listing = array();
        /*params*/
        $type = 1; // Default is new listing
        $show_from_cat_id = $catid; //integer , cat id
        $only_subcats = 0; //default 0, 0 means NO, 1 means YES
        $shuffle_listing = 1; //default 1, 0 means NO, 1 means YES
        /*end params*/
        $limit_cat_to = 0;
        if( $show_from_cat_id > 0 ) {
            if( $only_subcats == 1 ) {
                $mtCats->load( $show_from_cat_id );
                if( $cat_id > 0 && $mtCats->isChild($cat_id) ) {
                    $limit_cat_to = $cat_id;
                } else {
                    $limit_cat_to = $show_from_cat_id;
                }
            } else {
                $limit_cat_to = $show_from_cat_id;
            }
        } elseif ( $only_subcats == 1 ) {
            if( $cat_id > 0 ) {
                $limit_cat_to = $cat_id;
            } elseif ( $link_id > 0 ) {
                $link = new mtLinks( $database );
                $link->load( $link_id );
                $limit_cat_to = $link->getCatID();
            }
        }
        # Get sub_cats queries
        if( $limit_cat_to > 0 ) {
            $subcats = $mtCats->getSubCats_Recursive( $limit_cat_to );
            $subcats[] = $limit_cat_to;
            $only_subcats_sql = '';
            if ( count($subcats) > 0 ) {
                $only_subcats_sql = "\n AND cl.cat_id IN (" . implode( ", ", $subcats ) . ")";
            }
        }
        switch( $type ) {
            case 1: // New listing
                $order = "link_created";
                $sort = "DESC";
                $ltask= "listnew";
                break;
            case 2: // Featured Listing
                $order = "link_featured";
                $sort = "ASC";
                $ltask= "listfeatured";
                break;
            case 3: // Popular Listing
                $order = "link_hits";
                $sort = "DESC";
                $ltask= "listpopular";
                break;
            case 4: // Most Rated Listing
                $order = "link_votes";
                $sort = "DESC";
                $ltask= "listmostrated";
                break;
            case 5: // Top Rated Listing
                $order = "link_rating";
                $sort = "DESC";
                $ltask= "listtoprated";
                break;
            case 6: // Most Reviewed Listing
                $order = "reviews";
                $sort = "DESC";
                $ltask= "listmostreviewed";
                break;
            case 7: // Recently updated listing
                $order = "link_modified";
                $sort = "DESC";
                $ltask= "listupdated";
                break;
        }
        if ( $type == 6 ) {
            $database->setQuery( "SELECT l.*, COUNT(r.link_id) AS reviews, c.cat_name, c.cat_id, img.filename AS link_image FROM (#__mt_links AS l, #__mt_cl AS cl, #__mt_cats AS c)"
                    .	"\n LEFT JOIN #__mt_reviews AS r ON r.link_id=l.link_id "
                    .	"\n LEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1"
                    .	"\n WHERE link_published='1' && link_approved='1' && img.img_id>0 "
                    . "\n AND ( publish_up = '0000-00-00 00:00:00' OR publish_up <= '$now'  ) "
                    . "\n AND ( publish_down = '0000-00-00 00:00:00' OR publish_down >= '$now' ) "
                    . "\n AND l.link_id = cl.link_id "
                    . "\n AND c.cat_id = cl.cat_id "
                    . "\n AND cl.main = 1 "
                    .	( (!empty($only_subcats_sql)) ? $only_subcats_sql : '' )
                    .	"\n GROUP BY r.link_id "
                    .	"\n ORDER BY $order $sort "
                    .	"\n LIMIT $count" );
            $listing = $database->loadObjectList();
            // Shuffled Featured Listing
        } elseif ( $type == 2 && $shuffle_listing ) {
            $database->setQuery( "SELECT l.*, c.cat_name, c.cat_id, img.filename AS link_image FROM (#__mt_links AS l, #__mt_cl AS cl, #__mt_cats AS c)"
                    .	"\n LEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1"
                    .	"\n WHERE link_published='1' && link_approved='1' && link_featured='1' && img.img_id>0 "
                    . "\n AND ( publish_up = '0000-00-00 00:00:00' OR publish_up <= '$now'  ) "
                    . "\n AND ( publish_down = '0000-00-00 00:00:00' OR publish_down >= '$now' ) "
                    . "\n AND l.link_id = cl.link_id "
                    . "\n AND c.cat_id = cl.cat_id "
                    . "\n AND cl.main = 1 "
                    .	( (!empty($only_subcats_sql)) ? $only_subcats_sql : '' )
                    .	"\n ORDER BY RAND() "
                    .	"\n LIMIT $count"
            );

            $listing = $database->loadObjectList();
            shuffle( $listing );
            $listing = array_slice( $listing, 0, $count );
            // Other normal listing
        } else {
            $sql = "SELECT l.*, c.cat_name, c.cat_id, img.filename AS link_image FROM (#__mt_links AS l, #__mt_cl AS cl, #__mt_cats AS c)"
                    .	"\n LEFT JOIN #__mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1"
                    .	"\n WHERE link_published='1' && link_approved='1' && img.img_id>0 "
                    .	( ($type == 2) ? "\n AND link_featured='1'" : '' )
                    . "\n AND ( publish_up = '0000-00-00 00:00:00' OR publish_up <= '$now'  ) "
                    . "\n AND ( publish_down = '0000-00-00 00:00:00' OR publish_down >= '$now' ) "
                    . "\n AND l.link_id = cl.link_id "
                    . "\n AND c.cat_id = cl.cat_id "
                    . "\n AND cl.main = 1 "
                    .	( ( $type == 5 && $mtconf->get('min_votes_for_toprated') && $mtconf->get('min_votes_for_toprated') >= 1 ) ? "\n AND l.link_votes >= " . $mtconf->get('min_votes_for_toprated') . " " : '' )
                    .	( (!empty($only_subcats_sql)) ? $only_subcats_sql : '' );
            if( $type == 3 ) {
                $sql .=	"ORDER BY link_hits DESC ";
            } else {
                $sql .=	"\n ORDER BY $order $sort ";
            }

            if( $type == 4 ) {
                $sql .= ', link_rating DESC ';
            } elseif( $type == 5 ) {
                $sql .= ', link_votes DESC ';
            }
            $sql .=	"\n LIMIT $count";
            $database->setQuery( $sql );
            $listing = $database->loadObjectList();
        }
        $rows = array();
        $i = 0;
        foreach ($listing as $item) {
            $rows[$i]->title = $item->link_name;
            $rows[$i]->image = $mtconf->getjconf('live_site').$mtconf->get('relative_path_to_listing_small_image').$item->link_image;
            $rows[$i]->link = sefRelToAbs("index.php?option=com_mtree&task=viewlink&link_id=$item->link_id&Itemid=$mt_itemid");
            $rows[$i]->showmore = sefRelToAbs("index.php?option=com_mtree&task=$ltask&" . (($only_subcats) ? "cat_id=$cat_id&" : (($show_from_cat_id) ? "cat_id=$show_from_cat_id&" : "") )."Itemid=$mt_itemid");
            $i++;
        }
        return $rows;
    }

    public function getLatestVideo($limit) {
        include_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'defines.community.php' );
        require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
        require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'tooltip.php' );
        #require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'helpers' . DS . 'string.php' );
        require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'helpers' . DS . 'videos.php' );
        CFactory::load( 'libraries','error');
        $usermodel =& CFactory::getModel('user');
        $db	 =& JFactory::getDBO();
        $query  = "SELECT * FROM #__community_videos WHERE published=1 ORDER BY created DESC LIMIT ".$limit;
        $db->setQuery( $query );
        $result = $db->loadObjectList();
        if($db->getErrorNum()) {
            JError::raiseError( 500, $db->stderr());
        }
        $rows = array();
        $i = 0;
        foreach($result as $item) {
            $user =& CFactory::getUser($item->creator);
            $rows[$i]->user = $user->name;
            $rows[$i]->image = JURI::base().$item->thumb;
            $rows[$i]->link = CRoute::_('index.php?option=com_community&view=videos&task=video&userid='.$item->creator.'&videoid='.$item->id);
            $rows[$i]->title = $item->title;
            $rows[$i]->view = $item->hits;
            $rows[$i]->alt = $item->description;
            $rows[$i]->showmore = CRoute::_("index.php?option=com_community&view=videos");
            $i++;
        }
        return $rows;
    }

    public function getLatestAlbums($limit) {
        require_once( JPATH_ROOT . DS . 'components' . DS . 'com_community' . DS . 'libraries' . DS . 'core.php' );
        $db	 =& JFactory::getDBO();
        $query  = "SELECT *  , count( a.photoid ) as photos FROM #__community_photos_albums AS a
                   LEFT JOIN #__community_photos AS p ON a.id = p.albumid
                   GROUP BY (a.id) ORDER BY a.created DESC LIMIT ".$limit;
        $db->setQuery( $query );
        $latestAlbums = $db->loadObjectList();
        if($db->getErrorNum()) {
            JError::raiseError( 500, $db->stderr());
        }
        $rows = array();
        $i=0;
        foreach ($latestAlbums as $album) {
            $user =& CFactory::getUser($album->creator);
            $rows[$i]->user = $user->name;
            $rows[$i]->photos = $album->photos;
            $rows[$i]->title = $album->name;
            $rows[$i]->link = CRoute::_('index.php?option=com_community&view=photos&task=album&albumid=' . $album->albumid .  '&userid=' . $album->creator);
            $rows[$i]->showmore = CRoute::_('index.php?option=com_community&view=photos' );
            $rows[$i]->image = JURI::base().$album->thumbnail;
            $rows[$i]->alt = $album->description;
            $i++;
        }
        return $rows;
    }
    //function nay dung de cho nguoi dung cat chu title va introtext khi hien thi ra mang hinh
    public function cutString($string,$wordcount) {
        if (mb_strlen($string) > $wordcount) {
            $endText = "...";
            $string = mb_substr($string, 0, $wordcount, 'utf-8');
            $string = mb_substr($string, 0, mb_strrpos($string, ' ', 'utf-8'), 'utf-8');
            return $string.$endText;
        }
        else return $string;
    }

    //function nay du`ng de lay query ra
    private function _getQueryContent() {
        $query = 'SELECT a.*,cc.image AS image,' .
                ' CASE WHEN CHAR_LENGTH(a.alias) THEN CONCAT_WS(":", a.id, a.alias) ELSE a.id END as slug,'.
                ' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug'.
                ' FROM #__content AS a' .
                //' LEFT JOIN #__content_frontpage AS f ON f.content_id = a.id'.
                ' INNER JOIN #__categories AS cc ON cc.id = a.catid' .
                ' INNER JOIN #__sections AS s ON s.id = a.sectionid' ;

        return $query;

    }
    //function nay dung de chon ra nhung bai viet publish va ngay moi nhat theo cat id
    private function _getWhereContentByCat(&$db,$catid) {
        $user		=& JFactory::getUser();
        $aid		= $user->get('aid', 0);
        $contentConfig = &JComponentHelper::getParams( 'com_content' );
        $access		= !$contentConfig->get('show_noauth');

        $nullDate	= $db->getNullDate();
        $date =& JFactory::getDate();
        $now = $date->toMySQL();
        //lay cate id ma nguoi dung nhap bo vao trong cau query
        $cateid = $this->_getCateId($catid);

        $where	=' WHERE a.state = 1'. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
                . ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
                .' AND s.id > 0' .
                ($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
                $cateid.
                //' AND f.content_id IS NULL '.
                ' AND s.published = 1' .
                ' AND cc.published = 1' ;

        return $where;
    }
    //function nay dung de chon ra nhung bai viet publish va ngay moi nhat theo keyword
    private function _getWhereContentByKeyword(&$db,$metakey) {
        $user		=& JFactory::getUser();
        $aid		= $user->get('aid', 0);
        $contentConfig = &JComponentHelper::getParams( 'com_content' );
        $access		= !$contentConfig->get('show_noauth');

        $nullDate	= $db->getNullDate();
        $date =& JFactory::getDate();
        $now = $date->toMySQL();
        //lay keyword ma nguoi dung nhap bo vao trong cau query
        $keyword = $this->_getKeyword($metakey);

        $where	=' WHERE a.state = 1'. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
                . ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
                .' AND s.id > 0' .
                ($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
                ' AND s.published = 1' .
                ' AND cc.published = 1'.
                ' AND (a.metakey LIKE "%' . implode( '%" OR a.metakey LIKE "%' , $keyword ).'%" )'  ;

        return $where;
    }

    //function nay lay ra cate id ma nguoi dung nhap,co the nhieu hon 1
    private function _getCateId($catid) {
        if ($catid) {
            $ids = explode( ',', $catid );//cắt chuỗi, cứ thấy dấu phẩy là tách ra
            JArrayHelper::toInteger( $ids );//chuyển nguyên mảng $ids sang kiểu số int
            $catCondition = ' AND (cc.id=' . implode( ' OR cc.id=', $ids ) . ')';
        }
        return ($catid ? $catCondition : '');
    }
    //function nay lay keyword ma nguoi dung da nhap(co the nhieu hon 1)
    private function _getKeyword($metakey) {
        $keys = explode( ',', $metakey );
        $keywords = array();
        foreach ($keys as $key) {
            $key = trim( $key );

            if ($key) {
                $keywords[] = $key;
            }
        }
        return $keywords;

    }
    //function lay ra bai viet sap xep theo thu tu giam dan va co hit hay khong co hit
    private function _getOrdering($mostview) {
        // Ordering
        if($mostview == 1)
            $ordering = 'a.hits DESC';
        else
            $ordering = 'a.modified DESC, a.created DESC';

        return $ordering;
    }

    //function nay se tra ra row co 4 tham so la title,link,introtext,image
    private function _getDetails(&$db, $count) {
        $rows = $db->loadObjectList();
        $rc = count($rows);
        $counter = $count;
        //day la duong dan de hinh trong database
        //$image_path = $params->get( 'image_path', 'images/stories' );
        //$image_path  = MOD_BASEURL;
        $image_path  = 'images/stories';

        for($i = 0;$i < $rc; $i++) {
            //if($thumb_embed && $counter)
            if($counter) {
                $rows[$i] = $this->_extractImg($rows[$i], $image_path);
            }
            $counter--;

            //hien thi du'ng ca'c ky tu dac biet trong html
            $rows[$i]->title = htmlspecialchars($rows[$i]->title);
            $rows[$i]->link = JRoute::_(ContentHelperRoute::getArticleRoute($rows[$i]->slug,$rows[$i]->catslug,$rows[$i]->sectionid));
            $introtext = strip_tags($rows[$i]->introtext);
            //$rows[$i]->introtext = preg_replace("/{[^}]*}/","",$rows[$i]->introtext);
            $rows[$i]->introtext = $introtext;
            $rows[$i]->showmore = JRoute::_(ContentHelperRoute::getCategoryRoute($rows[$i]->catid,$rows[$i]->sectionid));
        }

        return $rows;
    }
    //function nay dung de lay ra cai where trong cau query(dua vao article id ma nguoi dung nhap vao)
    private function _getWhereContentByArticle(&$db,$articles) {
        $user		=& JFactory::getUser();
        $aid		= $user->get('aid', 0);
        $contentConfig = &JComponentHelper::getParams( 'com_content' );
        $access		= !$contentConfig->get('show_noauth');

        $nullDate	= $db->getNullDate();
        $date =& JFactory::getDate();
        $now = $date->toMySQL();
        //lay cate id ma nguoi dung nhap bo vao trong cau query
        $articleid = $this->_getArticleId($articles);

        $where	=' WHERE a.state = 1'. ' AND ( a.publish_up = '.$db->Quote($nullDate).' OR a.publish_up <= '.$db->Quote($now).' )'
                . ' AND ( a.publish_down = '.$db->Quote($nullDate).' OR a.publish_down >= '.$db->Quote($now).' )'
                .' AND s.id > 0' .
                ($access ? ' AND a.access <= ' .(int) $aid. ' AND cc.access <= ' .(int) $aid. ' AND s.access <= ' .(int) $aid : '').
                $articleid.
                ' AND s.published = 1' .
                ' AND cc.published = 1' ;

        return $where;
    }
    //function nay lay ra article id...tu article ma nguoi dung nhap vao(co the nhieu hon mot)
    private function _getArticleId($articles) {
        if ($articles) {
            $ids = explode( ',', $articles );
            JArrayHelper::toInteger( $ids );
            $articleCondition = ' AND (a.id=' . implode( ' OR a.id=', $ids ) . ')';
        }
        return ($articles ? $articleCondition : '');
    }


    private function _extractImg($row, $image_path) {
        //preg_match_all("/<img[^>]*>/Ui",$row->introtext.$row->fulltext,$txtimg);
        preg_match_all("/<img[^>]*>/Ui",$row->introtext . ' ' . $row->fulltext,$txtimg);
        if(!empty($txtimg[0])) {
            foreach ($txtimg[0] as $txtimgel) {

                //bo tag img trong phan intro text
                $row->introtext = str_replace($txtimgel,"",$row->introtext);

                //ne'u trong tag img na`y co' chu~ http
                if (preg_match_all("#http#",$txtimgel,$txtimgelsr,PREG_PATTERN_ORDER) > 0) {
                    preg_match_all("#src=\"([\-\/\_A-Za-z0-9\.\:]+)\"#",$txtimgel,$txtimgelsr);

                    //tri'ch ra duo`ng da~n de'n hi`nh va` the^m va`o $row->image
                    if(!empty($row->images)) {
                        $row->images = $txtimgelsr[1][0] . "\n" . $row->images;
                    }
                    else {
                        $row->images = $txtimgelsr[1][0];
                    }
                }
                elseif (strstr($txtimgel, $image_path)) {
                    //xe't xem duong da~n tren server la tuong do'i hay tuyet do'i
                    //de tri'ch ra duo`ng da~n de'n hi`nh chi'nh xa'c
                    if(strstr($txtimgel, 'scr="/')) {
                        preg_match_all("#src=\"\/" . addslashes($image_path) . "\/([\:\-\/\_\sA-Za-z0-9\.]+)\"#",$txtimgel,$txtimgelsr);
                    }
                    else {
                        preg_match_all("#src=\"" . addslashes($image_path) . "\/([\:\-\/\_\sA-Za-z0-9\.]+)\"#",$txtimgel,$txtimgelsr);
                    }

                    if(!empty($row->images)) {
                        $row->images = $txtimgelsr[1][0] . "\n" . $row->images;
                    }
                    else {
                        $row->images = $txtimgelsr[1][0];
                    }
                }
            }

        }

        return $row;
    }

    private function fptn_limittext($text,$allowed_tags,$limit) {
        $strip = strip_tags($text);
        $endText = (strlen($strip) > $limit) ? "&nbsp;&nbsp;...&nbsp;" : "";
        $strip = substr($strip, 0, $limit);
        $striptag = strip_tags($text, $allowed_tags);
        $lentag = strlen($striptag);

        $display = "";

        $x = 0;
        $ignore = true;
        for($n = 0; $n < $limit; $n++) {
            for($m = $x; $m < $lentag; $m++) {
                $x++;
                if($striptag[$m] == "<") {
                    $ignore = false;
                } else if($striptag[$m] == ">") {
                    $ignore = true;
                }
                if($ignore == true) {
                    if($strip[$n] != $striptag[$m]) {
                        $display .= $striptag[$m];
                    } else {
                        $display .= $strip[$n];
                        break;
                    }
                } else {
                    $display .= $striptag[$m];
                }
            }
        }
        return $this->fix_tags ('<p>'.$display.$endText.'</p>');
    }

    private function fix_tags($html) {
        $result = "";
        $tag_stack = array();

        // these corrections can simplify the regexp used to parse tags
        // remove whitespaces before '/' and between '/' and '>' in autoclosing tags
        $html = preg_replace("#\s*/\s*>#is","/>",$html);
        // remove whitespaces between '<', '/' and first tag letter in closing tags
        $html = preg_replace("#<\s*/\s*#is","</",$html);
        // remove whitespaces between '<' and first tag letter
        $html = preg_replace("#<\s+#is","<",$html);

        while (preg_match("#(.*?)(<([a-z\d]+)[^>]*/>|<([a-z\d]+)[^>]*(?<!/)>|</([a-z\d]+)[^>]*>)#is",$html,$matches)) {
            $result .= $matches[1];
            $html = substr($html, strlen($matches[0]));

            // Closing tag
            if (isset($matches[5])) {
                $tag = $matches[5];

                if ($tag == $tag_stack[0]) {
                    // Matched the last opening tag (normal state)
                    // Just pop opening tag from the stack
                    array_shift($tag_stack);
                    $result .= $matches[2];
                } elseif (array_search($tag, $tag_stack)) {
                    // We'll never should close 'table' tag such way, so let's check if any 'tables' found on the stack
                    $no_critical_tags = !array_search('table',$tag_stack);
                    if (!$no_critical_tags) {
                        $no_critical_tags = (array_search('table',$tag_stack) >= array_search($tag, $tag_stack));
                    };

                    if ($no_critical_tags) {
                        // Corresponding opening tag exist on the stack (somewhere deep)
                        // Note that we can forget about 0 value returned by array_search, becaus it is handled by previous 'if'

                        // Insert a set of closing tags for all non-matching tags
                        $i = 0;
                        while ($tag_stack[$i] != $tag) {
                            $result .= "</{$tag_stack[$i]}> ";
                            $i++;
                        };

                        // close current tag
                        $result .= "</{$tag_stack[$i]}> ";
                        // remove it from the stack
                        array_splice($tag_stack, $i, 1);
                        $no_reopen_tags = array("tr","td","table","marquee","body","html");
                        if (array_search($tag, $no_reopen_tags) === false) {
                            while ($i > 0) {
                                $i--;
                                $result .= "<{$tag_stack[$i]}> ";
                            };
                        } else {
                            array_splice($tag_stack, 0, $i);
                        };
                    };
                } else {
                    // No such tag found on the stack, just remove it (do nothing in out case, as we have to explicitly
                    // add things to result
                };
            } elseif (isset($matches[4])) {
                // Opening tag
                $tag = $matches[4];
                array_unshift($tag_stack, $tag);
                $result .= $matches[2];
            } else {
                // Autoclosing tag; do nothing specific
                $result .= $matches[2];
            };
        };

        // Close all tags left
        while (count($tag_stack) > 0) {
            $tag = array_shift($tag_stack);
            $result .= "</".$tag.">";
        }

        return $result;
    }

    //chon 1 duo`ng da~n de'n ba`i vie't
    //ne'u bai vie't ko co' hi`nh se~ la'y hi`nh cua category
    //ne'u ko co' hinh cua category thi ???
    //truyen vao images va image

    private function edp_getImage($images,$image) {
        $imgPath = '';

        if (!empty($images)) {
            //$imgPath = 'imageS';
            $imgPath = strtok($images,"|\r\n");
            //kiem tra xem hinh co' phai lay tu internet hay ko
            $pos = stripos($imgPath, 'http://');


            //neu hinh lay tu server thi ta them ten server o truoc duo`ng da~n de'n hinh
            if($pos == false) {
                $imgPath = '/images/stories/'.$imgPath;
            }
        }
        elseif (!empty($image)) {
            $imgPath = '/images/stories/'.$image;
        }
        else {
            $imgPath = '';
        }

        return $imgPath;
    }

    //ha`m na`y se~ echo ra
    //<a href=""> <img src="" size=""/></a>
    //noi ca'ch kha'c no' se~ viet ra doan code html de hien thi thumb nail
    //va nguoi dung co' the click vo thumbnail de doc duoc toan bo ba`i viet
    //luu y: doan <img .../> thuc cha't la` do ha`m thumb_size thuc hien
    public function vxg_showThumb($images,$image,$thumb_width, $thumb_height,$link) {
        //tao ra 1 <a> roi dua tag img vao trong tag a
        $imgTag = '<a href="'.$link.'">';

        $img = $this->edp_getImage($images,$image);

        $imgTag .= $this->edp_getTagImgThumbnail($img, $thumb_width, $thumb_height);
        //do'ng tag a
        $imgTag .= '</a>';

        return $imgTag;
    }

    //ham nay xua't ra tag <img src=".." width="..." height="..."/>
    private function edp_getTagImgThumbnail($file, $wdth, $hgth) {

        $size = 'width="'.$wdth.'" height="'.$hgth.'"';
        $image= '<img src="'.$file.'" '.$size.' alt=""/>';


        return $image;
    }
    function getReturnURL($params, $type) {
        if($itemid =  $params->get($type)) {
            $menu =& JSite::getMenu();
            $item = $menu->getItem($itemid);
            $url = JRoute::_($item->link.'&Itemid='.$itemid, false);
        }

        $url = '/index.php';
        return base64_encode($url);
    }

    function getType() {
        $user = & JFactory::getUser();
        return (!$user->get('guest')) ? 'logout' : 'login';
    }
    
    function getUserMosetContent($options) {
        $limit = $options['limit'];
        //connect database
        $db	=& JFactory::getDBO();
        //get user
        $user =& JFactory::getUser();
        //query
        $query = "SELECT l.*, c.cat_name, c.cat_id, img.filename AS link_image FROM (jos_mt_links AS l, jos_mt_cl AS cl, jos_mt_cats AS c)";
        $query .= " LEFT JOIN jos_mt_images AS img ON img.link_id = l.link_id AND img.ordering = 1";
        $query .= " LEFT JOIN jos_users AS u ON l.user_id = u.id";
        $query .= " WHERE link_published='1' && link_approved='1' && img.img_id>0";
        $query .= " AND l.link_id = cl.link_id";
        $query .= " AND c.cat_id = cl.cat_id";
        $query .= " AND ( publish_up = '0000-00-00 00:00:00' OR publish_up <= now())";
        $query .= " AND ( publish_down = '0000-00-00 00:00:00' OR publish_down >= now())";
        $query .= " AND cl.main = 1";
        $query .= " AND l.user_id = $user->id";
        //$query .= " AND l.user_id = 63";
        $query .= " ORDER BY link_created DESC LIMIT $limit";

        //retrieving results
        $db->setQuery($query);
        $rows = $db->loadObjectList();

        $list = array();
        $i=0;
        foreach ($rows as $row) {
            $list[$i]->title = $row->link_name;
            $list[$i]->link = sefRelToAbs("index.php?option=com_mtree&task=viewlink&link_id=$row->link_id&Itemid=54"); //oh wtf i'm being lazy
            $i++;
        }
        return $list;
    }
     function make_safe($string) {
        $string = preg_replace('#<!\[CDATA\[.*?\]\]>#s', '', $string);
        $string = strip_tags($string);
        // The next line requires PHP 5.2.3, unfortunately.
        //$string = htmlentities($string, ENT_QUOTES, 'UTF-8', false);
        // Instead, use this set of replacements in older versions of PHP.
        $string = str_replace('<', '&lt;', $string);
        $string = str_replace('>', '&gt;', $string);
        $string = str_replace('(', '&#40;', $string);
        $string = str_replace(')', '&#41;', $string);
        $string = str_replace('"', '&quot;', $string);
        $string = str_replace('\'', '&#039;', $string);
        return $string;
    }
    function getRSSImage($string,$minDimension=80,$maxDimension=140) {
        // find images
        $regex = "#<img.+?>#s";
        if (preg_match_all($regex, $string, $matches, PREG_PATTERN_ORDER) > 0) {
            $img = array();
            $img['tag'] = $matches[0][0];
            $srcPattern = "#src=\".+?\"#s";
            // grab the src of the first image
            if(preg_match($srcPattern,$matches[0][0],$match)) {
                $img['src'] = str_replace('src="','',$match[0]);
                $img['src'] = str_replace('"','',$img['src']);
                list($img['width'], $img['height'], $img['type'], $img['attr']) = @ getimagesize($img['src']);
                return $img;
            }
        }
    }
    function getRSSContent($option) {
        $url = $option['url'];
        isset($option['timeout']) ? $timeout = $option['timeout'] : $timeout = 5;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,$url);
        curl_setopt ($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $timeout );
        curl_setopt( $ch, CURLOPT_TIMEOUT, $timeout);
        $xml = curl_exec($ch);
        curl_close($ch);
        require_once 'xml_regex.php';

        // An RSS 2.0 feed must have a channel title, and it will
        // come before the news items. So it's safe to grab the
        // first title element and assume that it's the channel
        // title.
        $channel_title = value_in('title', $xml);
        // An RSS 2.0 feed must also have a link element that
        // points to the site that the feed came from.
        $channel_link = value_in('link', $xml);

        // Create an array of item elements from the XML feed.
        $news_items = element_set('item', $xml);
        foreach($news_items as $item) {
            $title = value_in('title', $item);
            $url = value_in('link', $item);
            $description = value_in('description', $item);
            $timestamp = strtotime(value_in('pubDate', $item));
            $img = $this->getRSSImage($description);
            $item_array[] = array(
                    'title' => $title,
                    'url' => $url,
                    'description' => $description,
                    'timestamp' => $timestamp,
                    'image' =>$img['src']
            );
        }
        return $item_array;
    }
}
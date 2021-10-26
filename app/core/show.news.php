<?php
/**
 * @package Show
 * @access private
 */

if ($allow_full_story){
    $query = !empty($post)? [$post]: $sql->select(['news', 'join' => ['story', 'id'], 'where' => ["id = $id", 'or', "url = $id"]]);
   
} else {

    $where = [];
    $where = run_filters('news-where', $where);

    if (!cute_get_rights('edit_all') or !cute_get_rights('delete_all')){
        $where[] = 'hidden = 0';
		$where[] = 'and';
    }

    if ( isset($author) ){
		$where[] = 'author = '.($author ? $author : $user);
		$where[] = 'and';
    }

    if ($year and !$month){
	    $where[] = 'date > '.@mktime(0, 0, 0, 1, 1, $year);
	    $where[] = 'and';
	    $where[] = 'date < '.@mktime(23, 59, 59, 12, 31, $year);
    } elseif ($year and $month and !$day){
	    $where[] = 'date > '.@mktime(0, 0, 0, $month, 1, $year);
	    $where[] = 'and';
	    $where[] = 'date < '.@mktime(23, 59, 59, $month, 31, $year);
    } elseif ($year and $month and $day){
	    $where[] = 'date > '.@mktime(0, 0, 0, $month, $day, $year);
	    $where[] = 'and';
	    $where[] = 'date < '.@mktime(23, 59, 59, $month, $day, $year);
    } else {
	    $where[] = 'date < '.time;
    }

    if (!empty($category)) {


	    if (in_array('0', explode(',', $category))){
	    	$where[] = 'and';
            $where[] = 'FIND_IN_SET('.$category.', category)';
	    	//$where[]  = '(category ? ['.str_replace([',', '0|', '|0'], ['|', '', ''], $category).']';
	    	$where[] = 'or';
	    	$where[] = 'category = )';
		} else {
	    	$where[] = 'and';
            $where[] = 'FIND_IN_SET('.$category.', category)';
            //$where[]  = 'category ? ['.str_replace([',', '0|', '|0'], ['|', '', ''], $category).']';
		}
	}

	$query = $sql->select(['news',
		'select'  => ['date', 'author', 'title', 'image', 'category', 'url', 'views', 'comments', 'tags', 'id', 'type', 'short', 'votes', 'attach', 'english'],
		'join'    => ['story', 'id'],
		'where'   => $where,
		'orderby' => [['sticky', 'DESC'], $sort],
		'limit'   => [($skip ? $skip : 0), $number]]);
}

if ( empty($query) ) {	
	
	if ( $allow_full_story ){
		$allow_comment_form = false;
		$allow_comments = false;  
		header('HTTP/1.1 404 Not Found');
        exit;		
	}
}

$count = $sql->count(['news', 'select' => ['id'], 'where' => $where]);
$users = $sql->UsersByPostIDs($query);
 
foreach ($query as $row){

	$tpl['post']      = $row;
	$tpl['post']['_'] = $row;

    if (!in_array(basename($PHP_SELF), run_filters('unset-template', array())) and !$static and ($categories[$category]['template'] or $categories[$row['category']]['template'])){
        
		if ($categories[$category]['template']){
            $tpl['template'] = $categories[$category]['template'];
        } elseif ($categories[$row['category']]['template']){
            $tpl['template'] = $categories[$row['category']]['template'];
        }
		
    } else {
        $tpl['template'] = $template;
    }

    if (($categories[$category]['usergroups'] and !in_array($member['usergroup'], explode(',', $categories[$category]['usergroups']))) or ($categories[$row['category']]['usergroups'] and !in_array($member['usergroup'], explode(',', $categories[$row['category']]['usergroups'])))){
    	$count--;
    	continue;
    }

    $row['full'] = explode('<!--nextpage-->', $row['full']);
    $page_count  = sizeof($row['full']);
    $row['full'] = $row['full'][($page ? ($page - 1) : 0)];
    $pages       = [] ;

    if ($page_count > 1) {
        for ($i = 1; $i < $page_count + 1; $i++){
            if (($page and $page == $i) or ($allow_full_story and !$page and $i == 1)){
                $pages[] = '<b>'.$i.'</b>';
            } else {
                $pages[] = '<a href="'.cute_get_link(array_merge($row, ['page' => $i]), 'page').'">'.$i.'</a>';
            }
        }
    }

    if ($config['date_header'] and $dateheader  != langdate($config['date_headerformat'], $row['date'])){
        $tpl['post']['dateheader'] = $dateheader = langdate($config['date_headerformat'], $row['date']);
    } else {
        $tpl['post']['dateheader'] = '';
    }

    if ($cat_arr = explode(',', $row['category'])){
        $cat = [];

        foreach ($cat_arr as $v){
            $cat['id'][]   = $v;
            $cat['url'][]  = $categories[$v]['url'];
            $cat['name'][] = $categories[$v]['name'] ? '<a href="'.cute_get_link($categories[$v], 'category').'" title="'.replace_news('admin', $categories[$v]['name']).'">'.$categories[$v]['name'].'</a>' : '';
            $cat['icon'][] = $categories[$v]['icon'] ? '<a href="'.cute_get_link($categories[$v], 'category').'"><img src="'.$categories[$v]['icon'].'" border="0" align="absmiddle" alt=""></a>' : '';
            $cat['desc'][] = $categories[$v]['desc'];
        }
    }
    
    if ($is_logged_in){
        if (cute_get_rights('edit_all') or cute_get_rights('delete_all') or ((cute_get_rights('edit') or cute_get_rights('delete')) and $member['username'] == $row['author'])){
            $tpl['post']['if-right-have'] = true;
        } else {
            $tpl['post']['if-right-have'] = false;
        }

        $tpl['if-logged'] = true;
    } else {
        $tpl['if-logged'] = false;
    }

    if ($users[$row['author']]['mail'] and !$users[$row['author']]['hide_mail']){
        $tpl['comment']['mail'] = $users[$row['author']]['mail'];
    }

    foreach (($rufus_file ? $rufus_file : parse_ini_file(rufus_file, true)) as $type_k => $type_v){
        if (is_array($type_v)){
            foreach ($type_v as $k => $v){
                if ($type_k == 'home'){
                    $tpl['post']['link'][$k] = cute_get_link($row, $k);
                }
                    $tpl['post']['link'][$type_k.'/'.$k] = cute_get_link($row, $k, $type_k);
            }
        }
    }

    $tpl['post']['description'] = $row['description'] ? run_filters('news-entry-content', $row['description']) : run_filters('news-entry-content', $row['title']);
    $tpl['post']['avatar']      = $users[$row['author']]['avatar'] ? $config['path_userpic_upload'].'/'.$row['author'].'.'.$users[$row['author']]['avatar'] : '';
  
    
    $row['image'] = $row['image'] ? $row['image']: 'default.png';

    $tpl['post']['image'] = $cute->getPostIcon($row);

    /*if (strpos($row['image'], 'https://')!== false or strpos($row['image'], 'http://')!== false)
    {
        $tpl['post']['image'] = $row['image'];
    } else {
        //$tpl['post']['image'] = $config['path_image_upload'].'/posts/'.$row['image'];
        $tpl['post']['image'] = UPIMAGE.'/posts/'.$row['image'];
    }*/
    
    $tpl['post']['icon']        = $row['image'] ? $config['path_image_upload'].(file_exists(UPLOADS.'/thumbs/'.$row['image']) ? '/thumbs/' : '/').$row['image'] : '';
    $tpl['post']['lj-username'] = '<a href="http://'.$users[$row['author']]['lj_username'].'.livejournal.com/profile"><img src="'.$config['http_script_dir'].'/skins/images/user.gif" alt="[info]" align="absmiddle" border="0"></a><a href="http://'.$users[$row['author']]['lj_username'].'.livejournal.com">'.$users[$row['author']]['lj_username'].'</a>';
    $tpl['post']['author']      = $users[$row['author']]['name'];
	$tpl['post']['user']        = cute_get_link($users[$row['author']], 'author'); //$users[$row['author']];
    $tpl['post']['username']    = $users[$row['author']]['username'];
    $tpl['post']['user-id']     = $users[$row['author']]['id'];

    $tpl['post']['title']       = run_filters('news-entry-content', $row['title']);
//$tpl['post']['date']        = langdate($config['timestamp_active'], $row['date']);

    $tpl['post']['date'] = defined('CUTEDATE') ? cuteDate($row) : langdate($config['timestamp_active'], $row['date']);
    
    $tpl['post']['votes']       = $row['votes'];
    $tpl['post']['category']    = [
		   'id'   => join(', ', $cat['id']), 
		   'url'  => join(', ', $cat['url']), 
		   'icon' => join(', ', $cat['icon']),
		   'name' => join(', ', $cat['name']),
		   'desc' => join(', ', $cat['desc'])
    ];
	
    $tpl['post']['attach']     = $row['attach'] ?: '';	
	$tpl['post']['english']     = run_filters('news-entry-content', $row['english']);
	
	$tpl['post']['short-story'] = run_filters('news-entry-content', $row['short']);
    $tpl['post']['full-story']  = run_filters('news-entry-content', $row['full']);
    $tpl['post']['pages']       = join(' ', $pages);
    $tpl['post']['alternating'] = cute_that('cn_news_odd', 'cn_news_even');
    $tpl['post']                = run_filters('news-show-generic', $tpl['post']);

    ob_start();
    include templates_directory.DS.$tpl['template'].DS.($allow_full_story ? 'full' : 'active').'.tpl';
    $output = ob_get_clean();
    $output = run_filters('news-entry', $output);
    $output = replace_news('show', $output);
    
    $output = str_replace('$[post:title]', $row['title'], $output) ;
	$output = str_replace('$[config:email]', $config['site_mail'], $output);
    $output = str_replace('$[config:phone]', $config['site_phone'], $output);

    $output = preg_replace_callback('#\$\[funct:post\((.*?)\)\]#i', function($m) use ($config) {
        return isset($_GET['id']) ? (new Post($config))->show($m[1]) : false;
    }, $output);


    echo $output;

    if ( isset($post['id']) and isset($post['views']) and empty($page) and $allow_full_story ) 
    {
        if ( !isset($_SESSION['post'][$post['id']]) )
        {
            $_SESSION['post'] = [];
            $_SESSION['post'][$post['id']] = $post['views'] + 1;
            $sql->update(['news', 'where'  => $post['id'], 'values' => ['views' => $_SESSION['post'][$post['id']]]]);
        }
    }
}

// << Previous & Next >>
$prev_next_msg = $template_prev_next;

//----------------------------------
// Previous link
//----------------------------------
if ($skip){
    $tpl['prev-next']['prev-link'] = cute_get_link(['skip' => ($skip - $number)], 'skip');
} else {
    $tpl['prev-next']['prev-link'] = '';
    $no_prev = true;
}

//----------------------------------
// Pages
//----------------------------------
if ($number){
	$count         = ($allow_full_story ? 0 : $count);
	$pages_count   = @ceil($count / $number);
	$pages_skip    = 0;
	$pages         = [];
	$pages_section = (int)$config['pages_section'];
	$pages_break   = (int)$config['pages_break'];

    if ($pages_break and $pages_count > $pages_break){
        for ($j = 1; $j <= $pages_section; $j++){
            if ($pages_skip != $skip){
            	$pages[] = '<a href="'.cute_get_link(['skip' => $pages_skip], 'skip').'">'.$j.'</a>';
            } else {
            	$pages[] = '<b>'.$j.'</b>';
            }

            $pages_skip += $number;
        }

        if (((($skip / $number) + 1) > 1) and ((($skip / $number) + 1) < $pages_count)){
            $pages[]   = ((($skip / $number) + 1) > ($pages_section + 2)) ? '...' : '';
            $page_min  = ((($skip / $number) + 1) > ($pages_section + 1)) ? ($skip / $number) : ($pages_section + 1);
            $page_max  = ((($skip / $number) + 1) < ($pages_count - ($pages_section + 1))) ? (($skip / $number) + 1) : $pages_count - ($pages_section + 1);
            $pages_skip = ($page_min - 1) * $number;

            for ($j = $page_min; $j < $page_max + ($pages_section - 1); $j++){
                if ($pages_skip != $skip){
                	$pages[] = '<a href="'.cute_get_link(['skip' => $pages_skip], 'skip').'">'.$j.'</a>';
                } else {
                	$pages[] = '<b>'.$j.'</b>';
                }

                $pages_skip += $number;
            }

            $pages[] = ((($skip / $number) + 1) < $pages_count - ($pages_section + 1)) ? '...' : '';
        } else {
        	$pages[] = '...';
        }

        $pages_skip = ($pages_count - $pages_section) * $number;

        for ($j = ($pages_count - ($pages_section - 1)); $j <= $pages_count; $j++){
            if ($pages_skip != $skip){
            	$pages[] = '<a href="'.cute_get_link(['skip' => $pages_skip], 'skip').'">'.$j.'</a>';
            } else {
            	$pages[] = '<a>'.$j.'</a>';
            }

            $pages_skip += $number;
        }
		
    } else {
         for ($j = 1; $j <= $pages_count; $j++){
            if ($pages_skip != $skip){
            	$pages[] = '<a href="'.cute_get_link(['skip' => $pages_skip], 'skip').'">'.$j.'</a>';
            } else {
            	$pages[] = '<a>'.$j.'</a>';
            }

            $pages_skip += $number;
        }
    }

    $tpl['prev-next']['pages']        = '<li>' .join('</li><li> ', $pages). '</li>';
    $tpl['prev-next']['current-page'] = (($skip + $number) / $number);
    $tpl['prev-next']['total-pages']  = $pages_count;
}

//----------------------------------
// Next link
//----------------------------------
if ($skip + $number < $count){
    $tpl['prev-next']['next-link'] = cute_get_link(['skip' => ($skip + $number)], 'skip');
} else {
    $tpl['prev-next']['next-link'] = '';
    $no_next = true;
}

if ( empty($no_prev) or empty($no_next) ) {
	ob_start();
	include templates_directory.'/'.$tpl['template'].'/prev_next.tpl';
	$pagenation = ob_get_clean();
	echo $pagenation;
}

?>
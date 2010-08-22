<?php
/*
# ------------------------------------------------------------------------
# JA Comments component for Joomla 1.5
# ------------------------------------------------------------------------
# Copyright (C) 2004-2010 JoomlArt.com. All Rights Reserved.
# @license - PHP files are GNU/GPL V2. CSS / JS are Copyrighted Commercial,
# bound by Proprietary License of JoomlArt. For details on licensing, 
# Please Read Terms of Use at http://www.joomlart.com/terms_of_use.html.
# Author: JoomlArt.com
# Websites:  http://www.joomlart.com -  http://www.joomlancers.com
# Redistribution, Modification or Re-licensing of this file in part of full, 
# is bound by the License applied. 
# ------------------------------------------------------------------------
*/

defined ( '_JEXEC' ) or die ( 'Restricted access' );

jimport ( 'joomla.application.component.view' );
/*
 * 
 */
class JACommentViewComments extends JView {
	/**	
	 * Display the view
	 */
	function display($tmpl = null) {
	   
		global $mainframe;		
		switch ($this->getLayout ()) {
			case 'preview' :
				$this->showScript ();
				break;                
            case 'showchild' :
            	$this->setLayout('default');
            	$this->showChilds();
            	break;
            case 'paging' :
            	$this->setLayout('default');            	
            	$this->pagingData();
            	break;
            case 'sort' :
            	$this->setLayout('default');
            	$this->sortComment();
            	break;
            case 'button':
                $this->showLinks();
                break; 
            case 'youtube':
                $this->showYouTube();
                break;
            case 'attach_file':
                $this->showAttachFile();
                break;            
            case 'showformedit':
            	$this->setLayout('default');
                $this->showFormEdit();
                break;
            case 'showreply':
            	$this->setLayout('default');
                $this->showFormReply();
                break;    
            case 'changetype':
            	$this->setLayout('default');
                $this->changeType();
                break;                                     	                    
			default :				               
				$this->displayItems ();		
		}
					
		parent::display ( $tmpl );
	}			
		
	function showFormReply(){
		global $jacconfig;
		$currentUserInfo = JFactory::getUser ();
		$object = array ();
		$k = 0;
		$message = '<script type="text/javascript">jacdisplaymessage();</script>';
		if($jacconfig['permissions']->get('post', 'all') == "member" && $currentUserInfo->guest){			
			$object [$k] = new stdClass ( );
			$object [$k]->id = '#jac-msg-succesfull';
			$object [$k]->type = 'html';
			$object [$k]->content = JText::_("You must login to post comment").$message;						
			$k++;				
		}else{			
			$currentTotal = JRequest::getInt("currenttotal", 0);
			if($currentTotal >= $jacconfig["comments"]->get("maximum_comment_in_item", 1)){
				$object [$k] = new stdClass ( );
				$object [$k]->id = '#jac-msg-succesfull';
				$object [$k]->type = 'html';
				$object [$k]->content = JText::_("You can't post new comment because the comment in this article is full.").$message;						
				$k++;								
			}else{							
				$this->assign ( 'id', JRequest::getInt("id",0) );		
				$this->assign('replyto', JRequest::getVar("replyto",''));			
				
				$message = '<script type="text/javascript">actionBeforEditReply("'. JRequest::getInt("id",0) .'", "'. JText::_("Reply") .'", "reply", "'. JText::_("Posting") .'");jacChangeDisplay("jac-result-reply-comment-'.JRequest::getInt("id",0) .'", "block")</script>';
				
				$object [$k] = new stdClass ( );
				$object [$k]->id 		= '#jac-result-reply-comment-'.JRequest::getInt("id",0);
				$object [$k]->type  	= 'html';				
				$object [$k]->content 	= $this->loadTemplate('reply').$message;
				$k++;	
			}							
		}
		$helper = new JACommentHelpers ( );
		$data = "({'data':[";
		$data .= $helper->parse_JSON ( $object );
		$data .= "]})";
		echo $data;
		exit ();
		
	}
	
	function showComment($item){
		global $jacconfig;						 
		$isReply =  JRequest::getVar ( "isreply",0);
		
		$item = $this->assignItems($item);				        		
       	$this->assign("isreply", $isReply);		
		$this->assign ( 'items', $item );
		if($isReply)						
		$this->assign ( 'ischild', '1' );
		
		return  $this->loadTemplate('comments');		
	}
	
	function showFormEdit(){
		global $jacconfig;
		$model = $this->getModel();
		$helper = new JACommentHelpers ( );
		
		$id = JRequest::getInt("id",0);						
		$item = $model->getItem($id);	
							
		$this->assign("item", $item);
		$this->assign("id", $id);						
		$this->assign("fucSmiley", "jacInsertSmileyEdit");
		$theme	 = $jacconfig["layout"]->get("theme", "default");
		$session = &JFactory::getSession();
		if(JRequest::getVar("jacomment_theme", '')){
			jimport( 'joomla.filesystem.folder' );
			$themeURL = JRequest::getVar("jacomment_theme");
			if(JFolder::exists('components/com_jacomment/themes/'.$themeURL)){
				$theme =  $themeURL;						
			}
			$session->set('jacomment_theme', $theme);			
		}else{
			if($session->get('jacomment_theme', null)){
				$theme = $session->get('jacomment_theme', $theme);
			}
		}
		$this->assign("theme", $theme);
		
		$this->assign("isAttachImage", $jacconfig["comments"]->get("is_attach_image", 0));	
		$this->assign("enableAfterTheDeadline", $jacconfig['layout']->get('enable_after_the_deadline', 0));
		$this->assign("enableBbcode", $jacconfig['layout']->get('enable_bbcode', 0));
		$this->assign("isEnableEmailSubscription", $jacconfig['comments']->get('is_enable_email_subscription', 1));
		$this->assign("enableSmileys", $jacconfig['layout']->get('enable_smileys', 0));
		$this->assign("totalAttachFile", $jacconfig['comments']->get('total_attach_file', 5));
		$this->assign("enableYoutube", $jacconfig['layout']->get('enable_youtube', 1));
		$this->assign("isEnableAutoexpanding", $jacconfig['comments']->get('is_enable_autoexpanding', 0));		
		$this->assign("maxLength", $jacconfig['spamfilters']->get('max_length', 0));				 										
		
		$object = array ();
		$k = 0;
		$object [$k] = new stdClass ( );
		$object [$k]->id 		= '#jac-edit-comment-'.$id;
		$object [$k]->type  	= 'html';		
		ob_start ();
		require $helper->jaLoadBlock("comments/edit.php");
		$content = ob_get_contents ();
		ob_end_clean ();			
		$object [$k]->content 	= $content.'<script type="text/javascript">$("newcommentedit").focus();jacChangeDisplay("jac-edit-comment-'.$id.'","block")</script>';
		$k++;				
				
		$data = "({'data':[";
		$data .= $helper->parse_JSON ( $object );
		$data .= "]})";
		echo $data;
		exit ();
	}	
		
	
	function sortComment(){	
		global $jacconfig;				
		$limit 		= JRequest::getInt ( 'limit', '10' );
		$limitstart = JRequest::getInt ( 'limitstart', '0' );
					
		$wherejatotalcomment = "";
		$wherejacomment		 = "";
		
		$this->buildWhereComment($wherejatotalcomment, $wherejacomment);                   
        		
		$object = array ();
		$k = 0;
				
		$object [$k] = new stdClass ( );
		$object [$k]->id = '#jac-container-comment';
		$object [$k]->type = 'html';				
		$object [$k]->content = $this->loadContentChangeData($wherejatotalcomment, $wherejacomment , $limit, $limitstart, 'sort');
		$k++;						
		
		$helper = new JACommentHelpers ( );
		
		$data = "({'data':[";
		$data .= $helper->parse_JSON ( $object );
		$data .= "]})";
		echo $data;
		exit ();
	}
	
    function showLinks(){
        //$model = & JModel::getInstance ( 'addons', 'JACommentModel' );
        require_once (JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'models'.DS.'addons.php');
        $model = new JACommentModelAddons();
        $paramsArrays = $model->getScript ();                             
        $this->assign("paramsArray", $paramsArrays);
        
        //$QUERY_STRING = $_SERVER['QUERY_STRING'];
        //$arr = explode('links=', $QUERY_STRING);
        //$links = $arr[1];
        $links = JRequest::getVar( 'links' );

        
        $this->assign ( 'links', $links); 
        return $links;
    }    	
	
	/*
     * perform when user click page number, list limit or search comment
     */
    function pagingData(){
    	$model = & $this->getModel ();
    					
		$limitstart = JRequest::getVar ( 'limitstart', '0' );			
		$limit = JRequest::getVar ( 'limit', '10' );										
		$wherejatotalcomment = "";
        $wherejacomment		 = "";
		
        $this->buildWhereComment($wherejatotalcomment, $wherejacomment);
        
		$object = array ();
		$k = 0;
				
		$object [$k] = new stdClass ( );
		$object [$k]->id = '#jac-container-comment';
		$object [$k]->type = 'html';				
		$object [$k]->content = $this->loadContentChangeData($wherejatotalcomment, $wherejacomment, $limit, $limitstart, 'paging');
		$k++;					

		$this->getObjectPaging($object, $k);
		 
		$helper = new JACommentHelpers ( );
		
		$data = "({'data':[";
		$data .= $helper->parse_JSON ( $object );
		$data .= "]})";
		echo $data;
		exit ();
    }
    
    function getObjectPaging(&$object, &$k){
    	$lists = array();
		$this->getPaging($lists);
		$helper = new JACommentHelpers ( );
		
		$object [$k] = new stdClass ( );
		$object [$k]->id = '#jac-pagination';
		$object [$k]->type = 'html';				
		ob_start ();
		require $helper->jaLoadBlock("comments/paging.php");
		$content = ob_get_contents ();
		ob_end_clean ();		
		$object [$k]->content = $content;
    }
    
	
	function showChilds(){		
		$parentID = JRequest::getVar ( 'parentid', 0 );
		$wherejatotalcomment = "";
        $wherejacomment		 = "";
        
        $this->buildWhereComment($wherejatotalcomment, $wherejacomment);		        		        		
		
		$k = 0;
		$object = array ();
		$object [$k] = new stdClass ( );
		$object [$k]->id = '#childen-comment-of-'.$parentID;
		$object [$k]->type = 'html';
		$object [$k]->content = $this->loadContentChangeData($wherejatotalcomment, $wherejacomment, '', '', 'getChilds');
		$k++;
		
		$helper = new JACommentHelpers ( );
		
		$data = "({'data':[";
		$data .= $helper->parse_JSON ( $object );
		$data .= "]})";
		echo $data;
		
		exit ();
	}
	
	/*
	 * when action is complete - load data again
	 */	
	function loadContentChangeData($searchTotal='', $search='', $limit=10, $limitstart=0, $action='', $commentID = 0){
		global $jacconfig;						            	                      				                                 		
			
        $orderBy  	= JRequest::getVar ( 'orderby', '' );
        if(JRequest::getVar ( 'typeorderby', '' )){
			$orderBy.=" ".JRequest::getVar ( 'typeorderby', '' );        	
        }        
        $model = & $this->getModel ();
        $itemAll = array();  
        if($action == "getChilds"){           	
			$indexOf		= strpos($search,"AND c.parentid =");			
			$searchAll 		= substr($search,0, $indexOf);			
			$itemAll		= $model->getItemsFrontEnd($searchAll, "all", $limitstart, $orderBy);																	
        	$items 			= $model->getChildItems($search, $limit, $limitstart, '','','', $commentID);        	
        	$this->assign ( 'ischild', 1 );          	     	    
        }else{        	  	            	            	
			$searchAll 		= str_replace("AND c.parentid = 0","",$search);
			$itemAll		= $model->getItemsFrontEnd($searchAll, "all", $limitstart, $orderBy);			
        	$items 			= $model->getItemsFrontEnd($search, $limit, $limitstart, $orderBy);
        }	        
        
		//$lists['limitstart'] 	= $limitstart;
		//$lists['limit'] 		= $limit;			
		$lists['order'] 		= $orderBy;						        
       	$this->assignRef('lists', $lists);					
		
       	$items =  $this->assignItems($items, $itemAll);
		$this->assign ( 'items', $items );
												
		return  $this->loadTemplate('comments');		
	}
				
	/*
	 * 
	 */
    function getVarFromUrl($url)
    {
        $str = explode("&", $url);
        foreach($str as $k => $v){
            $str_arr[] = explode("=", $v);   
        }
        foreach($str_arr as $key => $val){
            $arr[$val[0]] = $val[1];   
        }
        return $arr;
    }
    
	function buildWhereComment(&$wherejatotalcomment, &$wherejacomment){
		$helper = new JACommentHelpers ( );
		$contentOption 	= JRequest::getVar ( 'contentoption', '');		
		$contentID 	   	= JRequest::getInt ( 'contentid', 0);
		$commentType   	= JRequest::getInt ( 'commenttype', 1);
		$parentID   	= JRequest::getInt ( 'parentid', 0);
				
		$wherejatotalcomment 	 = " AND c.option= '".$contentOption."'";
		
		//check user is specialUser
        $isSpecialUser = $helper->isSpecialUser();                
        //get aproved comment if user isn't special User
        if(!$isSpecialUser){                	 
        	$wherejatotalcomment 	.= " AND c.type = ".$commentType;
        }
		$wherejatotalcomment 	.= " AND c.contentid= '".$contentID."'";
						
		$wherejacomment 		 = $wherejatotalcomment;
		$wherejacomment 		.= " AND c.parentid = ".$parentID."";
	}
    
	function displayItems() {
		global $option, $mainframe, $jacconfig;
		$helper = new JACommentHelpers ( );											            	                      
		$task 	= JRequest::getCmd('task');				
		$model = & $this->getModel ();	
    
		if($task == 'edit'){			
			$cid = JRequest::getVar( 'cid', array(0), '', '' );			
			$item = $model->getItem ($cid[0]);						
			$this->assign ( 'item', $item );
		}else{			
			//check user is specialUser
            $isSpecialUser = $helper->isSpecialUser();            
            if($task == 'preview') {	            
                $limit = JRequest::getInt ( 'limit', 10 );
                $limitstart = JRequest::getInt ( 'limitstart', 0 );             	                
                $search = '';	
                                
                $opt = 'com_content';
				$contentid = 45;          	               
                                       
                $lists['contentoption'] = $opt;
                $search .= ' AND c.option="'.$opt.'"';

                $lists['contentid'] = $contentid;
                $search .= ' AND c.contentid='.$contentid.'';

                // -- add by congtq 23/11/2009 
                $lists['parentid']          = 0;
                $lists['commenttype']       = 1;                                                            
                                                                                
                $orderBy       = $jacconfig['layout']->get('default_sort', 'date');                
                $orderBy .= " " . $jacconfig['layout']->get('default_sort_type', 'ASC');            
                
                $lists['searchtotal']      = $search;
                $totalType                 = $model->getTotalByType($search);        
                if($totalType)       
                    $totalAll                 = (int)array_sum($totalType);
                else 
                    $totalAll                 = 0;
                
                $search .= ' AND parentid = 0';
                $lists['search'] = $search;                                                    
                //$lists['limitstart'] = $limitstart;
                //$lists['limit'] = $limit;            
                $lists['order'] = "";                            
                $this->assignRef('lists', $lists);
                           
                $this->assign ( 'totalAll', $totalAll );                                                                                
                // ++ add by congtq 25/11/2009 
                //print_r($jacconfig);
                
                $custom_addthis = '';
                if(JRequest::getInt('enable_addthis')==1){
                    $custom_addthis = $jacconfig['layout']->get('custom_addthis');
                }
                $this->assign ( 'custom_addthis', $custom_addthis);    
                
                $custom_addtoany = '';
                if(JRequest::getInt('enable_addtoany')==1){
                    $custom_addtoany = $jacconfig['layout']->get('custom_addtoany');
                }    
                $this->assign ( 'custom_addtoany', $custom_addtoany);                                    
                
                $custom_tweetmeme = '';
                if(JRequest::getInt('enable_tweetmeme')==1){
                    $custom_tweetmeme = $jacconfig['layout']->get('custom_tweetmeme');
                }                
                $this->assign ( 'custom_tweetmeme', $custom_tweetmeme);
                
				$this->assign ( 'preview_enable_youtube', JRequest::getInt('enable_youtube',$jacconfig["layout"]->get("enable_youtube", 1)));				                
				
				$this->assign ( 'preview_enable_bbcode', JRequest::getInt('enable_bbcode', $jacconfig["layout"]->get("enable_bbcode", 1)));				                
				
				$this->assign ( 'preview_enable_after_the_deadline', JRequest::getInt('enable_after_the_deadline', $jacconfig["layout"]->get("enable_after_the_deadline", 0)));				                				
				
				$this->assign ( 'preview_enable_smileys', JRequest::getInt('enable_smileys', $jacconfig["layout"]->get("enable_smileys", 0)));				                				                                                               
           	}else{            		       										                                            
                $search = '';
                
                $lists['contentoption'] = JRequest::getVar('contentoption', ''); 
                if($lists['contentoption']){
                    $search .= " AND c.option='{$lists['contentoption']}'";     
                }                				
				
                $lists['contentid'] = JRequest::getInt('contentid', 0);
                if($lists['contentid']){
                    $search .= ' AND c.contentid='.(int)$lists['contentid'].'';    
                }

			    $lists['parentid'] 		   = 0;
			    $lists['commenttype']	   = 1;	
									    			    																			    
			    $orderBy  	  = $jacconfig['layout']->get('default_sort', 'date');			    
			    $orderBy     .= " " . $jacconfig['layout']->get('default_sort_type', 'ASC');									    			    
                
                //get aproved comment if user isn't special User
                if(!$isSpecialUser){                	 
                	$search .= ' AND type=1';
                }
                
			    $lists['searchtotal'] 	= $search;
			    $totalType 				= $model->getTotalByType($search);        
			    if($totalType)       
				    $totalAll         		= (int)array_sum($totalType);
			    else 
				    $totalAll         		= 0;
	            
	            $search .= ' AND parentid = 0';
	            $lists['search'] = $search;	        		                
	            	            
				$limit = JRequest::getInt ( 'limit', 10 );
			    $limitstart = JRequest::getInt ( 'limitstart', 0 );			
			    $lists['order'] = "";					        
	       	    $this->assignRef('lists', $lists);			       	    
	            $this->assign ( 'totalAll', $totalAll );                				    			                    
                // ++ add by congtq 25/11/2009                               
//                $custom_addthis = '';
//                if($jacconfig['layout']->get('enable_addthis')==1){
//                    $custom_addthis = $jacconfig['layout']->get('custom_addthis');
//                }
//                $this->assign ( 'custom_addthis', $custom_addthis);    
//
//                $custom_addtoany = '';
//                if($jacconfig['layout']->get('enable_addtoany')==1){
//                    $custom_addtoany = $jacconfig['layout']->get('custom_addtoany');
//                }	
//                $this->assign ( 'custom_addtoany', $custom_addtoany);                                     
//                
//                $custom_tweetmeme = '';
//                if($jacconfig['layout']->get('enable_tweetmeme')==1){
//                    $custom_tweetmeme = $jacconfig['layout']->get('custom_tweetmeme');
//                }
//                $this->assign ( 'custom_tweetmeme', $custom_tweetmeme);
            }                                  		           
	        //get Rss Link
	        $linkRss = 'index.php?option=com_jacomment&amp;view=jafeeds&amp;layout=rss&amp;contentid='.$lists['contentid'].'&amp;contentoption='.$lists['contentoption'].'&amp;tmpl=component';
	        $this->assign("linkRss", $linkRss);
	        
	        //get smiley											
			$this->assign ("fucSmiley", "jacInsertSmiley"); 
			$this->assign ("id", "");
	          
		   	//--assign item in to html           
		   	$searchAll 			= str_replace(" AND parentid = 0","",$search);
		   	$currentCommentID 	= JRequest::getInt('currentCommentID', 0);
		   	if($currentCommentID != 0){		   																  
		   		$itemAll 		= $model->getItemsFrontEnd($searchAll, "all", $limitstart, $orderBy);
		   		$itemAllNoStruct = $itemAll;
		   		
		   		$currentItem	= array();
		   		$currentItem 	= $this->getCurrentItem($itemAll, $currentCommentID);
		   		
		   		//if found this item		   				   		
		   		if(isset($currentItem) && isset($currentItem->id)){		   				   		
			   		//if it is hasn't parent			   		
		   			if($currentItem->parentid == 0){		   							   					   						   				
		   				$this->buildParentAndLimitStart($itemAll, $limitstart, $currentCommentID);			   						   			
			   			JRequest::setVar("limitstart", $limitstart);
				   		JRequest::setVar("limit", 10);
				   		$items 		  		= $model->getItemsFrontEnd($search, $limit, $limitstart, $orderBy);				   				   					   		
		        		$items				= $this->assignItems($items, $itemAll, $itemAllNoStruct, "highLight");		   				   		
			   		}
			   		//if it has parent
			   		else{			   						   						   					   			
			   			$searchItems   = array();			   			   			
			   			$limitstart    = 0;			
			   			$rootParentID  = 0;
			   			
			   			$structItemAll = $this->buildStructParent($itemAll);
			   			//get array items			   						   						   		
			   			$this->getArrayParent($structItemAll, $itemAll, $currentItem, $searchItems, $rootParentID);
			   						   						   			
			   			//get and get limitstart base on root parent
			   			//$rootParentID = $searchItems[count($searchItems)-1][0]->parentid;			   					   				   			
			   			$limitstart = $this->getLimitStart($itemAll, $rootParentID);
			   						   			
			   			JRequest::setVar("limitstart", $limitstart);
				   		JRequest::setVar("limit", 10);
				   						   		
				   		$items 		  		= $model->getItemsFrontEnd($search, $limit, $limitstart, $orderBy);							   					   				   						   						   				   					  
		        		$items				= $this->assignItems($items, $structItemAll, $itemAllNoStruct, "highLight");		        										        		
		        		
		        		$this->assign("searchItems", $searchItems);		        		
		        		$this->assign("rootParentID", $rootParentID);
			   		}				   						   					   		
		   		}
		   		//don't find it in database - display nomal
		   		else{
		   			$items 		  		= $model->getItemsFrontEnd($search, $limit, $limitstart, $orderBy);		  
	        		$items				= $this->assignItems($items, $itemAll);	
		   		}
		   	}else{
	        	$itemAll 		    = $model->getItemsFrontEnd($searchAll, "all", $limitstart, $orderBy);
		   		$items 		  		= $model->getItemsFrontEnd($search, $limit, $limitstart, $orderBy);		  
	        	$items				= $this->assignItems($items, $itemAll);
		   	}	        	        
		   	$this->assign("items",$items);		   	
		   	$this->assign("currentCommentID", $currentCommentID);		   
		   	//get paging
		   	$this->getPaging($lists);		  
		   	if($task != 'preview') { 			   	
		   		echo $this->loadTemplate("block");
		   		exit();				     
		   	}
		   	//prview comment
		   	echo "<div id='jac-wrapper'>".$this->loadTemplate("block")."</div>";
		}										
    }	    
    
	//show array items when pass items 
	function showItems($items, $searchItems, $currentCommentID, $rootParentID){				
		$this->assign("items",$items);		   	
		$this->assign("currentCommentID", $currentCommentID);
		$this->assign ( "ischild", 1 );
		$this->assign("searchItems", $searchItems);		
		$this->assign("subParentID", $rootParentID);										
		return $this->loadTemplate('comments');
	}
    
    function getArrayParent($structItemAll, $itemAll, $item, &$searchItems, &$rootParentID){
    	//assign value for search item
    	$tmpArray = $structItemAll[$item->parentid];    	
    	$tmpArray = $this->assignItems($tmpArray, $structItemAll,$itemAll, "highLight");		    	
    	
    	$searchItems[$item->parentid] = $tmpArray;    	
    	$currentItem   = $this->getCurrentItem($itemAll, $item->parentid);    	
    	if($currentItem->parentid != 0){
    		$this->getArrayParent($structItemAll, $itemAll, $currentItem, $searchItems, $rootParentID);		
    	}else{
    		$rootParentID = $item->parentid;
    	}    	
    }
    
    //get current item when pass itemid
    function getCurrentItem($itemAll, $currentCommentID){    	
    	foreach ($itemAll as $item){
    		if($item->id == $currentCommentID){
    			return $item;    				
    		}
    	}
    	return false;
    }        
    
    function getLimitStart($itemAll, $currentCommentID){    	
    	$limitStart = 0;
    	foreach ($itemAll as $item){    				
			if($item->parentid == 0 && $currentCommentID != 0){
				$limitStart ++;
				if($item->id == $currentCommentID){
					break;
				}				
			}																
    	}        	
    	$limitStart = intval(($limitStart /10))*10;   
    	return $limitStart.""; 	
    }
    
    //buil struct of all items and get number page of this item.
    function buildParentAndLimitStart(&$itemAll, &$limitStart, $currentCommentID){
    	$children 	= array();
		$list 		= array();
		$position 	= 0;				
		$limit 		= JRequest::getInt ( 'limit', 10 );
		
    	foreach ($itemAll as $item){    	
			$pt = $item->parentid;			
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push( $list, $item );
			$children[$pt] = $list;
			if($item->parentid == 0 && $currentCommentID != 0){
				if($item->id == $currentCommentID){
					$currentCommentID = 0;
				}
				$position ++;
			}																
    	}    	
    	
    	//0 - 0, 10 - 0, 20 -10, 30 - 20
    	if(($position % $limit) == 0){
    		if($position >$limit)
    			$limitStart = $position-$limit;    		    	
    	}else{
    		$limitStart = intval(($position /$limit))*$limit;
    	}    	
    	
    	//unset($itemAll);
    	$itemAll = $children;    	    	    	
    }
    
    //buil struct of parent
    function buildStructParent($itemAll){
    	$children = array();
		$list 		= array();
		
    	foreach ($itemAll as $item){    	
			$pt = $item->parentid;			
			$list = @$children[$pt] ? $children[$pt] : array();
			array_push( $list, $item );
			$children[$pt] = $list;						
								
    	}
    	return $children;
    }
    
//    function getLevelOfComment($itemAll, $parentID, &$level){
//    		
//    }
    
    function getNumberOfChildrent($itemAll, $itemID, &$countChildren){    	
    	if(isset($itemAll[$itemID])){
    		$countChildren += count($itemAll[$itemID]);    	
    		foreach ($itemAll[$itemID] as $arr){
    			$this->getNumberOfChildrent($itemAll, $arr->id, $countChildren);					
    		}
    	}    	    	
    }
    
    /*
    *    
    */
    function assignItems($items, $itemAll = 0, $itemAllNoStruct = 0, $actionCall = ''){
		global $jacconfig;					
		$helper = new JACommentHelpers ( );		
		$model = & $this->getModel ();		
		$parentID = JRequest::getVar ( 'parentid', 0 );	
		$parentArray = array();						
		
	    if(!isset($jacconfig['permissions'])){
			$jacconfig['permissions'] = new JParameter('');
		}
	    if(!isset($jacconfig['comments'])){
			$jacconfig['comments'] = new JParameter('');
		}
	    if(!isset($jacconfig['layout'])){
			$jacconfig['layout'] = new JParameter('');
		}
   	   	$avatarSize    		= $jacconfig["layout"]->get("avatar_size",1);
       	$reportComment 		= $jacconfig["comments"]->get("is_allow_report",1);
       	$typeAllowReport 	= $jacconfig['permissions']->get('report', "all");
	   	$currentUserInfo 	= JFactory::getUser();
       	$isAllowVoting		= $jacconfig['comments']->get('is_allow_voting', 1);
        $voteComment		= $jacconfig['permissions']->get('vote', "all");
	   	$typeVote			= $jacconfig['permissions']->get('type_voting', 1);
	   	$enableTimestamp 	= $jacconfig['layout']->get('enable_timestamp', 1);
 		$typeEditing		= $jacconfig['permissions']->get('type_editing', 1);
 		$sessionAddnew 		= array();
 		if($typeEditing == 2){
 			// Returns a reference to the global JSession object, only creating it if it doesn't already exist
			$session = &JFactory::getSession();								
			// Get a value from a session var
			$sessionAddnew = $session->get('jacaddNew', null);											
 		}
		$lagEditing			= $jacconfig['permissions']->get('lag_editing', 172800);
		           		

		//check user is specialUser
        $isSpecialUser = $helper->isSpecialUser();
        
        if($itemAll){     
        	if($actionCall == ""){
        		$itemAllNoStruct = $itemAll;        		
        		$itemAll = $this->buildStructParent($itemAll);        		   	        	
        	}   	
        }            		                        
        
        for($i = 0; $i < count($items); $i++){
			$item =& $items[$i];
			
			//get level for item
			if($i == 0){
				$item->level = 0;				 				
				//use itemAll with NoStruct for search level of comment
				//$model->getLevelOfComment($item->id, $item->level);
				$this->getLevelOfComment($item->id, $item->level, $itemAllNoStruct);				
			}												
			
			//if current user is special user pass parent type
			$item->parentType = 1;
			if($isSpecialUser){
				//$item->parentid
				if($item->parentid >0){
					$item->parentType =  $this->getTypeOfComment($item->parentid, $itemAllNoStruct);
				}
			}
						
			//get number of children
			$countChildren = 0;
			$this->getNumberOfChildrent($itemAll, $item->id, $countChildren);
			$item->children = $countChildren;
			
			//BEGIN - get info of user
			$userInfo = JFactory::getUser($item->userid);
			if($userInfo->id == 0){																 
				$item->strUser 	= $item->name;
				$item->strEmail 	= $item->email;					
                  
                if($item->website && stristr($item->website, 'http://') === FALSE) {
                    $item->strWebsite = 'http://'.$item->website;
                }else{
                    $item->strWebsite = $item->website;
                }
			}else{																																											
				$item->strUser 		= $userInfo->name;
				$item->strEmail 	= $userInfo->email;	                    
                $item->strWebsite   = '';						                    
			}
			
			$item->isCurrentUser = 0;
			if($currentUserInfo->id == $userInfo->id && $userInfo->id != 0){
				$item->isCurrentUser = 1;	
			}
			
			$item->isSpecialUser = 0;						
        	if( $helper->isSpecialUser($userInfo->id, 'check')){        		
				$item->isSpecialUser = 1;	
			}
			
			$item->rpx_avatar = '';
           	$item->icon = '';
           	if(isset($item->usertype) && $item->usertype){
            	$item->paramsUser = $this->showParamsUser($item->userid);
	            if(is_object($item->paramsUser)){   
	                if($item->paramsUser->providerName=='Twitter' || $item->paramsUser->providerName=='Facebook'){
	                    if(array_key_exists('photo', $item->paramsUser)){
	                        $item->rpx_avatar = $item->paramsUser->photo;
	                        $item->icon = '<img height="16" width="16" class="jac-provider-icon-'.$avatarSize.'" alt="'.$item->paramsUser->providerName.'" src="'.JURI::base().'components/com_jacomment/asset/images/'.strtolower($item->paramsUser->providerName).'.ico" />';
	                    }
	                    $item->strUser = $item->paramsUser->displayName;        
	                    $item->strWebsite = $item->paramsUser->url;
	                    
	                }else if($item->paramsUser->providerName=='Yahoo!'){
	                    $item->icon = '<img height="16" width="16" class="jac-provider-icon-'.$avatarSize.'" alt="'.$item->paramsUser->providerName.'" src="'.JURI::base().'components/com_jacomment/asset/images/'.strtolower($item->paramsUser->providerName).'.gif" />';
	                }                    
	            }
           	}
				                 
		   	$item->avatar = $helper->getAvatar($userInfo->id);						                                           
			if($item->rpx_avatar){
           		$item->avatar = $item->rpx_avatar;   
            }
			           	
            //BEGIN - vote
           	if($item->voted == 0){
				$item->totalVote = $item->voted;
				$item->jacVoteClass = "jac-vote0";
			}else if($item->voted > 0){
				$item->totalVote = "+".$item->voted;
				$item->jacVoteClass = "jac-vote1";
			}else{
				$item->totalVote = $item->voted;
				$item->jacVoteClass = "jac-vote-1";
			}
			$item->isAllowVote = 0;
			//if user has been loged
			if (!$currentUserInfo->guest) {
				if($isAllowVoting && ($currentUserInfo->id != $item->userid) && $this->isEnableCommentUser($currentUserInfo->id, $item->id, $typeVote)){
					$item->isAllowVote = 1;
				}
			}else{
				//check email don't allow vote when new post
				$email   		= JRequest::getVar ( 'email', 0 );													
				if($isAllowVoting && $this->isEnableCommentGuest($item->id, $typeVote) && $voteComment == "all" && !$email){
					$item->isAllowVote = 1;
				}
				
			}
			//END - vote
               
            $item->isDisableReportButton = 1;
			if(!$currentUserInfo->guest){
				if($this->isEnableReportCommentUser($currentUserInfo->id,$item->id))
					$item->isDisableReportButton = 0;
			}
			else{																	
				if($this->isEnableReportCommentGuest($item->id) && $typeAllowReport == "all")
					$item->isDisableReportButton = 0;	
			}
				
			//END - get info of user			
			$item->isAllowEditComment = 0;			 									
			if(!$isSpecialUser){
				if($item->userid == $currentUserInfo->id && $currentUserInfo->id != 0){
					if($typeEditing == 1){
						$item->isAllowEditComment = 1;	
					}else if($typeEditing == 2){
						if(isset($sessionAddnew) && count($sessionAddnew) > 1){
							if(in_array($item->id, $sessionAddnew)){
								$item->isAllowEditComment = 1;		
							}
						}
					}else {
						if((time() - strtotime($item->date)) <= $lagEditing){
							$item->isAllowEditComment = 1;
						}
					}
				}		
			}else{
				$item->isAllowEditComment = 1;
			}
															
		}
		return $items;	
    }
    
	function getTypeOfComment($commentID, $itemAllNoStruct){
    	foreach ($itemAllNoStruct as $item){
    		if($item->id == $commentID){
    			return $item->type;    			
    		}
    	}
    }
    
	function getLevelOfComment($id, &$level, $itemAllNoStruct){		
		$parentID = 0;
		if(!$itemAllNoStruct){
			return;			
		}
		foreach ($itemAllNoStruct as $comment){
			if($comment->id == $id){
				$parentID = $comment->parentid;	
			}
		}
				
		if($parentID != 0){			
			$level ++;			
			$this->getLevelOfComment($parentID, $level, $itemAllNoStruct);
		}
	}
    
    function showEditor($item = 0){
    	$this->assign("item",$item);
    	echo $this->loadTemplate("editor");    	    
    }
    
    	    
    /*
    *    
    */
    function showScript($name=''){     	      
        $model = & JModel::getInstance ( 'addons', 'JACommentModel' );
        $addons = $model->getScript ($name);        
       // print_r($addons);        
        return $addons;
    }
	
	function getListLimit($limitstart, $limit, $order=''){		
    	$array = array(5, 10, 15, 20, 50, 100);
    	$list_html = array();
    	foreach ($array as $value){
    		$list_html[] = JHTML::_('select.option', $value, $value);  
    	}
    	//limitstart, limit, order
    	$onchange = "$limitstart, $limit, '$order'";
    	$keyword = JRequest::getVar('keyword');
    	$listID = "list";    	
    	$list_html = JHTML::_('select.genericlist',   $list_html, $listID, ' onchange="jac_doPaging('.'0'.$limitstart.', this.value, \''.$order.'\', \''.$keyword.'\')"', 'value', 'text', $limit);    		    	
    	return $list_html;
    }
    
	/*
	 * paging page
	 */	
	function getPaging(&$lists){		
		$model = $this->getModel();	
                
		$limitstart = JRequest::getInt ( 'limitstart', '0' );		
		$limit = JRequest::getVar ( 'limit', '' );
					
        $keyword = JRequest::getVar ( 'keyword');

        if(!$limitstart) JRequest::setVar('limitstart', '0');
        if(!$limit) JRequest::setVar('limit', '0');
        if(!$keyword) JRequest::setVar('keyword', '');
        
       
        $link = '';
        if($keyword) $link = "index.php?keyword=".$keyword;
        
		if($limit == ''){						   			
			$getLists = $model->_getVars();
			$lists = array_merge($lists, $getLists);					
       		$pagination = &$model->getPagination($lists['limitstart'], $lists['limit'], 'jac-pagination', $link);       	        	
       		$this->assignRef('lists', $lists);
       		$this->assignRef('pagination', $pagination);	
		}else{																
			$getLists['limitstart'] = $limitstart;			
			$getLists['limit'] = $limit;	
			$getLists['order'] = '';				
			$lists = array_merge($lists, $getLists);					
			
       		$pagination = &$model->getPagination($limitstart, $limit, 'jac-pagination', $link);       		       		
       		
       		$this->assignRef('lists', $lists);
       		$this->assignRef('pagination', $pagination);	
		}													
       	//return $this->loadTemplate('paging');
	}
	
	/*
	 *check enable or disable button report of comment when user is a guest 
	 */
	function isEnableReportCommentGuest($id){
		global $mainframe;										
		$cookieName = JUtility::getHash ( $mainframe->getName () . 'reportcomments' . $id );							
		// ToDo - may be adding those information to the session?
		
		$voted = JRequest::getVar ( $cookieName, '0', 'COOKIE', 'INT' );
		//this guest already vote for comment
		if($voted){			
			return 0;			
		}else{
			return 1;
		}
	}
	
	/*
	 *check enable or disable report of comment when user has loged 
	 */
	function isEnableReportCommentUser($userid, $id){
		global $mainframe;								
		//check display voting for comment			
		//$modelLogs = & JModel::getInstance ( 'logs', 'JACommentModel' );
        require_once (JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'models'.DS.'logs.php');
        $modelLogs = new JACommentModelLogs(); 
        
		$logs = $modelLogs->getItemByUser ( $userid, $id );		
					
		//----------Only one for earch comment item----------
		if (! $logs || $logs->reports == 0) {
			return 1;	 
		}else{
			return 0;
		}						
	}
	
	/*
	 *check enable or disable vote of comment when user is a guest 
	 */
	function isEnableCommentGuest($id, $typeVote){		
		global $mainframe;
		switch ($typeVote){
			case 2:
				//----------Only one for earch comment item in a session-------- 
				$session = &JFactory::getSession();
				
				// Get a value from a session var
				$sessionVote = $session->get('vote', null);				
				//if comment don't exit in session vote												
				if(isset($sessionVote[$id])){
					return 0;				
				}else{
					return 1;
				}
				break;			
			default:				
				//----------Only one for earch comment item----------
				$cookieName = JUtility::getHash ( $mainframe->getName () . 'comments' . $id );							
				// ToDo - may be adding those information to the session?
				
				$voted = JRequest::getVar ( $cookieName, '0', 'COOKIE', 'INT' );
				//this guest already vote for comment
				if($voted){			
					return 0;			
				}else{
					return 1;
				}		
		}						
	}
	
	/*
	 *check enable or disable vote of comment when user has loged 
	 */
	function isEnableCommentUser($userid, $id, $typeVote){
		global $mainframe;								
		//check display voting for comment			
		//$modelLogs = & JModel::getInstance ( 'logs', 'JACommentModel' );
        require_once (JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'models'.DS.'logs.php');
        $modelLogs = new JACommentModelLogs(); 
        
		$logs = $modelLogs->getItemByUser ( $userid, $id );		
		switch ($typeVote){
			case 2:
				//----------Only one for earch comment item in a session-------- 
				$session = &JFactory::getSession();
				
				// Get a value from a session var
				$sessionVote = $session->get('vote', null);
				
				//if comment don't exit in session vote												
				if(isset($sessionVote[$id])){
					return 0;				
				}else{
					return 1;
				}
				break;
			case 3:
				//----------use lag to voting----------------------
				if (! $logs || $logs->votes == 0) {
					return 1;
				}else{
					$timeExpired = $logs->time_expired;
					if(time() < $timeExpired){
						return 0;	
					}else{
						return 1;	
					}
				}
				break;
			default:				
				//----------Only one for earch comment item----------
				if (! $logs || $logs->votes == 0) {
					return 1;	 
				}else{
					return 0;
				}				
		}
	}
    
    // ++ add by congtq 26/11/2009     
    function showYouTube(){
        $cid = JRequest::getVar( 'cid', '' );
        $id = $cid[0]?$cid[0]:'';
        $this->assign ("id", $id);
        
    }
    
    function showAttachFile(){
    	global $jacconfig;
    	$cid = JRequest::getVar( 'cid', '' );
        $id = $cid[0]?$cid[0]:'';
        $this->assign ("id", $id);
                
		$totalAttachFile = $jacconfig["comments"]->get("total_attach_file", 5);
		$this->assign ("totalAttachFile", $totalAttachFile);
		
		$theme = $jacconfig["layout"]->get("theme", "default");
		$session = &JFactory::getSession();
		if(JRequest::getVar("jacomment_theme", '')){
			jimport( 'joomla.filesystem.folder' );
			$themeURL = JRequest::getVar("jacomment_theme");
			if(JFolder::exists('components/com_jacomment/themes/'.$themeURL)){
				$theme =  $themeURL;						
			}
			$session->set('jacomment_theme', $theme);			
		}else{
			if($session->get('jacomment_theme', null)){
				$theme = $session->get('jacomment_theme', $theme);
			}
		}
		$this->assign ("theme", $theme);
		
		$attachFileType	 = $jacconfig["comments"]->get("attach_file_type", "doc,docx,pdf,txt,zip,rar,jpg,bmp,gif,png");
		$this->assign ("attachFileType", $attachFileType);
		
		$listFiles		 = JRequest::getVar("listfile");
		$this->assign ("listFiles", $listFiles);
    }
    
    function showYouTubeLink($id){
        $document =& JFactory::getDocument();
                            
        $document->addScriptDeclaration("jQuery(document).ready( function() { 
                                            jQuery('#".$id."').append('&nbsp;<a href=\"javascript:open_embed();\" class=\"plugin\"><img title=\"Add a YouTube Video\" alt=\"YouTube\" src=\"".JURI::base()."components/com_jacomment/asset/images/youtube.ico\"> <span>".JText::_("Embed Video")."<\/span><\/a>');
                                            
                                        });");
                                        
        $document->addScriptDeclaration("function open_embed(){
                                            jacCreatForm('open_youtube',0,340,200,0,0,'".JText::_("Embed a YouTube Video")."',0,'".JText::_("Embed Video")."');
                                        }");                                        
        
        
    }
    function showAfterDeadLineLink($id){
        $document =& JFactory::getDocument();
        
        if(!defined('JACOMMENT_PLUGIN_ATD')){
            JHTML::stylesheet('atd.css', 'components/com_jacomment/libs/js/atd/');
            JHTML::script('jquery.atd.js', 'components/com_jacomment/libs/js/atd/');
            JHTML::script('csshttprequest.js', 'components/com_jacomment/libs/js/atd/');
            JHTML::script('atd.js', 'components/com_jacomment/libs/js/atd/');
                                       
            define('JACOMMENT_PLUGIN_ATD', true);            
        }

        $document->addScriptDeclaration("jQuery(document).ready( function() { 
          jQuery('#".$id."').append('&nbsp;<a href=\"javascript:jac_check_atd(\'\')\"><img title=\"Proofread Comment w/ After the Deadline\" alt=\"AtD\" src=\"".JURI::base()."components/com_jacomment/asset/images/atd.gif\"> <span id=\"checkLink\">".JText::_("Check Spelling")."<\/span><\/a>');
        });");
                                   
    }
    // -- add by congtq 26/11/2009
    
    function showSmileys($id=0, $cid=0){
        //$cid = '';
        $cid = $cid?$cid:'';
        if($cid){
            $func = 'jacInsertSmileyEdit';
        }else{
            $func = 'jacInsertSmiley';
        }
        
        $this->assign ("func", $func); 
        $this->assign ("cid", $cid); 
        
        return $this->loadTemplate('smiley');
    }
    
    function showBBCode($id, $cid, $textAreaID){    	
        if($cid){
            $func = 'insertBBcodeEdit';
        }else{
            $func = 'insertBBcode';
        }
        
        $this->assign ("func", $func); 
        $this->assign ("cid", $cid); 
        $this->assign("textAreaID",$textAreaID);        		
        
        return $this->loadTemplate('bbcode');
    }
    
    // ++ add by congtq 02/12/2009  
    function showRPX($realm, $api_key, $id){
        $document =& JFactory::getDocument();
        
        $tokenurl = urlencode(JURI::base().'index.php?'.$_SERVER['QUERY_STRING']);
        
        JHTML::script('', 'https://rpxnow.com/openid/v2/widget');
        $document->addScriptDeclaration("RPXNOW.overlay = true; RPXNOW.language_preference = 'en';");
        $document->addScriptDeclaration("jQuery(document).ready( function() { 
                                            jQuery('#".$id."').append('&nbsp;<a id=\"rpxlogin\" class=\"rpxnow\" onclick=\"return false;\" href=\"https://".$realm."/openid/v2/signin?token_url=".$tokenurl."\">".JTEXT::_("Sign In")." <\/a>');
                                        });"); 
                                            
    }
    // -- add by congtq 02/12/2009 
    
    function showLoginStatus($auth, $id=''){
        if($id){
            $jquery = '';    
            $arrid = explode(',', $id);
            for($i=0; $count=sizeof($arrid), $i<$count; $i++){
                $jquery .= "jQuery('#".$arrid[$i]."').remove();";
            }
            
            $document =& JFactory::getDocument();
            $document->addScriptDeclaration("jQuery(document).ready( function() { 
                                                ".$jquery."                                                
                                            });");
        }
        
        
        if(array_key_exists('photo', $auth)) $this->assign ("photo", $auth['photo']);        
        if(array_key_exists('url', $auth)) $this->assign ("url", $auth['url']); 
        
        $this->assign ("displayName", $auth['displayName']); 
        $this->assign ("providerName", $auth['providerName']); 
        
        return $this->loadTemplate('login_status');
    }

    function showParamsUser($userid){
        require_once (JPATH_SITE.DS.'components'.DS.'com_jacomment'.DS.'models'.DS.'users.php');
        $modelusers = new JACommentModelUsers();
        $paramsUsers = $modelusers->getParam ($userid);                             

        return $paramsUsers;
    }                    
}
?>
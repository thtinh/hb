<?php
$itemid = JRequest::getVar('Itemid'); 
switch ($itemid)
{
	case 2:$tbg = 'tbg-1.png';break;
	case 3:$tbg = 'tbg-2.png';break;
	default:$tbg = 'tbg.png';break;
}
$webtitle = "Hoa Binh Village";
?>
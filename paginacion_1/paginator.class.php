<?php


class pages{

	public function page_navigation($all_count, $per_page, $link_navigation){


		$page = (int) $_GET['page'];

		$pages = @ceil($all_count / $per_page);

		if($page == 0 || $page == ''){
			$page = 1;
		}

		if($all_count > $per_page){
			$page = $page-1;
			$start=($page * $per_page);
		}else{
			$start=0;
		}

		if($pages != 1){

			for($i=1;$i<$pages+1;$i++){

				if($page+1==$i){

					$nav_link.=$i;

				}else{

					$nav_link.=" <a href=\"".$link_navigation.$i."\">".$i."</a> ";

				}


			}
		}else{
			$nav_link = null;
		}

		return $nav_link;
	}


}
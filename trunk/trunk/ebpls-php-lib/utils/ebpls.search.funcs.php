<?
/************************************************************************************

Module : ebpls.search.funcs.php

Description :
	- provides a generic function which displays searched records in an HTML table.

Created By : Stephen Lou B. Banal
Email : sbanal@yahoo.com
Date Created : 8/1/2004 10:07AM

************************************************************************************/

function print_search_results( $uri_key, $post_vars, $title, $columns, $search_result, $search_command_func = NULL, $style_table = "", $style_title = "", $style_header = "search_result_header", $search_result_header_font = "search_result_header_font", $style_record = "search_result_record", $style_record2 = "search_result_record2", $style_paging_font_selected = "paging_font_selected", $style_paging_font = "paging_font", $style_command = "search_command" ) {
	
	$records = $search_result["result"];
	$max_records = count($records);
	$paging_url = get_search_url_string( $uri_key, $post_vars );
	if ( $paging_url == "" ) {
		$paging_url = $uri_key;
	}
	
	$column_add = 0;
	
	if ( $search_command_func != NULL ) {
		$column_add = 1;	
	}


	// - print headers
	foreach ( $columns as $key=>$value ) {
		
		if ( $key == $post_vars["order_key"] ) { // reverse order by on current order column...
			
			$order = ($post_vars["order"]=="DESC")?"ASC":"DESC";			
			echo "<td class=$style_header><a href=\"" . str_replace("<PAGE>", $search_result["page_info"]["page"], $paging_url) . "&order_key=$key&order=$order\"><font class=\"$search_result_header_font\">$value</font></a></td>\n";
			
		} else{
			
			echo "<td class=$style_header><a href=\"" . str_replace("<PAGE>", $search_result["page_info"]["page"], $paging_url) . "&order_key=$key&order=DESC\"><font class=\"$search_result_header_font\">$value</font></a></td>\n";		
			
		}

	}

	if ( $search_command_func != NULL ) {
		
		// command column
		echo "<td class=$style_header><font class=\"$search_result_header_font\">Command</font></td>\n";		
		
	}
	
?>
</tr>
<?
	// print search results here
	if ( $max_records > 0 ) {
		
		for( $i = 0 ; $i < $max_records; $i++ ) {
			
			echo "<tr>\n";
		
			foreach ( $columns as $key=>$value){
				
				if ($i%2) {			
					echo "<td class=\"$style_record\">" . $records[$i]->getData($key) . "&nbsp;</td>\n";
				} else {
					echo "<td class=\"$style_record2\">" . $records[$i]->getData($key) . "&nbsp;</td>\n";
				}
				
			}
			
			if ( @$column_add > 0 ) {
								
				
				if (@$i%2) {
				//	@echo "<td class=\"$style_record\"><font class=$style_command>" . @call_user_func( $search_command_func, &$records[$i] ) . "</font>&nbsp;</td>\n";				
				} else {
				//	@echo "<td class=\"$style_record2\"><font class=$style_command>" .  @call_user_func( $search_command_func, &$records[$i] ) . "</font>&nbsp;</td>\n";					
				}
				
			}
	
			echo "</tr>\n";
					
		}
		
	} else {
		
		?>
		<tr><td align=center class="<?= $style_record ?>" colspan="<?= count($columns) + $column_add ?>">No records found.</td></tr>
		<?
			
	}
	
?>
<tr><td class="<?= $style_header ?>" colspan="<?= count($columns) + $column_add ?>">&nbsp;</td></tr>
<tr><td><font colspan="<?= count($columns) + $column_add ?>">Page(s) :</font>&nbsp;<? print_pages( $paging_url, $search_result["page_info"], $style_paging_font_selected, $style_paging_font  ); ?> </td></tr>
</table>
<?	
	
}

function get_search_url_string( $uri_key, $post_vars ) {
	
	//eBPLS_PAGE_TAX_FEE_TABLE_FILTER
	$paging_url = getURI($uri_key) . "&";
	
	if ( isset($post_vars) ) {
		
		$pg_cnt = 0;
		
		foreach( $post_vars as $key=>$value ){
			
			if ( $key == "part" ) continue;
			if ( $key == "order_key" ) continue;
						
			if ( $key == "order" ) {
				
				if ( $value == "" || $value == "DESC" ) {
					$paging_url .= "&$key=ASC";
				} else if ( $value == "ASC" ){
					$paging_url .= "&$key=DESC";
				}				
			
			} else if ( $key == "pg" ) {
				
				$paging_url .= "&$key=<PAGE>";
				$pg_cnt++;
				
			} else {		
					
				$paging_url .= "&$key=" . urlencode($value);
				
			}
					
		}
		
		if ( $pg_cnt == 0 ) {
		
			$paging_url .= "&pg=<PAGE>&order=DESC";
			
		}
		
	} else {
		
		$paging_url .= "pg=<PAGE>";
		
			
	}

	return $paging_url;
	
}

function print_pages( $url, $page_info, $paging_font_selected, $paging_font ){		
		
	$cur_page = $page_info["page"]+0;		
	
	for( $pg = 1; $pg <= $page_info["max_pages"]; $pg++ ){
		
		if ( $cur_page == $pg ) {

			echo "<font class=\"$paging_font_selected\">$pg</font>&nbsp;";

		} else {

			$tmp_url = str_replace("<PAGE>",$pg,$url);						
			echo "<a href=\"$tmp_url\"><font class=\"$paging_font\">$pg</font></a>&nbsp;";
			
		}
		
		if ( $pg < $page_info["max_pages"] ) {
			
			echo "<font class=\"$paging_font_selected\">|&nbsp;</font>";	
			
		}
		
	}
	
}

?>

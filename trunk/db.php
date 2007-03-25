<?
  /*
   * This script connects to the MySQL server and selects the group chat database
   * It sets the following *global* variable: $gDb_link
   * All scripts requiring database access must "require_once()" this file.
   * If the script cannot connect to or select the database, it will simply
   *   notify the e-mail addresses found in $gApplicationWatcher
   */
  $gDb_link = mysql_connect ("192.168.1.5", "ebpls", "ebpls");

  if (!$gDb_link)
  {
      $error_msg = "[$gNowMySql][$PHP_SELF]\n\tError connecting to the database!\n\t" . mysql_error();
      if ($gVerbose) echo "$error_msg<br>";
      mail($gApplication_watchers, "database error", $error_msg, "From: dot70@chikka.com");
	    return;
  }

  if (!mysql_select_db ("ebpls",$gDb_link))
  {
      $error_msg = "[$gNowMySql][$PHP_SELF]\n\tError selecting the database!\n\t" . mysql_error();
      if ($gVerbose) echo "$error_msg<br>";
      mail($gApplication_watchers, "database error", $error_msg, "From: ebpls@pagoda.com");
	    return;
  }
  
  $gDb_linkGSM = mysql_connect ("localhost", "smsgated", "smsgated");

  if (!$gDb_linkGSM)
  {
      $error_msg = "[$gNowMySql][$PHP_SELF]\n\tError connecting to the database!\n\t" . mysql_error();
      if ($gVerbose) echo "$error_msg<br>";
      mail($gApplication_watchers, "database error", $error_msg, "From: dot70@chikka.com");
	    return;
  }

  if (!mysql_select_db ("smsgated",$gDb_linkGSM))
  {
      $error_msg = "[$gNowMySql][$PHP_SELF]\n\tError selecting the database!\n\t" . mysql_error();
      if ($gVerbose) echo "$error_msg<br>";
      mail($gApplication_watchers, "database error", $error_msg, "From: ebpls@pagoda.com");
	    return;
  }
  
?>

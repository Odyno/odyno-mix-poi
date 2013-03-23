<?

  $req_dump = print_r($_REQUEST, TRUE);
  $fp = fopen('request.log', 'c');
  fwrite($fp, $req_dump);
  fclose($fp);
  exit;

?>

<?php
if (!isset($_SESSION)) {
  session_start();
}
$MM_authorizedUsers = "";
$MM_donotCheckaccess = "true";

// *** Restrict Access To Page: Grant or deny access to this page
function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
  // For security, start by assuming the visitor is NOT authorized. 
  $isValid = False; 

  // When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
  // Therefore, we know that a user is NOT logged in if that Session variable is blank. 
  if (!empty($UserName)) { 
    // Besides being logged in, you may restrict access to only certain users based on an ID established when they login. 
    // Parse the strings into arrays. 
    $arrUsers = Explode(",", $strUsers); 
    $arrGroups = Explode(",", $strGroups); 
    if (in_array($UserName, $arrUsers)) { 
      $isValid = true; 
    } 
    // Or, you may restrict access to only certain users based on their username. 
    if (in_array($UserGroup, $arrGroups)) { 
      $isValid = true; 
    } 
    if (($strUsers == "") && true) { 
      $isValid = true; 
    } 
  } 
  return $isValid; 
}

$MM_restrictGoTo = "index.php";
if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
  $MM_qsChar = "?";
  $MM_referrer = $_SERVER['PHP_SELF'];
  if (strpos($MM_restrictGoTo, "?")) $MM_qsChar = "&";
  if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
  $MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
  $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
  header("Location: ". $MM_restrictGoTo); 
  exit;
}
?>
<?php include"header.php"; ?>
<div id="box_content">
    <div id="sidebar">
     <?php include"menu_painel.php"; ?>
     </div><!--Sidebar-->
    <div id="conteudo">
    <?php
	$visitas_total = mysql_query("SELECT Sum(visitas) AS visitas FROM silpa_posts")
	                  or die(mysql_error());
	if(@mysql_num_rows($visitas_total) <= '0') echo '';
	$views = 0;
	$visitas = mysql_result($visitas_total, $views, 'Visitas');
	
	$visitas_media = mysql_query("SELECT id FROM silpa_posts")
	                 or die(mysql_error());
	$linhas = mysql_num_rows($visitas_media);
	$contps = "../contador.php";
	require($contps);
	$acessos = $a;
	?>

     <h1 class="titu">Bem vindo ao seu painel de gerenciamento.</h1>
     <h1>Total de Visitas nos posts</h1>
     Visitas = <strong><?php echo $visitas;?></strong>
     <h1>Total de visitas no site</h1>
     Visitas = <strong><?php echo $acessos;?></strong>
    </div><!--Conteudo-->
   </div><!--box_content-->
<?php include "footer.php";?>
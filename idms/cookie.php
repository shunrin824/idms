<?php
ini_set('display_errors', "on");
if(isset($_GET['r'])){
    setcookie("r", $_GET['r'], time()+2592000);
}


if(isset($_GET['type'])){
    if($_GET['type'] == "all"){
        setcookie("type", $_GET['type'], time()-100);
    }else{
        setcookie("type", $_GET['type'], time()+2592000);
    }
}


if(isset($_GET['type'])){
    setcookie("num", $_GET['num'], time()+2592000);
}


if(isset($_GET['foretaste'])){
    setcookie("foretaste", $_GET['foretaste'], time()+2592000);
}else{
    setcookie("foretaste", 'none', time()-100);
}


if(isset($_GET['fullsc'])){
    setcookie("fullsc", $_GET['fullsc'], time()+2592000);
}else{
    setcookie("fullsc", 'none', time()-100);
}


if(isset($_GET['txt'])){
    setcookie("txt", $_GET['txt'], time()+2592000);
}else{
    setcookie("txt", 'none', time()-100);
}


if(isset($_GET['search'])){
    if(isset($_GET['page'])){
        $get = '?search='.$_GET['search'].'&page='.$_GET['page'];
    }else{
        $get = '?search='.$_GET['search'];
    }
}else{
    if(isset($_GET['page'])){
        $get = '?page='.$_GET['page'];
    }else{
        $get = '';
    }
}

if(isset($_GET['size'])){
	if($_GET['size'] == "0"){
		setcookie("size", $_GET['size'], time()+2592000);
	}else{
		setcookie("size", $_GET['size'], time()+2592000);
	}
}
?>
<html></meta><meta http-equiv="refresh" content="0; URL='/idms/content.php<?=($get)?>'"></meta></html>
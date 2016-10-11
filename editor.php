<?php

if(!empty($_POST['FileName']) && !empty($_POST['OpenFile'])){
    $file = __DIR__ . '/'. $_POST['FileName'];
    if(file_exists($file))
    {
        $fileContent = file_get_contents($file);
        echo $fileContent;
    }else echo "Archivo no encontrado, se generará cuando se guarde ($file)";
    die();
}

if(!empty($_POST['FileName']) && !empty($_POST['SaveFile']) && !empty($_POST['FileContent'])){
    $file = __DIR__ . '/'. $_POST['FileName'];
    try{
        file_put_contents($file, $_POST['FileContent']);
        echo "Archivo guardado exitosamente!";
    }catch(Exception $ex)
    {
        echo "Ocurrio un error guardando el archivo";
    }
    die();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Editor HTML</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
    <style type="text/css" media="screen">
        #editor {
            position: absolute;
            top: 240px;
            right: 0;
            bottom: 0;
            left: 0;
            width: 100%;
            height: 500px;
        }
    </style>
</head>
<body>
<div class="container-fluid">
    <div style="background: #0299ae; padding: 3px;" class="row">
        <img src="img/header.png" class="img-responsive center-block">
    </div>
    <div class="row" style="padding: 2%">
        <?php
        if(empty($_REQUEST['page']))
        {
            echo '<h1 class="text-center">No se recibio ningún nombre de archivo</h1></div></div></body></html>';
            die();
        }
        ?>
        <div class="col-sm-8">
            <h3 id="FileName" class="text-center">Nombre del Archivo: </h3>
        </div>
        <div class="col-sm-1">
            <button id="SaveBtn" type="button" class="btn btn-primary">Guardar</button>
        </div>
        <div class="col-sm-1">
            <button id="SeeBtn" type="button" class="btn btn-default">Ver</button>
        </div>
    </div>
</div>
<div id="editor">
</div>

</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.2.3/ace.js"></script>
<script>
    //HOST NAME
    var host = "http://localhost/HTMLOnlineEditor/";

    // FILE NAME
    var fileName = '<?php echo $_REQUEST['page'];?>';
    $('#FileName').text('Nombre de Archivo: ' + fileName);

    // ACE EDITOR
    var editor = ace.edit("editor");
    editor.$blockScrolling = Infinity;
    editor.setTheme("ace/theme/monokai");
    editor.getSession().setMode("ace/mode/html");

    //LOADING FILE CONTENT
    $.ajax({
        url: 'editor.php',
        method: 'POST',
        data: {FileName: fileName, OpenFile: true},
        success: function(respuesta){
            //console.log(respuesta);
            editor.getSession().setValue(respuesta);
        }
    });
	//OPEN PAGE IN NEW TAB
	$('#SeeBtn').on('click', function(){
		var r = Math.random();
		window.open(host + fileName + '?' + r, '_blank');
	});

    //SAVING FILE
    $('#SaveBtn').on('click', SaveFile);
    function SaveFile()
    {
        $.ajax({
            url: 'editor.php',
            method: 'POST',
            data: {FileName: fileName, SaveFile: true, FileContent: editor.getSession().getValue()},
            success: function(respuesta){
                alert(respuesta);
            }
        });
    }
    //editor.getSession().setValue('');
</script>

</html>
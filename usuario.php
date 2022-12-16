<?php
 include("valida.php");
 if(isset($_FILES['imagem'])){
    $arquivopng = addslashes(file_get_contents($_FILES['imagem']['tmp_name']));
    if(!isset($_SESSION)){session_start();}
    $query_arquivo = "UPDATE alunos SET imagem = '{$arquivopng}' WHERE ID_Aluno = '{$_SESSION['ID_Aluno']}'";
    $resultado = $mysqli->query($query_arquivo) or die($mysqli->error);
 }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="css/loginNovo.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <script src="js/constroi.js"> </script>
</head>
<!-- CORPO DA PAGINA USUARIO -->
<body class="usuario">
    <!-- CABEÇALHO DA PAGINA -->
    <nav>
        <img style="width:100%; height: 30vh;" src="img/logonav.png">
        
    </nav>
    <div sytle="display:flex;">
    <div id="modal">   
        <button type="button" onclick="fecha()">X</button>
                <h3>Selecione um avatar</h3>
                    <form  method="POST" enctype="multipart/form-data">
		                <label for="imagem">Imagem:</label>
		                <input type="file" name="imagem"/>
		                <input type="submit" value="Enviar"/>
	                </form>
        </div>
    </div>
    <!-- TROCA AVATAR DO USUARIO E VOLTA NA PAGINA INICIAL -->
    <div class="usuarioDiv">
        <?php 
        if(!isset($_SESSION)){session_start();}
        $arquiv = "SELECT imagem FROM alunos WHERE ID_ALUNO = '{$_SESSION['ID_Aluno']}'";
        $result = $mysqli->query($arquiv) or die($mysqli->error);
        $row = mysqli_fetch_array($result);
        echo '<img id="fotoLogin" src="data:image/jpeg;base64,' . base64_encode( $row['imagem'] ) . '" />';
        ?>>
        <div id="usuarioId">
        <p style="font-size:30px;margin-top:-5vh;margin-bottom:2vh"><?php if(!isset($_SESSION)){session_start();}
            echo $_SESSION['nome'];
            ?>
        </p>
        
        <button type="button" onclick="altera()">Alterar Avatar</button>
        <a href="index.html">SAIR</a>
        <a href="certificado.php">BAIXAR</a>
        </div>
    </div>
    
    <hr>
    <!--CURSOS DO ALUNO -->
    <h1>Seus Cursos</h1><br> 
    
   <div id="cursos">
   <div id="cursoCont">
        <a href="javascript:volta();">Voltar</a>
    </div>
    <?php 
        /*
        $consulta = "SELECT * FROM cursos";
        $consulta2 = "SELECT * FROM aluno_curso_progressos";
        $con = $mysqli->query($consulta) or die($mysqli->error);
        $con2 = $mysqli->query($consulta2) or die($mysqli->error);
        $i = 0;
        while($c2 = mysqli_fetch_array($con2)){
            if($c2['ID_Aluno'] ==  $_SESSION['ID_Aluno']){
                while($c = mysqli_fetch_array($con)){ 
                    if($c['ID_Curso'] == $c2['ID_Curso']){
                        echo "<div style='text-align:center' id = 'oi'>".$c['Nome_curso']."<img  src='img/apertomao.jpg'>"
                        ."<a>Acessar Curso</a>"."</div>";
                        $i++;
                        break;
                    }
                } 
            } 
         }
         while($i>0){
            echo "<script>adcElemento()</script>";
            $i--;
         }
         
        */
        $oi = $_SESSION['ID_Aluno']; $i = 0;
        $consulta = "SELECT cursos.Nome_curso, cursos.ID_Curso from alunos join aluno_curso_progressos ON aluno_curso_progressos.ID_Aluno = alunos.ID_Aluno join cursos ON cursos.ID_Curso = aluno_curso_progressos.ID_Curso WHERE alunos.ID_Aluno = $oi";
        $con = $mysqli->query($consulta) or die($mysqli->error);
        $i3 = 0;
        while($c = mysqli_fetch_array($con)){
            $oi2 = $c['ID_Curso'];
            $consulta2 = "SELECT cursos.aulas_totais from cursos join aluno_curso_progressos ON aluno_curso_progressos.ID_Curso = cursos.ID_Curso join alunos ON aluno_curso_progressos.ID_Aluno = alunos.ID_Aluno WHERE alunos.ID_Aluno = $oi and cursos.ID_Curso = $oi2";
            $con2 = $mysqli->query($consulta2) or die($mysqli->error);
            $aulas = mysqli_fetch_array($con2)[0];
            echo "<div style='text-align:center' id = 'oi'>".$c['Nome_curso']."<img  src='cursos/".$c['ID_Curso']."/".$c['ID_Curso'].".png'>"
                        ."<a onclick='mostraCurso".$c['ID_Curso']."();'>Acessar Curso</a></div><script>
                            function mostraCurso".$c['ID_Curso']."(){
                            let d = document.getElementsByClassName('cursoConteudo');
                            let u = document.querySelectorAll('#oi2');;
                            let e = document.getElementsByClassName('cursoTela');
                            for (let i = 0; i < e.length; i++) {
                              e[i].style.display = 'none';
                            }
                            document.getElementById('cursoCont').style.display = 'block';
                            let i2 = 0;            
                            for(let i3 = 0; i3 < d.length; i3++){
                               if(i2<u.length && u[i3].getAttribute('name') ==".$c['ID_Curso']."){
                                     d[i3].style.display = 'block';
                                     i2++;
                                }
                            }
                         }
                        </script>";
            for($i2 = 1; $aulas >= $i2;$i2++){
                echo "<div style='text-align:center;display:none' id = 'oi2' name='".$c['ID_Curso']."'>Aula:".$i2."<img  src='cursos/".$c['ID_Curso']."/".$i2."/img/0.jpg'>"
                ."<a onclick='mostraFase".$c['ID_Curso']."aula".$i2."()'>Fase ".$i2."</a></div>";
                echo "<div id='modal2' name='Curso".$c['ID_Curso']."Aula".$i2."'>
                <a onclick='fechaFase".$c['ID_Curso']."aula".$i2."()' style='background-color:red;cursor:pointer'>xx</a>
                <div id='modal2Cont'>Curso:".$c['ID_Curso']." Aula:".$i2." 
                </div>
                <div id='modal2Op'>
                <a href='cursos/".$c['ID_Curso']."/".$i2."/img/0.jpg'><i class='bi bi-cast'></i> -------</a>
                <a href=''><i class='bi bi-postcard'></i> -------</a>
                <a href='cursos/".$c['ID_Curso']."/".$i2."/fixacao.pdf'><i class='bi bi-pencil-square'></i>  -------</a>
                <a href='cursos/".$c['ID_Curso']."/".$i2."/teste.txt'><i class='bi bi-journal-check'></i></a></div>
                </div>
                <script>
                    function mostraFase".$c['ID_Curso']."aula".$i2."(){
                        let contFase = document.getElementsByName('Curso".$c['ID_Curso']."Aula".$i2."');
                        contFase[0].style.display = 'block';
                    }
                    function fechaFase".$c['ID_Curso']."aula".$i2."(){
                        let contFase = document.getElementsByName('Curso".$c['ID_Curso']."Aula".$i2."');
                        contFase[0].style.display = 'none';
                    }
                </script>";
                $i3++;
            }     
            $i++;
        }
        while($i>0){
            echo "<script>adcElemento()</script>";
            $i--;
        }
        while($i3>0){
            echo "<script>adcCurso();</script>";
            $i3--;
        }
    ?>      
    </div>
</body>
</html>
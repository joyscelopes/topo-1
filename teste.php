<?php 
    include("valida.php");
    $consulta = "SELECT * FROM alunos ";
    $con = $mysqli->query($consulta) or die($mysqli->error);
?>
<html>
    <head><meta charset="utf8"> 
    <link rel="stylesheet" href="css/loginNovo.css" type="text/css">
    <script src="js/altera.js"></script>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teste</title>
    <link rel="sortcut icon" href="img/iconetopo.jpg" type="image/jpg" />

    <link rel="stylesheet" href="css/bootstrap.min.css" type="text/css">
    <link rel="stylesheet" href="css/font-awesome.min.css" type="text/css">
    <link rel="stylesheet" href="css/flaticon.css" type="text/css">
    <link rel="stylesheet" href="css/owl.carousel.min.css" type="text/css">
    <link rel="stylesheet" href="css/barfiller.css" type="text/css">
    <link rel="stylesheet" href="css/magnific-popup.css" type="text/css">
    <link rel="stylesheet" href="css/slicknav.min.css" type="text/css">
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <link rel="stylesheet" href="css/loginNovo.css" type="text/css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.2/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <script src="js/constroi.js"> </script>
    </head>
    <body>
    <nav class="menuTeste">
        <a href="./usuario.php" ><img src="img/iconetopo.jpg"></a>
    </nav>
        <section  id="formTeste">
        <h2 id="oi"><?php if(!isset($_SESSION)){session_start();} echo $_SESSION['nome']; ?></h2>
        <form  id="formTeste" method="POST" action="valida.php">
            <fieldset id="field">
        <?php
            $arq= "cursos/".$_GET['idcurso']."/".$_GET['i2']."/teste.txt";
            $pont = fopen($arq,"r");
            $linha = fgets($pont);
            $i4 = 0;
            $i5 = 0;
            $i6 = 0;
            while($linha){
                if(substr_count($linha, ';;')){
                    if($i4 == 0){
                        echo "<div>";
                    }
                    $i4++;
                    if($i4 == 2){
                        $linha = str_replace(";;", "", $linha);
                        echo "<p>".$linha."</p>";
                    }
                    if($i4 > 2 && $i4<7){
                        $i5++;
                        $linha = str_replace(";;", "", $linha);
                        echo "<input type='checkbox' class='checkmark 'name='pergunta".$i6."'value='".$i5."'>".$linha."</br>";
                    }
                    if($i4 == 7){
                        $i4 = 0;
                        $i5 = 0;
                        $linha = str_replace(";;", "", $linha);
                        $inteiro = (int)$linha;
                        echo "<a style='display:none' value='".$inteiro."' id='resposta".$i6."'></a>";
                        $i6++;
                        echo "</div><br>";
                    }
                }
                $linha = fgets($pont);
            }
            echo "<a style='display:none' id='quant' value='".$i6."'></a>";
            echo "<input type='number' style='display:none' name='idcurso' value='".$_GET['idcurso']."'>
            <input type='number' style='display:none' name='aula' value='".$_GET['i2']."'>";
            if(isset($_COOKIE['total'])){
                unset($_COOKIE['total']);
            }
        ?>
        <a onclick="validar()" class="btn btn-success btn-sm" id="mostraResultado">Mostrar resultado</a>
        </fieldset>
        <div id="nota" style="display:none;">
            <h2>nota:</h2><h1 id="resul">nota</h1>
            <h1 id="result2">nota</h1>
        </div>
        <input type="submit" value="Enviar" name="enviarteste"  class="btn btn-success btn-sm" id="enviar" style="display:none">
        </form>
        
        <script type="text/javascript"> 
            function validar(){
                let a = document.getElementById("quant").getAttribute("value");
                let r = 100/a;
                let total = 0;
                for(let i= 0; i<a;i++){
                    let checkboxes = document.getElementsByName("pergunta"+i);
                    let respostas = document.getElementById("resposta"+i);  
                    let iCheck = 0;  
                    for(let i2 = 0; i2 < checkboxes.length; i2++){  
                        if(checkboxes[i2].checked){
                            iCheck++; 
                            if(checkboxes[i2].getAttribute("value") === respostas.getAttribute("value")){
                                total+=r;
                            }
                        }   
                    }
                    if(iCheck == 0){  
                        i++;
                        alert("Você não marcou nenhuma opção na pergunta numero "+i);  
                        return false;  
                    }  
                    if(iCheck > 1){  
                        i++;
                        alert("Você marcou mais de uma vez na pergunta numero "+i);  
                        return false;  
                    }
                }
                document.getElementById("field").style.display = "none"; 
                document.getElementById("nota").style.display = "flex"; 
                document.getElementById("resul").innerHTML = total;
                document.getElementById("enviar").style.display = "block";
                if(total>70){
                    let re = document.getElementById("result2");
                    let imagem = document.createElement("img");
                    imagem.setAttribute("src", "/topo/img/aprovado.png");
                    imagem.setAttribute("id", "imgResul");
                    re.innerHTML = "APROVADO";
                    re.setAttribute("id", "aprov");
                    document.getElementById("nota").appendChild(imagem);
                }
                if(total<70){
                    let re = document.getElementById("result2");
                    let imagem = document.createElement("img");
                    imagem.setAttribute("src", "/topo/img/reprovado.png");
                    imagem.setAttribute("id", "imgResul");
                    re.innerHTML = "REPROVADO";
                    re.setAttribute("id", "reprov");
                    document.getElementById("nota").appendChild(imagem);
                }
                document.cookie = "total="+total+"";
            }
        </script>
        </section>
    </body>
</html>
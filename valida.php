<?php
//FAZ A CONEXAO COM O BANCO DE DADOS PODIUM
$host = "localhost";
$user = "root";
$pass = "";
$db = "podium";
$mysqli = new mysqli($host, $user, $pass, $db);
//SELECIONA AS TABELAS ALUNOS E CURSOS
$consulta = "SELECT * FROM alunos";
$consulta2 = "SELECT * FROM cursos";
$consulta3 = "SELECT * FROM colaboradores";
$consulta4 = "SELECT * FROM aluno_testes";
$con = $mysqli->query($consulta) or die($mysqli->error);
$con2 = $mysqli->query($consulta2) or die($mysqli->error);
$con3 = $mysqli->query($consulta3) or die($mysqli->error);
$con4 = $mysqli->query($consulta4) or die($mysqli->error);
if($mysqli->connect_errno){
    echo "falha na conexao: (".$mysqli->connect_errno.") " .$mysqli->connect_error;
}
while($c = mysqli_fetch_array($con)){
	if(isset($_POST['ID_Aluno']) && isset($_POST['Senha'])){
		if($_POST['ID_Aluno'] == $c['ID_Aluno'] && $_POST['Senha'] == $c['Senha']){
			session_start();
			$_SESSION['nome'] = $c['Nome'];
			$_SESSION['ID_Aluno'] = $_POST['ID_Aluno'];
			$resultado = 1;
			break;
		}
	}
}

// else{
// 	echo $resultado;
// 	header('location: ./services.html');
//     exit;
// }
while($c3 = mysqli_fetch_array($con3)){
	if(isset($_POST['ID_Aluno']) && isset($_POST['Senha'])){
		if($_POST['ID_Aluno'] == $c3['Login'] && $_POST['Senha'] == $c3['Senha']){
			session_start();
			$_SESSION['nome'] = $c3['Nome'];
			$_SESSION['ID_Colaborador'] = $_POST['ID_Aluno'];
			header('Location: ./admin.php');
		}	
	}
}
 

if(isset($_POST['Enviar'])){
	$email = $mysqli->escape_string($_POST['email']);
	if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
		$error[] = "Email errado";
		echo $error;
	}
	if(count($error) == 0){
	$novasenha = substr(md5(time()) ,0,6);
	$novasenhacrip = md5(md5($novasenha));
	if( mail($email, "Sua Nova Senha", "Sua Nova senha é:" .$novasenha)){
		$sql_code = "UPDATE alunos SET Senha = '$novasenhacrip' WHERE Email = '$email' ";
		$sql_query = $mysqli->query($sqli_code) or die($mysqli->error);
		}
	}
}
if(isset($_POST['enviarteste'])){
	$inse = 0;
	if(!isset($_SESSION)){session_start();}
	while($c4 = mysqli_fetch_array($con4)){
		if($_POST['aula'] == $c4['Numero_aula'] && $_POST['idcurso'] == $c4['ID_Curso'] && $_SESSION['ID_Aluno'] == $c4['ID_Aluno'] && $c4['Nota'] < $_COOKIE['total']){
			$sqlteste = "UPDATE aluno_testes SET Nota = '{$_COOKIE['total']}' WHERE ID_Aluno = '{$_SESSION['ID_Aluno']}' AND Numero_Aula = '{$_POST['aula']}' AND ID_Curso = '{$_POST['idcurso']}'";
			$sqltestebd = $mysqli->query($sqlteste) or die($mysqli->error);
			$sqlprogresso = "UPDATE aluno_curso_progressos SET Estagio = '2' WHERE ID_Aluno = '{$_SESSION['ID_Aluno']}' AND ID_Curso = '{$_POST['idcurso']}'";
			$sqlpro= $mysqli->query($sqlprogresso) or die($mysqli->error);
			//$arquiv = "SELECT imagem FROM alunos WHERE ID_ALUNO = '{$_SESSION['ID_Aluno']}'";
			if($_COOKIE['total'] > 70){
				$atual = $_POST['aula'] + 1;
				$sqlprogresso2 = "UPDATE aluno_curso_progressos SET Estagio = '1', Aula_atual = '{$atual}' WHERE ID_Aluno = '{$_SESSION['ID_Aluno']}' AND ID_Curso = '{$_POST['idcurso']}'";
				$sqlpro2= $mysqli->query($sqlprogresso2) or die($mysqli->error);
				$atual = "SELECT Aula_atual FROM aluno_curso_progressos WHERE ID_Aluno = '{$_SESSION['ID_Aluno']}' AND ID_Curso = '{$_POST['idcurso']}'";
				$sqlatual = $mysqli->query($atual) or die($mysqli->error);
				$atual2 = mysqli_fetch_array($sqlatual)[0];
				$consul = "SELECT cursos.aulas_totais from cursos join aluno_curso_progressos ON aluno_curso_progressos.ID_Curso = cursos.ID_Curso join alunos ON aluno_curso_progressos.ID_Aluno = alunos.ID_Aluno WHERE alunos.ID_Aluno = '{$_SESSION['ID_Aluno']}' and cursos.ID_Curso = '{$_POST['idcurso']}'";
            	$cons= $mysqli->query($consul) or die($mysqli->error);
            	$aulas2 = mysqli_fetch_array($cons)[0];
				if($atual2 == $aulas2){
					$aula = $_POST['aula'];
					$sqlprogresso2 = "UPDATE aluno_curso_progressos SET Estagio = '3', Aula_atual = '{$aula}' WHERE ID_Aluno = '{$_SESSION['ID_Aluno']}' AND ID_Curso = '{$_POST['idcurso']}'";
					$sqlpro2= $mysqli->query($sqlprogresso2) or die($mysqli->error);
				}
			}
			$inse++;
		}
		if($_POST['aula'] == $c4['Numero_aula'] && $_POST['idcurso'] == $c4['ID_Curso'] && $_SESSION['ID_Aluno'] == $c4['ID_Aluno'] && $c4['Nota'] > $_COOKIE['total']){
			$inse++;
		}
		if($_POST['aula'] == $c4['Numero_aula'] && $_POST['idcurso'] == $c4['ID_Curso'] && $_SESSION['ID_Aluno'] == $c4['ID_Aluno'] && $c4['Nota'] == $_COOKIE['total']){
			$inse++;
		}
	}
	if($inse == 0){
		$sqlprogresso = "UPDATE aluno_curso_progressos SET Estagio = '2' WHERE ID_Aluno = '{$_SESSION['ID_Aluno']}' AND ID_Curso = '{$_POST['idcurso']}'";
		$sqlpro= $mysqli->query($sqlprogresso) or die($mysqli->error);
		$sqlteste2 = "INSERT INTO aluno_testes (ID_Aluno, ID_Curso, Numero_Aula, Nota) VALUES  ('{$_SESSION['ID_Aluno']}', '{$_POST['idcurso']}', '{$_POST['aula']}', '{$_COOKIE['total']}')";
		$sqltestebd = $mysqli->query($sqlteste2) or die($mysqli->error);
		if($_COOKIE['total'] > 70){
			$sqlprogresso = "UPDATE aluno_curso_progressos SET Estagio = '1' WHERE ID_Aluno = '{$_SESSION['ID_Aluno']}' AND ID_Curso = '{$_POST['idcurso']}'";
			$sqlpro= $mysqli->query($sqlprogresso) or die($mysqli->error);
			$atual = "SELECT Aula_atual FROM aluno_curso_progressos WHERE ID_Aluno = '{$_SESSION['ID_Aluno']}' AND ID_Curso = '{$_POST['idcurso']}'";
			$sqlatual = $mysqli->query($atual) or die($mysqli->error);
			$atual2 = mysqli_fetch_array($sqlatual)[0];
			$consul = "SELECT cursos.aulas_totais from cursos join aluno_curso_progressos ON aluno_curso_progressos.ID_Curso = cursos.ID_Curso join alunos ON aluno_curso_progressos.ID_Aluno = alunos.ID_Aluno WHERE alunos.ID_Aluno = '{$_SESSION['ID_Aluno']}' and cursos.ID_Curso = '{$_POST['idcurso']}'";
            $cons= $mysqli->query($consul) or die($mysqli->error);
            $aulas2 = mysqli_fetch_array($cons)[0];
			if($_POST['aula'] <= $atual2){
				if($atual2 < $aulas2){
					$aula = $_POST['aula'] + 1;
					$sqlprogresso2 = "UPDATE aluno_curso_progressos SET Estagio = '1', Aula_atual = '{$aula}' WHERE ID_Aluno = '{$_SESSION['ID_Aluno']}' AND ID_Curso = '{$_POST['idcurso']}'";
					$sqlpro2= $mysqli->query($sqlprogresso2) or die($mysqli->error);
				}
				if($atual2 == $aulas2){
					$aula = $_POST['aula'];
					$sqlprogresso2 = "UPDATE aluno_curso_progressos SET Estagio = '3', Aula_atual = '{$aula}' WHERE ID_Aluno = '{$_SESSION['ID_Aluno']}' AND ID_Curso = '{$_POST['idcurso']}'";
					$sqlpro2= $mysqli->query($sqlprogresso2) or die($mysqli->error);
				}
			}
		}
		
		header('Location: ./usuario.php');
	}
	else{
		header('Location: ./usuario.php');
	}
	
}
if(isset($_POST['enviareditarAluno'])){
	echo $_POST['nome'];
	echo $_POST['email'];
	echo $_POST['id'];
}
/*if($contador!=1){
	header('Location: /topo/login.html');
}
else{
}*/
/*<?php
            if(!isset($_SESSION)){session_start();}
            $exibir = "SELECT imagem FROM alunos WHERE ID_Aluno = '{$_SESSION['ID_Aluno']}'";
            $resultado2 = $mysqli->query($exibir) or die($mysqli->error);
            $row = mysqli_fetch_array($resultado2);
            echo "<img src='data:image;base64, ".base64_encode($row['imagem'])."'>";
        ?>>*/
?>

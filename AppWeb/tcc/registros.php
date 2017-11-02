<?php
/*
 * Projeto:
 *  - Ponto Eletrônico Digital
 *
 * Descrição:
 *  - TCC para a Faccamp Facundade Campo Limpo Paulista,
 *    como requesito parcial para obtenção do título de
 *    Bacharel em Ciência da Computação.
 *
 * criado em 26 Abril 2015
 * by André Devecchi
 * contato: andre.devecchi@gmail.com
 * ----------------------------------------------------------------------------
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Devecchi, André; Silva, André; faccuser@gmail.com
 * 
 * Permission is hereby granted, free of charge, to any person obtaining a copy of
 * this software and associated documentation files (the "Software"), to deal in
 * the Software without restriction, including without limitation the rights to
 * use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 * the Software, and to permit persons to whom the Software is furnished to do so,
 * subject to the following conditions:
 * 
 * The above copyright notice and this permission notice shall be included in all
 * copies or substantial portions of the Software.
 * 
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 * FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 * COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 * IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 * CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*/

session_start();
if (!isset($_SESSION['admin']))
{
	header('location:index.php');
}

include_once 'dao/DataMapper.class.php';
include_once 'dao/CollaboratorMapper.class.php';
include_once 'controller/Collaborator.class.php';

$collaborator = new Collaborator();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Ponto Eletrônico Digital (TCC)</title>
	<link rel="stylesheet" type="text/css" href="style/style.css">
</head>
<body>
	<div id="main">
		<div id="logo"><img src="imagem/logo.png"></div>
		<h1>Trabalho de Conclusão de Curso (TCC)</h1>
		<p id="sair"><a href="logout.php">Sair</a></p>
		<div id="selecao">
			<p><label>Colaborador </label>
			<select name="colaborador" onchange="pagina(this.value, 1)">
				<option value="">Selecionar</option>
				<?php echo $collaborator->getCollaborators(); ?>
			</select>
			<span id="loader"><img src="imagem/loader.gif"></span></p>
		</div>
		<div id="registro">
			<p class="normal">Selecione o colaborador para mostrar os registros dos horarios...</p>
		</div>
		<p id="void">&nbsp;</p>
	</div>
	<div id="footer">&copy; 2015 by André Devecchi</div>
	<script type="text/javascript" src="js/script.js"></script>
</body>
</html>	
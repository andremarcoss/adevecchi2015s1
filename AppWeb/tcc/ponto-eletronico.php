<?php
/*
 * Projeto:
 *  - Ponto Eletrфnico Digital
 *
 * Descriзгo:
 *  - TCC para a Faccamp Facundade Campo Limpo Paulista,
 *    como requesito parcial para obtenзгo do tнtulo de
 *    Bacharel em Ciкncia da Computaзгo.
 *
 * criado em 26 Abril 2015
 * by Andrй Devecchi
 * contato: andre.devecchi@gmail.com
 * ----------------------------------------------------------------------------
 * The MIT License (MIT)
 *
 * Copyright (c) 2014 Devecchi, Andrй; Silva, Andrй; faccuser@gmail.com
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

include_once 'dao/DataMapper.class.php';
include_once 'dao/AddRecordMapper.class.php';
include_once 'controller/AddRecord.class.php';

$nome = new AddRecord($_POST['uid'], $_POST['data'], $_POST['hora'], $_POST['dia']);

if ($nome->getNome() == 'Erro')
	$feedback = '{"nome": "Erro No Registro!"}';
else
	$feedback = '{"nome": "'.$nome->getNome().'"}';

header('Cache-Control: no-cache, must-revalidate'); 
header('Content-Type: application/json; charset=utf-8');

echo $feedback;
?>
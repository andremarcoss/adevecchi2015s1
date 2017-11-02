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

final class AddRecordMapper extends DataMapper
{
	public static function get($uid, $data, $hora, $dia)
	{		
		try {
			$stmt = self::$db->prepare("SELECT MAX(idregistro) AS ultimo FROM registro");
			$stmt->execute();
			$id = $stmt->fetch(PDO::FETCH_OBJ);
		
			$id = (intval($id->ultimo)+1);
			
			$stmt = self::$db->prepare(
				"insert into registro 
					(idregistro, colaborador_idcolaborador, data_registro, hora_registro, dia_registro) 
				 values (:id, :uid, :data, :hora, :dia)"
			);
			$stmt->bindParam(':id', $id);
			$stmt->bindParam(':uid', $uid);
			$stmt->bindParam(':data', $data);
			$stmt->bindParam(':hora', $hora);
			$stmt->bindParam(':dia', $dia);
			$stmt->execute();
			
			$stmt = self::$db->prepare("select nome from colaborador where idcolaborador = :uid");
			$stmt->bindParam(':uid', $uid);
			$stmt->execute();
			$colaborador = $stmt->fetch(PDO::FETCH_OBJ);
			
			return $colaborador->nome;
		} catch (Exception $e) {
			return 'Erro';	//echo '{"nome": "Erro No Registro!"}';
		}
	}
}
?>